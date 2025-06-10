<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\NotificacionesModel;
use App\Models\TareasModel;
use App\Models\AreasModel;
use App\Models\AccionesTicketsModel;
use App\Models\DocumentosTareasModel;

class Reportes extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $notificaciones;
    protected $tareas;
    protected $areas;
    protected $acciones;
    protected $documentos;

    public function __construct() {
        # Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();
        $this->areas = new AreasModel();
        $this->acciones = new AccionesTicketsModel();
        $this->documentos = new DocumentosTareasModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function requerimientos() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Generar Reportes';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todas las areas...
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('reportes/reporte-requerimientos', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function tareas() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Generar Reportes';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todas las areas...
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('reportes/reporte-tareas', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function reporteTareas() {
        $data['titulo_pagina'] = 'Metrix | Generar Reportes';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        $area_id = $this->request->getPost('area_id');
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');

        $validationRules = [
            'area_id' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
            return redirect()->to("reportes/tareas")->withInput();
        }

        $area = $this->areas->obtenerArea($area_id);
        $data['area'] = $area;

        if (empty($area)) {
            return redirect()->to("reportes/tareas");
        }

        $tareas = $this->tareas->obtenerTareasPorAreaEnrango($area_id, $fecha_inicio, $fecha_fin);
        $data['tareas'] = $tareas;

        if (empty($tareas)) {
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "No hay tareas asignadas a este área en el tiempo seleccionado, inténtalo nuevamente.",
                'tipo' => "warning"
            ]);
            return redirect()->to("reportes/tareas")->withInput();
        }

        $cumplidos = 0;
        $incumplidos = 0;
        $pendientes = 0;

        foreach ($tareas as $req) {
            $fecha_creacion = new \DateTime($req['fecha_creacion']);
            $fecha_vencimiento = new \DateTime($req['fecha_vencimiento']);
            $fecha_cierre = $req['fecha_solucion'] ? new \DateTime($req['fecha_solucion']) : null;
            $fecha_actual = new \DateTime();

            if ($fecha_cierre) {
                if ($fecha_cierre <= $fecha_vencimiento) {
                    $cumplidos++;
                } else {
                    $incumplidos++;
                }
            } else {
                if ($fecha_actual > $fecha_vencimiento) {
                    $incumplidos++;
                } elseif ($fecha_actual >= $fecha_creacion && $fecha_actual <= $fecha_vencimiento) {
                    $pendientes++;
                }
            }
        }

        $data['cumplidos'] = $cumplidos;
        $data['incumplidos'] = $incumplidos;
        $data['pendientes'] = $pendientes;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('reportes/resultados/respuesta-tareas', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function reporteRequerimientos() {
        $data['titulo_pagina'] = 'Metrix | Generar Reportes';
        
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        $area_id = $this->request->getPost('area_id');
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');

        $validationRules = [
            'area_id' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
            return redirect()->to("reportes/requerimientos")->withInput();
        }

        $area = $this->areas->obtenerArea($area_id);
        $data['area'] = $area;

        if (empty($area)) {
            return redirect()->to("reportes/requerimientos");
        }

        $requerimientos = $this->tickets->obtenerTicketsPorAreaEnRango($area_id, $fecha_inicio, $fecha_fin);
        $data['requerimientos'] = $requerimientos;

        if (empty($requerimientos)) {
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "No hay requerimientos asignados a este área en el tiempo seleccionado, inténtalo nuevamente.",
                'tipo' => "warning"
            ]);
            return redirect()->to("reportes/requerimientos")->withInput();
        }

        $cumplidos = 0;
        $incumplidos = 0;
        $pendientes = 0;

        foreach ($requerimientos as $req) {
            $fecha_creacion = new \DateTime($req['fecha_creacion']);
            $fecha_vencimiento = new \DateTime($req['fecha_vencimiento']);
            $fecha_cierre = $req['fecha_cierre'] ? new \DateTime($req['fecha_cierre']) : null;
            $fecha_actual = new \DateTime();

            if ($fecha_cierre) {
                if ($fecha_cierre <= $fecha_vencimiento) {
                    $cumplidos++;
                } else {
                    $incumplidos++;
                }
            } else {
                if ($fecha_actual > $fecha_vencimiento) {
                    $incumplidos++;
                } elseif ($fecha_actual >= $fecha_creacion && $fecha_actual <= $fecha_vencimiento) {
                    $pendientes++;
                }
            }
        }

        $data['cumplidos'] = $cumplidos;
        $data['incumplidos'] = $incumplidos;
        $data['pendientes'] = $pendientes;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('reportes/resultados/respuesta-requerimientos', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
}
