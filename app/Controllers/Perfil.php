<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\AreasModel;
use App\Models\NotificacionesModel;

class Perfil extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $notificaciones;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->areas = new AreasModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Mi Perfil';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTicketsPorCliente(session('session_data.id'));
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obbtenemos la informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario(session('session_data.id'));
        $data['usuario'] = $usuario;

        # Obtenemos todas las areas...
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('perfil/informacion-perfil', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function actualizar() {
        # Obtenemos informacion del formulario...
        $correo = $this->request->getPost('correo');
        $contrasena = $this->request->getPost('contrasena');

        # Definimos el id del usuario...
        $usuario_id = session('session_data.id');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'correo' => "required|valid_email|is_unique[tbl_usuarios.correo,id,{$usuario_id}]",
            'contrasena' => 'required'
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("perfil/")->withInput();
        }

        # Creamos la variable de acctualizacion...
        $infoUsuario = [
            'correo' => $correo,
            'contrasena' => $contrasena
        ];

        # Actualizamos la informacion de la cuenta...
        if ($this->usuarios->actualizarUsuario($usuario_id, $infoUsuario)) {
            #  Se actualizo el usuario...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la informacion de la cuenta de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("perfil/");
        } else {
            # No se pudo actualizar...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido actualizar la informacion de la cuenta, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("perfil/")->withInput();
        }
    }

    public function eliminarCuenta() {
        # Obtenemos informacion de la cuenta...
        $cuenta = $this->usuarios->obtenerUsuario(session('session_data.id'));

        # Eliminamos la cuenta...
        if ($this->usuarios->eliminarUsuario($cuenta['id'])) {
            # Se ha eliminado la cuenta...
            # Guardamos el mensaje de éxito en flashdata
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Cuenta eliminada exitosamente.",
                'tipo' => "success"
            ]);

            # Destruimos la sesión
            $this->session->destroy();

            # Redirigimos al login, después de destruir la sesión
            return redirect()->to('autenticacion/inicio');
        } else {
            # No se pudo eliminar la cuenta...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar la cuenta, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            # Destruimos la sesión
            $this->session->destroy();

            # Redirigimos al perfil en caso de error
            return redirect()->to("perfil/");
        }
    }
}
