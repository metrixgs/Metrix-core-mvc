<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\AreasModel;
use App\Models\ArchivosModel;
use App\Models\AccionesTicketsModel;
use App\Models\NotificacionesModel;
use App\Models\TareasModel;
use App\Models\CampanasModel;
use App\Models\TiposIncidenciasModel;
use App\Models\SLAModel;

class Tickets extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $archivos;
    protected $acciones;
    protected $notificaciones;
    protected $tareas;
    protected $campanas;
    protected $sla;
    protected $tipos;
    protected $database;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->areas = new AreasModel();
        $this->archivos = new ArchivosModel();
        $this->acciones = new AccionesTicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->tareas = new TareasModel();
        $this->campanas = new CampanasModel();
        $this->sla = new SLAModel();
        $this->tipos = new TiposIncidenciasModel();

        # Cargar los Helpers
        helper(['Alerts', 'Email', 'Rol', 'Menu', 'bitacora']);
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Lista de Tickets';

        # Obtenemos todos los tickets...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/lista-tickets', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
    
    public function tipos() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Tipos de Incidencias';
        
        # Obtenemos todos los tipos de incidencias...
        $tiposInciencias = $this->tipos->obtenerTiposIncidencias();
        $data['tipos_incidencias'] = $tiposInciencias;
        
        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/lista-tipos', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
    
    public function crearTipo() {
        // Registrar en bitácora
        log_activity(
            $this->getUsuarioId(),
            'Tipos de Incidencias',
            'Creación',
            $_POST
        );
        print_r($_POST);
    }

    public function ticketsCreados() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Tickets Creados';

        # Obtenemos todos los tickets...
        $tickets = $this->tickets->obtenerTicketsCreadosPorUsuario(session('session_data.id'));
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/tickets-creados', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function ticketsAsignados() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Tickets Asignados';

        # Obtenemos todos los tickets...
        $tickets = $this->tickets->obtenerTicketsPorArea(session('session_data.area_id'));
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/tickets-asignados', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function nuevoTicket() {
        $data['titulo_pagina'] = 'Metrix | Nuevo Ticket';
    
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;
    
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;
    
        $tickets = $this->tickets->obtenerTicketsPorCliente(session('session_data.id'));
        $data['tickets'] = $tickets;
    
        $lista_sla = $this->sla->obtenerSLAs();
        $data['lista_sla'] = $lista_sla;
    
        $clientes = $this->usuarios->obtenerUsuariosPorRol(3);
        $data['clientes'] = $clientes;
    
        $campanas = $this->campanas->obtenerCampanas();
        $data['campanas'] = $campanas;
    
        $tipos_incidencias = $this->tipos->findAll();
        $data['tipos_incidencias'] = $tipos_incidencias;
    
        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/nuevo-ticket', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
    
    public function detalle($ticket_id) {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Detalle del Ticket';

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todas las areas del sistema...
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTicketsPorCliente(session('session_data.id'));
        $data['tickets'] = $tickets;

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($ticket_id);
        $data['ticket'] = $ticket;

        # Validamos si existe el ticket...
        if (empty($ticket)) {
            # No existe el ticket...
            return redirect()->to("tickets/");
        }

        # Obtenemos todos los archivos por ticket...
        $archivos = $this->archivos->obtenerArchivosPorTicket($ticket_id);
        $data['archivos'] = $archivos;

        # Obtenemos todas las acciones por ticket...
        $acciones = $this->acciones->obtenerAccionesPorTicket($ticket_id);
        $data['acciones'] = $acciones;

        # Obtenemos todas las tareas por ticket...
        $tareas = $this->tareas->obtenerTareasPorTicket($ticket_id);
        $data['tareas'] = $tareas;

        # Obtenemos los usuarios por area...
        $usuarios = $this->usuarios->obtenerUsuariosPorArea($ticket['area_id']);
        $data['usuarios'] = $usuarios;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('tickets/detalle-ticket', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function subirArchivo() {
        # Obtenemos los datos del formulario
        $descripcion = $this->request->getPost('descripcion');
        $ticket_id = $this->request->getPost('ticket_id');

        # Obtener el archivo subido
        $archivo = $this->request->getFile('archivo');

        # Definimos las reglas de validación para los campos
        $validationRules = [
            'ticket_id' => 'required',
            'descripcion' => 'required',
        ];

        # Validamos los datos del formulario
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión
            return redirect()->to("tickets/");
        }

        # Comprobamos si el archivo es válido
        if ($archivo->isValid() && !$archivo->hasMoved()) {
            # Obtener información del archivo
            $nombre_original = $archivo->getClientName();
            $extencion = $archivo->getExtension();
            $tamano = $archivo->getSize();

            # Definir una lista de extensiones permitidas
            $extensiones_permitidas = ['txt', 'pdf', 'jpeg', 'jpg', 'png', 'xls', 'xlsx', 'doc', 'docx'];

            # Verificamos si la extensión está permitida
            if (!in_array($extencion, $extensiones_permitidas)) {
                $this->session->setFlashdata([
                    'titulo' => "¡Error!",
                    'mensaje' => "El archivo tiene una extensión no válida.",
                    'tipo' => "danger"
                ]);
                return redirect()->to("tickets/detalle/{$ticket_id}");
            }

            # Generar un nombre único para el archivo
            $newName = $archivo->getRandomName();
            $ruta = 'public/uploads/tickets/archivos/' . $newName;
            $archivo->move('public/uploads/tickets/archivos/', $newName);
        } else {
            # El archivo no es válido
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "El archivo no es válido o no se ha subido.",
                'tipo' => "danger"
            ]);
            return redirect()->to("tickets/detalle/{$ticket_id}");
        }

        # Obtenemos información del ticket
        $ticket = $this->tickets->obtenerTicket($ticket_id);

        # Obtener el usuario_id
        $usuario_id = session('session_data.id');
        if (empty($usuario_id)) {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha encontrado el usuario en la sesión. Por favor, inicia sesión nuevamente.",
                'tipo' => "danger"
            ]);
            return redirect()->to("login");
        }

        # Preparar los datos para la base de datos
        $infoArchivo = [
            'ticket_id' => $ticket_id,
            'usuario_id' => $usuario_id,
            'descripcion' => $descripcion,
            'ruta' => $ruta,
            'extencion' => $extencion,
            'tamano' => $tamano,
            'tipo_mime' => $extencion,
            'fecha_subida' => date('Y-m-d H:i:s')
        ];

        # Creamos el archivo en la base de datos
        if ($this->archivos->crearArchivo($infoArchivo)) {
            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Subir Archivo',
                [
                    'ticket_id' => $ticket_id,
                    'ticket_identificador' => $ticket['identificador'],
                    'archivo_nombre' => $newName,
                    'tamano' => $tamano
                ]
            );
            
            # Se subió el archivo con éxito
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Se ha subido un archivo al Ticket: {$ticket['identificador']}, de forma exitosa.",
                'tipo' => "success"
            ]);

            # Creamos la accion realizada...
            $infoAccion = [
                'ticket_id' => $ticket_id,
                'usuario_id' => session('session_data.id'),
                'area_id' => session('session_data.area_id'),
                'titulo' => 'Documento de Soporte',
                'descripcion' => "Se ha subido un nuevo documento de soporte al ticket <strong>{$ticket['identificador']}</strong>",
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
                    'titulo' => 'Documento de Soporte',
                    'descripcion' => "Se ha subido un nuevo documento de soporte al ticket <strong>{$ticket['identificador']}</strong>",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            return redirect()->to("tickets/detalle/{$ticket_id}");
        } else {
            # No se subió el archivo
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido subir el archivo al Ticket: {$ticket['identificador']}, inténtalo nuevamente.",
                'tipo' => "danger"
            ]);

            # Si algo salió mal con la base de datos, eliminar el archivo subido
            unlink($ruta);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }
    }

    public function crearComentario() {
        # Obtenemos los datos del formulario...
        $ticket_id = $this->request->getPost('ticket_id');
        $comentario = $this->request->getPost('comentario');

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($ticket_id);

        # Validamos si existe el ticket...
        if (empty($ticket)) {
            # No existe el ticket...
            return redirect()->to("tickets/");
        }

        # Creamos la accion realizada...
        $infoAccion = [
            'ticket_id' => $ticket_id,
            'usuario_id' => session('session_data.id'),
            'area_id' => session('session_data.area_id'),
            'titulo' => 'Comentario',
            'descripcion' => $comentario,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        # Creamos la accion en el sistema...
        if ($this->acciones->crearAccion($infoAccion)) {
            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Crear Comentario',
                [
                    'ticket_id' => $ticket_id,
                    'ticket_identificador' => $ticket['identificador'],
                    'comentario' => substr($comentario, 0, 100) . '...' // Guardar solo parte del comentario
                ]
            );
            
            # Se creo el comentario...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado el comentario de forma exitosa en el ticket "
                . "<strong>{$ticket['identificador']}</strong>.",
                'tipo' => "success"
            ]);

            # Obtenemos todos los usuarios por dependencia...
            $usuarios = $this->usuarios->obtenerUsuariosPorArea($ticket['area_id']);

            # Creamos la notificacion...
            foreach ($usuarios as $usuario) {
                $infoNotificacion = [
                    'usuario_id' => $usuario['id'],
                    'titulo' => 'Comentario',
                    'descripcion' => "Se ha añadido el siguiente comentario: {$comentario} al ticket ID: <strong>{$ticket['identificador']}</strong>",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            return redirect()->to("tickets/detalle/{$ticket_id}");
        } else {
            # No se pudo crear el comentario...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido crear el comentario para el ticket "
                . "<strong>{$ticket['identificador']}</strong>.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }
    }

    public function enviarRecordatorio($ticket_id) {
        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($ticket_id);

        # Validamos si existe el ticket...
        if (empty($ticket)) {
            # No existe el ticket...
            return redirect()->to("tickets/");
        }

        # Obtenemos informacion del area...
        $area = $this->areas->obtenerArea($ticket['area_id']);

        # Definimos variable de los correos...
        $correos = [];

        # Obtenemos informacion del cliente...
        $cliente = $this->usuarios->obtenerUsuario($ticket['cliente_id']);

        # Añadimos el correo del cliente a la lista...
        $correos[] = $cliente['correo'];

        # Obtenemos los usuarios por dependencia...
        $usuarios = $this->usuarios->obtenerUsuariosPorArea($ticket['area_id']);

        # Recorremos el arreglo para obtener todos los correos de los usuarios del area...
        foreach ($usuarios as $usuario) {
            $correos[] = $usuario['correo'];
        }

        # Enviamos la notificación al correo...
        # Configuración de datos
        $destinatarios = [session('session_data.correo')];
        $asunto = 'Recordatorio del Ticket';
        $adjuntos = [];
        $bcc = $correos;

        # Cargar la plantilla del recordatorio
        $template = file_get_contents('app/Views/templates/notificacion_recordatorio_ticket.php');

        # Reemplazar las variables dinámicas
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
         if (enviarCorreo($destinatarios, $asunto, $mensaje, $adjuntos, true, $bcc)) {
            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Enviar Recordatorio',
                [
                    'ticket_id' => $ticket_id,
                    'ticket_identificador' => $ticket['identificador'],
                    'destinatarios' => count($correos)
                ]
            );
            
            # Se envio el recordatorio...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha enviado un recordatorio del ticket <strong>{$ticket['identificador']}</strong>.",
                'tipo' => "success"
            ]);

            # Creamos la notificacion...
            foreach ($usuarios as $usuario) {
                $infoNotificacion = [
                    'usuario_id' => $usuario['id'],
                    'titulo' => 'Recordatorio',
                    'descripcion' => "Se ha enviado un recordatorio del ticket "
                    . "<strong>{$ticket['identificador']}</strong>, a todos los usuarios del area",
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
                'titulo' => 'Recordatorio',
                'descripcion' => "Ha enviado un recordatorio del ticket "
                . "<strong>{$ticket['identificador']}</strong>, a todos los involucrados"
                . " por medio de correo.",
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            # Se guarda la accion...
            $this->acciones->crearAccion($infoAccion);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        } else {
            # No se envio el recordatorio...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo enviar el recordatorio, "
                . "intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }
    }

    public function cerrarTicket() {
        # Obtenemos la informacion del formulario...
        $ticket_id = $this->request->getPost('ticket_id');
        $comentario = $this->request->getPost('comentario');

        # Obtenemos informacion del ticket...
        $ticket = $this->tickets->obtenerTicket($ticket_id);

        # Validamos si existe el ticket...
        if (empty($ticket)) {
            # No existe el ticket...
            return redirect()->to("tickets/");
        }

        # Creamos la variable de modificacion del ticket...
        $infoTicket = [
            'estado' => 'Cerrado',
            'fecha_cierre' => date('Y-m-d H:i:s'),
        ];

        # Actualizamos el ticket...
        if ($this->tickets->actualizarTicket($ticket_id, $infoTicket)) {
            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Cerrar Ticket',
                [
                    'ticket_id' => $ticket_id,
                    'ticket_identificador' => $ticket['identificador'],
                    'comentario' => $comentario
                ]
            );
            
            # Se cerro el ticket...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha cerrado el ticket <strong>{$ticket['identificador']}</strong>, de forma exitosa.",
                'tipo' => "success"
            ]);

            # Creamos la accion de cierre...
            $infoAccion = [
                'ticket_id' => $ticket_id,
                'usuario_id' => session('session_data.id'),
                'area_id' => session('session_data.area_id'),
                'titulo' => 'Cierre de Ticket',
                'descripcion' => "Ha realizado cierre del ticket "
                . "<strong>{$ticket['identificador']}</strong>, de forma exitosa "
                . "y se ha informado a todos los involucrados por medio de correo.",
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            # Se guarda la accion...
            $this->acciones->crearAccion($infoAccion);

            # Definimos variable de los correos...
            $correos = [];

            # Obtenemos informacion del cliente...
            $cliente = $this->usuarios->obtenerUsuario($ticket['cliente_id']);

            # Añadimos el correo del cliente a la lista...
            $correos[] = $cliente['correo'];

            # Obtenemos los usuarios por dependencia...
            $usuarios = $this->usuarios->obtenerUsuariosPorArea($ticket['area_id']);

            # Recorremos el arreglo para obtener todos los correos de los usuarios del area...
            foreach ($usuarios as $usuario) {
                $correos[] = $usuario['correo'];
            }

            # Enviamos la notificación al correo...
            # Configuración de datos
            $destinatarios = [session('session_data.correo')];
            $asunto = 'Cierre de Ticket';
            $adjuntos = [];
            $bcc = $correos;

            # Cargar la plantilla del recordatorio
            $template = file_get_contents('app/Views/templates/notificacion_cierre_ticket.php');

            # Reemplazar las variables dinámicas
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
                    'titulo' => 'Cierre de Ticket',
                    'descripcion' => "Se ha realizado cierre del ticket "
                    . "<strong>{$ticket['identificador']}</strong>, de forma exitosa.",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            return redirect()->to("tickets/detalle/{$ticket_id}");
        } else {
            # No se pudo cerrar el ticket...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido cerrar el ticket, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$ticket_id}");
        }
    }

    public function crear() {
        # Obtenemos la informacion del formulario...
        $area_id = $this->request->getPost('area_id');
        $campana_id = $this->request->getPost('campana_id');
        $cliente_id = $this->request->getPost('cliente_id');
        $titulo = $this->request->getPost('titulo');
        $descripcion = $this->request->getPost('descripcion');
        $prioridad = $this->request->getPost('prioridad');
        $latitud = $this->request->getPost('latitud');
        $longitud = $this->request->getPost('longitud');

        $estado_p = $this->request->getPost('estado');
        $municipio = $this->request->getPost('municipio');
        $colonia = $this->request->getPost('colonia');
        $df = $this->request->getPost('df');
        $dl = $this->request->getPost('dl');
        $seccion_electoral = $this->request->getPost('seccion_electoral');
        $codigo_postal = $this->request->getPost('codigo_postal');
        $direccion_completa = $this->request->getPost('direccion_completa');
        $direccion_solicitante = $this->request->getPost('direccion_solicitante');
        $mismo_domicilio = $this->request->getPost('mismo_domicilio');

        # Verificación y subida de imagen
        $imagenNombre = null;
        $imagen = $this->request->getFile('imagen'); // Nombre del campo en el formulario: <input type="file" name="imagen">
        
        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'area_id' => 'required|numeric',
            'campana_id' => 'required|numeric',
            'cliente_id' => 'required|numeric',
            'titulo' => 'required|min_length[3]|max_length[255]',
            'descripcion' => 'required|string|max_length[1000]',
            'prioridad' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'estado' => 'permit_empty|string|max_length[100]',
            'municipio' => 'permit_empty|string|max_length[100]',
            'colonia' => 'permit_empty|string|max_length[100]',
            'df' => 'permit_empty|string|max_length[50]', // Distrito Federal
            'dl' => 'permit_empty|string|max_length[50]', // Distrito Local
            'seccion_electoral' => 'permit_empty|numeric',
            'codigo_postal' => 'permit_empty|numeric',
            'direccion_completa' => 'permit_empty|string|max_length[255]',
            'direccion_solicitante' => 'permit_empty|string|max_length[255]',
            'mismo_domicilio' => 'permit_empty|in_list[Si,No]',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("tickets/nuevo")->withInput();
        }

        # Obtenemos los SLA...
        $lista_sla = $this->sla->obtenerSLAs();

        $slas = [];

        foreach ($lista_sla as $sla) {
            $slas[$sla['titulo']] = $sla['tiempo'];
        }

        // Obtener las horas correspondientes al SLA para la prioridad seleccionada
        if (isset($slas[$prioridad])) {
            $sla_hours = $slas[$prioridad];
        } else {
            // Si la prioridad no está definida, asignar un valor por defecto (opcional)
            $sla_hours = 48;  // Por ejemplo, 48 horas por defecto
        }

        // Calcular la fecha de vencimiento sumando el SLA en horas
        $fecha_creacion = date('Y-m-d H:i:s'); // Fecha de creación actual
        $fecha_vencimiento = date('Y-m-d H:i:s', strtotime("+$sla_hours hours", strtotime($fecha_creacion)));

        # Creamos la variable con la informacion del ticket...
        $infoTicket = [
            'cliente_id' => $cliente_id,
            'area_id' => $area_id,
            'usuario_id' => $this->getUsuarioId(),
            'campana_id' => $campana_id,
            'identificador' => 'TKD-' . strtoupper(bin2hex(random_bytes(5))),
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'prioridad' => $prioridad,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'estado_p' => $estado_p,
            'estado' => 'Abierto',
            'fecha_creacion' => $fecha_creacion,
            'fecha_cierre' => NULL,
            'fecha_vencimiento' => $fecha_vencimiento, // Aquí se asigna la fecha calculada
            'municipio' => $municipio, // Nuevo campo
            'colonia' => $colonia, // Nuevo campo
            'df' => $df, // Nuevo campo
            'dl' => $dl, // Nuevo campo
            'seccion_electoral' => $seccion_electoral, // Nuevo campo
            'codigo_postal' => $codigo_postal, // Nuevo campo
            'direccion_completa' => $direccion_completa, // Nuevo campo
            'direccion_solicitante' => $direccion_solicitante, // Nuevo campo
            'mismo_domicilio' => $mismo_domicilio, // Nuevo campo
        ];

        # Creamos el ticket...
        if ($this->tickets->crearTicket($infoTicket)) {
            # Obtengo el último requerimiento creado...
            $ticket_id = $this->tickets->insertID();

            # Comprobamos si el archivo es válido y si se subio un archivo...
            if ($imagen->isValid() && !$imagen->hasMoved()) {
                # Obtener información del archivo
                $nombre_original = $imagen->getClientName();  // Nombre original del archivo
                $extencion = $imagen->getExtension();        // Extensión del archivo (sin el punto)
                $tamano = $imagen->getSize();

                # Definir una lista de extensiones permitidas
                $extensiones_permitidas = ['txt', 'pdf', 'jpeg', 'jpg', 'png', 'xls', 'xlsx', 'doc', 'docx'];

                # Verificamos si la extensión está permitida
                if (!in_array($extencion, $extensiones_permitidas)) {
                    $this->session->setFlashdata([
                        'titulo' => "¡Error!",
                        'mensaje' => "El archivo tiene una extensión no válida.",
                        'tipo' => "danger"
                    ]);
                    return redirect()->to("tickets/detalle/{$ticket_id}");
                }

                # Generar un nombre único para el archivo sin agregar la extensión dos veces
                $usuario_id = $this->getUsuarioId();
                if (empty($usuario_id)) {
                    $this->session->setFlashdata([
                        'titulo' => "¡Error!",
                        'mensaje' => "No se ha encontrado el usuario en la sesión. Por favor, inicia sesión nuevamente.",
                        'tipo' => "danger"
                    ]);
                    return redirect()->to("login");
                }
                $imagen->move('public/uploads/tickets/archivos/', $newName); // Guardamos el archivo sin extensión adicional
                # Ahora añadimos la extensión solo una vez
                $rutaConExtension = 'public/uploads/tickets/archivos/' . $newName;
                $ruta = $rutaConExtension;  // Actualizamos la ruta para usar la nueva ruta con la extensión correcta

                # Preparar los datos para la base de datos
                $infoArchivo = [
                    'ticket_id' => $ticket_id,
                    'usuario_id' => $usuario_id,
                    'descripcion' => $descripcion,
                    'ruta' => $ruta,
                    'extencion' => $extencion, // Guardamos la extensión en lugar del MIME
                    'tamano' => $tamano,
                    'tipo_mime' => $extencion,
                    'fecha_subida' => date('Y-m-d H:i:s')
                ];

                # Creamos el archivo...
                $this->archivos->crearArchivo($infoArchivo);
            }

            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Crear Ticket',
                [
                    'ticket_id' => $this->tickets->insertID(),
                    'titulo' => $titulo,
                    'prioridad' => $prioridad,
                    'area_id' => $area_id
                ]
            );
            # Se creo el ticket
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado el ticket de forma exitosa.",
                'tipo' => "success"
            ]);

            # Obtenemos todos los usuarios por area...
            $usuariosDependencia = $this->usuarios->obtenerUsuariosPorArea($area_id);

            # Obtenemos informacion del area...
            $area = $this->areas->obtenerArea($area_id);

            # Recorremos el arreglo para obtener todos los correos...
            $correos = [];

            foreach ($usuariosDependencia as $usuario) {
                $correos[] = $usuario['correo'];
            }

            # Configuración de datos
            $destinatarios = [session('session_data.correo')];
            $asunto = 'Nuevo Ticket';
            $adjuntos = [];
            $bcc = $correos;

            # Cargar la plantilla
            $template = file_get_contents('app/Views/templates/notificacion_nuevo_ticket.php');

            # Reemplazar las variables dinámicas
            $variables = [
                '{{area_nombre}}' => $area['nombre'],
                '{{ticket_identificador}}' => $infoTicket['identificador'],
                '{{ticket_titulo}}' => $infoTicket['titulo'],
                '{{ticket_descripcion}}' => $infoTicket['descripcion'],
                '{{ticket_prioridad}}' => $infoTicket['prioridad'],
                '{{ticket_fecha_creacion}}' => $infoTicket['fecha_creacion'],
                '{{ticket_fecha_vencimiento}}' => $infoTicket['fecha_vencimiento'],
                '{{ticket_estado}}' => $infoTicket['estado'],
            ];

            # Modificamos la plantilla con la informacion...
            $mensaje = str_replace(array_keys($variables), array_values($variables), $template);

            # Enviamos el correo a los detinatarios
            enviarCorreo($destinatarios, $asunto, $mensaje, $adjuntos, true, $bcc);

            # Creamos la notificacion...
            foreach ($usuariosDependencia as $usuario) {
                $infoNotificacion = [
                    'usuario_id' => $usuario['id'],
                    'titulo' => 'Nuevo Ticket',
                    'descripcion' => "Se ha creado un nuevo ticket y se ha asignado al area"
                    . ", se ha informado a todos los usuarios del area",
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                # Creamos la notificacion...
                $this->notificaciones->crearNotificacion($infoNotificacion);
            }

            return redirect()->to("tickets/");
        } else {
            # No se creo el ticket
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "Se ha podido crear el ticket, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/nuevo/")->withInput();
        }
    }

    public function eliminar($requerimiento_id) {
        # Obtenemos informacion del requerimiento....
        $requerimiento = $this->tickets->obtenerTicket($requerimiento_id);

        # Validamos si existe el requerimiento...
        if (empty($requerimiento)) {
            # No existe el requerimiento...
            return redirect()->to("tickets/");
        }

        # validamos si el rol es administrativo...
        if (session('session_data.rol_id') != 1) {
            # No es administrador...
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "Solo el administrador puede eliminar tickets dentro del sistema.",
                'tipo' => "warning"
            ]);

            return redirect()->to("tickets/detalle/{$requerimiento_id}");
        }

        # Eliminamos el reuquerimiento...
        if ($this->tickets->eliminarTicket($requerimiento_id)) {
            # Registrar en bitácora
            log_activity(
                $this->getUsuarioId(),
                'Tickets',
                'Eliminar Ticket',
                [
                    'ticket_id' => $requerimiento_id,
                    'ticket_identificador' => $requerimiento['identificador']
                ]
            );
            
            # Se elimino el requerimiento...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado el requerimiento de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("tickets/");
        } else {
            # No se pudo eliminar el requerimiento...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido eliminar el requerimiento, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("tickets/detalle/{$requerimiento_id}");
        }
    }

    private function getUsuarioId() {
        return (int) (session('session_data')['id'] ?? session('session_data')['usuario_id'] ?? 1);
    }
}
