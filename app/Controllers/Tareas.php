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
use App\Models\SLAModel;

class Tareas extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $notificaciones;
    protected $tareas;
    protected $areas;
    protected $acciones;
    protected $documentos;
    protected $sla;

    public function __construct() {
        # Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();
        $this->areas = new AreasModel();
        $this->acciones = new AccionesTicketsModel();
        $this->documentos = new DocumentosTareasModel();
        $this->sla = new SLAModel;

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Lista de Tareas';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las tareas...
        $tareas = $this->tareas->obtenerTareasPorArea(session('session_data.area_id'));
        $data['tareas'] = $tareas;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tareas/lista-tareas', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function detalle($tarea_id) {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Detalle de la Tarea';

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos informacion de la tarea...
        $tarea = $this->tareas->obtenerTarea($tarea_id);
        $data['tarea'] = $tarea;

        # Validamos si la tarea existe...
        if (empty($tarea)) {
            # No existe la tarea...
            return redirect()->to("tareas/");
        }

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($tarea['ticket_id']);
        $data['ticket'] = $ticket;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos los documentos por tarea...
        $documentos = $this->documentos->obtenerDocumentosPorTareas($tarea_id);
        $data['documentos'] = $documentos;

        # Validamos el estado de la tarea...
        if ($tarea['estado'] === 'Resuelto') {
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "La tarea ya esta resuelta y no se pueden realizar cambios en la misma",
                'tipo' => "warning"
            ]);
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tareas/detalle-tarea', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function crear() {
        # Obtenemos los datos del formulario...
        $ticket_id = $this->request->getPost('ticket_id');
        $area_id = $this->request->getPost('area_id');
        $prioridad = $this->request->getPost('prioridad');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'ticket_id' => 'required',
            'area_id' => 'required',
            'prioridad' => 'required',
            'descripcion' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Hay errores en el formulario...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Hay errores en el formulario al momento de crear la tarea, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($ticket_id);

        # Validamos si existe...
        if (empty($ticket)) {
            # No existe el ticket...
            return redirect()->to("tickets/");
        }

        # Obtenemos los SLA...
        $lista_sla = $this->sla->obtenerSLAs();

        $slas = [];

        foreach ($lista_sla as $sla) {
            $slas[$sla['titulo']] = $sla['tiempo'];
        }

        # Verificar si la prioridad es válida
        if (array_key_exists($prioridad, $slas)) {
            # Obtener el SLA en horas según la prioridad
            $slaHoras = $slas[$prioridad];

            # Calcular la fecha de vencimiento sumando las horas del SLA
            $fecha_vencimiento = date('Y-m-d H:i:s', strtotime("+$slaHoras hours"));
        } else {
            # En caso de prioridad no válida, asignar un valor por defecto o manejar el error
            $fecha_vencimiento = NULL; # O puedes lanzar una excepción o manejarlo según el caso
        }

        # Creamos la variable de creacion de la tarea...
        $infoTarea = [
            'ticket_id' => $ticket_id,
            'emisor_id' => session('session_data.id'),
            'receptor_id' => NULL,
            'area_id' => $area_id,
            'prioridad' => $prioridad,
            'descripcion' => $descripcion,
            'solucion' => NULL,
            'fecha_vencimiento' => $fecha_vencimiento,
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'fecha_solucion' => NULL,
            'estado' => 'Pendiente'
        ];

        # Creamos la tarea...
        if ($this->tareas->crearTarea($infoTarea)) {
            # Se creo la tarea
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado la nueva tarea y se ha informado al area correspondiente.",
                'tipo' => "success"
            ]);

            # Obtenemos todos los usuarios del arae...
            $usuarios = $this->usuarios->obtenerUsuariosPorArea($area_id);

            # Obtenemos informaccion del area...
            $area = $this->areas->obtenerArea($area_id);

            # Procesamos y obtenemos todos los correos...
            $correos = [];

            foreach ($usuarios as $usuario) {
                $correos[] = $usuario['correo'];
            }

            # Envimaos la notificacion por medio de correo electronico...
            # Configuración de datos
            $destinatarios = $correos;
            $asunto = 'Nueva Tarea';
            $adjuntos = [];
            $bcc = [];

            # Cargar la plantilla del recordatorio
            $template = file_get_contents('app/Views/templates/notificacion_nueva_tarea.php');

            // Reemplazar las variables dinámicas
            $variables = [
                '{{area_nombre}}' => $area['nombre'],
                '{{ticket_identificador}}' => $ticket['identificador'],
                '{{ticket_titulo}}' => $ticket['titulo'],
                '{{ticket_descripcion}}' => $ticket['descripcion'],
                '{{ticket_prioridad}}' => $ticket['prioridad'],
                '{{ticket_fecha_creacion}}' => $ticket['fecha_creacion'],
                '{{ticket_fecha_vencimiento}}' => $ticket['fecha_vencimiento'],
                '{{ticket_estado}}' => $ticket['estado'],
            ];

            # Modificamos la plantilla con la información del ticket
            $mensaje = str_replace(array_keys($variables), array_values($variables), $template);

            # Enviamos el correo a los destinatarios
            enviarCorreo($destinatarios, $asunto, $mensaje, $adjuntos, true, $bcc);

            # Creamos la notificacion...
            foreach ($usuarios as $usuario) {
                $infoNotificacion = [
                    'usuario_id' => $usuario['id'],
                    'titulo' => 'Nueva Tarea',
                    'descripcion' => "Se ha asignado una nueva tarea correspondiente "
                    . "al ticket <strong>{$ticket['identificador']}</strong>, da solucion "
                    . "lo antes posible para evitar una mala calificacion.",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            # Creamos la accion realizada...
            $infoAccion = [
                'ticket_id' => $ticket_id,
                'usuario_id' => session('session_data.id'),
                'area_id' => session('session_data.area_id'),
                'titulo' => 'Nueva Tarea',
                'descripcion' => "Ha añadido una nueva tarea en el ticket la cual debe ser "
                . "resulta por el area <strong>{$area['nombre']}</strong>, con una fecha "
                . "limite hasta el <strong>{$fecha_vencimiento}</strong>, la tarea consiste "
                . "en <strong>¡{$descripcion}!</strong>, y se ha informado al area por medio de correo electronico "
                . "y notificacion en el sistema",
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            # Se guarda la accion...
            $this->acciones->crearAccion($infoAccion);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        } else {
            # No se ha podido crear el ticket...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido crear el ticket, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }
    }

    public function solucionar() {
        # Obtenemos los datos del formulario...
        $tarea_id = $this->request->getPost('tarea_id');
        $solucion = $this->request->getPost('solucion');

        # Obtenemos informacion de la tarea...
        $tarea = $this->tareas->obtenerTarea($tarea_id);

        # Validamos si existe la tarea...
        if (empty($tarea)) {
            # No existe la tarea...
            return redirect()->to("tareas/");
        }

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($tarea['ticket_id']);

        # Obtenemos informacion del usuario...
        $usuario = session('session_data');

        # Creamos la variable de solucion de la tarea...
        $infoSolucionTareas = [
            'solucion' => $solucion,
            'receptor_id' => session('session_data.id'),
            'fecha_solucion' => date('Y-m-d H:i:s'),
            'estado' => 'Resuelto',
        ];

        # Actualizamos la informacion de la tarea...
        if ($this->tareas->actualizarTarea($tarea_id, $infoSolucionTareas)) {
            # Se actualizo la tarea...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha dado solucion a la tarea y se ha notificado a todos los involucrados.",
                'tipo' => "success"
            ]);

            # Creamos la accion realizada...
            $infoAccion = [
                'ticket_id' => $ticket['id'],
                'usuario_id' => session('session_data.id'),
                'area_id' => session('session_data.area_id'),
                'titulo' => 'Tarea Realizada',
                'descripcion' => "La tarea <strong>¡¡¡{$tarea['descripcion']}!!!</strong>, ha sido "
                . "resuelta de forma exitosa por el usuario <strong>{$usuario['nombre']}</strong>",
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            # Creamos la accion en el sistema...
            $this->acciones->crearAccion($infoAccion);

            # Obtenemos todos los usuarios por dependencia...
            $usuarios = $this->usuarios->obtenerUsuariosPorArea($ticket['area_id']);

            # Creamos la notificacion...
            foreach ($usuarios as $usuario) {
                $infoNotificacion = [
                    'usuario_id' => $usuario['id'],
                    'titulo' => 'Tarea Realizada',
                    'descripcion' => "La tarea <strong>¡¡¡{$tarea['descripcion']}!!!</strong>, ha sido "
                    . "resuelta de forma exitosa por el usuario <strong>{$usuario['nombre']}</strong>",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            return redirect()->to("tareas/detalle/{$tarea_id}");
        } else {
            # No se pudo actualizar la tarea...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido actualizar la tarea, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tareas/detalle/{$tarea_id}");
        }
    }

    public function documento() {
        # Obtenemos los datos del formulario
        $tarea_id = $this->request->getPost('tarea_id');
        $usuario_id = $this->request->getPost('usuario_id');
        $comentario = $this->request->getPost('comentario');

        # Obtenemos informacion de la tarea...
        $tarea = $this->tareas->obtenerTarea($tarea_id);

        # Validamos si la tarea existe...
        if (empty($tarea)) {
            # No existe la tarea...
            return redirect()->to("tareas/");
        }

        # Verificamos si se ha cargado un archivo
        if ($this->request->getFile('archivo')->isValid()) {
            $archivo = $this->request->getFile('archivo');

            # Definimos la carpeta de destino
            $rutaDestino = 'public/uploads/tareas/archivos/';

            # Creamos el directorio si no existe
            if (!is_dir($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }

            # Creamos un nuevo nombre para el archivo
            $nuevoNombre = uniqid('tarea_' . $tarea_id . '_') . '.' . $archivo->getExtension();

            # Movemos el archivo al directorio con el nuevo nombre
            if ($archivo->move($rutaDestino, $nuevoNombre)) {
                # Ruta relativa para guardar en la base de datos
                $rutaArchivoBD = 'public/uploads/tareas/archivos/' . $nuevoNombre;

                # Guardamos los datos en la base de datos
                $infoArchivo = [
                    'tarea_id' => $tarea_id,
                    'usuario_id' => $usuario_id,
                    'comentario' => $comentario,
                    'archivo' => $rutaArchivoBD,
                    'fecha_creacion' => date('Y-m-d H:i:s'),
                ];

                # Subimos el documento de la tarea...
                if ($this->documentos->crearDocumento($infoArchivo)) {
                    # Se subio el documento...
                    $this->session->setFlashdata([
                        'titulo' => "¡Exito!",
                        'mensaje' => "Se ha subido correctamente el documento en la tarea.",
                        'tipo' => "success"
                    ]);

                    return redirect()->to("tareas/detalle/{$tarea_id}");
                } else {
                    # No se subio el documento...
                    $this->session->setFlashdata([
                        'titulo' => "¡Error!",
                        'mensaje' => "No se ha podido subir el documento, intentalo nuevamente.",
                        'tipo' => "danger"
                    ]);

                    return redirect()->to("tareas/detalle/{$tarea_id}");
                }
            } else {
                # Error al mover el archivo
                $this->session->setFlashdata([
                    'titulo' => "¡Error!",
                    'mensaje' => "No se ha podido subir el documento, intentalo nuevamente.",
                    'tipo' => "danger"
                ]);

                return redirect()->to("tareas/detalle/{$tarea_id}");
            }
        } else {
            # Archivo no valido...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "El archivo no es valido, o no se ha subido. Intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tareas/detalle/{$tarea_id}");
        }
    }
}
