<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\AreasModel;
use App\Models\NotificacionesModel;
use App\Models\ConversacionesModel;
use App\Models\MensajesModel;

class Soporte extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $notificaciones;
    protected $conversaciones;
    protected $mensajes;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->areas = new AreasModel();
        $this->conversaciones = new ConversacionesModel();
        $this->mensajes = new MensajesModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamos el título de la página...
        $data['titulo_pagina'] = 'Metrix | Lista de Conversaciones';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todas las conversaciones...
        $conversaciones = $this->conversaciones->ObtenerConversaciones();
        $data['conversaciones'] = $conversaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('soporte/lista-conversaciones', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function detalle($conversacion_id) {
        # Creamos el título de la página...
        $data['titulo_pagina'] = 'Metrix | Detalle de la Conversacion';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos la conversación por usuario...
        $conversacion = $this->conversaciones->ObtenerConversacion($conversacion_id);
        $data['conversacion'] = $conversacion;

        # Validamos si hay una conversación antes de intentar obtener mensajes
        if ($conversacion) {
            $data['conversacion'] = $conversacion;

            # Obtenemos todos los mensajes por conversación...
            $mensajes = $this->mensajes->obtenerMensajesPorConversacion($conversacion['id']);
            $data['mensajes'] = $mensajes;
        } else {
            # Si no hay conversación, inicializamos valores vacíos
            $data['conversacion'] = null;
            $data['mensajes'] = [];
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('soporte/detalle-conversacion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function crear() {
        # Obtenemos los datos del formulario...
        $mensaje = $this->request->getPost('mensaje');
        $conversacion_id = $this->request->getPost('conversacion_id');

        # Definimos el id del usuario...
        $usuario_id = session('session_data.id');

        # Obtenemos informacion del usuario...
        $usuarioActual = $this->usuarios->obtenerUsuario($usuario_id);

        # Obtenemos informacion de la conversacion...
        $conversacion = $this->conversaciones->ObtenerConversacion($conversacion_id);

        # Creamos la variable del mensaje...
        $infoMensaje = [
            'conversacion_id' => $conversacion['id'],
            'remitente_id' => $usuario_id,
            'mensaje' => $mensaje,
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'leido' => 0
        ];

        # Crear el mensaje...
        if ($this->mensajes->crearMensaje($infoMensaje)) {
            # Obtenemos la informacion del usuario...
            $usuario = $this->usuarios->obtenerUsuario($conversacion['usuario_id']);

            # Definimos la variable de los correos...
            $correos = [$usuario['correo']];

            # Creamos la notificacion...
            $infoNotificacion = [
                'usuario_id' => $conversacion['usuario_id'],
                'titulo' => 'Respuesta Soporte',
                'descripcion' => "Un administrador ha respondido al ticket de soporte que has realizado.",
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            # Creamos la notificacion...
            $this->notificaciones->crearNotificacion($infoNotificacion);

            # Enviamos la notificación al correo...
            # Configuración de datos
            $destinatarios = $correos;
            $asunto = 'Respuesta Soporte';
            $adjuntos = [];
            $bcc = [];

            # Cargar la plantilla del recordatorio
            $template = file_get_contents('app/Views/templates/notificacion_respuesta_soporte.php');

            # Reemplazar las variables dinámicas con la información de la notificación de soporte
            $variables = [];

            # Modificamos la plantilla con la información dinámica
            $mensaje = str_replace(array_keys($variables), array_values($variables), $template);

            # Enviamos el correo a los destinatarios
            enviarCorreo($destinatarios, $asunto, $mensaje, $adjuntos, true, $bcc);

            return redirect()->to("soporte/conversaciones/detalle/{$conversacion_id}");
        } else {
            # No se pudo enviar el mensaje...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo enviar el mensaje, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("soporte/");
        }
    }

    public function mensajes() {
        # Suponiendo que estás obteniendo los mensajes de la base de datos
        header('Content-Type: application/json');

        # Definimos el usuario...
        $usuario_id = session('session_data.id');

        # Obtenemos la conversacion del usuario...
        $convresacion = $this->conversaciones->ObtenerConversacionPorUsuario($usuario_id);

        # Obtenemos los mensajes de la conversacion...
        $mensajes = $this->mensajes->obtenerMensajesPorConversacion($convresacion['id']);

        echo json_encode($mensajes);
    }

    public function descargar() {
        # Definimos el usuario...
        $usuario_id = session('session_data.id');

        # Obtenemos la conversacion del usuario...
        $convresacion = $this->conversaciones->ObtenerConversacionPorUsuario($usuario_id);

        # Obtenemos los mensajes de la conversacion...
        $mensajes = $this->mensajes->obtenerMensajesPorConversacion($convresacion['id']);

        // Crear el contenido del archivo
        $contenido = "";
        foreach ($mensajes as $mensaje) {
            $remitente = ($mensaje['remitente_id'] === session('session_data.id')) ? "Tú" : $mensaje['nombre_remitente'];
            $fecha = date('d M Y h:i a', strtotime($mensaje['fecha_creacion']));  // Formato de la fecha
            $contenido .= "$remitente ($fecha):\n";
            $contenido .= $mensaje['mensaje'] . "\n\n";
        }

        // Definir el nombre del archivo
        $filename = "conversacion_" . date('Y_m_d_H_i_s') . ".txt";

        // Configurar las cabeceras para la descarga del archivo
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($contenido));

        // Imprimir el contenido del archivo
        echo $contenido;

        exit;
    }
}
