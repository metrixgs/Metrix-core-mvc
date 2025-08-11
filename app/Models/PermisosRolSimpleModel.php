<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisosRolSimpleModel extends Model
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

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'rol_id' => 'required|integer',
        'modulo_id' => 'required|integer',
        'lectura' => 'in_list[0,1]',
        'escritura' => 'in_list[0,1]',
        'actualizacion' => 'in_list[0,1]',
        'eliminacion' => 'in_list[0,1]',
        'activo' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'rol_id' => [
            'required' => 'El rol es obligatorio',
            'integer' => 'El rol debe ser un número válido'
        ],
        'modulo_id' => [
            'required' => 'El módulo es obligatorio',
            'integer' => 'El módulo debe ser un número válido'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtener permisos efectivos de un usuario basado en su rol
     */
    public function obtenerPermisosEfectivosUsuario($usuarioId)
    {
        return $this->db->query("
            SELECT 
                u.id as usuario_id,
                u.nombre as usuario_nombre,
                r.id as rol_id,
                r.nombre as rol_nombre,
                r.id as rol_codigo,
                m.id as modulo_id,
                m.nombre as modulo_nombre,
                m.ruta as modulo_ruta,
                m.icono as modulo_icono,
                COALESCE(rp.lectura, 0) as lectura,
                COALESCE(rp.escritura, 0) as escritura,
                COALESCE(rp.actualizacion, 0) as actualizacion,
                COALESCE(rp.eliminacion, 0) as eliminacion
            FROM tbl_usuarios u
            INNER JOIN tbl_roles r ON u.rol_id = r.id
            CROSS JOIN tbl_modulos m
            LEFT JOIN tbl_rol_permisos rp ON r.id = rp.rol_id AND m.id = rp.modulo_id AND rp.activo = 1
            WHERE u.id = ? AND u.activo = 1 AND r.activo = 1 AND m.activo = 1
            ORDER BY m.orden, m.nombre
        ", [$usuarioId])->getResultArray();
    }

    /**
     * Verificar si un usuario tiene un permiso específico
     */
    public function verificarPermisoUsuario($usuarioId, $moduloNombre, $tipoPermiso)
    {
        $resultado = $this->db->query("
            SELECT fn_verificar_permiso_usuario_rol(?, ?, ?) as tiene_permiso
        ", [$usuarioId, $moduloNombre, $tipoPermiso])->getRow();

        return $resultado ? (bool)$resultado->tiene_permiso : false;
    }

    /**
     * Obtener permisos de un rol específico
     */
    public function obtenerPermisosRol($rolId)
    {
        return $this->db->query("
            SELECT 
                rp.*,
                m.nombre as modulo_nombre,
                m.ruta as modulo_ruta,
                m.icono as modulo_icono,
                m.orden as modulo_orden
            FROM tbl_rol_permisos rp
            INNER JOIN tbl_modulos m ON rp.modulo_id = m.id
            WHERE rp.rol_id = ? AND rp.activo = 1 AND m.activo = 1
            ORDER BY m.orden, m.nombre
        ", [$rolId])->getResultArray();
    }

    /**
     * Asignar permisos a un rol
     */
    public function asignarPermisosRol($rolId, $permisos)
    {
        $this->db->transStart();

        try {
            foreach ($permisos as $permiso) {
                $this->db->query("
                    CALL sp_asignar_permisos_rol(?, ?, ?, ?, ?, ?)
                ", [
                    $rolId,
                    $permiso['modulo_id'],
                    $permiso['lectura'] ?? 0,
                    $permiso['escritura'] ?? 0,
                    $permiso['actualizacion'] ?? 0,
                    $permiso['eliminacion'] ?? 0
                ]);
            }

            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error asignando permisos a rol: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Copiar permisos entre roles
     */
    public function copiarPermisosRol($rolOrigenId, $rolDestinoId)
    {
        try {
            $this->db->query("CALL sp_copiar_permisos_rol(?, ?)", [$rolOrigenId, $rolDestinoId]);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error copiando permisos entre roles: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los roles disponibles
     */
    public function obtenerRoles()
    {
        return $this->db->query("
            SELECT * FROM tbl_roles 
            WHERE activo = 1 
            ORDER BY nivel_acceso DESC, nombre
        ")->getResultArray();
    }

    /**
     * Obtener todos los módulos disponibles
     */
    public function obtenerModulos()
    {
        return $this->db->query("
            SELECT * FROM tbl_modulos 
            WHERE activo = 1 
            ORDER BY orden, nombre
        ")->getResultArray();
    }

    /**
     * Asignar rol a usuario
     */
    public function asignarRolUsuario($usuarioId, $rolId)
    {
        try {
            $this->db->query("CALL sp_asignar_rol_usuario(?, ?)", [$usuarioId, $rolId]);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error asignando rol a usuario: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener resumen de permisos por rol
     */
    public function obtenerResumenPermisosRol()
    {
        return $this->db->query("
            SELECT * FROM vista_resumen_permisos_rol
            ORDER BY rol_nivel DESC, rol_nombre
        ")->getResultArray();
    }

    /**
     * Obtener un rol por su ID
     */
    public function obtenerRolPorId($rolId)
    {
        return $this->db->table('tbl_roles')
            ->where('id', $rolId)
            ->get()
            ->getRowArray();
    }

    /**
     * Contar usuarios por rol
     */
    public function contarUsuariosPorRol($rolId)
    {
        return $this->db->table('tbl_usuarios')
            ->where('rol_id', $rolId)
            ->where('activo', 1)
            ->countAllResults();
    }

    /**
     * Obtener usuarios con sus roles asignados
     */
    public function obtenerUsuariosConRoles()
    {
        return $this->db->table('tbl_usuarios u')
            ->select('u.id, u.nombre, u.correo, u.activo, u.rol_id, r.nombre as rol_nombre, r.descripcion as rol_descripcion')
            ->join('tbl_roles r', 'u.rol_id = r.id', 'left')
            ->orderBy('u.nombre')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener usuarios por rol
     */
    public function obtenerUsuariosPorRol($rolId = null)
    {
        // Verificar si las tablas existen
        $rolesTableExists = $this->db->query("
            SELECT COUNT(*) as count 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'tbl_roles'
        ")->getRow()->count;

        if ($rolesTableExists == 0) {
            return [];
        }

        // Verificar si la columna nivel_acceso existe
        $columnExists = $this->db->query("
            SELECT COUNT(*) as count 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'tbl_roles' 
            AND COLUMN_NAME = 'nivel_acceso'
        ")->getRow()->count;

        if ($columnExists > 0) {
            $sql = "
                SELECT 
                    u.id as usuario_id,
                    u.nombre as usuario_nombre,
                    u.correo as usuario_correo,
                    r.id as rol_id,
                    r.nombre as rol_nombre,
                    r.id as rol_codigo,
                    r.nivel_acceso,
                    u.created_at as fecha_creacion
                FROM tbl_usuarios u
                INNER JOIN tbl_roles r ON u.rol_id = r.id
                WHERE u.activo = 1 AND r.activo = 1
            ";

            $params = [];
            if ($rolId) {
                $sql .= " AND r.id = ?";
                $params[] = $rolId;
            }

            $sql .= " ORDER BY r.nivel_acceso DESC, u.nombre";
        } else {
            $sql = "
                SELECT 
                    u.id as usuario_id,
                    u.nombre as usuario_nombre,
                    u.correo as usuario_correo,
                    r.id as rol_id,
                    r.nombre as rol_nombre,
                    r.id as rol_codigo,
                    u.created_at as fecha_creacion
                FROM tbl_usuarios u
                INNER JOIN tbl_roles r ON u.rol_id = r.id
                WHERE u.activo = 1 AND r.activo = 1
            ";

            $params = [];
            if ($rolId) {
                $sql .= " AND r.id = ?";
                $params[] = $rolId;
            }

            $sql .= " ORDER BY r.nombre, u.nombre";
        }

        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Validar integridad del sistema de permisos
     */
    public function validarIntegridad()
    {
        $errores = [];

        // Verificar usuarios sin rol
        $usuariosSinRol = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_usuarios 
            WHERE rol_id IS NULL AND activo = 1
        ")->getRow()->total;

        if ($usuariosSinRol > 0) {
            $errores[] = "Hay {$usuariosSinRol} usuarios activos sin rol asignado";
        }

        // Verificar roles sin permisos
        $rolesSinPermisos = $this->db->query("
            SELECT r.nombre 
            FROM tbl_roles r
            LEFT JOIN tbl_rol_permisos rp ON r.id = rp.rol_id AND rp.activo = 1
            WHERE r.activo = 1 AND rp.id IS NULL
        ")->getResultArray();

        if (!empty($rolesSinPermisos)) {
            $roles = array_column($rolesSinPermisos, 'nombre');
            $errores[] = "Los siguientes roles no tienen permisos asignados: " . implode(', ', $roles);
        }

        return $errores;
    }

    /**
     * Obtener estadísticas del sistema
     */
    public function obtenerEstadisticas()
    {
        return [
            'roles_activos' => $this->db->query("SELECT COUNT(*) as total FROM tbl_roles")->getRow()->total,
            'usuarios_con_rol' => $this->db->query("SELECT COUNT(*) as total FROM tbl_usuarios WHERE rol_id IS NOT NULL AND activo = 1")->getRow()->total,
            'usuarios_sin_rol' => $this->db->query("SELECT COUNT(*) as total FROM tbl_usuarios WHERE rol_id IS NULL AND activo = 1")->getRow()->total,
            'permisos_asignados' => $this->db->query("SELECT COUNT(*) as total FROM tbl_rol_permisos WHERE activo = 1")->getRow()->total,
            'modulos_activos' => $this->db->query("SELECT COUNT(*) as total FROM tbl_modulos WHERE activo = 1")->getRow()->total
        ];
    }

    /**
     * Generar reporte de auditoría
     */
    public function generarReporteAuditoria($fechaInicio = null, $fechaFin = null, $limite = 100)
    {
        $sql = "
            SELECT 
                arp.*,
                u.nombre as usuario_nombre,
                CASE 
                    WHEN arp.tabla_afectada = 'roles' THEN 'Roles'
                    WHEN arp.tabla_afectada = 'rol_permisos' THEN 'Permisos de Rol'
                    WHEN arp.tabla_afectada = 'usuarios' THEN 'Usuarios'
                END as tabla_nombre
            FROM tbl_auditoria_rol_permisos arp
            LEFT JOIN tbl_usuarios u ON arp.usuario_id = u.id
            WHERE 1=1
        ";

        $params = [];

        if ($fechaInicio) {
            $sql .= " AND arp.created_at >= ?";
            $params[] = $fechaInicio;
        }

        if ($fechaFin) {
            $sql .= " AND arp.created_at <= ?";
            $params[] = $fechaFin;
        }

        $sql .= " ORDER BY arp.created_at DESC LIMIT ?";
        $params[] = $limite;

        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Limpiar permisos inválidos
     */
    public function limpiarPermisosInvalidos()
    {
        $this->db->transStart();

        try {
            // Eliminar permisos de roles inactivos
            $this->db->query("
                DELETE rp FROM tbl_rol_permisos rp
                INNER JOIN tbl_roles r ON rp.rol_id = r.id
                WHERE r.activo = 0
            ");

            // Eliminar permisos de módulos inactivos
            $this->db->query("
                DELETE rp FROM tbl_rol_permisos rp
                INNER JOIN tbl_modulos m ON rp.modulo_id = m.id
                WHERE m.activo = 0
            ");

            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error limpiando permisos inválidos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Exportar configuración de permisos de un rol
     */
    public function exportarPermisosRol($rolId)
    {
        $rol = $this->db->query("SELECT * FROM tbl_roles WHERE id = ?", [$rolId])->getRow();
        
        if (!$rol) {
            return null;
        }

        $permisos = $this->obtenerPermisosRol($rolId);

        return [
            'rol' => $rol,
            'permisos' => $permisos,
            'exportado_en' => date('Y-m-d H:i:s'),
            'version' => '1.0'
        ];
    }

    /**
     * Importar configuración de permisos a un rol
     */
    public function importarPermisosRol($rolId, $configuracion)
    {
        if (!isset($configuracion['permisos']) || !is_array($configuracion['permisos'])) {
            return false;
        }

        $this->db->transStart();

        try {
            // Limpiar permisos existentes del rol
            $this->db->query("DELETE FROM tbl_rol_permisos WHERE rol_id = ?", [$rolId]);

            // Insertar nuevos permisos
            foreach ($configuracion['permisos'] as $permiso) {
                $this->insert([
                    'rol_id' => $rolId,
                    'modulo_id' => $permiso['modulo_id'],
                    'lectura' => $permiso['lectura'] ?? 0,
                    'escritura' => $permiso['escritura'] ?? 0,
                    'actualizacion' => $permiso['actualizacion'] ?? 0,
                    'eliminacion' => $permiso['eliminacion'] ?? 0
                ]);
            }

            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error importando permisos a rol: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener permisos de usuario para el menú/sidebar
     */
    public function obtenerPermisosMenuUsuario($usuarioId)
    {
        return $this->db->query("
            SELECT 
                m.id,
                m.nombre,
                m.ruta,
                m.icono,
                m.orden,
                m.padre_id,
                COALESCE(rp.lectura, 0) as lectura
            FROM tbl_usuarios u
            INNER JOIN tbl_roles r ON u.rol_id = r.id
            INNER JOIN tbl_modulos m ON m.activo = 1
            LEFT JOIN tbl_rol_permisos rp ON r.id = rp.rol_id AND m.id = rp.modulo_id AND rp.activo = 1
            WHERE u.id = ? AND u.activo = 1 AND r.activo = 1
            AND COALESCE(rp.lectura, 0) = 1
            ORDER BY m.orden, m.nombre
        ", [$usuarioId])->getResultArray();
    }
}