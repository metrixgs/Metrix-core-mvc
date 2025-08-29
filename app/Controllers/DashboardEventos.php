<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventosModel;
use App\Models\ApoyosModel;

class DashboardEventos extends BaseController
{
    /**
     * Dashboard de Eventos - basado en la tabla dashboard_demo
     */
    protected $eventosModel;

    public function __construct()
    {
        $this->eventosModel = new EventosModel();
    }

    public function index()
    {
        // Obtener sectores para el selector
        $data['sectores'] = $this->eventosModel->getSectores();
        
        // KPIs iniciales (sin filtro)
        $data['total_apoyos'] = $this->eventosModel->getTotalApoyos();
        $data['total_eventos'] = $this->eventosModel->getTotalEventos();
        $data['promedio_apoyos'] = $this->eventosModel->getPromedioApoyos();
        $data['promedio_eventos'] = $this->eventosModel->getPromedioEventos();
        $data['porcentaje_apoyos'] = $this->eventosModel->getPorcentajeApoyos();
        $data['porcentaje_eventos'] = $this->eventosModel->getPorcentajeEventos();
        
        // Datos para gráficos - Demografía
        $data['genero_data'] = $this->eventosModel->getDistribucionGenero();
        $data['edad_data'] = $this->eventosModel->getDistribucionEdades();
        
        // Datos para gráficos - Liderazgo
        $data['top_lideres_data'] = $this->eventosModel->getTopLideres();
        $data['sectores_treemap_data'] = $this->eventosModel->getSectoresTreemap();
        $data['coordinador_data'] = $this->eventosModel->getCoordinadorData();
        
        // Datos para gráficos - Apoyos
        $data['apoyos_por_sector_data'] = $this->eventosModel->getApoyosPorSector();
        $data['promedio_apoyos_sector_data'] = $this->eventosModel->getPromedioApoyosPorSector();
        
        // Datos para gráficos - Colonias
        $data['colonias_data'] = $this->eventosModel->getColoniasData();
        
        // Datos para gráficos - Eventos
        $data['eventos_por_anio_data'] = $this->eventosModel->getEventosPorAnio();
        $data['eventos_por_sector_data'] = $this->eventosModel->getEventosPorSector();
        $data['eventos_por_lider_data'] = $this->eventosModel->getEventosPorLider();
        
        // Datos para gráficos - Top 10 Secciones Electorales
        $data['top_apoyos_seccion_data'] = $this->eventosModel->getTopApoyosPorSeccion();
        $data['top_eventos_seccion_data'] = $this->eventosModel->getTopEventosPorSeccion();
        
        // Datos para selectores de filtros
        $data['liderazgos'] = $this->eventosModel->getLiderazgos();
        $data['coordinadores'] = $this->eventosModel->getCoordinadores();
        $data['secciones_electorales'] = $this->eventosModel->getSeccionesElectorales();
        $data['colonias'] = $this->eventosModel->getColonias();
        $data['sectores'] = $this->eventosModel->getSectores();
        $data['anios_alta'] = $this->eventosModel->getAniosAlta();
        

        
        return view('dashboard/eventos', $data);
    }

