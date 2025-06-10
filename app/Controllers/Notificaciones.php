<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\NotificacionesModel;

class Notificaciones extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $notificaciones;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Lista de Notificaciones';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('notificaciones/lista-notificaciones', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function nueva() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Nueva Notificacion';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todos los usuarios...
        $usuarios = $this->usuarios->obtenerUsuarios();
        $data['usuarios'] = $usuarios;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('notificaciones/nueva-notificacion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function crear() {
        # Obtenemos la informacion del formulairo...
        $usuario_id = $this->request->getPost('usuario_id');
        $titulo = $this->request->getPost('titulo');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'usuario_id' => 'required|numeric',
            'titulo' => 'required',
            'descripcion' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("notificaciones/nueva")->withInput();
        }

        # Obtenemos informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);

        # Validamos si el usuario es el mismo logueado...
        if ($usuario['id'] === session('session_data.id')) {
            # No se puede enviar una notificacion al mismo usuario...
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "No se puede enviar una notificacion al mismo usuario logueado, "
                . "intentalo nuevamente seleccionando un usuario diferente.",
                'tipo' => "warning"
            ]);

            return redirect()->to("notificacion/nueva");
        }

        # Creamos la variable de la notificacion...
        $infoNotificacion = [
            'usuario_id' => $usuario_id,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        # Creamos la nueva notificacion...
        if ($this->notificaciones->crearNotificacion($infoNotificacion)) {
            # Se creo la notificacion...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha enviado la notificacion al usuario "
                . "<strong>{$usuario['nombre']}</strong> de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("notificaciones/");
        } else {
            # No se creo la notificacion...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo enviar la notificacion del usuario "
                . "<strong>{$usuario['nombre']}</strong>, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("notificaciones/nueva");
        }
    }

    public function eliminar($notificacion_id) {
        # Obtenemos informacion de la notificacion...
        $notificacion = $this->notificaciones->obtenerNotificacion($notificacion_id);

        # Validamos si existe la notificacion...
        if (empty($notificacion)) {
            # No existe la notificacion...
            return redirect()->to("notificaciones/");
        }

        # Validamos si la notificacion le pertenece al usuario...
        if ($notificacion['usuario_id'] != session('session_data.id')) {
            # No es del usuario...
            return redirect()->to("notificaciones/");
        }

        # Eliminamos la notificacion...
        if ($this->notificaciones->eliminarNotificacion($notificacion_id)) {
            # Notitificacion eliminada
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado la notificacion de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("notificaciones/");
        } else {
            # No se elimino la notificacion...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar la notificacion, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("notificaciones/detalle/{$notificacion_id}");
        }
    }
}
