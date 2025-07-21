<?php
namespace App\Controllers;

use App\Models\BitacoraUsuariosModel;
use App\Models\NotificacionesModel;

class BitacoraController extends BaseController
{
    private $bitacoraModel;
    protected $notificaciones;

    public function __construct()
    {
        $this->bitacoraModel = new BitacoraUsuariosModel();
        $this->notificaciones = new NotificacionesModel();
        helper('menu');
    }

    public function index()
    {
        $filtros = [
            'search' => $this->request->getGet('search'),
            'type' => $this->request->getGet('type'),
            'user_id' => $this->request->getGet('user_id')
        ];

        $data = [
            'titulo_pagina' => 'Metrix | Bitácora de Usuarios',
            'bitacoras' => $this->bitacoraModel->obtenerBitacorasFiltradas($filtros),
            'usuarios' => $this->bitacoraModel->obtenerUsuariosParaSelect(),
            'tipos_accion' => $this->bitacoraModel->obtenerTiposAccion(),
            'filtros_actuales' => $filtros,
            'total_actividades' => $this->bitacoraModel->contarRegistros($filtros),
            'actividades_hoy' => $this->bitacoraModel->where('DATE(fecha_creacion)', date('Y-m-d'))->countAllResults(),
            'inicios_sesion' => $this->bitacoraModel->where('accion', 'Login')->countAllResults(),
            'creaciones' => $this->bitacoraModel->like('accion', 'Creación')->countAllResults()
        ];

        // Manteniendo exactamente la misma estructura de vistas que tenías
        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('bitacora/index', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }
}