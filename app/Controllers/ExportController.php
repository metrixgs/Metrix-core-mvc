<?php

namespace App\Controllers;

use App\Models\DirectorioModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class ExportController extends Controller
{
    protected $directorioModel;

    public function __construct()
    {
        $this->directorioModel = new DirectorioModel();
        helper('bitacora'); // Cargar helper de bitácora
    }

    public function excel()
    {
        $contactos = $this->directorioModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Apellido Paterno');
        $sheet->setCellValue('C1', 'Apellido Materno');
        $sheet->setCellValue('D1', 'ID Ciudadano');
        $sheet->setCellValue('E1', 'Residencia');
        $sheet->setCellValue('F1', 'Empresa');
        $sheet->setCellValue('G1', 'Cargo');

        $row = 2;
        foreach ($contactos as $c) {
            $sheet->setCellValue("A{$row}", $c['nombre']);
            $sheet->setCellValue("B{$row}", $c['primer_apellido']);
            $sheet->setCellValue("C{$row}", $c['segundo_apellido']);
            $sheet->setCellValue("D{$row}", $c['codigo_ciudadano']);
            $sheet->setCellValue("E{$row}", $c['residencia']);
            $sheet->setCellValue("F{$row}", $c['empresa']);
            $sheet->setCellValue("G{$row}", $c['cargo']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'directorio.xlsx';

        // Descargar
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // Registrar en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'Excel', [
            'descripcion' => 'Exportación de directorio en formato Excel',
            'formato' => 'Excel (.xlsx)',
            'total_registros' => count($contactos),
            'archivo_generado' => $filename,
            'metodo_descarga' => 'directa'
        ], 'info');
        
        $writer->save('php://output');
        exit;
    }

    public function csv()
    {
        $contactos = $this->directorioModel->findAll();
        $filename = "directorio.csv";

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv;");

        $file = fopen("php://output", "w");

        // Encabezado
        $header = ['Nombre', 'Apellido Paterno', 'Apellido Materno', 'ID Ciudadano', 'Residencia', 'Empresa', 'Cargo'];
        fputcsv($file, $header);

        foreach ($contactos as $c) {
            fputcsv($file, [
                $c['nombre'],
                $c['primer_apellido'],
                $c['segundo_apellido'],
                $c['codigo_ciudadano'],
                $c['residencia'],
                $c['empresa'],
                $c['cargo']
            ]);
        }

        // Registrar en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'CSV', [
            'descripcion' => 'Exportación de directorio en formato CSV',
            'formato' => 'CSV (.csv)',
            'total_registros' => count($contactos),
            'archivo_generado' => $filename,
            'metodo_descarga' => 'directa'
        ], 'info');
        
        fclose($file);
        exit;
    }

    public function pdf()
    {
        $contactos = $this->directorioModel->findAll();

        $html = '<h2>Listado de Ciudadanos</h2><table border="1" cellpadding="5" cellspacing="0"><thead>
            <tr><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>ID</th><th>Residencia</th><th>Empresa</th><th>Cargo</th></tr>
        </thead><tbody>';

        foreach ($contactos as $c) {
            $html .= "<tr>
                <td>{$c['nombre']}</td>
                <td>{$c['primer_apellido']}</td>
                <td>{$c['segundo_apellido']}</td>
                <td>{$c['codigo_ciudadano']}</td>
                <td>{$c['residencia']}</td>
                <td>{$c['empresa']}</td>
                <td>{$c['cargo']}</td>
            </tr>";
        }

        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        // Registrar en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'PDF', [
            'descripcion' => 'Exportación de directorio en formato PDF',
            'formato' => 'PDF (.pdf)',
            'total_registros' => count($contactos),
            'archivo_generado' => 'directorio.pdf',
            'orientacion' => 'landscape',
            'metodo_descarga' => 'directa'
        ], 'info');
        
        $dompdf->stream('directorio.pdf', ['Attachment' => 1]);
    }

    public function enviarCorreo()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $tipo = $data['tipo'] ?? 'excel';
    $email = $data['email'] ?? null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Registrar intento con email inválido en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'Error Validación Email', [
            'descripcion' => 'Intento de exportación con correo electrónico inválido',
            'formato' => strtoupper($tipo),
            'email_invalido' => $email,
            'error' => 'Formato de correo electrónico inválido'
        ], 'warning');
        
        return $this->response->setJSON([
            'status' => 400,
            'message' => 'Correo electrónico inválido'
        ]);
    }

    $contactos = $this->directorioModel->findAll();
    $nombreArchivo = 'directorio_' . time();
    $rutaArchivo = WRITEPATH . 'exports/';

    if (!is_dir($rutaArchivo)) {
        mkdir($rutaArchivo, 0777, true);
    }

    switch ($tipo) {
        case 'pdf':
            $nombreArchivo .= '.pdf';
            $html = '<h2>Listado de Ciudadanos</h2><table border="1" cellpadding="5" cellspacing="0"><thead>
                        <tr><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>ID</th><th>Residencia</th><th>Empresa</th><th>Cargo</th></tr>
                    </thead><tbody>';

            foreach ($contactos as $c) {
                $html .= "<tr>
                    <td>{$c['nombre']}</td>
                    <td>{$c['primer_apellido']}</td>
                    <td>{$c['segundo_apellido']}</td>
                    <td>{$c['codigo_ciudadano']}</td>
                    <td>{$c['residencia']}</td>
                    <td>{$c['empresa']}</td>
                    <td>{$c['cargo']}</td>
                </tr>";
            }

            $html .= '</tbody></table>';

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            file_put_contents($rutaArchivo . $nombreArchivo, $dompdf->output());
            break;

        case 'csv':
            $nombreArchivo .= '.csv';
            $fp = fopen($rutaArchivo . $nombreArchivo, 'w');
            fputcsv($fp, ['Nombre', 'Apellido Paterno', 'Apellido Materno', 'ID Ciudadano', 'Residencia', 'Empresa', 'Cargo']);
            foreach ($contactos as $c) {
                fputcsv($fp, [$c['nombre'], $c['primer_apellido'], $c['segundo_apellido'], $c['codigo_ciudadano'], $c['residencia'], $c['empresa'], $c['cargo']]);
            }
            fclose($fp);
            break;

        case 'excel':
        default:
            $nombreArchivo .= '.xlsx';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray(['Nombre', 'Apellido Paterno', 'Apellido Materno', 'ID Ciudadano', 'Residencia', 'Empresa', 'Cargo'], null, 'A1');

            $row = 2;
            foreach ($contactos as $c) {
                $sheet->fromArray([$c['nombre'], $c['primer_apellido'], $c['segundo_apellido'], $c['codigo_ciudadano'], $c['residencia'], $c['empresa'], $c['cargo']], null, "A$row");
                $row++;
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($rutaArchivo . $nombreArchivo);
            break;
    }

    // Enviar correo
    $emailService = \Config\Services::email();

    $emailService->setTo($email);
    $emailService->setSubject("Exportación del directorio");
    $emailService->setMessage("Adjunto encontrarás el archivo solicitado con los datos del directorio.");
    $emailService->attach($rutaArchivo . $nombreArchivo);

    if ($emailService->send()) {
        // Registrar en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'Envío Email', [
            'descripcion' => 'Directorio enviado por correo electrónico',
            'formato' => strtoupper($tipo),
            'destinatario' => $email,
            'total_registros' => count($contactos),
            'archivo_generado' => $nombreArchivo,
            'metodo_entrega' => 'email',
            'estado_envio' => 'exitoso'
        ], 'info');
        
        // Limpieza opcional
        @unlink($rutaArchivo . $nombreArchivo);

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Correo enviado exitosamente a ' . $email
        ]);
    } else {
        // Registrar error en bitácora
        $usuario_id = session('session_data.usuario_id') ?? 999;
        log_activity($usuario_id, 'Exportación', 'Error Envío Email', [
            'descripcion' => 'Error al enviar directorio por correo electrónico',
            'formato' => strtoupper($tipo),
            'destinatario' => $email,
            'total_registros' => count($contactos),
            'archivo_generado' => $nombreArchivo,
            'metodo_entrega' => 'email',
            'estado_envio' => 'fallido',
            'error' => 'Error en el servicio de correo'
        ], 'error');
        
        return $this->response->setJSON([
            'status' => 500,
            'message' => 'Error al enviar el correo'
        ]);
    }
}

}
