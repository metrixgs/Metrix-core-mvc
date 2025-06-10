<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\TareasModel;
use App\Models\NotificacionesModel;

class Panel extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $tareas;
    protected $notificaciones;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Panel de Control';
        
        $incidencias = $this->tickets->obtenerTickets();
        $data['incidencias'] = $incidencias;
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;
        
        $requerimientos = $tickets;
        
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
        
        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;
        
        # Obtenemos todas las tareas por area...
        $tareas = $this->tareas->obtenerTareasPorArea(session('session_data.area_id'));
        $data['tareas'] = $tareas;

        return view('incl/head-application', $data)
                .view('incl/header-application', $data)
                .view('incl/menu-admin', $data)
                . view('panel/inicio-panel', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
}
