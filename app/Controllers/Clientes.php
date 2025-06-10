<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\AreasModel;
use App\Models\NotificacionesModel;
use App\Models\PermisosModel;

class Clientes extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $notificaciones;
    protected $permisos;

    # Definimos el id del modulo...
    protected $modulo_id = 1;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->areas = new AreasModel();
        $this->permisos = new PermisosModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamos el título de la página...
        $data['titulo_pagina'] = 'Metrix | Lista de Clientes';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos la lista de clientes...
        $clientes = $this->usuarios->obtenerUsuariosPorRol('cliente');
        $data['clientes'] = $clientes;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('clientes/lista-clientes', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function detalle($cliente_id) {
        # Creamos el título de la página...
        $data['titulo_pagina'] = 'Metrix | Informacion del Cliente';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemso informacion del cliente...
        $cliente = $this->usuarios->obtenerUsuario($cliente_id);
        $data['cliente'] = $cliente;

        # Validamos si existe el cliente...
        if (empty($cliente)) {
            # No existe el cliente...
            return redirect()->to("clientes/");
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('clientes/detalle-cliente', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function crear() {
        # Obtenemos información del formulario...
        $nombre = $this->request->getPost('nombre');
        $correo = $this->request->getPost('correo');
        $telefono = $this->request->getPost('telefono');
        $contrasena = $this->request->getPost('contrasena');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'nombre' => 'required',
            'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
            'telefono' => 'required|numeric',
            'contrasena' => 'required|min_length[8]'
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());
            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Hasheamos la contraseña para seguridad
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        # Creamos la variable del nuevo cliente...
        $infoCliente = [
            'area_id' => NULL,
            'cargo' => NULL,
            'nombre' => strtoupper($nombre),
            'correo' => $correo,
            'telefono' => $telefono,
            'contrasena' => $hashedPassword,
            'rol' => 'cliente',
                # No necesitamos incluir fecha_registro ya que se generará automáticamente por default
        ];

        # Creamos el nuevo cliente...
        if ($this->usuarios->crearUsuario($infoCliente)) {
            # Se creó el cliente...
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Se ha creado de forma exitosa el cliente <strong>{$nombre}</strong>.",
                'tipo' => "success"
            ]);
            return redirect()->to("clientes/");
        } else {
            # No se pudo crear el cliente...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido crear el cliente <strong>{$nombre}</strong>, inténtalo nuevamente.",
                'tipo' => "danger"
            ]);
            return redirect()->to("clientes/");
        }
    }

    public function actualizar() {
        # Obtenemos los datos del formulario...
        $cliente_id = $this->request->getPost('cliente_id');
        $nombre = $this->request->getPost('nombre');
        $correo = $this->request->getPost('correo');
        $contrasena = $this->request->getPost('contrasena');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'nombre' => 'required',
            'correo' => "required|valid_email|is_unique[tbl_usuarios.correo,id,{$cliente_id}]",
            'contrasena' => 'required|min_length[8]',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("clientes/detalle/{$cliente_id}")->withInput();
        }

        # Creamos la variable de actualizacion...
        $infoCliente = [
            'nombre' => $nombre,
            'correo' => $correo,
            'contrasena' => $contrasena
        ];

        # Actualizamos la informacion del cliente...
        if ($this->usuarios->actualizarUsuario($cliente_id, $infoCliente)) {
            # Se actualizo al cliente...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la informacion del cliente de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("clientes/detalle/{$cliente_id}");
        } else {
            # No se pudo actualizar
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido actualizar la informacion del cliente, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("clientes/detalle/{$cliente_id}");
        }
    }

    public function eliminar($cliente_id) {
        # Obtenemos informacion del cliente...
        $cliente = $this->usuarios->obtenerUsuario($cliente_id);

        # Validamos si existe el cliente...
        if (empty($cliente)) {
            # No existe el cliente...
            return redirect()->to("clientes/");
        }

        # Eliminamos el cliente...
        if ($this->usuarios->eliminarUsuario($cliente_id)) {
            # Se elimino el cliente...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado el cliente de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("clientes/");
        } else {
            # No se elimino el cliente...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar el cliente, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("clientes/detalle/{$cliente_id}");
        }
    }
}
