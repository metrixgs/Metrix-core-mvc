<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrigadasModel;
use App\Models\UsuariosModel;
use App\Models\AreasModel;

class Brigadas extends BaseController
{
    protected $brigadas;
    protected $usuarios;
    protected $areas;
    protected $db;

    public function __construct()
    {
        $this->brigadas = new BrigadasModel();
        $this->usuarios = new UsuariosModel();
        $this->areas = new AreasModel();
        $this->db = \Config\Database::connect();
        helper(['Alerts', 'Rol', 'Menu']);
    }

    public function index()
    {
        $data['titulo_pagina'] = 'Metrix | Brigadas';
        $data['brigadas'] = $this->brigadas->getAllBrigadas();
        $data['coordinadores'] = $this->usuarios->getCoordinadores();
        $data['operativos'] = $this->usuarios->getOperativos();
        $data['areas'] = $this->areas->findAll();

        log_message('debug', 'Renderizando vista de lista de brigadas');
        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('brigadas/lista-brigadas', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function detalle($brigada_id)
{
    $data['titulo_pagina'] = 'Metrix | Detalle de la Brigada';
    $brigada = $this->brigadas->get_brigada_by_id($brigada_id);
    log_message('debug', 'Brigada data: ' . print_r($brigada, true));
    $data['brigada'] = $brigada;

    if (empty($brigada)) {
        log_message('error', 'Brigada no encontrada para ID: ' . $brigada_id);
        $this->session->setFlashdata([
            'titulo' => '¡Error!',
            'mensaje' => 'Brigada no encontrada',
            'tipo' => 'danger'
        ]);
        return redirect()->to('brigadas');
    }

    // Obtener actividades de la brigada
    /*$actividades = $this->db->table('actividades')
                            ->select('tipo_actividad, descripcion, fecha')
                            ->where('brigada_id', $brigada_id)
                            ->orderBy('fecha', 'DESC')
                            ->get()
                            ->getResultArray();
    $data['brigada']['actividades'] = $actividades;}*/

    log_message('debug', 'Renderizando vista detalle para brigada ID: ' . $brigada_id);
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('brigadas/detalles-brigada', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}

    public function crear()
    {
        log_message('debug', 'Solicitud POST recibida en Brigadas::crear');
        $area_id = $this->request->getPost('area_id');
        $coordinador_id = $this->request->getPost('coordinador_id');
        $enlace_id = $this->request->getPost('enlace_id');
        $integrantes = $this->request->getPost('integrantes') ?: [];

        $validationRules = [
            'area_id' => 'required|numeric|is_not_unique[tbl_areas.id]',
            'coordinador_id' => 'required|numeric|is_not_unique[tbl_usuarios.id]',
            'enlace_id' => 'required|numeric|is_not_unique[tbl_usuarios.id]',
            'integrantes.*' => 'permit_empty|numeric|is_not_unique[tbl_usuarios.id]'
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Errores de validación: ' . print_r($errors, true));
            $this->session->setFlashdata('validation', $errors);
            return redirect()->to('brigadas')->withInput();
        }

        // Validar que coordinador_id sea coordinador
        $coordinador = $this->db->table('tbl_usuarios')->select('rol')->where('id', $coordinador_id)->get()->getRow();
        if (!$coordinador || $coordinador->rol !== 'coordinador') {
            log_message('error', 'El coordinador debe ser un coordinador');
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'El coordinador debe ser un usuario con rol coordinador.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }

        // Validar que enlace_id sea coordinador
        $enlace = $this->db->table('tbl_usuarios')->select('rol')->where('id', $enlace_id)->get()->getRow();
        if (!$enlace || $enlace->rol !== 'coordinador') {
            log_message('error', 'El enlace debe ser un coordinador');
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'El enlace debe ser un usuario con rol coordinador.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }

        // Validar que integrantes sean operativos
        if (!empty($integrantes)) {
            $invalidIntegrantes = $this->db->table('tbl_usuarios')
                                           ->select('id')
                                           ->whereIn('id', $integrantes)
                                           ->where('rol !=', 'operativo')
                                           ->get()
                                           ->getResultArray();
            if (!empty($invalidIntegrantes)) {
                log_message('error', 'Los integrantes deben ser operativos');
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'Los integrantes deben ser usuarios con rol operativo.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('brigadas')->withInput();
            }
        }

        $infoBrigada = [
            'area_id' => $area_id,
            'coordinador_id' => $coordinador_id,
            'enlace_id' => $enlace_id,
            'integrantes' => $integrantes
        ];

        try {
            if ($this->brigadas->createBrigada($infoBrigada)) {
                log_message('debug', 'Brigada creada exitosamente');
                $this->session->setFlashdata([
                    'titulo' => '¡Éxito!',
                    'mensaje' => 'Se ha creado la brigada de forma exitosa.',
                    'tipo' => 'success'
                ]);
                return redirect()->to('brigadas');
            } else {
                log_message('error', 'No se pudo crear la brigada');
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'No se pudo crear la brigada, inténtalo nuevamente.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('brigadas')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al crear la brigada: ' . $e->getMessage());
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'Error inesperado al crear la brigada: ' . $e->getMessage(),
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }
    }

    public function actualizar()
    {
        log_message('debug', 'Solicitud POST recibida en Brigadas::actualizar');
        $brigada_id = $this->request->getPost('id');
        $area_id = $this->request->getPost('area_id');
        $coordinador_id = $this->request->getPost('coordinador_id');
        $enlace_id = $this->request->getPost('enlace_id');
        $integrantes = $this->request->getPost('integrantes') ?: [];

        $validationRules = [
            'id' => 'required|numeric|is_not_unique[brigadas.id]',
            'area_id' => 'required|numeric|is_not_unique[tbl_areas.id]',
            'coordinador_id' => 'required|numeric|is_not_unique[tbl_usuarios.id]',
            'enlace_id' => 'required|numeric|is_not_unique[tbl_usuarios.id]',
            'integrantes.*' => 'permit_empty|numeric|is_not_unique[tbl_usuarios.id]'
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Errores de validación: ' . print_r($errors, true));
            $this->session->setFlashdata('validation', $errors);
            return redirect()->to('brigadas')->withInput();
        }

        $brigada = $this->brigadas->getBrigadaById($brigada_id);
        if (empty($brigada)) {
            log_message('error', 'Brigada no encontrada para ID: ' . $brigada_id);
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'Brigada no encontrada',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas');
        }

        // Validar que coordinador_id sea coordinador
        $coordinador = $this->db->table('tbl_usuarios')->select('rol')->where('id', $coordinador_id)->get()->getRow();
        if (!$coordinador || $coordinador->rol !== 'coordinador') {
            log_message('error', 'El coordinador debe ser un coordinador');
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'El coordinador debe ser un usuario con rol coordinador.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }

        // Validar que enlace_id sea coordinador
        $enlace = $this->db->table('tbl_usuarios')->select('rol')->where('id', $enlace_id)->get()->getRow();
        if (!$enlace || $enlace->rol !== 'coordinador') {
            log_message('error', 'El enlace debe ser un coordinador');
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'El enlace debe ser un usuario con rol coordinador.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }

        // Validar que integrantes sean operativos
        if (!empty($integrantes)) {
            $invalidIntegrantes = $this->db->table('tbl_usuarios')
                                           ->select('id')
                                           ->whereIn('id', $integrantes)
                                           ->where('rol !=', 'operativo')
                                           ->get()
                                           ->getResultArray();
            if (!empty($invalidIntegrantes)) {
                log_message('error', 'Los integrantes deben ser operativos');
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'Los integrantes deben ser usuarios con rol operativo.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('brigadas')->withInput();
            }
        }

        $infoBrigada = [
            'area_id' => $area_id,
            'coordinador_id' => $coordinador_id,
            'enlace_id' => $enlace_id,
            'integrantes' => $integrantes
        ];

        try {
            if ($this->brigadas->updateBrigada($brigada_id, $infoBrigada)) {
                log_message('debug', 'Brigada actualizada exitosamente');
                $this->session->setFlashdata([
                    'titulo' => '¡Éxito!',
                    'mensaje' => 'Se ha actualizado la brigada de forma exitosa.',
                    'tipo' => 'success'
                ]);
                return redirect()->to('brigadas');
            } else {
                log_message('error', 'No se pudo actualizar la brigada');
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'No se pudo actualizar la brigada, inténtalo nuevamente.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('brigadas')->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al actualizar la brigada: ' . $e->getMessage());
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'Error inesperado al actualizar la brigada: ' . $e->getMessage(),
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas')->withInput();
        }
    }

    public function eliminar()
    {
        log_message('debug', 'Solicitud POST recibida en Brigadas::eliminar');
        $brigada_id = $this->request->getPost('id');

        $validationRules = [
            'id' => 'required|numeric|is_not_unique[brigadas.id]'
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Errores de validación: ' . print_r($errors, true));
            $this->session->setFlashdata('validation', $errors);
            return redirect()->to('brigadas');
        }

        $brigada = $this->brigadas->getBrigadaById($brigada_id);
        if (empty($brigada)) {
            log_message('error', 'Brigada no encontrada para ID: ' . $brigada_id);
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'Brigada no encontrada',
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas');
        }

        try {
            if ($this->brigadas->deleteBrigada($brigada_id)) {
                log_message('debug', 'Brigada eliminada exitosamente');
                $this->session->setFlashdata([
                    'titulo' => '¡Éxito!',
                    'mensaje' => 'Se ha eliminado la brigada de forma exitosa.',
                    'tipo' => 'success'
                ]);
                return redirect()->to('brigadas');
            } else {
                log_message('error', 'No se pudo eliminar la brigada');
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'No se pudo eliminar la brigada, inténtalo nuevamente.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('brigadas');
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al eliminar la brigada: ' . $e->getMessage());
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'Error inesperado al eliminar la brigada: ' . $e->getMessage(),
                'tipo' => 'danger'
            ]);
            return redirect()->to('brigadas');
        }
    }
}