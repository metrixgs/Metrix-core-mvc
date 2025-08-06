<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SurveyModel;
use App\Models\SurveyResponseModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyController extends BaseController
{
    protected $surveyModel;
    protected $surveyResponseModel;

    public function __construct()
    {
        $this->surveyModel = new SurveyModel();
        $this->surveyResponseModel = new SurveyResponseModel();
        helper('bitacora'); // Cargar helper para la bitácora
    }

    protected function renderView($mainView, $data = [])
    {
        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view($mainView, $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function index()
    {
        $surveys = $this->surveyModel->paginate(4);

        $data = [
            'titulo_pagina' => 'Listado de Encuestas',
            'surveys' => $surveys,
            'pager' => $this->surveyModel->pager
        ];

        return $this->renderView('survey/index', $data);
    }

    public function create()
    {
        $data['titulo_pagina'] = 'Crear Encuesta';
        return $this->renderView('survey/create', $data);
    }

     public function store()
{
    $title = $this->request->getPost('title');
    $description = $this->request->getPost('description');
    $questionsData = $this->request->getPost('questions');

    $imageFile = $this->request->getFile('image');
    $imagePath = null;

    if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
        $newName = $imageFile->getRandomName();
        $imageFile->move('uploads/surveys', $newName);
        $imagePath = 'uploads/surveys/' . $newName;
    }

    $processedQuestions = [];
    $files = $this->request->getFiles();

    if (isset($files['questions'])) {
        foreach ($questionsData as $index => $question) {
            $processedQuestion = [
                'text' => $question['text'],
                'type' => $question['type']
            ];

            if (isset($files['questions'][$index]['image']) &&
                $files['questions'][$index]['image']->isValid() &&
                !$files['questions'][$index]['image']->hasMoved()) {

                $questionFile = $files['questions'][$index]['image'];
                $newName = $questionFile->getRandomName();
                $questionFile->move('uploads/questions', $newName);
                $processedQuestion['image'] = 'uploads/questions/' . $newName;
            }

            if ($question['type'] === 'multiple' && isset($question['options'])) {
                $processedQuestion['options'] = array_filter($question['options'], fn($opt) => trim($opt) !== '');
            }

            $processedQuestions[] = $processedQuestion;
        }
    }

    $data = [
        'title' => $title,
        'description' => $description,
        'questions' => json_encode($processedQuestions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        'image' => $imagePath,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->surveyModel->save($data);
    $surveyId = $this->surveyModel->getInsertID();

    // 🟡 Bitácora opcional solo si hay usuario en sesión
    if (session()->has('session_data.usuario_id')) {
        log_activity(
            session('session_data.usuario_id'),
            'Encuestas',
            'Creación',
            [
                'survey_id' => $surveyId,
                'titulo' => $title,
                'preguntas' => count($processedQuestions)
            ]
        );
    }

    return redirect()->to('/survey/' . $surveyId)
        ->with('message', 'Encuesta guardada exitosamente.');
}



    public function show($id)
    {
        $survey = $this->surveyModel->find($id);

        if (!$survey) {
            return redirect()->to('/survey')->with('error', 'Encuesta no encontrada');
        }

        $data = [
            'titulo_pagina' => 'Detalle de Encuesta',
            'survey' => $survey
        ];

        return $this->renderView('survey/show', $data);
    }

    public function storeResponse($id)
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $answers = $this->request->getPost('answers');

        $data = [
            'survey_id'  => $id,
            'name'       => $name,
            'email'      => $email,
            'answers'    => json_encode($answers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ];

        $this->surveyResponseModel->save($data);
        $responseId = $this->surveyResponseModel->getInsertID();

        // Registrar en bitácora
        log_activity(
            session('session_data.usuario_id') ?? 0, // o 0 para invitados
            'Encuestas',
            'Respuesta',
            [
                'survey_id' => $id,
                'response_id' => $responseId,
                'nombre' => $name,
                'email' => $email
            ]
        );

        return redirect()->to('/survey/' . $id . '/responded')->with('message', 'Gracias por responder!');
    }

    public function surveyResponded($id)
    {
        $data['titulo_pagina'] = 'Encuesta Respondida';
        return $this->renderView('survey/survey_responded', $data);
    }

    public function showResponses($id)
    {
        $responses = $this->surveyResponseModel->where('survey_id', $id)->findAll();
        $survey = $this->surveyModel->find($id);

        $data = [
            'titulo_pagina' => 'Respuestas de Encuesta',
            'survey' => $survey,
            'responses' => $responses
        ];

        return $this->renderView('survey/responses', $data);
    }

    public function exportResponses($id)
    {
        $responses = $this->surveyResponseModel->where('survey_id', $id)->findAll();
        $survey = $this->surveyModel->find($id);

        if (!$survey) {
            return redirect()->back()->with('error', 'Encuesta no encontrada');
        }

        $questions = json_decode($survey['questions'], true);

        if (!is_array($questions)) {
            return redirect()->back()->with('error', 'Las preguntas de la encuesta no son válidas');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Respuestas de la Encuesta: ' . $survey['title']);
        $sheet->mergeCells('A1:' . chr(65 + count($questions) + 2) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['Nombre', 'Correo Electrónico', 'Fecha de Respuesta'];
        foreach ($questions as $question) {
            $headers[] = $question['text'];
        }

        $column = 0;
        foreach ($headers as $header) {
            $sheet->setCellValue(chr(65 + $column) . '2', $header);
            $column++;
        }

        $row = 3;
        foreach ($responses as $response) {
            $sheet->setCellValue('A' . $row, $response['name']);
            $sheet->setCellValue('B' . $row, $response['email']);
            $sheet->setCellValue('C' . $row, date('d/m/Y H:i', strtotime($response['created_at'])));

            $answers = json_decode($response['answers'], true);
            $column = 4;
            foreach ($questions as $index => $question) {
                $answer = isset($answers[$index]) ? $answers[$index]['response'] : 'No respondida';
                $sheet->setCellValue(chr(65 + $column) . $row, $answer);
                $column++;
            }

            $row++;
        }

        // Registrar en bitácora
        log_activity(
            session('session_data.usuario_id') ?? 0,
            'Encuestas',
            'Exportación',
            [
                'survey_id' => $id,
                'titulo' => $survey['title'],
                'respuestas' => count($responses)
            ]
        );

        $writer = new Xlsx($spreadsheet);
        $filename = 'respuestas_encuesta_' . $survey['id'] . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function estadistica()
{
    $surveyModel = new SurveyModel();
    $surveyResponseModel = new SurveyResponseModel();

    $surveys = $surveyModel->findAll();

    $estadisticas = [];

    foreach ($surveys as $survey) {
        $respuestas = $surveyResponseModel->where('survey_id', $survey['id'])->countAllResults();
        $estadisticas[] = [
            'id' => $survey['id'],
            'title' => $survey['title'],
            'total_respuestas' => $respuestas,
        ];
    }

    $data = [
        'titulo_pagina' => 'Estadísticas de Encuestas',
        'estadisticas' => $estadisticas
    ];

    return $this->renderView('survey/estadistica', $data);
} 
     public function estadisticaPorEncuesta($id)
{
    $survey = $this->surveyModel->find($id);
    $responses = $this->surveyResponseModel->where('survey_id', $id)->findAll();

    $totalRespuestas = count($responses);

    if (!$survey || empty($responses)) {
        return $this->renderView('survey/estadistica_por_encuesta', [
            'titulo_pagina' => 'Estadísticas',
            'survey' => $survey,
            'totalRespuestas' => 0,
            'agrupadas' => []
        ]);
    }

    $agrupadas = []; // pregunta => [respuesta => cantidad]

    foreach ($responses as $resp) {
        $respuestas = json_decode($resp['answers'], true);
        if (!is_array($respuestas)) continue;

        foreach ($respuestas as $r) {
            $tipo = $r['tipo'] ?? null;
            $pregunta = $r['pregunta'] ?? null;
            $respuesta = $r['respuesta'] ?? null;

            // 🔴 OMITIR si no es múltiple o texto (es decir: ignorar tipo "foto" u otros archivos)
            if (!$pregunta || !$respuesta || !in_array($tipo, ['multiple', 'text'])) continue;

            // Si es un objeto tipo {"nombre": "..."}
            if (is_array($respuesta) && isset($respuesta['nombre'])) {
                $respuesta = $respuesta['nombre'];
            }

            // Ignorar si la respuesta sigue siendo un array (probablemente archivo base64 o imagen)
            if (is_array($respuesta)) continue;

            $respuesta = trim($respuesta);
            if ($respuesta === '') continue;

            if (!isset($agrupadas[$pregunta])) {
                $agrupadas[$pregunta] = [];
            }

            $agrupadas[$pregunta][$respuesta] = ($agrupadas[$pregunta][$respuesta] ?? 0) + 1;
        }
    }

    return $this->renderView('survey/estadistica_por_encuesta', [
        'titulo_pagina' => 'Estadísticas de Encuesta',
        'survey' => $survey,
        'agrupadas' => $agrupadas,
        'totalRespuestas' => $totalRespuestas
    ]);
}

}
