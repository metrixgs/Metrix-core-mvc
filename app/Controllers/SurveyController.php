<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SurveyModel;
use App\Models\SurveyResponseModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyController extends BaseController {
    // Lista todas las encuestas
    public function index()
    {
        $surveyModel = new SurveyModel();
        $surveys = $surveyModel->findAll();
        return view('survey/index', ['surveys' => $surveys]);
    }
    
    // Muestra el formulario para crear una encuesta
    public function create()
    {
        return view('survey/create');
    }
    
    // Almacena una nueva encuesta con preguntas dinámicas
    public function store() {
        $surveyModel = new SurveyModel();
        
        // Obtener datos del formulario
        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $questionsData = $this->request->getPost('questions');
        
        // Procesar la imagen principal de la encuesta
        $imageFile = $this->request->getFile('image');
        $imagePath = null;
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imagePath = $imageFile->move('uploads/surveys', $newName);
            // Ajustar la ruta para que sea relativa
            $imagePath = 'uploads/surveys/' . $newName;
        }
        
        // Procesar las preguntas y sus imágenes
        $processedQuestions = [];
        $files = $this->request->getFiles();
        
        // Verificar si hay archivos de imágenes para las preguntas
        if (isset($files['questions'])) {
            foreach ($questionsData as $index => $question) {
                $processedQuestion = [
                    'text' => $question['text'],
                    'type' => $question['type']
                ];
                
                // Procesar imagen para esta pregunta si existe
                if (isset($files['questions'][$index]['image']) && 
                    $files['questions'][$index]['image']->isValid() && 
                    !$files['questions'][$index]['image']->hasMoved()) {
                    
                    $questionFile = $files['questions'][$index]['image'];
                    $newName = $questionFile->getRandomName();
                    $questionFile->move('uploads/questions', $newName);
                    $processedQuestion['image'] = 'uploads/questions/' . $newName;
                }
                
                // Procesar opciones para preguntas de selección múltiple
                if ($question['type'] === 'multiple' && isset($question['options'])) {
                    $processedQuestion['options'] = array_filter($question['options'], fn($opt) => trim($opt) !== '');
                }
                
                $processedQuestions[] = $processedQuestion;
            }
        }
        
        // Preparar datos para guardar en la base de datos
        $data = [
            'title' => $title,
            'description' => $description,
            'questions' => json_encode($processedQuestions),
            'image' => $imagePath,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Guardar en la base de datos
        $surveyModel->save($data);
        
        return redirect()->to('/survey/' . $surveyModel->getInsertID())
            ->with('message', 'Encuesta guardada exitosamente.');
    }
    
    // Muestra una encuesta específica
    public function show($id)
    {
        $surveyModel = new SurveyModel();
        $survey = $surveyModel->find($id);
        
        return view('survey/show', ['survey' => $survey]);
    }
    
    // Guarda una respuesta a una encuesta
    public function storeResponse($id)
    {
        $surveyResponseModel = new SurveyResponseModel();
        
        $data = [
            'survey_id'  => $id,
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'answers'    => json_encode($this->request->getPost('answers'))
        ];
        
        $surveyResponseModel->save($data);
        
        return redirect()->to('/survey/' . $id . '/responded')->with('message', 'Gracias por responder!');
    }

    // Encuesta respondida con éxito
public function surveyResponded($id)
{
    return view('survey/survey_responded');
}

// Muestra todas las respuestas de una encuesta
public function showResponses($id)
{
    $surveyModel = new SurveyModel();
    $surveyResponseModel = new SurveyResponseModel();

    // Obtener las respuestas de la encuesta
    $responses = $surveyResponseModel->where('survey_id', $id)->findAll();
    $survey = $surveyModel->find($id);

    return view('survey/responses', ['survey' => $survey, 'responses' => $responses]);
}

public function exportResponses($id)
{
    $surveyModel = new SurveyModel();
    $surveyResponseModel = new SurveyResponseModel();

    // Obtener las respuestas de la encuesta
    $responses = $surveyResponseModel->where('survey_id', $id)->findAll();
    $survey = $surveyModel->find($id);

    if (!$survey) {
        return redirect()->back()->with('error', 'Encuesta no encontrada');
    }

    // Decodificar las preguntas
    $questions = json_decode($survey['questions'], true);

    // Verificar si las preguntas son un array
    if (!is_array($questions)) {
        return redirect()->back()->with('error', 'Las preguntas de la encuesta no son válidas');
    }

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Título de la hoja de cálculo
    $sheet->setCellValue('A1', 'Respuestas de la Encuesta: ' . $survey['title']);
    $sheet->mergeCells('A1:' . chr(65 + count($questions) + 2) . '1'); // Merge the header row
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Escribir los encabezados
    $headers = ['Nombre', 'Correo Electrónico', 'Fecha de Respuesta'];

    // Añadir preguntas como encabezados
    foreach ($questions as $question) {
        $headers[] = $question['text'];
    }

    // Escribir los encabezados en la primera fila
    $column = 0;
    foreach ($headers as $header) {
        $sheet->setCellValue(chr(65 + $column) . '2', $header); // Cambiado a setCellValue con coordenada
        $column++;
    }

    // Escribir las respuestas
    $row = 3;
    foreach ($responses as $response) {
        $sheet->setCellValue('A' . $row, $response['name']);
        $sheet->setCellValue('B' . $row, $response['email']);
        $sheet->setCellValue('C' . $row, date('d/m/Y H:i', strtotime($response['created_at'])));

        $answers = json_decode($response['answers'], true);
        $column = 4;
        foreach ($questions as $index => $question) {
            $answer = isset($answers[$index]) ? $answers[$index]['response'] : 'No respondida';
            $sheet->setCellValue(chr(65 + $column) . $row, $answer); // Cambiado a setCellValue con coordenada
            $column++;
        }

        $row++;
    }

    // Crear un escritor de Excel
    $writer = new Xlsx($spreadsheet);

    // Generar el archivo Excel y forzar la descarga
    $filename = 'respuestas_encuesta_' . $survey['id'] . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
}

}