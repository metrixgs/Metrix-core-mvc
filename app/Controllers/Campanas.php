<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\AreasModel;
use App\Models\TicketsModel;
use App\Models\TareasModel;
use App\Models\NotificacionesModel;
use App\Models\CampanasModel;
use App\Models\TiposCampanasModel;
use App\Models\SubtiposCampanasModel;
use App\Models\SegmentacionesModel;
use App\Models\SurveyModel;
use App\Libraries\Breadcrumb;
 
 

class Campanas extends BaseController {

    protected $usuarios;
    protected $areas;
    protected $tickets;
    protected $tareas;
    protected $notificaciones;
    protected $campanas;
    protected $tiposCampanas;
    protected $subtiposCampanas;
    protected $segmentaciones;
    protected $survey;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
    $this->areas = new AreasModel();
    $this->tickets = new TicketsModel();
    $this->notificaciones = new NotificacionesModel();
    $this->tareas = new TareasModel();
    $this->campanas = new CampanasModel();
    $this->tiposCampanas = new TiposCampanasModel();
    $this->subtiposCampanas = new SubtiposCampanasModel();
    $this->segmentaciones = new SegmentacionesModel();
    $this->survey = new SurveyModel();


        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    } public function index() {
    $data['titulo_pagina'] = 'Metrix | Panel de Control';

    // Tickets y notificaciones
    $data['tickets'] = $this->tickets->obtenerTickets();
    $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));

    // Campañas con nombre del coordinador
    $campanas = $this->campanas->obtenerCampanas();
    foreach ($campanas as &$campana) {
        $usuario = $this->usuarios->find($campana['coordinador']);
        $campana['nombre_coordinador'] = $usuario['nombre'] ?? 'N/D';
    }
    $data['campanas'] = $campanas;

    // Encuestas
    $data['surveys'] = $this->survey->findAll();

    // Generar nuevo ID visual de campaña
    $new_id = 1;
    if (!empty($campanas)) {
        $ids = array_column($campanas, 'id');
        $last_id = max($ids);
        $new_id = $last_id + 1;
    }
    $data['new_campana_id'] = '#CAM-' . str_pad($new_id, 6, '0', STR_PAD_LEFT);

    // Tipos, segmentaciones y áreas
    $data['tipos_campanas'] = $this->tiposCampanas->obtenerTiposCampanas();
    $data['todas_segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();
    $data['areas'] = $this->areas->obtenerAreas();

    // Usuarios desde ID 2
    $data['usuarios_desde_2'] = $this->usuarios
        ->select('id, nombre')
        ->where('id >=', 2)
        ->findAll();

    // Usar función reutilizable de breadcrumb
    $data['breadcrumb'] = $this->generarBreadcrumb([
        ['title' => 'Inicio', 'url' => base_url('/')],
        ['title' => 'Campañas'],
    ]);

    // Renderizar vistas
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('campanas/lista-campanas', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}



 public function tipos() {
    // Título de la página
    $data['titulo_pagina'] = 'Metrix | Tipos de Campañas';

    // Breadcrumb
    $data['breadcrumb'] = $this->generarBreadcrumb([
        ['title' => 'Inicio', 'url' => base_url('/')],
        ['title' => 'Tipos de Campañas'],
    ]);

    // Obtener tipos de campañas
    $data['tipos_campanas'] = $this->tiposCampanas->obtenerTiposCampanas();

    // Renderizar vistas
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('campanas/tipos-campanas', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}


    public function subtipos() {
    // Título de la página
    $data['titulo_pagina'] = 'Metrix | Subtipos de Campañas';

    // Breadcrumb
    $data['breadcrumb'] = $this->generarBreadcrumb([
        ['title' => 'Inicio', 'url' => base_url('/')],
        ['title' => 'Subtipos de Campañas'],
    ]);

    // Obtener tipos y subtipos de campañas
    $data['tipos_campanas'] = $this->tiposCampanas->obtenerTiposCampanas();
    $data['subtipos_campanas'] = $this->subtiposCampanas->obtenerSubtiposCampanas();

    // Renderizar vistas
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('campanas/subtipos-campanas', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}

public function detalle($campana_id)
{
    // Título de la página
    $data['titulo_pagina'] = 'Metrix | Detalle de la Campaña';

    // Notificaciones
    $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));

    // Información de la campaña
    $campana = $this->campanas->obtenerCampana($campana_id);
    if (empty($campana)) {
        return redirect()->to("campanas/");
    }
    $data['campana'] = $campana;

    // Tickets por campaña
    $data['tickets'] = $this->tickets->obtenerTicketsPorCampana($campana['id']);

    // Tipos de campañas y áreas
    $data['tipos_campanas'] = $this->tiposCampanas->obtenerTiposCampanas();
    $data['areas'] = $this->areas->obtenerAreas();

    // Breadcrumb
    $data['breadcrumb'] = $this->generarBreadcrumb([
        ['title' => 'Inicio', 'url' => base_url('/')],
        ['title' => 'Campañas', 'url' => base_url('campanas')],
        ['title' => 'Detalle'],
    ]);

    // Indicadores dinámicos desde BD
    $data['stats'] = [
        [
            'icon' => 'ri-map-pin-line',
            'color' => 'primary',
            'value' => count($this->campanas->obtenerRondasPorCampana($campana_id)), // ya implementado
            'label' => 'Rondas'
        ],
        [
            'icon' => 'ri-group-line',
            'color' => 'success',
            'value' => $this->campanas->contarBrigadasPorCampana($campana_id), // ya implementado
            'label' => 'Brigadas'
        ],
        [
            'icon' => 'ri-target-line',
            'color' => 'purple',
            'value' => $this->campanas->contarVisitasPorCampana($campana_id), // debes implementarlo tú
            'label' => 'Visitas'
        ],
        [
            'icon' => 'ri-alert-line',
            'color' => 'warning',
            'value' => $this->tickets->contarIncidenciasPorCampana($campana_id), // ya implementado
            'label' => 'Incidencias'
        ],
        [
            'icon' => 'ri-file-text-line',
            'color' => 'info',
            'value' => $this->survey->contarEncuestasPorCampana($campana_id), // ya implementado
            'label' => 'Encuestas'
        ],
        [
            'icon' => 'ri-truck-line',
            'color' => 'danger',
            'value' => $this->campanas->contarEntregasPorCampana($campana_id), // debes implementarlo tú
            'label' => 'Entregas'
        ],
        [
            'icon' => 'ri-handshake-line',
            'color' => 'teal',
            'value' => $this->campanas->contarPeticionesPorCampana($campana_id), // debes implementarlo tú
            'label' => 'Peticiones'
        ],
    ];

    // Renderizar vistas
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('campanas/detalle-campana', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}


 public function detalle_tipo($tipo_id) {
    // Título de la página
    $data['titulo_pagina'] = 'Metrix | Tipo de campaña';

    // Obtener información del tipo de campaña
    $tipo_campana = $this->tiposCampanas->obtenerTiposCampana($tipo_id);
    if (empty($tipo_campana)) {
        return redirect()->to('campanas/tipos');
    }
    $data['tipo_campana'] = $tipo_campana;

    // Obtener subtipos de campañas por tipo
    $data['subtipos_campana'] = $this->subtiposCampanas->obtenerSubtiposCampanasPorTipoCampana($tipo_id);

    // Breadcrumb
    $data['breadcrumb'] = $this->generarBreadcrumb([
        ['title' => 'Inicio', 'url' => base_url('/')],
        ['title' => 'Tipos de Campañas', 'url' => base_url('campanas/tipos')],
        ['title' => 'Detalle de Tipo'],
    ]);

    // Renderizar vistas
    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('campanas/detalle-tipo-campana', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}


    public function crear() {
    $nombre = $this->request->getPost('nombre');
    $coordinador = $this->request->getPost('coordinador');
    $tipo_id = $this->request->getPost('tipo_id');
    $subtipo_id = $this->request->getPost('subtipo_id');
    $area_id = $this->request->getPost('area_id');
    $estado = $this->request->getPost('estado');
    $descripcion = $this->request->getPost('descripcion');
    $fecha_inicio = $this->request->getPost('fecha_inicio');
    $fecha_fin = $this->request->getPost('fecha_fin');
    $encuesta = $this->request->getPost('encuesta');
    $entregables = $this->request->getPost('entregables');
    $universo = $this->request->getPost('universo');
    $territorio = $this->request->getPost('territorio');
    $territorio_subtype = $this->request->getPost('territorio-electorales-subtype') 
        ?? $this->request->getPost('territorio-geograficos-subtype');
    $sectorizacion = $this->request->getPost('sectorizacion');
    

    $validationRules = [
        'nombre' => 'permit_empty|max_length[100]',
        'coordinador' => 'permit_empty|max_length[100]',
        'tipo_id' => 'permit_empty|numeric',
        'area_id' => 'permit_empty|numeric',
        'estado' => 'permit_empty|in_list[Programada,Activa,Finalizada,Propuesta]',
        'descripcion' => 'permit_empty|string',
        'fecha_inicio' => 'permit_empty|valid_date',
        'fecha_fin' => 'permit_empty|valid_date',
        'encuesta' => 'permit_empty|numeric',
        'entregables' => 'permit_empty|string',
        'universo' => 'permit_empty|string',
        'territorio' => 'permit_empty|in_list[electorales,geograficos]',
        'territorio_subtype' => 'permit_empty|string',
        'sectorizacion' => 'permit_empty',
    ];

    if (!empty($subtipo_id)) {
        $validationRules['subtipo_id'] = 'numeric';
    }

    if (!$this->validate($validationRules)) {
        session()->setFlashdata('validation', $this->validator->getErrors());
        return redirect()->to("campanas/")->withInput();
    }

    $infoCampana = [
        'nombre' => $nombre,
        'coordinador' => $coordinador,
        'tipo_id' => $tipo_id,
        'area_id' => $area_id,
        'estado' => $estado,
        'descripcion' => $descripcion,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'encuesta' => $encuesta ?? null,
        'entregables' => $entregables ?? null,
        'universo' => $universo ?? null,
        'territorio' => $territorio ?? null,
        'territorio_subtype' => $territorio_subtype ?? null,
        'sectorizacion' => is_array($sectorizacion) ? json_encode($sectorizacion) : $sectorizacion,
        'canal_difusion' => $this->request->getPost('canal_difusion'),
'objetivo' => $this->request->getPost('objetivo'),
'sector_electoral' => $this->request->getPost('sector_electoral'),
'territorio_local' => $this->request->getPost('territorio_local'),

    ];

    if (!empty($subtipo_id)) {
        $infoCampana['subtipo_id'] = $subtipo_id;
    }

    if ($this->campanas->crearCampana($infoCampana)) {
        $this->session->setFlashdata([
            'titulo' => "¡Éxito!",
            'mensaje' => "Se ha creado la campaña de forma exitosa.",
            'tipo' => "success"
        ]);
        return redirect()->to("campanas/");
    } else {
        $this->session->setFlashdata([
            'titulo' => "¡Error!",
            'mensaje' => "No se pudo crear la campaña, inténtalo nuevamente o contacta con soporte técnico.",
            'tipo' => "danger"
        ]);
        return redirect()->to("campanas/")->withInput();
    }
}


    public function crear_tipo() {
        # Obtenemos los datos del formulario...
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

        # Creamos la variable para crear el tipo...
        $infoTipo = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ];

        # Creamos el tipo...
        if ($this->tiposCampanas->crearTiposCampana($infoTipo)) {
            # Se creo el tipo...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado de forma exitosa el nuevo tipo de campaña.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos");
        } else {
            # No se pudo crear el tipo...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo crear el tipo de campaña intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos");
        }
    }

    public function crear_subtipo() {
        # Obtenemos los datos del formulario...
        $tipo_id = $this->request->getPost('tipo_id');
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'tipo_id' => 'required',
            'nombre' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Creamos la variable de creacion...
        $infoSubtipo = [
            'tipo_campana_id' => $tipo_id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ];

        # Creamos el subtipo...
        if ($this->subtiposCampanas->crearSubtiposCampana($infoSubtipo)) {
            # Se creo el subtipo...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha creado el subtipo de campaña de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        } else {
            # No se pudo crear el subtipo...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo crear el subtipo de campaña, intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        }
    }

    public function eliminar_tipo() {
        # Obtenemos los datos del formulario...
        $tipo_id = $this->request->getPost('id');

        # Obtenemos informaciond el tipo...
        $tipo = $this->tiposCampanas->obtenerTiposCampana($tipo_id);

        # Validamos si existe...
        if (empty($tipo)) {
            # No existe el tipo...
            return redirect()->back();
        }

        # Eliminamos el tipo...
        if ($this->tiposCampanas->eliminarTiposCampana($tipo_id)) {
            # Se elimino el tipo de campaña de forma correcta...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado el tipo de campaña de forma correcta.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos/");
        } else {
            # No se pudo eliminar el tipo de campaña...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo eliminar el tipo de campaña, "
                . "intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos/");
        }
    }

    public function eliminar_subtipo() {
        # Obtenemos los datos del formulario...
        $subtipo_id = $this->request->getPost('subtipo_id');
        $tipo_id = $this->request->getPost('tipo_id');

        # Eliminamos el subtipo...
        if ($this->subtiposCampanas->eliminarSubtiposCampana($subtipo_id)) {
            # Se elimino el subtipo de forma exitosa...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado el subtipo de campaña de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        } else {
            # No se elimino el subtipo...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo eliminar el subtipo de campaña, "
                . "intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        }
    }

    public function actualizar_tipo() {
        # Obtenemos los datos del formulario...
        $tipo_id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');

        # Obtenemos informacion del tipo...
        $tipo = $this->tiposCampanas->obtenerTiposCampana($tipo_id);

        # Validamos si existe...
        if (empty($tipo)) {
            # No exist eel tipo...
            return redirect()->back();
        }

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'id' => 'required',
            'nombre' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Creamos la variable de actualziacion...
        $infoTipo = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ];

        # Actualizamos el tipo...
        if ($this->tiposCampanas->actualizarTiposCampana($tipo_id, $infoTipo)) {
            # Se actualizo...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado el tipo de caampaña exitosamente.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        } else {
            # No se pudo actualizar...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo actualizar el tipo de campaña, "
                . "intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        }
    }

    public function actualizar_subtipo() {
        $subtipo_id = $this->request->getPost('subtipo_id');
        $tipo_id = $this->request->getPost('tipo_id');
        $nombre = $this->request->getPost('nombre');
        $descripcion = $this->request->getPost('descripcion');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'subtipo_id' => 'required',
            'tipo_id' => 'required',
            'nombre' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->back()->withInput();
        }

        # Creamos la variable de actualizacion...
        $infoSubtipo = [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];

        # Actualizamos el subtitpo...
        if ($this->subtiposCampanas->actualizarSubtiposCampana($subtipo_id, $infoSubtipo)) {
            # Se actualizo el subtipo...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado el subtipo de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        } else {
            # No se actualizo el subtipo...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se ha podido actualizar el subtipo, intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/tipos/detalle/{$tipo_id}");
        }
    }

    public function actualizar() {
        # Obtenemos los datos del formulario...
        $campana_id = $this->request->getPost('campana_id');
        $titulo = $this->request->getPost('titulo');
        $responsable = $this->request->getPost('responsable');
        $descripcion = $this->request->getPost('descripcion');
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'campana_id' => 'required',
            'titulo' => 'required',
            'responsable' => 'required',
            'descripcion' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("companas/detalle/{$campana_id}")->withInput();
        }

        # Obtenemos informacion de la campana...
        $campana = $this->campanas->obtenerCampana($campana_id);

        # Validamos si existe la campana...
        if (empty($campana)) {
            # No existe la campaña...
            return redirect()->to("campanas/");
        }

        # Creamos la variable de actualziacion...
        $infoCampana = [
            'titulo' => $titulo,
            'responsable' => strtoupper($responsable),
            'descripcion' => $descripcion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ];

        # Actualizamos la campaña...
        if ($this->campanas->actualizarCampana($campana_id, $infoCampana)) {
            # Se actualizo la campaña...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la informacion de la campaña de forma correcta.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/detalle/{$campana_id}");
        } else {
            # No se pudo actualizar la campaña...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo actualizar la campaña, intentalo nuevamente o contacta con soporte tecnico.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/detalle/{$campana_id}");
        }
    }

    public function eliminar() {
        # Obtenemos los datos del formulario...
        $campana_id = $this->request->getPost('campana_id');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'campana_id' => 'required',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            return redirect()->to("campanas/");
        }

        # Obtenemos informacion de la campana...
        $campana = $this->campanas->obtenerCampana($campana_id);

        # Validamos si existe la campana...
        if (empty($campana)) {
            # No existe la campana...
            return redirect()->to("campanas/");
        }

        # Eliminamos la campana...
        if ($this->campanas->eliminarCampana($campana_id)) {
            # Se elimino la campana...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado la campaña de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("campanas/");
        } else {
            # No se elimino la campana...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo eliminar la campana, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("campanas/detalle/{$campana_id}");
        }
    }

    public function obtener_subtipos($tipo_id = null) {
        // Asegurarnos que no hay output previo
        ob_clean();

        if (empty($tipo_id)) {
            return $this->response->setJSON(['error' => 'ID de tipo no proporcionado']);
        }

        $subtipos = $this->subtiposCampanas->obtenerSubtiposCampanasPorTipoCampana($tipo_id);

        // Asegurarnos que solo devolvemos JSON
        header('Content-Type: application/json');

        // Retornar JSON limpio
        return $this->response->setJSON($subtipos);
    }

    public function nueva() {
        $data['titulo_pagina'] = 'Metrix | Nueva Campaña';
        $data['tipos_campanas'] = $this->tiposCampanas->obtenerTiposCampanas();
        $data['areas'] = $this->areas->obtenerAreas();
        $data['usuarios_desde_2'] = $this->usuarios
            ->select('id, nombre')
            ->where('id >=', 2)
            ->findAll();
        $data['surveys'] = $this->survey->findAll();

        // Usar función reutilizable de breadcrumb
        $data['breadcrumb'] = $this->generarBreadcrumb([
            ['title' => 'Inicio', 'url' => base_url('/')],
            ['title' => 'Campañas', 'url' => base_url('campanas')],
            ['title' => 'Nueva Campaña'],
        ]);

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('campanas/crear-campana', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    protected function generarBreadcrumb($items = [])
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        foreach ($items as $item) {
            $breadcrumb->add($item['title'], $item['url'] ?? null);
        }
        return $breadcrumb->render();
    }
}

