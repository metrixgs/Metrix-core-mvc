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

class Rondas extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $tareas;
    protected $rondas;
    protected $segmentaciones;
    protected $rondasSegmentaciones;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->tareas = new TareasModel();
        $this->rondas = new RondasModel();
        $this->rondasSegmentaciones = new RondasSegmentacionesModel();
        $this->segmentaciones = new SegmentacionesModel();
        $this->survey = new SurveyModel(); // Corregido: Instanciar SurveyModel (singular)

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

    // Cargar usuarios y segmentaciones
    $data['segmentaciones'] = $this->segmentaciones->obtenerSegmentaciones();
    $data['surveys'] = $this->survey->findAll(); // Corregido: Obtener todas las encuestas usando $this->survey
    // Obtener solo usuarios con rol_id = 9 (Coordinador) para el campo 'coordinador' (Brigada)
    $data['brigadas'] = $this->usuarios->where('rol_id', 9)->findAll();
    // Obtener solo usuarios con rol_id = 5 (Operador) para el campo 'encargado'
    $data['operadores'] = $this->usuarios->where('rol_id', 5)->findAll();
    // Obtener solo usuarios con rol_id = 9 (Coordinador) para el campo 'coordinador_campana'
    $data['usuarios_coordinador'] = $this->usuarios->where('rol_id', 9)->findAll();

    if ($this->request->getMethod() === 'post') {
        // Recoger los datos del formulario
        $datosRonda = [
            'campana_id' => $this->request->getPost('campana_id') ?: 1,
            'nombre' => $this->request->getPost('nombre'),
            'coordinador' => $this->request->getPost('coordinador'),
            'encargado' => $this->request->getPost('encargado'),
            'coordinador_campana' => $this->request->getPost('coordinador_campana'),
            'encuesta_ronda' => $this->request->getPost('encuesta_ronda'), // Nuevo campo
            'fecha_actividad' => $this->request->getPost('fecha_actividad'),
            'hora_actividad' => $this->request->getPost('hora_actividad'),
            'estado' => $this->request->getPost('estado') ?: 'Programada'
        ];

        // Crear la ronda
        $rondaId = $this->rondas->crearRonda($datosRonda);

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

        return redirect()->to('/rondas')->with('success', 'Ronda creada correctamente');
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

        # Primero eliminar las relaciones con segmentaciones
        $this->rondasSegmentaciones->eliminarRelacionesPorRonda($id);

        # Luego eliminar la ronda
        $this->rondas->eliminarRonda($id);

        return redirect()->to('/rondas')->with('success', 'Ronda eliminada correctamente');
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

}
