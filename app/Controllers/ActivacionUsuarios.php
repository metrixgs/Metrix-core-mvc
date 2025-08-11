<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\ActivacionUsuariosModel;
use App\Models\ConfiguracionSistemaModel;
use App\Models\CuentasModel;
use App\Models\RolesModel;


class ActivacionUsuarios extends BaseController {

    protected $usuariosModel;
    protected $activacionModel;
    protected $configuracionModel;
    protected $cuentasModel;
    protected $rolesModel;


    public function __construct() {
        $this->usuariosModel = new UsuariosModel();
        $this->activacionModel = new ActivacionUsuariosModel();
        $this->configuracionModel = new ConfiguracionSistemaModel();
        $this->cuentasModel = new CuentasModel();
        $this->rolesModel = new RolesModel();

        
        helper(['Alerts', 'Rol', 'Menu', 'bitacora']);
    }

    /**
     * Panel principal de activación de usuarios
     */
    public function index() {
        $data['titulo_pagina'] = 'Metrix | Activación de Usuarios';
        
        // Obtener información del usuario actual
        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');
        
        // Verificar permisos y preparar datos según el rol
        if ($rolId == 1) {
            // Usuario Master - Estadísticas generales
            $data['es_master'] = true;
            $data['estadisticas_generales'] = $this->activacionModel->obtenerEstadisticasUsuarios();
            $data['cuentas_activas'] = $this->cuentasModel->where('activo', 1)->countAllResults();
            $data['historial_reciente'] = $this->activacionModel->obtenerHistorialActivaciones(null, 10);
        } elseif ($rolId == 2) {
            // Usuario Administrador - Estadísticas de su cuenta
            $data['es_administrador'] = true;
            $data['estadisticas_cuenta'] = $this->activacionModel->obtenerEstadisticasUsuarios($cuentaId);
            $data['usuarios_activos'] = $this->activacionModel->obtenerUsuariosActivos($cuentaId, 5);
            $data['usuarios_inactivos'] = $this->activacionModel->obtenerUsuariosInactivos($cuentaId, 5);
        } else {
            // Usuario sin permisos
            return redirect()->to('/')->with('message', 'No tiene permisos para acceder a esta sección');
        }
        
        // Datos comunes
        $data['rol_usuario'] = $rolId;
        $data['configuraciones'] = $this->configuracionModel->obtenerConfiguracionesPorCategoria('usuarios');

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('activacion_usuarios/index', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    /**
     * Panel principal de activación - Master
     */
    public function panelMaster() {
        // Verificar que sea usuario Máster
        if (session('session_data.rol_id') != 3) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $data['titulo_pagina'] = 'Metrix | Panel Master - Gestión de Usuarios';
        
        // Obtener estadísticas generales
        $data['estadisticas'] = $this->activacionModel->obtenerEstadisticasUsuarios();
        
        // Obtener usuarios por cuenta
        $data['cuentas'] = $this->cuentasModel->obtenerCuentas();
        $data['usuarios_por_cuenta'] = [];
        
        foreach ($data['cuentas'] as $cuenta) {
            $data['usuarios_por_cuenta'][$cuenta['id']] = [
                'cuenta' => $cuenta,
                'estadisticas' => $this->activacionModel->obtenerEstadisticasUsuarios($cuenta['id']),
                'usuarios_activos' => $this->activacionModel->obtenerUsuariosActivos($cuenta['id']),
                'usuarios_inactivos' => $this->activacionModel->obtenerUsuariosInactivos($cuenta['id'])
            ];
        }
        
        // Obtener historial reciente
        $data['historial_reciente'] = $this->activacionModel->obtenerHistorialActivaciones(null, 20);
        
        // Obtener configuraciones
        $data['configuraciones'] = $this->configuracionModel->obtenerConfiguracionesPorCategoria('usuarios');

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('activacion_usuarios/panel_master', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    /**
     * Panel de administrador - Gestión de usuarios de su cuenta
     */
    public function panelAdministrador() {
        // Verificar que sea usuario Admin Oficina
        if (session('session_data.rol_id') != 2) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $cuentaId = session('session_data.cuenta_id');
        $data['titulo_pagina'] = 'Metrix | Panel Administrador - Gestión de Usuarios';
        
        // Obtener estadísticas de la cuenta
        $data['estadisticas'] = $this->activacionModel->obtenerEstadisticasUsuarios($cuentaId);
        
        // Obtener usuarios de la cuenta
        $data['usuarios_activos'] = $this->activacionModel->obtenerUsuariosActivos($cuentaId);
        $data['usuarios_inactivos'] = $this->activacionModel->obtenerUsuariosInactivos($cuentaId);
        
        // Obtener historial de la cuenta
        $data['historial_cuenta'] = $this->activacionModel->obtenerHistorialActivaciones(null, 50);
        
        // Filtrar historial por usuarios de la cuenta
        $usuariosIds = array_merge(
            array_column($data['usuarios_activos'], 'id'),
            array_column($data['usuarios_inactivos'], 'id')
        );
        
        $data['historial_cuenta'] = array_filter($data['historial_cuenta'], function($item) use ($usuariosIds) {
            return in_array($item['usuario_afectado_id'], $usuariosIds);
        });
        
        // Obtener roles disponibles para la cuenta (excluir Máster)
        $data['roles'] = $this->rolesModel->whereNotIn('id', [1, 2, 3, 4])->findAll();

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('activacion_usuarios/panel_administrador', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    /**
     * Activar usuario
     */
    public function activarUsuario() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        $usuarioId = $this->request->getPost('usuario_id');
        $motivo = $this->request->getPost('motivo');
        $ejecutorId = session('session_data.id');
        $ipAddress = $this->request->getIPAddress();

        // Verificar permisos
        if (!$this->verificarPermisos($ejecutorId, $usuarioId)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No tiene permisos para activar este usuario'
            ]);
        }

        $resultado = $this->activacionModel->activarUsuario($usuarioId, $ejecutorId, $motivo, $ipAddress);
        
        // Registrar en bitácora general
        if ($resultado['success']) {
            $usuario = $this->usuariosModel->find($usuarioId);
            log_activity(
                $ejecutorId,
                'usuarios',
                'activar_usuario',
[
    'message' => "Usuario {$usuario['nombre']} activado",
    'data' => [
        'usuario_id' => $usuario['id'],
        'nombre' => $usuario['nombre']
    ]
]
            );
        }

        return $this->response->setJSON($resultado);
    }

    /**
     * Desactivar usuario
     */
    public function desactivarUsuario() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        $usuarioId = $this->request->getPost('usuario_id');
        $motivo = $this->request->getPost('motivo');
        $ejecutorId = session('session_data.id');
        $ipAddress = $this->request->getIPAddress();

        // Verificar permisos
        if (!$this->verificarPermisos($ejecutorId, $usuarioId)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No tiene permisos para desactivar este usuario'
            ]);
        }

        // No permitir desactivar al propio usuario
        if ($usuarioId == $ejecutorId) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No puede desactivar su propio usuario'
            ]);
        }

        $resultado = $this->activacionModel->desactivarUsuario($usuarioId, $ejecutorId, $motivo, $ipAddress);
        
        // Registrar en bitácora general
        if ($resultado['success']) {
            $usuario = $this->usuariosModel->find($usuarioId);
            log_activity(
                $ejecutorId,
                'usuarios',
                'desactivar_usuario',
[
    'message' => "Usuario {$usuario['nombre']} desactivado",
    'data' => [
        'usuario_id' => $usuario['id'],
        'nombre' => $usuario['nombre']
    ]
]
            );
        }

        return $this->response->setJSON($resultado);
    }

    /**
     * Obtener información de usuario
     */
    public function obtenerUsuario($id) {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        $ejecutorId = session('session_data.id');
        
        // Verificar permisos
        if (!$this->verificarPermisos($ejecutorId, $id)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No tiene permisos para ver este usuario'
            ]);
        }

        $usuario = $this->usuariosModel->obtenerUsuario($id);
        
        if (!$usuario) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        // Obtener historial del usuario
        $historial = $this->activacionModel->obtenerHistorialActivaciones($id, 10);
        
        return $this->response->setJSON([
            'success' => true,
            'usuario' => $usuario,
            'historial' => $historial
        ]);
    }

    /**
     * Obtener estadísticas en tiempo real
     */
    public function obtenerEstadisticas() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');

        if (in_array($rolId, [1, 2, 3, 4])) {
            // Máster: estadísticas globales
            $estadisticas = $this->activacionModel->obtenerEstadisticasUsuarios();
        } else {
            // Administrador: estadísticas de su cuenta
            $estadisticas = $this->activacionModel->obtenerEstadisticasUsuarios($cuentaId);
        }

        return $this->response->setJSON([
            'success' => true,
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Configurar sistema
     */
    public function configurarSistema() {
        // Solo Máster puede configurar
        if (session('session_data.rol_id') != 3) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        if ($this->request->getMethod() === 'POST') {
            $configuraciones = $this->request->getPost('config');
            
            foreach ($configuraciones as $clave => $valor) {
                $this->configuracionModel->establecerConfiguracion($clave, $valor);
            }
            
            log_activity(
                session('session_data.id'),
                'configuracion',
                'actualizar_configuracion',
[
    'message' => 'Configuraciones del sistema actualizadas',
    'data' => [
        'updated_by' => session('session_data.id'),
        'timestamp' => date('Y-m-d H:i:s')
    ]
]
            );
            
            return redirect()->back()->with('success', 'Configuraciones actualizadas correctamente');
        }

        $data['titulo_pagina'] = 'Metrix | Configuración del Sistema';
        $data['configuraciones'] = $this->configuracionModel->obtenerTodasConfiguraciones();
        
        // Agrupar por categoría
        $data['configuraciones_agrupadas'] = [];
        foreach ($data['configuraciones'] as $config) {
            $data['configuraciones_agrupadas'][$config['categoria']][] = $config;
        }

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('activacion_usuarios/configuracion', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    /**
     * Verificar permisos según jerarquía
     */
    private function verificarPermisos($ejecutorId, $usuarioObjetivoId) {
        $ejecutor = $this->usuariosModel->find($ejecutorId);
        $usuarioObjetivo = $this->usuariosModel->find($usuarioObjetivoId);
        
        if (!$ejecutor || !$usuarioObjetivo) {
            return false;
        }

        // Máster puede gestionar cualquier usuario
        if ($ejecutor['rol_id'] == 3) {
            return true;
        }

        // Admin Oficina puede gestionar usuarios de su cuenta (excepto Máster)
        if ($ejecutor['rol_id'] == 2) {
            return $usuarioObjetivo['cuenta_id'] == $ejecutor['cuenta_id'] && $usuarioObjetivo['rol_id'] != 3;
        }

        return false;
    }

    /**
     * Exportar reporte de activaciones
     */
    public function exportarReporte() {
        // Verificar permisos
        $rolId = session('session_data.rol_id');
        if (!in_array($rolId, [1, 2, 3, 4]) && $rolId != 22) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $fechaInicio = $this->request->getGet('fecha_inicio') ?: date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?: date('Y-m-d');
        $cuentaId = in_array($rolId, [1, 2, 3, 4]) ? $this->request->getGet('cuenta_id') : session('session_data.cuenta_id');

        // Obtener datos para el reporte
        $historial = $this->activacionModel->obtenerHistorialActivaciones(null, 1000);
        
        // Filtrar por fechas y cuenta si es necesario
        $historial = array_filter($historial, function($item) use ($fechaInicio, $fechaFin, $cuentaId, $rolId) {
            $fechaItem = date('Y-m-d', strtotime($item['fecha_hora']));
            $dentroRango = $fechaItem >= $fechaInicio && $fechaItem <= $fechaFin;
            
            if ($rolId == 22 && $cuentaId) {
                // Verificar que el usuario pertenezca a la cuenta del admin oficina
                $usuario = $this->usuariosModel->find($item['usuario_afectado_id']);
                return $dentroRango && $usuario && $usuario['cuenta_id'] == $cuentaId;
            }
            
            return $dentroRango;
        });

        // Generar CSV
        $filename = 'reporte_activaciones_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'Fecha/Hora',
            'Usuario Afectado',
            'Usuario Ejecutor',
            'Acción',
            'Estado Anterior',
            'Estado Nuevo',
            'Motivo',
            'IP Address'
        ]);
        
        // Datos
        foreach ($historial as $item) {
            fputcsv($output, [
                $item['fecha_hora'],
                $item['usuario_afectado_nombre'],
                $item['usuario_ejecutor_nombre'],
                ucfirst($item['accion']),
                $item['estado_anterior'] ? 'Activo' : 'Inactivo',
                $item['estado_nuevo'] ? 'Activo' : 'Inactivo',
                $item['motivo'],
                $item['ip_address']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * API para obtener usuarios filtrados
     */
    public function obtenerUsuariosFiltrados() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud no válida']);
        }

        $filtros = [
            'estado' => $this->request->getPost('estado'), // 'activo', 'inactivo', 'todos'
            'rol' => $this->request->getPost('rol'),
            'cuenta' => $this->request->getPost('cuenta'),
            'busqueda' => $this->request->getPost('busqueda')
        ];

        $rolEjecutor = session('session_data.rol_id');
        $cuentaEjecutor = session('session_data.cuenta_id');

        $builder = $this->usuariosModel->select('tbl_usuarios.*, tbl_roles.nombre as rol_nombre, tbl_cuentas.nombre as cuenta_nombre')
                                      ->join('tbl_roles', 'tbl_roles.id = tbl_usuarios.rol_id', 'left')
                                      ->join('tbl_cuentas', 'tbl_cuentas.id = tbl_usuarios.cuenta_id', 'left');

        // Filtros según rol del ejecutor
        if ($rolEjecutor == 2) {
            // Administrador: solo usuarios de su cuenta
            $builder->where('tbl_usuarios.cuenta_id', $cuentaEjecutor)
                   ->where('tbl_usuarios.rol_id !=', 3); // Excluir Máster
        } elseif ($rolEjecutor == 3) {
            // Máster: todos los usuarios
            if ($filtros['cuenta']) {
                $builder->where('tbl_usuarios.cuenta_id', $filtros['cuenta']);
            }
        }

        // Aplicar filtros
        if ($filtros['estado'] == 'activo') {
            $builder->where('tbl_usuarios.activo', 1)
                   ->where('tbl_usuarios.cuenta_activada', 1);
        } elseif ($filtros['estado'] == 'inactivo') {
            $builder->groupStart()
                   ->where('tbl_usuarios.activo', 0)
                   ->orWhere('tbl_usuarios.cuenta_activada', 0)
                   ->groupEnd();
        }

        if ($filtros['rol']) {
            $builder->where('tbl_usuarios.rol_id', $filtros['rol']);
        }

        if ($filtros['busqueda']) {
            $builder->groupStart()
                   ->like('tbl_usuarios.nombre', $filtros['busqueda'])
                   ->orLike('tbl_usuarios.correo', $filtros['busqueda'])
                   ->groupEnd();
        }

        $usuarios = $builder->orderBy('tbl_usuarios.nombre', 'ASC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }
}