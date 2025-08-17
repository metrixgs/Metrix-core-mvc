<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketsModel;

class DashboardAjax extends BaseController {

    protected $tickets;

    public function __construct() {
        $this->tickets = new TicketsModel();
        helper('Alerts');
    }

    public function obtenerEstadisticas() {
        // Verificar que sea una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');
        $periodo = $this->request->getPost('periodo');

        // Calcular fechas según el período si no se proporcionan fechas específicas
        if (!$fecha_inicio || !$fecha_fin) {
            $fechas = $this->calcularFechasPorPeriodo($periodo);
            $fecha_inicio = $fechas['inicio'];
            $fecha_fin = $fechas['fin'];
        }

        try {
            // Obtener todas las estadísticas
            $estadisticas = [
                'generales' => $this->tickets->obtenerEstadisticasGenerales($fecha_inicio, $fecha_fin),
                'prioridad' => $this->tickets->obtenerTicketsPorPrioridad($fecha_inicio, $fecha_fin),
                'categoria' => $this->tickets->obtenerTicketsPorCategoria($fecha_inicio, $fecha_fin),
                'tiempo_area' => $this->tickets->obtenerTiempoPromedioPorArea($fecha_inicio, $fecha_fin),
                'tendencia' => $this->tickets->obtenerTendenciaMensual($fecha_inicio, $fecha_fin),
                'colonias' => $this->tickets->obtenerTop10Colonias($fecha_inicio, $fecha_fin),
                'area_responsable' => $this->tickets->obtenerTicketsPorAreaResponsable($fecha_inicio, $fecha_fin),
                'comparativa' => $this->tickets->obtenerComparativaTemporalPorCategoria($fecha_inicio, $fecha_fin)
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas,
                'periodo' => [
                    'inicio' => $fecha_inicio,
                    'fin' => $fecha_fin
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    public function exportarExcel() {
        // Verificar que sea una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');
        $periodo = $this->request->getPost('periodo');

        // Calcular fechas según el período si no se proporcionan fechas específicas
        if (!$fecha_inicio || !$fecha_fin) {
            $fechas = $this->calcularFechasPorPeriodo($periodo);
            $fecha_inicio = $fechas['inicio'];
            $fecha_fin = $fechas['fin'];
        }

        try {
            // Obtener datos para exportar
            $tickets = $this->tickets->select('tbl_tickets.*, 
                                              u1.nombre AS nombre_usuario, 
                                              u2.nombre AS nombre_cliente,
                                              tbl_categorias.nombre AS nombre_categoria,
                                              tbl_areas.nombre AS nombre_area_responsable,
                                              tbl_prioridades.nombre AS nombre_prioridad')
                                    ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                                    ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                                    ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                                    ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                                    ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                                    ->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                                    ->where('tbl_tickets.fecha_creacion <=', $fecha_fin)
                                    ->orderBy('tbl_tickets.id', 'DESC')
                                    ->findAll();

            // Generar nombre del archivo
            $filename = 'dashboard_incidencias_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = WRITEPATH . 'exports/' . $filename;

            // Crear directorio si no existe
            if (!is_dir(WRITEPATH . 'exports/')) {
                mkdir(WRITEPATH . 'exports/', 0755, true);
            }

            // Crear archivo CSV
            $file = fopen($filepath, 'w');
            
            // Escribir encabezados
            fputcsv($file, [
                'ID', 'Título', 'Descripción', 'Cliente', 'Categoría', 'Área Responsable', 
                'Prioridad', 'Estado', 'Colonia', 'Municipio', 'Fecha Creación', 
                'Fecha Cierre', 'Fecha Vencimiento'
            ]);

            // Escribir datos
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket['id'],
                    $ticket['titulo'],
                    $ticket['descripcion'],
                    $ticket['nombre_cliente'],
                    $ticket['nombre_categoria'],
                    $ticket['nombre_area_responsable'],
                    $ticket['nombre_prioridad'],
                    $ticket['estado'],
                    $ticket['colonia'],
                    $ticket['municipio'],
                    $ticket['fecha_creacion'],
                    $ticket['fecha_cierre'],
                    $ticket['fecha_vencimiento']
                ]);
            }

            fclose($file);

            return $this->response->setJSON([
                'success' => true,
                'download_url' => base_url('writable/exports/' . $filename),
                'filename' => $filename,
                'total_records' => count($tickets)
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al exportar datos: ' . $e->getMessage()
            ]);
        }
    }

    private function calcularFechasPorPeriodo($periodo) {
        $hoy = new \DateTime();
        $inicio = new \DateTime();
        $fin = new \DateTime();

        switch($periodo) {
            case 'hoy':
                $inicio = new \DateTime('today');
                $fin = new \DateTime('tomorrow');
                break;
            case 'semana':
                $inicio = new \DateTime('monday this week');
                $fin = new \DateTime('sunday this week');
                $fin->modify('+1 day');
                break;
            case 'mes':
                $inicio = new \DateTime('first day of this month');
                $fin = new \DateTime('last day of this month');
                $fin->modify('+1 day');
                break;
            case 'trimestre':
                $mes_actual = (int)$hoy->format('n');
                $trimestre = ceil($mes_actual / 3);
                $mes_inicio = (($trimestre - 1) * 3) + 1;
                $inicio = new \DateTime($hoy->format('Y') . '-' . str_pad($mes_inicio, 2, '0', STR_PAD_LEFT) . '-01');
                $fin = clone $inicio;
                $fin->modify('+3 months -1 day');
                $fin->modify('+1 day');
                break;
            case 'año':
                $inicio = new \DateTime('first day of January this year');
                $fin = new \DateTime('last day of December this year');
                $fin->modify('+1 day');
                break;
            default:
                // Por defecto este mes
                $inicio = new \DateTime('first day of this month');
                $fin = new \DateTime('last day of this month');
                $fin->modify('+1 day');
        }

        return [
            'inicio' => $inicio->format('Y-m-d'),
            'fin' => $fin->format('Y-m-d')
        ];
    }
}