    // AJAX: Actualizar métricas por filtros
    public function updateMetrics()
    {
        $filtros = $this->request->getJSON(true);
        
        $data = [
            'total_apoyos' => $this->eventosModel->getTotalApoyos($filtros),
            'total_eventos' => $this->eventosModel->getTotalEventos($filtros),
            'promedio_apoyos' => $this->eventosModel->getPromedioApoyos($filtros),
            'promedio_eventos' => $this->eventosModel->getPromedioEventos($filtros),
            'porcentaje_apoyos' => $this->eventosModel->getPorcentajeApoyos($filtros),
            'porcentaje_eventos' => $this->eventosModel->getPorcentajeEventos($filtros),
            
            // Actualizar gráficos
            'genero_data' => $this->eventosModel->getDistribucionGenero($filtros),
            'edad_data' => $this->eventosModel->getDistribucionEdades($filtros),
            'niveles_lider_data' => $this->eventosModel->getNivelesLider($filtros),
            'top_lideres_data' => $this->eventosModel->getTopLideres($filtros),
            'sectores_treemap_data' => $this->eventosModel->getSectoresTreemap($filtros),
            'coordinador_data' => $this->eventosModel->getCoordinadorData($filtros),
            'apoyos_por_sector_data' => $this->eventosModel->getApoyosPorSector($filtros),
            'promedio_apoyos_sector_data' => $this->eventosModel->getPromedioApoyosPorSector($filtros),
            'eventos_por_anio_data' => $this->eventosModel->getEventosPorAnio($filtros),
            'eventos_por_sector_data' => $this->eventosModel->getEventosPorSector($filtros),
            'eventos_por_lider_data' => $this->eventosModel->getEventosPorLider($filtros),
            
            // Datos para gráficos de secciones electorales
            'top_apoyos_seccion_data' => $this->eventosModel->getTopApoyosPorSeccion($filtros),
            'top_eventos_seccion_data' => $this->eventosModel->getTopEventosPorSeccion($filtros),

        ];
        
        return $this->response->setJSON($data);
    }

    // AJAX: Obtener opciones actualizadas para selectores
    public function getSelectorsData()
    {
        $filtros = $this->request->getPost();
        
        $data = [
            'liderazgos' => $this->eventosModel->getLiderazgos($filtros),
            'coordinadores' => $this->eventosModel->getCoordinadores($filtros),
            'secciones_electorales' => $this->eventosModel->getSeccionesElectorales($filtros),
            'colonias' => $this->eventosModel->getColonias($filtros),
            'sectores' => $this->eventosModel->getSectores($filtros),
            'anios_alta' => $this->eventosModel->getAniosAlta($filtros)
        ];
        
        return $this->response->setJSON($data);
    }

    // AJAX: Obtener datos para top N dinámico
    public function getTopLideres()
    {
        $topN = $this->request->getPost('topN') ?? 10;
        $filtros = $this->request->getPost('filtros') ?? [];
        
        $data = $this->eventosModel->getTopLideres($filtros, $topN);
        
        return $this->response->setJSON($data);
    }
    
    // Método para depurar datos de secciones electorales
    public function debugSeccionData()
    {
        $topApoyos = $this->eventosModel->getTopApoyosPorSeccion();
        $topEventos = $this->eventosModel->getTopEventosPorSeccion();
        
        $debug = [
            'top_apoyos_count' => count($topApoyos),
            'top_apoyos_data' => $topApoyos,
            'top_eventos_count' => count($topEventos),
            'top_eventos_data' => $topEventos
        ];
        
        return $this->response->setJSON($debug);
    }
    
    public function debugLideresData()
    {
        $eventosModel = new EventosModel();
        
        $data = [
            'top_lideres_data' => $eventosModel->getEventosPorLider()
        ];
        
        return $this->response->setJSON($data);
    }
    
    public function debugJavaScript()
    {
        $eventosModel = new EventosModel();
        $apoyosModel = new ApoyosModel();
        
        // Obtener datos para las nuevas gráficas
        $top_apoyos_seccion_data = $apoyosModel->getApoyosPorSeccion();
        $top_eventos_seccion_data = $eventosModel->getEventosPorSeccion();
        
        // Generar solo el JavaScript problemático
        $js = "// Debug JavaScript\n";
        $js .= "const top_apoyos_seccion_data = " . json_encode($top_apoyos_seccion_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";\n";
        $js .= "const top_eventos_seccion_data = " . json_encode($top_eventos_seccion_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";\n";
        $js .= "console.log('Apoyos data:', top_apoyos_seccion_data);\n";
        $js .= "console.log('Eventos data:', top_eventos_seccion_data);\n";
        
        return $this->response->setContentType('text/javascript')->setBody($js);
    }
}