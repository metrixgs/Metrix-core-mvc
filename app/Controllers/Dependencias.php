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
use App\Models\RolesModel;

class Dependencias extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $notificaciones;
    protected $tareas;
    protected $areas;
    protected $acciones;
    protected $documentos;
    protected $roles; // Añadir propiedad para RolesModel

    public function __construct() {
        # Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();
        $this->areas = new AreasModel();
        $this->acciones = new AccionesTicketsModel();
        $this->documentos = new DocumentosTareasModel();
        $this->roles = new RolesModel(); // Instanciar RolesModel

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

        # Obtenemos el ID del rol "Operador"
        $rolOperador = $this->roles->obtenerRolPorNombre('Operador');
        $rolOperadorId = $rolOperador ? $rolOperador['id'] : null;

        # Obtenemos todos los usuarios con rol "Operador" que no están asignados a esta dependencia
        $usuariosOperadoresDisponibles = [];
        if ($rolOperadorId) {
            $todosOperadores = $this->usuarios->obtenerUsuariosPorRol($rolOperadorId);
            $usuariosAsignadosIds = array_column($usuarios, 'id'); // IDs de usuarios ya asignados
            
            foreach ($todosOperadores as $operador) {
                // Solo añadir si el usuario no tiene un area_id o si su area_id no es la actual
                // y no está ya en la lista de usuarios asignados a esta dependencia
                if (empty($operador['area_id']) || ($operador['area_id'] != $dependencia_id && !in_array($operador['id'], $usuariosAsignadosIds))) {
                    $usuariosOperadoresDisponibles[] = $operador;
                }
            }
        }
        $data['usuarios_operadores_disponibles'] = $usuariosOperadoresDisponibles;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('dependencias/detalle-dependencia', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function asignarUsuario($dependencia_id) {
        # Creamos el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Asignar Usuario a Dependencia';

        # Obtenemos informacion de la dependencia...
        $dependencia = $this->areas->obtenerArea($dependencia_id);
        $data['dependencia'] = $dependencia;

        # Validamos si existe la dependencia...
        if (empty($dependencia)) {
            # No existe la dependencia...
            return redirect()->to("dependencias/");
        }

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos el ID del rol "Operador"
        $rolOperador = $this->roles->obtenerRolPorNombre('Operador');
        $rolOperadorId = $rolOperador ? $rolOperador['id'] : null;

        # Obtenemos todos los usuarios con rol "Operador" que no están asignados a esta dependencia
        $usuariosOperadoresDisponibles = [];
        if ($rolOperadorId) {
            $todosOperadores = $this->usuarios->obtenerUsuariosPorRol($rolOperadorId);
            $usuariosAsignadosIds = array_column($this->usuarios->obtenerUsuariosPorArea($dependencia_id), 'id'); // IDs de usuarios ya asignados
            
            foreach ($todosOperadores as $operador) {
                // Solo añadir si el usuario no tiene un area_id o si su area_id no es la actual
                // y no está ya en la lista de usuarios asignados a esta dependencia
                if (empty($operador['area_id']) || ($operador['area_id'] != $dependencia_id && !in_array($operador['id'], $usuariosAsignadosIds))) {
                    $usuariosOperadoresDisponibles[] = $operador;
                }
            }
        }
        $data['usuarios_operadores_disponibles'] = $usuariosOperadoresDisponibles;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('dependencias/asignar-usuario', $data)
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
    public function asignarUsuarioADependencia() {
        # Obtenemos los datos del formulario
        $dependencia_id = $this->request->getPost('dependencia_id');
        $usuario_id = $this->request->getPost('usuario_id');

        # Validamos que ambos IDs existan
        if (empty($dependencia_id) || empty($usuario_id)) {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Datos incompletos para la asignación.",
                'tipo' => "danger"
            ]);
            return redirect()->back();
        }

        # Obtenemos información del usuario y la dependencia
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);
        $dependencia = $this->areas->obtenerArea($dependencia_id);

        # Validamos que el usuario y la dependencia existan
        if (empty($usuario) || empty($dependencia)) {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Usuario o dependencia no encontrados.",
                'tipo' => "danger"
            ]);
            return redirect()->back();
        }

        # Actualizamos el campo area_id del usuario
        $dataUsuario = ['area_id' => $dependencia_id];
        if ($this->usuarios->actualizarUsuario($usuario_id, $dataUsuario)) {
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "El usuario <strong>{$usuario['nombre']}</strong> ha sido asignado a la dependencia <strong>{$dependencia['nombre']}</strong> de forma exitosa.",
                'tipo' => "success"
            ]);
        } else {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo asignar el usuario a la dependencia. Inténtalo nuevamente.",
                'tipo' => "danger"
            ]);
        }

        return redirect()->to("dependencias/detalle/{$dependencia_id}");
    }

    public function desasignarUsuarioDeDependencia() {
        # Obtenemos los datos del formulario
        $dependencia_id = $this->request->getPost('dependencia_id');
        $usuario_id = $this->request->getPost('usuario_id');

        # Validamos que ambos IDs existan
        if (empty($dependencia_id) || empty($usuario_id)) {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Datos incompletos para la desasignación.",
                'tipo' => "danger"
            ]);
            return redirect()->back();
        }

        # Obtenemos información del usuario y la dependencia
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);
        $dependencia = $this->areas->obtenerArea($dependencia_id);

        # Validamos que el usuario y la dependencia existan
        if (empty($usuario) || empty($dependencia)) {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Usuario o dependencia no encontrados.",
                'tipo' => "danger"
            ]);
            return redirect()->back();
        }

        # Actualizamos el campo area_id del usuario a NULL para desasignarlo
        $dataUsuario = ['area_id' => null];
        if ($this->usuarios->actualizarUsuario($usuario_id, $dataUsuario)) {
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "El usuario <strong>{$usuario['nombre']}</strong> ha sido desasignado de la dependencia <strong>{$dependencia['nombre']}</strong> de forma exitosa.",
                'tipo' => "success"
            ]);
        } else {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo desasignar el usuario de la dependencia. Inténtalo nuevamente.",
                'tipo' => "danger"
            ]);
        }

        return redirect()->to("dependencias/detalle/{$dependencia_id}");
    }
}
