<?php
namespace App\Controllers;

use App\Models\BitacoraUsuariosModel;

class BitacoraController extends BaseController
{
    protected $bitacoraModel;

    public function __construct()
    {
        $this->bitacoraModel = new BitacoraUsuariosModel();
        helper('url');
    }

    public function index()
    {
        $filtros = [
            'search' => $this->request->getGet('search'),
            'type' => $this->request->getGet('type'),
            'user_id' => $this->request->getGet('user_id'),
            'module' => $this->request->getGet('module'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date')
        ];

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $bitacoras = $this->bitacoraModel
            ->obtenerBitacorasFiltradas($filtros)
            ->paginate($perPage, 'default', $currentPage);

        $total_registros = $this->bitacoraModel->contarRegistros($filtros);
        $total_paginas = ceil($total_registros / $perPage);

        $data = [
            'titulo_pagina' => 'Bitácora de Usuarios',
            'bitacoras' => $bitacoras,
            'usuarios' => $this->bitacoraModel->obtenerUsuariosParaSelect(),
            'tipos_accion' => $this->bitacoraModel->obtenerTiposAccion(),
            'modulos' => $this->bitacoraModel->obtenerModulos(),
            'filtros_actuales' => $filtros,
            'total_registros' => $total_registros,
            'total_actividades' => $total_registros,
            'actividades_hoy' => $this->bitacoraModel->where('DATE(fecha_creacion)', date('Y-m-d'))->countAllResults(),
            'inicios_sesion' => $this->bitacoraModel->where('accion', 'Login')->countAllResults(),
            'creaciones' => $this->bitacoraModel->like('accion', 'Creación')->countAllResults(),
            'pager' => $this->bitacoraModel->pager,
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'total_paginas' => $total_paginas
        ];

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('bitacora/index', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }
}
