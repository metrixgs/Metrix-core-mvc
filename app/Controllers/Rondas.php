<?php

namespace App\Controllers;

use App\Models\RondasModel;
use App\Models\TareasModel;
use App\Models\TicketsModel;
use App\Models\UsuariosModel;
use App\Models\SurveyModel; // Corregido: Cargar SurveyModel (singular)
use App\Controllers\BaseController;
use App\Models\SegmentacionesModel;
use App\Models\RondasSegmentacionesModel;
use App\Models\AreasModel; // Añadir el modelo de Áreas (Dependencias)
use App\Models\TagModel; // Añadir el modelo de Tags
use App\Models\BrigadasModel; // Añadir el modelo de Brigadas
use App\Models\CampanasModel; // Añadir el modelo de Campanas
use App\Models\RondaOperadorPuntosModel; // Añadir el modelo de RondaOperadorPuntos

class Rondas extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $tareas;
    protected $rondas;
    protected $segmentaciones;
    protected $rondasSegmentaciones;
    protected $areas; // Añadir propiedad para AreasModel
    protected $brigadasModel; // Añadir propiedad para BrigadasModel
    protected $campanasModel; // Añadir propiedad para CampanasModel
    protected $rondaOperadorPuntosModel; // Añadir propiedad para RondaOperadorPuntosModel

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->tareas = new TareasModel();
        $this->rondas = new RondasModel();
        $this->rondasSegmentaciones = new RondasSegmentacionesModel();
        $this->segmentaciones = new SegmentacionesModel();
        $this->areas = new AreasModel(); // Instanciar AreasModel
        $this->survey = new SurveyModel();
        $this->tagsModel = new TagModel(); // Instanciar TagModel
        $this->brigadasModel = new BrigadasModel(); // Instanciar BrigadasModel
        $this->campanasModel = new CampanasModel(); // Instanciar CampanasModel
        $this->rondaOperadorPuntosModel = new RondaOperadorPuntosModel(); // Instanciar RondaOperadorPuntosModel

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
        helper('Rol');
        helper('Menu');
    }

    public function index() {
    # Creamos el titulo de la pagina...
    $data['titulo_pagina'] = 'Metrix | Lista de Rondas';

    # Obtenemos todas las rondas con nombres de coordinador y encargado
    $rondas = $this->rondas->obtenerRondasConUsuarios();

    # Obtener las segmentaciones para cada ronda
    foreach ($rondas as $key => $ronda) {
        // Obtener relaciones de segmentación para esta ronda
        $relaciones = $this->rondasSegmentaciones->obtenerRelacionesPorRonda($ronda['id']);

        // Inicializar array para almacenar las segmentaciones
        $segmentacionesRonda = [];

        // Obtener detalles de cada segmentación
        foreach ($relaciones as $relacion) {
            $segmentacion = $this->segmentaciones->obtenerSegmentacion($relacion['segmentacion_id']);
            if ($segmentacion) {
                $segmentacionesRonda[] = $segmentacion;
            }
        }

        // Añadir las segmentaciones a la información de la ronda
        $rondas[$key]['segmentaciones'] = $segmentacionesRonda;
    }

    $data['rondas'] = $rondas;

    # También podemos preparar la lista de segmentaciones para el filtro
    $data['todas_segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();

    return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('rondas/lista-rondas', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
}


    public function detalle($id = null) {
        if (!$id) {
            return redirect()->to('/rondas')->with('error', 'ID de ronda no especificado');
        }

        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Detalle de Ronda';

        # Obtener información de la ronda
        $ronda = $this->rondas->obtenerRonda($id);

        if (!$ronda) {
            return redirect()->to('/rondas')->with('error', 'Ronda no encontrada');
        }

        // Obtener nombres de coordinador, encargado y coordinador de campaña
        $coordinador = $this->usuarios->find($ronda['coordinador']);
        $ronda['coordinador_nombre'] = $coordinador['nombre'] ?? 'N/A';

        $encargado = $this->usuarios->find($ronda['encargado']);
        $ronda['encargado_nombre'] = $encargado['nombre'] ?? 'N/A';

        $coordinador_campana = $this->usuarios->find($ronda['coordinador_campana']);
        $ronda['coordinador_campana_nombre'] = $coordinador_campana['nombre'] ?? 'N/A';

        // Obtener nombre de la campaña
        $campanaModel = new \App\Models\CampanasModel();
        $campana = $campanaModel->find($ronda['campana_id']);
        $ronda['nombre_campana'] = $campana['nombre'] ?? 'N/A';

        // Obtener título de la encuesta de ronda
        $ronda['encuesta_ronda_id'] = $ronda['encuesta_ronda'] ?? null; // Asegurar que el ID de la encuesta esté disponible
        $survey = null;
        if (!empty($ronda['encuesta_ronda_id'])) {
            $survey = $this->survey->find($ronda['encuesta_ronda_id']);
        }
        $ronda['encuesta_ronda_titulo'] = $survey['title'] ?? 'N/A';

        # Obtener segmentaciones de la ronda
        $relaciones = $this->rondasSegmentaciones->obtenerRelacionesPorRonda($id);
        $segmentacionesRonda = [];

        foreach ($relaciones as $relacion) {
            $segmentacion = $this->segmentaciones->obtenerSegmentacion($relacion['segmentacion_id']);
            if ($segmentacion) {
                $segmentacionesRonda[] = $segmentacion;
            }
        }

        $ronda['segmentaciones'] = $segmentacionesRonda;
        $data['ronda'] = $ronda;

        // Obtener datos reales para indicadores
        $brigadas_activas = $this->rondas->contarBrigadasActivas($id);
        $visitas_realizadas = $this->rondas->contarVisitasRealizadas($id);
        $universo = $ronda['universo'] ?? 0; // Obtener el universo de la ronda, si existe

        $progreso = 0;
        if ($universo > 0) {
            $progreso = round(($visitas_realizadas / $universo) * 100);
        }

        $data['ronda']['progreso'] = $progreso;
        $data['ronda']['brigadas_activas'] = $brigadas_activas;
        $data['ronda']['visitas_realizadas'] = $visitas_realizadas;
        $data['ronda']['universo'] = $universo; // Asegurarse de que el universo esté disponible en la vista

        // Obtener la distribución de puntos por operador para esta ronda
        $puntos_operadores_raw = $this->rondaOperadorPuntosModel->where('ronda_id', $id)->findAll();
        $puntos_operadores = [];

        foreach ($puntos_operadores_raw as $puntos_op) {
            $operador = $this->usuarios->find($puntos_op['operador_id']);
            $puntos_operadores[] = [
                'operador_nombre' => $operador['nombre'] ?? 'N/A',
                'puntos_asignados' => $puntos_op['puntos_asignados']
            ];
        }
        $data['ronda']['puntos_operadores'] = $puntos_operadores;

        // Los siguientes indicadores se inicializan a 0 ya que no hay una fuente de datos clara vinculada a ronda_id
        // Si se requiere que sean reales, se necesita implementar la lógica de obtención de datos.
        $data['ronda']['incidencias_reportadas'] = 0;
        $data['ronda']['entregas_realizadas'] = 0;
        $data['ronda']['botagos_registrados'] = 0;
        $data['ronda']['peticiones_recibidas'] = 0;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/detalle-ronda', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

     public function crear() {
    $data['titulo_pagina'] = 'Metrix | Crear Ronda';

    // Obtener el campana_id de la URL
    $campanaId = $this->request->getGet('campana_id');
    $campana = null;

    if ($campanaId) {
        $campanaModel = new \App\Models\CampanasModel();
        $campana = $campanaModel->find($campanaId);
    }
    $data['campana'] = $campana; // Pasar la información de la campaña a la vista

    // Cargar usuarios y segmentaciones
    $data['segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();
    $data['surveys'] = $this->survey->findAll();
    // Obtener todas las brigadas del modelo BrigadasModel
    $data['brigadas'] = $this->brigadasModel->findAll();
    $data['operadores'] = []; // Inicialmente vacío, se llenará dinámicamente
    $data['usuarios_coordinador'] = $this->usuarios->where('rol_id', 9)->findAll(); // Esto parece ser para coordinadores, no para brigadas en sí.
    // Cargar tags para el modal Universo (solo tags con usuarios asociados y su conteo)
    $data['catalogo_tags'] = $this->tagsModel->getTagsWithUserCounts();
    $data['tag_stats'] = array_column($data['catalogo_tags'], 'user_count', 'slug');

    if ($this->request->getMethod() === 'post') {
        // Generar el nombre de la ronda como "rondaX"
        $lastRonda = $this->rondas->orderBy('id', 'DESC')->first();
        $nextRondaNumber = ($lastRonda['id'] ?? 0) + 1;
        $nombreRondaGenerado = 'ronda' . $nextRondaNumber;

        // Recoger los datos del formulario
        $datosRonda = [
            'campana_id' => $this->request->getPost('campana_id') ?: ($campanaId ?? 1),
            'nombre' => $nombreRondaGenerado, // Usar el nombre de ronda generado
            'coordinador' => $this->request->getPost('coordinador'),
            'coordinador_campana' => $this->request->getPost('coordinador_campana'),
            'encuesta_ronda' => $this->request->getPost('encuesta_ronda'),
            'fecha_actividad' => $this->request->getPost('fecha_actividad'),
            'hora_actividad' => $this->request->getPost('hora_actividad'),
            'estado' => $this->request->getPost('estado') ?: 'Programada',
            'universo' => $this->request->getPost('universo'),
            'entregable' => $this->request->getPost('entregable'),
            'territorio' => $this->request->getPost('territorio'),
            'nombre_territorio' => $this->request->getPost('nombre_territorio'),
            'sectorizacion' => $this->request->getPost('sectorizacion'),
            'nombre_sectorizacion' => $this->request->getPost('nombre_sectorizacion'),
        ];

        // Crear la ronda
        $rondaId = $this->rondas->crearRonda($datosRonda);

        if (!$rondaId) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la ronda. Por favor, intente de nuevo.');
        }

        // Relacionar segmentaciones
        $segmentacionesIds = $this->request->getPost('segmentaciones');
        if ($segmentacionesIds) {
            foreach ($segmentacionesIds as $segId) {
                $this->rondasSegmentaciones->crearRelacion([
                    'ronda_id' => $rondaId,
                    'segmentacion_id' => $segId
                ]);
            }
        }

        // Guardar la distribución de puntos por operador
        $puntosOperador = $this->request->getPost('puntos_operador');
        if (!empty($puntosOperador) && is_array($puntosOperador)) {
            foreach ($puntosOperador as $operadorId => $puntos) {
                $puntos = (int) $puntos;
                $this->rondaOperadorPuntosModel->insert([
                    'ronda_id' => $rondaId,
                    'operador_id' => $operadorId,
                    'puntos_asignados' => $puntos
                ]);
            }
        }

        // Obtener información del coordinador para area_id y cuenta_id
        $coordinadorInfo = $this->usuarios->find($datosRonda['coordinador']);
        $areaIdCoordinador = $coordinadorInfo['area_id'] ?? null;
        $cuentaIdCoordinador = $coordinadorInfo['cuenta_id'] ?? null;

        // ====================================================================
        // Lógica para inyectar puntos del universo como incidencias (tickets)
        // ====================================================================
        if (!empty($campana['universo'])) {
            $tagSlugs = array_filter(array_map('trim', explode(',', $campana['universo'])));

            if (!empty($tagSlugs)) {
                $usuariosDelUniverso = $this->usuarios->getUsersByTags($tagSlugs);

                foreach ($usuariosDelUniverso as $usuario) {
                    // Preparar datos para el ticket (incidencia)
                    $ticketData = [
                        'cliente_id'            => $usuario['id'],
                        'categoria_id'          => 1, // ID de categoría por defecto para "Incidencia"
                        'usuario_id'            => $datosRonda['coordinador'],
                        'campana_id'            => $datosRonda['campana_id'],
                        'ronda_id'              => $nombreRondaGenerado, // Usar el nombre de ronda generado (ej. "ronda1")
                        'tipo_id'               => 1, // Tipo de ticket por defecto (ej. Reporte)
                        'identificador'         => 'RND-' . $rondaId . '-USR-' . $usuario['id'],
                        'titulo'                => 'Incidencia de Ronda ' . $nombreRondaGenerado . ' para ' . $usuario['nombre'],
                        'descripcion'           => 'Incidencia generada automáticamente para el usuario ' . $usuario['nombre'] . ' (' . $usuario['correo'] . ') en la ronda ' . $nombreRondaGenerado . '.',
                        'prioridad'             => 'Media', // Prioridad como string
                        'latitud'               => (string)($usuario['latitud'] ?? ''),
                        'longitud'              => (string)($usuario['longitud'] ?? ''),
                        'estado_p'              => 'Pendiente',
                        'estado'                => 'Pendiente', // Coincide con el ejemplo JSON
                        'municipio'             => $usuario['municipio'] ?? '',
                        'colonia'               => $usuario['colonia'] ?? '',
                        'df'                    => '', // Dejar vacío si no hay datos
                        'dl'                    => '', // Dejar vacío si no hay datos
                        'seccion_electoral'     => 0, // Valor por defecto si no hay datos
                        'codigo_postal'         => $usuario['codigo_postal'] ?? '',
                        'direccion_completa'    => $usuario['calle'] ?? '',
                        'direccion_solicitante' => $usuario['residencia'] ?? '',
                        'mismo_domicilio'       => 'Si',
                        'fecha_creacion'        => date('Y-m-d H:i:s'),
                        'fecha_cierre'          => null,
                        'fecha_vencimiento'     => date('Y-m-d H:i:s', strtotime('+7 days')),
                        'cuenta_id'             => $cuentaIdCoordinador, // Cuenta del coordinador
                        'area_id'               => $areaIdCoordinador, // Área del coordinador
                        'nombreCiudadano'       => $usuario['nombre'] ?? '',
                        'correoCiudadano'       => $usuario['correo'] ?? '',
                        'telefonoCiudadano'     => $usuario['telefono'] ?? '',
                        'encuesta_contestada'   => 0,
                        'tipo_ticket_id'        => 1, // Valor por defecto
                        'status'                => 'Pendiente', // Coincide con el ejemplo JSON
                    ];
                    $ticketId = $this->tickets->crearTicket($ticketData);
                }
            }
        }

        return redirect()->to('/campanas/rondas/' . $datosRonda['campana_id'])->with('success', 'Ronda creada correctamente y incidencias generadas.');
    }

    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('rondas/crear-ronda', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}



    public function editar($id = null) {
        if (!$id) {
            return redirect()->to('/rondas')->with('error', 'ID de ronda no especificado');
        }

        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Editar Ronda';

        # Obtener información de la ronda
        $ronda = $this->rondas->obtenerRonda($id);

        if (!$ronda) {
            return redirect()->to('/rondas')->with('error', 'Ronda no encontrada');
        }

        // Cargar datos para los selects
        $campanaModel = new \App\Models\CampanasModel();
        $data['campanas'] = $campanaModel->findAll(); // O un método más específico si existe

        $data['brigadas'] = $this->usuarios->where('rol_id', 9)->findAll(); // Coordinadores de Brigada
        $data['operadores'] = $this->usuarios->where('rol_id', 5)->findAll(); // Operadores
        $data['usuarios_coordinador_campana'] = $this->usuarios->where('rol_id', 9)->findAll(); // Coordinadores de Campaña

        $data['surveys'] = $this->survey->findAll(); // Todas las encuestas

        # Obtener todas las segmentaciones para el formulario
        $data['segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();

        # Obtener segmentaciones asignadas actualmente
        $relaciones = $this->rondasSegmentaciones->obtenerRelacionesPorRonda($id);
        $segmentacionesAsignadas = [];

        foreach ($relaciones as $relacion) {
            $segmentacionesAsignadas[] = $relacion['segmentacion_id'];
        }

        $data['segmentaciones_asignadas'] = $segmentacionesAsignadas;
        $data['ronda'] = $ronda;
        $data['universo_ronda'] = $ronda['universo'] ?? ''; // Pasar el valor del universo a la vista

        # Verificar si es una solicitud POST
        if ($this->request->getMethod() === 'post') {
            # Recoger datos del formulario
            $datosRonda = [
                'campana_id' => $this->request->getPost('campana_id'),
                'nombre' => $this->request->getPost('nombre'),
                'coordinador' => $this->request->getPost('coordinador'),
                'encargado' => $this->request->getPost('encargado'),
                'coordinador_campana' => $this->request->getPost('coordinador_campana'), // Nuevo campo
                'encuesta_ronda' => $this->request->getPost('encuesta_ronda'), // Nuevo campo
                'fecha_actividad' => $this->request->getPost('fecha_actividad'),
                'hora_actividad' => $this->request->getPost('hora_actividad'),
                'estado' => $this->request->getPost('estado'),
                'universo' => $this->request->getPost('universo') // Nuevo campo
            ];

            # Actualizar la ronda
            $this->rondas->actualizarRonda($id, $datosRonda);

            # Eliminar segmentaciones actuales
            $this->rondasSegmentaciones->eliminarRelacionesPorRonda($id);

            # Procesar segmentaciones seleccionadas
            $segmentacionesIds = $this->request->getPost('segmentaciones');
            if ($segmentacionesIds) {
                foreach ($segmentacionesIds as $segId) {
                    $this->rondasSegmentaciones->crearRelacion([
                        'ronda_id' => $id,
                        'segmentacion_id' => $segId
                    ]);
                }
            }

            return redirect()->to('/rondas')->with('success', 'Ronda actualizada correctamente');
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/editar-ronda', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    public function eliminar($id = null) {
        if (!$id) {
            return redirect()->to('/rondas')->with('error', 'ID de ronda no especificado');
        }

        // Obtener la ronda antes de eliminarla para obtener el campana_id
        $ronda = $this->rondas->find($id);

        if (!$ronda) {
            return redirect()->to('/rondas')->with('error', 'Ronda no encontrada');
        }

        # Primero eliminar las relaciones con segmentaciones
        $this->rondasSegmentaciones->eliminarRelacionesPorRonda($id);

        # Luego eliminar la ronda
        $this->rondas->eliminarRonda($id);

        // Redirigir a la lista de rondas de la campaña
        return redirect()->to('/campanas/rondas/' . $ronda['campana_id'])->with('success', 'Ronda eliminada correctamente');
    }

    /**
     * Muestra las rondas filtradas por segmentación
     */
    public function por_segmentacion() {
        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Rondas por Segmentación';

        # Obtenemos todas las segmentaciones para el filtro
        $data['segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();

        # Verificamos si se ha seleccionado una segmentación
        $segmentacion_id = $this->request->getGet('segmentacion_id');

        if ($segmentacion_id) {
            # Obtener las relaciones de esta segmentación
            $relaciones = $this->rondasSegmentaciones->obtenerRelacionesPorSegmentacion($segmentacion_id);

            # Obtener las rondas relacionadas
            $rondas = [];
            foreach ($relaciones as $relacion) {
                $ronda = $this->rondas->obtenerRonda($relacion['ronda_id']);
                if ($ronda) {
                    # Para cada ronda, obtener sus segmentaciones
                    $segmentacionesRonda = [];
                    $relacionesRonda = $this->rondasSegmentaciones->obtenerRelacionesPorRonda($ronda['id']);

                    foreach ($relacionesRonda as $rel) {
                        $seg = $this->segmentaciones->obtenerSegmentacion($rel['segmentacion_id']);
                        if ($seg) {
                            $segmentacionesRonda[] = $seg;
                        }
                    }

                    $ronda['segmentaciones'] = $segmentacionesRonda;
                    $rondas[] = $ronda;
                }
            }

            $data['rondas'] = $rondas;
            $data['segmentacion_seleccionada'] = $this->segmentaciones->obtenerSegmentacion($segmentacion_id);
        } else {
            $data['rondas'] = [];
            $data['segmentacion_seleccionada'] = null;
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/por-segmentacion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    /**
     * Listar todas las segmentaciones
     */
    public function segmentaciones() {
        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Segmentaciones';

        # Obtener todas las segmentaciones
        $data['segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/segmentaciones/lista-segmentaciones', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    /**
     * Crear nueva segmentación
     */
    public function crear_segmentacion() {
        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Nueva Segmentación';

        # Verificar si es una solicitud POST
        if ($this->request->getMethod() === 'post') {
            # Recoger datos del formulario
            $datosSegmentacion = [
                'codigo' => $this->request->getPost('codigo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado') ? $this->request->getPost('estado') : 'Activo'
            ];

            # Crear la segmentación
            $this->segmentaciones->crearSegmentacion($datosSegmentacion);

            return redirect()->to('/segmentaciones')->with('success', 'Segmentación creada correctamente');
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/segmentaciones/crear-segmentacion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    /**
     * Editar segmentación existente
     */
    public function editar_segmentacion($id = null) {
        if (!$id) {
            return redirect()->to('/segmentaciones')->with('error', 'ID de segmentación no especificado');
        }

        # Título de la página
        $data['titulo_pagina'] = 'Metrix | Editar Segmentación';

        # Obtener información de la segmentación
        $data['segmentacion'] = $this->segmentaciones->obtenerSegmentacion($id);

        if (!$data['segmentacion']) {
            return redirect()->to('/segmentaciones')->with('error', 'Segmentación no encontrada');
        }

        # Verificar si es una solicitud POST
        if ($this->request->getMethod() === 'post') {
            # Recoger datos del formulario
            $datosSegmentacion = [
                'codigo' => $this->request->getPost('codigo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado')
            ];
            # Actualizar la segmentación
            $this->segmentaciones->actualizarSegmentacion($id, $datosSegmentacion);

            return redirect()->to('/segmentaciones')->with('success', 'Segmentación actualizada correctamente');
        }

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('rondas/segmentaciones/editar-segmentacion', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    /**
     * Eliminar segmentación
     */
    public function eliminar_segmentacion($id = null) {
        if (!$id) {
            return redirect()->to('/segmentaciones')->with('error', 'ID de segmentación no especificado');
        }

        # Verificar si la segmentación está siendo utilizada en alguna ronda
        $relaciones = $this->rondasSegmentaciones->obtenerRelacionesPorSegmentacion($id);

        if (!empty($relaciones)) {
            return redirect()->to('/segmentaciones')->with('error', 'No se puede eliminar esta segmentación porque está siendo utilizada en una o más rondas');
        }

        # Eliminar la segmentación
        $this->segmentaciones->eliminarSegmentacion($id);

        return redirect()->to('/segmentaciones')->with('success', 'Segmentación eliminada correctamente');
    }



public function puntos()
{
    $data = [
        'titulo_pagina' => 'Gestión de Puntos de Ronda',
        // Puedes agregar más variables si necesitas
    ];

    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('rondas/puntos', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}

public function zonas()
{
    $data = [
        'titulo_pagina' => 'Gestión de Zonas de Ronda',
    ];

    return view('incl/head-application', $data)
        . view('incl/header-application', $data)
        . view('incl/menu-admin', $data)
        . view('rondas/zonas', $data)
        . view('incl/footer-application', $data)
        . view('incl/scripts-application', $data);
}

 public function listar($campana_id = null)
{
    if (!$campana_id) {
        return $this->response->setJSON([]);
    }

    // Obtener todas las rondas asociadas a la campaña
    $rondas = $this->rondas
        ->select('id, fecha_actividad, hora_actividad, encargado, estado')
        ->where('campana_id', $campana_id)
        ->findAll();

    return $this->response->setJSON($rondas);
}

    public function obtenerOperadoresPorBrigada($brigada_id) {
        $operadores = $this->usuarios->obtenerUsuariosPorArea($brigada_id);
        return $this->response->setJSON($operadores);
    }

public function finalizarRondaWeb($id = null)
{
    if (!$id) {
        return redirect()->to('/rondas')->with('error', 'ID de ronda no especificado');
    }

    $actualizado = $this->rondas->update($id, [
        'estado' => 'Finalizada'
    ]);

    if ($actualizado) {
        return redirect()->to('/rondas')->with('success', 'Ronda finalizada correctamente.');
    } else {
        return redirect()->to('/rondas')->with('error', 'No se pudo finalizar la ronda.');
    }
}

    public function tags()
    {
        try {
            // Obtener todos los tags con el conteo de usuarios, incluyendo aquellos con 0 usuarios
            $tags = $this->tagsModel->getUsersCountByTag(); // Devuelve slug => user_count
            $allTags = $this->tagsModel->allOrdered(); // Devuelve id, tag, slug

            // Combinar la información para tener el formato esperado en el frontend
            $formattedTags = [];
            foreach ($allTags as $tagInfo) {
                $slug = $tagInfo['slug'];
                $formattedTags[] = [
                    'id'         => $tagInfo['id'],
                    'tag'        => $tagInfo['tag'],
                    'slug'       => $slug,
                    'user_count' => $tags[$slug] ?? 0 // Asignar el conteo o 0 si no existe
                ];
            }

            // Respuesta en formato JSON estándar
            return $this->response->setJSON([
                'ok'   => true,
                'data' => $formattedTags
            ]);
        } catch (\Throwable $e) {
            // Captura errores y responde con mensaje claro
            return $this->response->setJSON([
                'ok'        => false,
                'message'   => 'Error al obtener las etiquetas',
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cuenta el número de usuarios asociados a los tags seleccionados.
     * Este método es llamado vía AJAX desde la vista crear-ronda.php.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function countUsersBySelectedTags()
    {
        try {
            $tagSlugsString = $this->request->getGet('tags');
            $tagSlugs = [];

            if (!empty($tagSlugsString)) {
                $tagSlugs = array_map('trim', explode(',', $tagSlugsString));
                // Filtrar slugs vacíos si los hubiera
                $tagSlugs = array_filter($tagSlugs);
            }

            $userCount = 0;
            if (!empty($tagSlugs)) {
                $userCount = $this->usuarios->countUsersByTags($tagSlugs);
            }

            return $this->response->setJSON([
                'ok'    => true,
                'count' => $userCount
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'ok'        => false,
                'message'   => 'Error al contar usuarios por tags',
                'exception' => $e->getMessage()
            ]);
        }
    }
}
