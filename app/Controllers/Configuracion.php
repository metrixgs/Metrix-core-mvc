<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\TareasModel;
use App\Models\NotificacionesModel;
use App\Models\ConfiguracionModel;
use App\Models\SLAModel;

class Configuracion extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $tareas;
    protected $notificaciones;
    protected $configuracion;
    protected $sla;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();
        $this->configuracion = new ConfiguracionModel();
        $this->sla = new SLAModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Configuracion SMTP';

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos la informacion de la configuracion...
        $configuracion = session('configuracion');
        $data['configuracion'] = $configuracion;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('configuracion/inicio-configuracion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function sla() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Configuracion TDR';

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtener los SLA del sistema...
        $sla_list = $this->sla->obtenerSLAs();
        $data['sla_list'] = $sla_list;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('configuracion/configuracion-sla', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function eliminar_sla($sla_id) {
        # Obtenemos informacion del TDR...
        $sla = $this->sla->obtenerSLA($sla_id);

        # Validamos si existe el TDR...
        if (empty($sla)) {
            # No existe el TDR...
            return redirect()->to("configuracion/sla");
        }

        # Eliminamos el TDR...
        if ($this->sla->eliminarSLA($sla_id)) {
            # Se elimino el TDR...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado el TDR de titulo {$sla['titulo']}, de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("configuracion/sla");
        } else {
            # Se elimino el TDR...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar el TDR de titulo {$sla['titulo']}, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("configuracion/sla/detalle/{$sla_id}");
        }
    }

    public function crear_sla() {
        # Obtenemos los datos del formulario...
        $titulo = $this->request->getPost('titulo');
        $tiempo = $this->request->getPost('tiempo');
        $color = $this->request->getPost('color');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'titulo' => 'required',
            'tiempo' => 'required|numeric',
            'color' => 'required',
            'descripcion' => 'required'
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("configuracion/sla")->withInput();
        }

        # Creamos la variable para crear el SLA...
        $infoSLA = [
            'titulo' => $titulo,
            'tiempo' => $tiempo,
            'color' => $color,
            'descripcion' => $descripcion
        ];

        # Creamos el TDR...
        if ($this->sla->crearSLA($infoSLA)) {
            # Se creo el TDR
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado el TDR de titulo {$tiempo}, de forma correcta.",
                'tipo' => "success"
            ]);

            return redirect()->to("configuracion/sla");
        } else {
            # No se creo el TDR...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido crear el TDR de titulo {$titulo}, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("configuracion/sla");
        }
    }

    public function actualizar() {
        # Obtenemos los parametros del formulario...
        $fromEmail = $this->request->getPost('fromEmail');
        $fromName = $this->request->getPost('fromName');
        $SMTPHost = $this->request->getPost('SMTPHost');
        $SMTPUser = $this->request->getPost('SMTPUser');
        $SMTPPass = $this->request->getPost('SMTPPass');
        $SMTPPort = $this->request->getPost('SMTPPort');
        $SMTPCrypto = $this->request->getPost('SMTPCrypto');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'fromEmail' => 'required|valid_email', // Correo válido
            'fromName' => 'required|string|min_length[3]', // Nombre del remitente, debe ser mínimo 3 caracteres
            'SMTPHost' => 'required|string|min_length[3]', // Host SMTP, debe ser mínimo 3 caracteres
            'SMTPUser' => 'required|string|min_length[3]', // Usuario SMTP, debe ser mínimo 3 caracteres
            'SMTPPass' => 'required|string|min_length[8]', // Contraseña SMTP, debe ser mínimo 8 caracteres
            'SMTPPort' => 'required|numeric|min_length[3]|max_length[5]', // Puerto SMTP, debe ser numérico y entre 3 y 5 caracteres
            'SMTPCrypto' => 'required|in_list[tls,ssl]', // Cifrado, debe ser "tls" o "ssl"
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Creamos la variable de actualizacion...
        $infoConfiguracion = [
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'smtp_host' => $SMTPHost,
            'smtp_user' => $SMTPUser,
            'smtp_pass' => $SMTPPass,
            'smtp_port' => $SMTPPort,
            'smtp_crypto' => $SMTPCrypto
        ];

        # Definimos el id de la configuracion...
        $configuracion_id = 1;

        # Actualizamos la informacion de la configuracion...
        if ($this->configuracion->actualizarConfiguracion($configuracion_id, $infoConfiguracion)) {
            # Se actualizo la configuracion...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la informacion de la configuracion exitosamente.",
                'tipo' => "success"
            ]);

            # Obtenemos la informacion de la configuracion...
            $configuracion = $this->configuracion->obtenerConfiguracion($configuracion_id);

            # Actualizamos la informacion de la session...
            session()->set('configuracion', $configuracion);

            return redirect()->to("configuracion/");
        } else {
            # No se pudo actualizar la informacion...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo actualizar la informacion de la configuracion, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("configuracion/")->withInput();
        }
    }
}
