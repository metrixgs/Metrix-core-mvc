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

class Dependencias extends BaseController {

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

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Lista de Dependencias';

        # Obtenemos todas las dependencias...
        $dependencias = $this->areas->obtenerAreas();
        $data['dependencias'] = $dependencias;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('dependencias/lista-dependencias', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function detalle($dependencia_id) {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Detalle de la Dependencia';

        # Obtenemos informacion de la dependencia...
        $dependencia = $this->areas->obtenerArea($dependencia_id);
        $data['dependencia'] = $dependencia;

        # Validamos si existe la dependencia...
        if (empty($dependencia)) {
            # No xiste la dependencia...
            return redirect()->to("dependencias/");
        }

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todos los usuarios por dependencia...
        $usuarios = $this->usuarios->obtenerUsuariosPorArea($dependencia_id);
        $data['usuarios'] = $usuarios;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('dependencias/detalle-dependencia', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function crear() {
        # Obtenemos los datos del formulario...
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'nombre' => 'required',
            'descripcion' => 'permit_empty',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to('dependencias/')->withInput();
        }

        # Creamos la variable con la informacion de la dependencia...
        $infoDependencia = [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];

        # Creamos la nueva dependencia...
        if ($this->areas->crearArea($infoDependencia)) {
            # Se creo la dependencia...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado la dependencia de nombre <strong>{$nombre}</strong>, de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("dependencias/");
        } else {
            # No se pudo crear la dependencia...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido crear la dependencia de nombre "
                . "<strong>{$nombre}</strong>, intentalo nuevamnete.",
                'tipo' => "danger"
            ]);

            return redirect()->to("dependencias/");
        }
    }

    public function actualizar($dependencia_id) {
        # Obtenemos informacion de la dependedncia...
        $dependencia = $this->areas->obtenerArea($dependencia_id);

        # Validamos si existe la dependencias...
        if (empty($dependencia)) {
            # No existe la dependencia...
            return redirect()->to("dependencias/");
        }

        # Obtenemos la informacion del formulario...
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'nombre' => 'required',
            'descripcion' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Creamos la variable de actualizacion...
        $infoDependencia = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ];

        # Actualizamos la informacion de la dependencia...
        if ($this->areas->actualizarArea($dependencia_id, $infoDependencia)) {
            # Se actualizo la dependencia...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la dependencia de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("dependencias/detalle/{$dependencia_id}");
        } else {
            # No se actualizo la dependencia...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido actualizar la dependencia de nombre "
                . "<strong>{$dependencia['nombre']}</strong>, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("dependencias/detalle/{$dependencia_id}");
        }
    }

    public function eliminar() {
        # Obtenemos los datos del formulario...
        $dependencia_id = $this->request->getPost('dependencia_id');
        
        # Obtenemos informacion de la dependedncia...
        $dependencia = $this->areas->obtenerArea($dependencia_id);

        # Validamos si existe la dependencias...
        if (empty($dependencia)) {
            # No existe la dependencia...
            return redirect()->to("dependencias/");
        }

        # Procedemos a eliminar la dependencia...
        if ($this->areas->eliminarArea($dependencia_id)) {
            # Dependencia eliminada...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminad la dependencia de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("dependencias/");
        } else {
            # No se pudo eliminar la dependenciaa....
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar la dependencias, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("dependencias/detalle/{$dependencia_id}");
        }
    }
}
