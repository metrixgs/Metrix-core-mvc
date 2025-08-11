<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para gestión de permisos del sidebar
 * Maneja los permisos de acceso a módulos por rol de usuario
 */
class PermisosModel extends Model
{
    protected $table = 'tbl_rol_permisos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'rol_id', 'modulo_id', 'lectura', 'escritura', 
        'actualizacion', 'eliminacion', 'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Obtener permisos de usuario para sidebar
     */
    public function obtenerPermisosUsuario($usuario_id)
    {
        return $this->db->table('vista_permisos_usuario')
                       ->where('usuario_id', $usuario_id)
                       ->where('lectura', 1) // Solo módulos con permiso de lectura
                       ->orderBy('modulo_nombre')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Verificar permiso específico
     */
    public function verificarPermiso($usuario_id, $modulo, $accion)
    {
        $query = $this->db->query(
            "SELECT fn_verificar_permiso_usuario(?, ?, ?) as tiene_permiso",
            [$usuario_id, $modulo, $accion]
        );
        
        $resultado = $query->getRow();
        return $resultado ? (bool)$resultado->tiene_permiso : false;
    }

    /**
     * Obtener todos los roles del sistema
     */
    public function obtenerRoles()
    {
        return $this->db->table('tbl_roles')
                       ->orderBy('nivel')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Obtener todos los módulos del sidebar
     */
    public function obtenerModulosSidebar()
    {
        return [
            'dashboard' => 'Dashboard',
            'operacion' => 'Operación',
            'dependencias' => 'Dependencias',
            'clientes' => 'Clientes',
            'reportes' => 'Reportes / Incidencias',
            'tareas' => 'Reporte / Tareas',
            'notificaciones_menu' => 'Notificaciones',
            'modulos' => 'Módulos',
            'mapa' => 'Mapa',
            'encuestas' => 'Encuestas',
            'usuarios' => 'Usuarios',
            'cuentas' => 'Cuentas',
            'directorio' => 'Directorio',
            'campanas' => 'Campañas',
            'incidencias' => 'Incidencias',
            'campanas_rondas' => 'Campañas y Rondas',
            'ajustes_soporte' => 'Ajustes y Soporte',
            'configuracion' => 'Configuración',
            'perfil' => 'Perfil',
            'smtp' => 'SMTP',
            'tdr' => 'TDR',
            'soporte' => 'Soporte',
            'soporte_nuevo' => 'Nuevo Ticket',
            'soporte_conversaciones' => 'Conversaciones',
            'soporte_menu' => 'Menú Soporte',
            'gestion_sidebar' => 'Gestión de Sidebar',
            'panel_permisos' => 'Permisos del Sidebar',
            'configuracion_sidebar' => 'Configuración Sidebar',
            'auditoria_sidebar' => 'Auditoría Sidebar',
            'roles_sidebar' => 'Gestión de Roles Sidebar'
        ];
    }

    /**
     * Obtener permisos por rol y módulo
     */
    public function obtenerPermisosPorRol($rolesPermitidos = null)
    {
        // Si no se especifican roles permitidos, obtener todos
        $roles = $rolesPermitidos ?? $this->obtenerRoles();
        $modulos = $this->obtenerModulosSidebar();
        $permisos = [];

        foreach ($roles as $rol) {
            $permisos[$rol['id']] = [];
            foreach ($modulos as $moduloNombre => $moduloTitulo) {
                // Verificar si existe permiso en la base de datos
                $permiso = $this->db->table('tbl_sidebar_permisos')
                                   ->where('rol_id', $rol['id'])
                                   ->where('modulo_nombre', $moduloNombre)
                                   ->get()
                                   ->getRowArray();
                
                $permisos[$rol['id']][$moduloNombre] = $permiso ? (bool)$permiso['activo'] : false;
            }
        }

        return $permisos;
    }

    /**
     * Actualizar permiso específico
     */
    public function actualizarPermiso($rolId, $moduloNombre, $valor)
    {
        try {
            // Verificar si ya existe el registro
            $existente = $this->db->table('tbl_sidebar_permisos')
                                 ->where('rol_id', $rolId)
                                 ->where('modulo_nombre', $moduloNombre)
                                 ->get()
                                 ->getRowArray();

            if ($existente) {
                // Actualizar registro existente
                $this->db->table('tbl_sidebar_permisos')
                        ->where('rol_id', $rolId)
                        ->where('modulo_nombre', $moduloNombre)
                        ->update([
                            'activo' => $valor,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
            } else {
                // Crear nuevo registro
                $this->db->table('tbl_sidebar_permisos')
                        ->insert([
                            'rol_id' => $rolId,
                            'modulo_nombre' => $moduloNombre,
                            'activo' => $valor,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error actualizando permiso: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Configurar permisos por defecto según tipo de usuario
     */
    public function configurarPermisosPorDefecto()
    {
        $permisosEspecificos = $this->obtenerPermisosEspecificosPorTipo();
        $roles = $this->obtenerRoles();
        
        $this->db->transStart();
        
        try {
            foreach ($roles as $rol) {
                $tipoUsuario = $rol['nombre'] ?? 'DEFAULT';
                $permisos = $permisosEspecificos[$tipoUsuario] ?? $permisosEspecificos['DEFAULT'];
                
                foreach ($permisos as $modulo => $activo) {
                    $this->actualizarPermiso($rol['id'], $modulo, $activo ? 1 : 0);
                }
            }
            
            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error configurando permisos por defecto: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener permisos específicos por tipo de usuario
     */
    private function obtenerPermisosEspecificosPorTipo()
    {
        return [
            'MASTER' => [
                'dashboard' => true,
                'operacion' => true,
                'dependencias' => true,
                'clientes' => true,
                'reportes' => true,
                'tareas' => true,
                'notificaciones_menu' => true,
                'modulos' => true,
                'mapa' => true,
                'encuestas' => true,
                'usuarios' => true,
                'cuentas' => true,
                'directorio' => true,
                'campanas' => true,
                'incidencias' => true,
                'campanas_rondas' => true,
                'ajustes_soporte' => true,
                'configuracion' => true,
                'perfil' => true,
                'smtp' => true,
                'tdr' => true,
                'soporte' => true,
                'soporte_nuevo' => true,
                'soporte_conversaciones' => true,
                'soporte_menu' => true,
                'gestion_sidebar' => true,
                'panel_permisos' => true,
                'configuracion_sidebar' => true,
                'auditoria_sidebar' => true,
                'roles_sidebar' => true
            ],
            'ADMIN' => [
                'dashboard' => true,
                'operacion' => true,
                'dependencias' => true,
                'clientes' => true,
                'reportes' => true,
                'tareas' => true,
                'notificaciones_menu' => true,
                'modulos' => true,
                'mapa' => true,
                'encuestas' => true,
                'usuarios' => true,
                'directorio' => true,
                'campanas' => true,
                'incidencias' => true,
                'campanas_rondas' => true,
                'ajustes_soporte' => true,
                'configuracion' => true,
                'perfil' => true,
                'soporte' => true,
                'soporte_nuevo' => true,
                'soporte_conversaciones' => true,
                'soporte_menu' => true,
                'gestion_sidebar' => true,
                'panel_permisos' => true,
                'configuracion_sidebar' => true,
                'auditoria_sidebar' => true
            ],
            'OPERADOR' => [
                'dashboard' => true,
                'operacion' => true,
                'dependencias' => true,
                'clientes' => true,
                'reportes' => true,
                'tareas' => true,
                'notificaciones_menu' => true,
                'mapa' => true,
                'directorio' => true,
                'incidencias' => true,
                'perfil' => true,
                'soporte' => true,
                'soporte_nuevo' => true,
                'soporte_menu' => true
            ],
            'CLIENTE' => [
                'dashboard' => true,
                'reportes' => true,
                'notificaciones_menu' => true,
                'perfil' => true,
                'soporte' => true,
                'soporte_nuevo' => true,
                'soporte_menu' => true
            ],
            'DEFAULT' => [
                'dashboard' => true,
                'perfil' => true,
                'notificaciones_menu' => true,
                'soporte' => true,
                'soporte_nuevo' => true,
                'soporte_conversaciones' => true,
                'soporte_menu' => true
            ]
        ];
    }
}