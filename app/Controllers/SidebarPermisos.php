<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermisosModel;
use App\Models\UsuariosModel;
use App\Models\RolesModel;

/**
 * Controlador para gestión de permisos del sidebar
 * Permite a super admin, administradores y master gestionar permisos de acceso
 */
class SidebarPermisos extends BaseController
{
    protected $permisosModel;
    protected $usuariosModel;
    protected $rolesModel;

    public function __construct()
    {
        $this->permisosModel = new PermisosModel();
        $this->usuariosModel = new UsuariosModel();
        $this->rolesModel = new RolesModel();
    }

    /**
     * Página principal de gestión de permisos del sidebar
     */
    public function index()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return redirect()->to('/unauthorized');
        }

        try {
            // Obtener roles según el nivel de acceso del usuario
            $rolesPermitidos = $this->obtenerRolesSegunAcceso();
            
            $data = [
                'titulo' => 'Gestión de Permisos del Sidebar',
                'success' => true,
                'mensaje' => '',
                'roles' => $rolesPermitidos,
                'modulos' => $this->permisosModel->obtenerModulosSidebar(),
                'permisos' => $this->permisosModel->obtenerPermisosPorRol($rolesPermitidos)
            ];

            return view('sidebar_permisos', $data);
        } catch (\Exception $e) {
            $data = [
                'titulo' => 'Gestión de Permisos del Sidebar',
                'success' => false,
                'mensaje' => 'Error al cargar los permisos: ' . $e->getMessage(),
                'roles' => [],
                'modulos' => [],
                'permisos' => []
            ];

            return view('sidebar_permisos', $data);
        }
    }

    /**
     * Actualizar permiso específico vía AJAX
     */
    public function actualizar()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        $rolId = $this->request->getPost('rol_id');
        $moduloNombre = $this->request->getPost('modulo_nombre');
        $valor = (int)$this->request->getPost('valor');

        if (!$rolId || !$moduloNombre) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        // Verificar si el usuario puede modificar este rol (multi-tenant)
            if (!$this->puedeModificarRol($rolId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No tienes permisos para modificar este rol'
                ]);
            }

        try {
            $resultado = $this->permisosModel->actualizarPermiso($rolId, $moduloNombre, $valor);

            if ($resultado) {
                // Registrar en auditoría
                $this->registrarAuditoria('UPDATE', $rolId, $moduloNombre, $valor);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Permiso actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el permiso'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar si el usuario puede modificar un rol específico (multi-tenant)
     */
    private function puedeModificarRol($rolIdObjetivo)
    {
        $usuarioId = session('session_data.usuario_id');
        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');
        
        // Obtener información del rol actual
        $rolActual = $this->rolesModel->obtenerRol($rolId);
        if (!$rolActual) {
            return false;
        }
        
        $nombreRol = strtoupper($rolActual['nombre'] ?? '');
        
        // Si es MASTER puede modificar cualquier rol
        if ($rolId == 1 || in_array($nombreRol, ['MASTER', 'MEGA_ADMIN'])) {
            return true;
        }
        
        // Si es ADMINISTRADOR solo puede modificar roles de su cuenta
        if ($rolId == 2 || in_array($nombreRol, ['ADMIN', 'ADMINISTRADOR'])) {
            // No puede modificar el rol Master
            if ($rolIdObjetivo == 1) {
                return false;
            }
            
            // Verificar que el rol objetivo pertenezca a usuarios de su cuenta
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT COUNT(*) as count
                FROM tbl_usuarios u
                WHERE u.rol_id = ? AND u.cuenta_id = ? AND u.activo = 1
            ", [$rolIdObjetivo, $cuentaId]);
            
            $result = $query->getRow();
            return $result && $result->count > 0;
        }
        
        // Otros roles no pueden modificar permisos
        return false;
    }

    /**
     * Configurar permisos por defecto
     */
    public function configurarDefecto()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        try {
            $resultado = $this->permisosModel->configurarPermisosPorDefecto();

            if ($resultado) {
                // Registrar en auditoría
                $this->registrarAuditoria('BULK_UPDATE', null, 'configuracion_defecto', 1);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Configuración por defecto aplicada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al aplicar la configuración por defecto'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas de permisos
     */
    public function estadisticas()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        try {
            $roles = $this->obtenerRolesFormateados();
            $modulos = $this->permisosModel->obtenerModulosSidebar();
            $permisos = $this->permisosModel->obtenerPermisosPorRol();
            
            $estadisticas = [];
            
            foreach ($roles as $rolId => $rolNombre) {
                $totalModulos = count($modulos);
                $modulosActivos = 0;
                
                if (isset($permisos[$rolId])) {
                    $modulosActivos = array_sum($permisos[$rolId]);
                }
                
                $porcentaje = $totalModulos > 0 ? round(($modulosActivos / $totalModulos) * 100) : 0;
                
                $estadisticas[] = [
                    'rol' => $rolNombre,
                    'total_modulos' => $totalModulos,
                    'modulos_activos' => $modulosActivos,
                    'porcentaje' => $porcentaje
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar si el usuario tiene acceso a esta funcionalidad
     */
    private function verificarAcceso()
    {
        // CORREGIDO: Usar la estructura correcta de sesión
        $usuarioId = session('session_data.usuario_id');
        $rolId = session('session_data.rol_id');
        
        if (!$usuarioId || !$rolId) {
            return false;
        }

        // Obtener información del rol
        $rol = $this->rolesModel->obtenerRol($rolId);
        
        if (!$rol) {
            return false;
        }

        // Permitir acceso a MASTER, ADMIN y roles con nivel <= 2
        $rolesPermitidos = ['MASTER', 'ADMIN', 'SUPER_ADMIN', 'ADMINISTRADOR'];
        $nombreRol = strtoupper($rol['nombre'] ?? '');
        
        return in_array($nombreRol, $rolesPermitidos) || ($rol['nivel'] ?? 999) <= 2;
    }

    /**
     * Obtener roles según el nivel de acceso del usuario (multi-tenant)
     */
    private function obtenerRolesSegunAcceso()
    {
        $usuarioId = session('session_data.usuario_id');
        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');
        
        // Obtener información del usuario actual
        $usuarioActual = $this->usuariosModel->find($usuarioId);
        $rolActual = $this->rolesModel->obtenerRol($rolId);
        
        if (!$usuarioActual || !$rolActual) {
            return [];
        }
        
        $nombreRol = strtoupper($rolActual['nombre'] ?? '');
        
        // Si es MASTER (rol_id = 1) puede ver todos los roles
        if ($rolId == 1 || in_array($nombreRol, ['MASTER', 'MEGA_ADMIN'])) {
            return $this->obtenerRolesFormateados();
        }
        
        // Si es ADMINISTRADOR (rol_id = 2) solo puede ver roles de su cuenta
        if ($rolId == 2 || in_array($nombreRol, ['ADMIN', 'ADMINISTRADOR'])) {
            return $this->obtenerRolesPorCuenta($cuentaId);
        }
        
        // Otros roles no pueden gestionar permisos
        return [];
    }
    
    /**
     * Obtener roles de usuarios de una cuenta específica
     */
    private function obtenerRolesPorCuenta($cuentaId)
    {
        try {
            $db = \Config\Database::connect();
            
            // Obtener roles de usuarios de la cuenta específica (excluyendo Master)
            $query = $db->query("
                SELECT DISTINCT r.id, r.nombre, r.nivel
                FROM tbl_roles r
                INNER JOIN tbl_usuarios u ON r.id = u.rol_id
                WHERE u.cuenta_id = ? AND u.activo = 1 AND r.id != 1
                ORDER BY r.nivel ASC
            ", [$cuentaId]);
            
            $roles = $query->getResultArray();
            
            // Formatear roles
            $rolesFormateados = [];
            foreach ($roles as $rol) {
                $rolesFormateados[] = [
                    'id' => $rol['id'],
                    'nombre' => $rol['nombre'],
                    'nivel' => $rol['nivel']
                ];
            }
            
            return $rolesFormateados;
            
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo roles por cuenta: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener roles formateados para la vista
     */
    private function obtenerRolesFormateados()
    {
        $roles = $this->permisosModel->obtenerRoles();
        $rolesFormateados = [];
        
        foreach ($roles as $rol) {
            $rolesFormateados[] = [
                'id' => $rol['id'],
                'nombre' => $rol['nombre'],
                'nivel' => $rol['nivel'] ?? 0
            ];
        }
        
        return $rolesFormateados;
    }

    /**
     * Registrar acción en auditoría
     */
    private function registrarAuditoria($accion, $rolId, $modulo, $valor)
    {
        try {
            $db = \Config\Database::connect();
            
            $data = [
                'tabla_afectada' => 'tbl_sidebar_permisos',
                'accion' => $accion,
                'rol_afectado_id' => $rolId,
                'modulo_nombre' => $modulo,
                'valor_nuevo' => $valor,
                'usuario_responsable_id' => session('session_data.usuario_id'),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'fecha_cambio' => date('Y-m-d H:i:s')
            ];
            
            $db->table('tbl_auditoria_sidebar_permisos')->insert($data);
            
            // Registrar también en bitácora general
            helper('bitacora');
            $usuario_id = session('session_data.usuario_id') ?? 999;
            $rolNombre = $this->rolesModel->obtenerRol($rolId)['nombre'] ?? 'ID: ' . $rolId;
            $detalles = [
                'descripcion' => "Permiso {$accion} para rol {$rolNombre}",
                'rol_id' => $rolId,
                'rol_nombre' => $rolNombre,
                'modulo' => $modulo,
                'valor_anterior' => $valorAnterior ?? null,
                'valor_nuevo' => $valor,
                'accion_realizada' => $accion
            ];
            log_activity($usuario_id, 'Sidebar Permisos', $accion, $detalles, 'info');
            
        } catch (\Exception $e) {
            log_message('error', 'Error registrando auditoría de permisos: ' . $e->getMessage());
        }
    }

    /**
     * Exportar configuración de permisos
     */
    public function exportar()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return redirect()->to('/unauthorized');
        }

        try {
            $roles = $this->obtenerRolesFormateados();
            $modulos = $this->permisosModel->obtenerModulosSidebar();
            $permisos = $this->permisosModel->obtenerPermisosPorRol();
            
            $configuracion = [
                'fecha_exportacion' => date('Y-m-d H:i:s'),
                'usuario_exportacion' => session('session_data.nombre'),
                'roles' => $roles,
                'modulos' => $modulos,
                'permisos' => $permisos
            ];
            
            $nombreArchivo = 'permisos_sidebar_' . date('Y-m-d_H-i-s') . '.json';
            
            return $this->response
                        ->setHeader('Content-Type', 'application/json')
                        ->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"')
                        ->setBody(json_encode($configuracion, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al exportar configuración: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar historial de cambios
     */
    public function historial()
    {
        // Verificar permisos de acceso
        if (!$this->verificarAcceso()) {
            return redirect()->to('/unauthorized');
        }

        try {
            $db = \Config\Database::connect();
            
            $historial = $db->table('tbl_auditoria_sidebar_permisos asp')
                           ->select('asp.*, u.nombre as usuario_nombre, r.nombre as rol_nombre')
                           ->join('tbl_usuarios u', 'asp.usuario_responsable_id = u.id', 'left')
                           ->join('tbl_roles r', 'asp.rol_afectado_id = r.id', 'left')
                           ->orderBy('asp.fecha_cambio', 'DESC')
                           ->limit(100)
                           ->get()
                           ->getResultArray();
            
            $data = [
                'titulo' => 'Historial de Cambios - Permisos del Sidebar',
                'historial' => $historial
            ];
            
            return view('sidebar_permisos_historial', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar historial: ' . $e->getMessage());
        }
    }
}