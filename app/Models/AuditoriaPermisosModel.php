<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para auditoría de cambios de permisos
 * Registra todos los cambios realizados en el sistema de permisos
 */
class AuditoriaPermisosModel extends Model
{
    protected $table = 'tbl_auditoria_permisos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'tabla_afectada', 'registro_id', 'accion', 'usuario_afectado_id',
        'modulo_id', 'tipo_usuario_id', 'valores_anteriores', 'valores_nuevos',
        'usuario_responsable_id', 'ip_address', 'user_agent', 'observaciones'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_cambio';
    protected $updatedField = null;

    protected $validationRules = [
        'tabla_afectada' => 'required|max_length[100]',
        'accion' => 'required|in_list[INSERT,UPDATE,DELETE]',
        'valores_anteriores' => 'permit_empty|valid_json',
        'valores_nuevos' => 'permit_empty|valid_json',
        'ip_address' => 'permit_empty|valid_ip',
        'observaciones' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'tabla_afectada' => [
            'required' => 'La tabla afectada es obligatoria',
            'max_length' => 'El nombre de la tabla no puede exceder 100 caracteres'
        ],
        'accion' => [
            'required' => 'La acción es obligatoria',
            'in_list' => 'La acción debe ser INSERT, UPDATE o DELETE'
        ],
        'valores_anteriores' => [
            'valid_json' => 'Los valores anteriores deben ser JSON válido'
        ],
        'valores_nuevos' => [
            'valid_json' => 'Los valores nuevos deben ser JSON válido'
        ],
        'ip_address' => [
            'valid_ip' => 'La dirección IP no es válida'
        ],
        'observaciones' => [
            'max_length' => 'Las observaciones no pueden exceder 500 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['setAuditInfo'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Establecer información de auditoría antes de insertar
     */
    protected function setAuditInfo(array $data)
    {
        $request = \Config\Services::request();
        
        if (!isset($data['data']['usuario_responsable_id']) && session()->has('usuario_id')) {
            $data['data']['usuario_responsable_id'] = session('usuario_id');
        }
        
        if (!isset($data['data']['ip_address'])) {
            $data['data']['ip_address'] = $request->getIPAddress();
        }
        
        if (!isset($data['data']['user_agent'])) {
            $data['data']['user_agent'] = $request->getUserAgent()->getAgentString();
        }
        
        return $data;
    }

    /**
     * Registrar cambio en permisos de tipo de usuario
     */
    public function registrarCambioTipoUsuario($accion, $tipoUsuarioId, $moduloId, $valoresAnteriores = null, $valoresNuevos = null, $observaciones = null)
    {
        $data = [
            'tabla_afectada' => 'tbl_tipo_usuario_permisos',
            'accion' => strtoupper($accion),
            'tipo_usuario_id' => $tipoUsuarioId,
            'modulo_id' => $moduloId,
            'valores_anteriores' => $valoresAnteriores ? json_encode($valoresAnteriores) : null,
            'valores_nuevos' => $valoresNuevos ? json_encode($valoresNuevos) : null,
            'observaciones' => $observaciones
        ];

        return $this->insert($data);
    }

    /**
     * Registrar cambio en excepciones de usuario
     */
    public function registrarCambioExcepcionUsuario($accion, $usuarioId, $moduloId, $valoresAnteriores = null, $valoresNuevos = null, $observaciones = null)
    {
        $data = [
            'tabla_afectada' => 'tbl_usuario_permisos_excepciones',
            'accion' => strtoupper($accion),
            'usuario_afectado_id' => $usuarioId,
            'modulo_id' => $moduloId,
            'valores_anteriores' => $valoresAnteriores ? json_encode($valoresAnteriores) : null,
            'valores_nuevos' => $valoresNuevos ? json_encode($valoresNuevos) : null,
            'observaciones' => $observaciones
        ];

        return $this->insert($data);
    }

    /**
     * Registrar cambio en usuario (cambio de tipo)
     */
    public function registrarCambioUsuario($accion, $usuarioId, $valoresAnteriores = null, $valoresNuevos = null, $observaciones = null)
    {
        $data = [
            'tabla_afectada' => 'tbl_usuarios',
            'accion' => strtoupper($accion),
            'usuario_afectado_id' => $usuarioId,
            'valores_anteriores' => $valoresAnteriores ? json_encode($valoresAnteriores) : null,
            'valores_nuevos' => $valoresNuevos ? json_encode($valoresNuevos) : null,
            'observaciones' => $observaciones
        ];

        return $this->insert($data);
    }

    /**
     * Obtener historial de cambios de un usuario específico
     */
    public function obtenerHistorialUsuario($usuarioId, $limite = 50)
    {
        return $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                    ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                    ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                    ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                    ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left')
                    ->where('tbl_auditoria_permisos.usuario_afectado_id', $usuarioId)
                    ->orderBy('tbl_auditoria_permisos.fecha_cambio', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtener historial de cambios de un módulo específico
     */
    public function obtenerHistorialModulo($moduloId, $limite = 50)
    {
        return $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                    ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                    ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                    ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                    ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left')
                    ->where('tbl_auditoria_permisos.modulo_id', $moduloId)
                    ->orderBy('tbl_auditoria_permisos.fecha_cambio', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtener historial de cambios de un tipo de usuario
     */
    public function obtenerHistorialTipoUsuario($tipoUsuarioId, $limite = 50)
    {
        return $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                    ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                    ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                    ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                    ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left')
                    ->where('tbl_auditoria_permisos.tipo_usuario_id', $tipoUsuarioId)
                    ->orderBy('tbl_auditoria_permisos.fecha_cambio', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtener cambios recientes en el sistema
     */
    public function obtenerCambiosRecientes($limite = 100, $fechaDesde = null)
    {
        $builder = $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                        ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                        ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                        ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                        ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left');

        if ($fechaDesde) {
            $builder->where('tbl_auditoria_permisos.fecha_cambio >=', $fechaDesde);
        }

        return $builder->orderBy('tbl_auditoria_permisos.fecha_cambio', 'DESC')
                       ->limit($limite)
                       ->findAll();
    }

    /**
     * Obtener estadísticas de cambios por período
     */
    public function obtenerEstadisticasPorPeriodo($fechaInicio, $fechaFin)
    {
        $stats = [];

        // Total de cambios por acción
        $stats['cambios_por_accion'] = $this->db->query("
            SELECT 
                accion,
                COUNT(*) as total
            FROM tbl_auditoria_permisos
            WHERE fecha_cambio BETWEEN ? AND ?
            GROUP BY accion
            ORDER BY total DESC
        ", [$fechaInicio, $fechaFin])->getResultArray();

        // Cambios por tabla
        $stats['cambios_por_tabla'] = $this->db->query("
            SELECT 
                tabla_afectada,
                COUNT(*) as total
            FROM tbl_auditoria_permisos
            WHERE fecha_cambio BETWEEN ? AND ?
            GROUP BY tabla_afectada
            ORDER BY total DESC
        ", [$fechaInicio, $fechaFin])->getResultArray();

        // Usuarios más activos
        $stats['usuarios_mas_activos'] = $this->db->query("
            SELECT 
                u.nombre as usuario,
                COUNT(ap.id) as total_cambios
            FROM tbl_auditoria_permisos ap
            LEFT JOIN tbl_usuarios u ON ap.usuario_responsable_id = u.id
            WHERE ap.fecha_cambio BETWEEN ? AND ?
            GROUP BY ap.usuario_responsable_id, u.nombre
            ORDER BY total_cambios DESC
            LIMIT 10
        ", [$fechaInicio, $fechaFin])->getResultArray();

        // Módulos más modificados
        $stats['modulos_mas_modificados'] = $this->db->query("
            SELECT 
                m.nombre as modulo,
                COUNT(ap.id) as total_cambios
            FROM tbl_auditoria_permisos ap
            LEFT JOIN tbl_modulos m ON ap.modulo_id = m.id
            WHERE ap.fecha_cambio BETWEEN ? AND ? AND ap.modulo_id IS NOT NULL
            GROUP BY ap.modulo_id, m.nombre
            ORDER BY total_cambios DESC
            LIMIT 10
        ", [$fechaInicio, $fechaFin])->getResultArray();

        // Cambios por día
        $stats['cambios_por_dia'] = $this->db->query("
            SELECT 
                DATE(fecha_cambio) as fecha,
                COUNT(*) as total_cambios
            FROM tbl_auditoria_permisos
            WHERE fecha_cambio BETWEEN ? AND ?
            GROUP BY DATE(fecha_cambio)
            ORDER BY fecha ASC
        ", [$fechaInicio, $fechaFin])->getResultArray();

        return $stats;
    }

    /**
     * Buscar cambios por criterios específicos
     */
    public function buscarCambios($criterios = [])
    {
        $builder = $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                        ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                        ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                        ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                        ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left');

        if (isset($criterios['tabla_afectada'])) {
            $builder->where('tbl_auditoria_permisos.tabla_afectada', $criterios['tabla_afectada']);
        }

        if (isset($criterios['accion'])) {
            $builder->where('tbl_auditoria_permisos.accion', $criterios['accion']);
        }

        if (isset($criterios['usuario_responsable_id'])) {
            $builder->where('tbl_auditoria_permisos.usuario_responsable_id', $criterios['usuario_responsable_id']);
        }

        if (isset($criterios['usuario_afectado_id'])) {
            $builder->where('tbl_auditoria_permisos.usuario_afectado_id', $criterios['usuario_afectado_id']);
        }

        if (isset($criterios['modulo_id'])) {
            $builder->where('tbl_auditoria_permisos.modulo_id', $criterios['modulo_id']);
        }

        if (isset($criterios['tipo_usuario_id'])) {
            $builder->where('tbl_auditoria_permisos.tipo_usuario_id', $criterios['tipo_usuario_id']);
        }

        if (isset($criterios['fecha_desde'])) {
            $builder->where('tbl_auditoria_permisos.fecha_cambio >=', $criterios['fecha_desde']);
        }

        if (isset($criterios['fecha_hasta'])) {
            $builder->where('tbl_auditoria_permisos.fecha_cambio <=', $criterios['fecha_hasta']);
        }

        if (isset($criterios['ip_address'])) {
            $builder->where('tbl_auditoria_permisos.ip_address', $criterios['ip_address']);
        }

        if (isset($criterios['observaciones'])) {
            $builder->like('tbl_auditoria_permisos.observaciones', $criterios['observaciones']);
        }

        $limite = $criterios['limite'] ?? 100;
        $orden = $criterios['orden'] ?? 'DESC';

        return $builder->orderBy('tbl_auditoria_permisos.fecha_cambio', $orden)
                       ->limit($limite)
                       ->findAll();
    }

    /**
     * Obtener resumen de actividad de un usuario
     */
    public function obtenerResumenActividadUsuario($usuarioId, $dias = 30)
    {
        $fechaInicio = date('Y-m-d', strtotime("-{$dias} days"));
        $fechaFin = date('Y-m-d H:i:s');

        return $this->db->query("
            SELECT 
                COUNT(*) as total_cambios,
                COUNT(DISTINCT DATE(fecha_cambio)) as dias_activos,
                COUNT(CASE WHEN accion = 'INSERT' THEN 1 END) as inserciones,
                COUNT(CASE WHEN accion = 'UPDATE' THEN 1 END) as actualizaciones,
                COUNT(CASE WHEN accion = 'DELETE' THEN 1 END) as eliminaciones,
                COUNT(DISTINCT modulo_id) as modulos_afectados,
                COUNT(DISTINCT usuario_afectado_id) as usuarios_afectados,
                MIN(fecha_cambio) as primer_cambio,
                MAX(fecha_cambio) as ultimo_cambio
            FROM tbl_auditoria_permisos
            WHERE usuario_responsable_id = ? 
            AND fecha_cambio BETWEEN ? AND ?
        ", [$usuarioId, $fechaInicio, $fechaFin])->getRow();
    }

    /**
     * Limpiar registros antiguos de auditoría
     */
    public function limpiarRegistrosAntiguos($diasAntiguedad = 365)
    {
        $fechaLimite = date('Y-m-d', strtotime("-{$diasAntiguedad} days"));
        
        return $this->where('fecha_cambio <', $fechaLimite)->delete();
    }

    /**
     * Exportar auditoría para respaldo
     */
    public function exportarAuditoria($fechaInicio = null, $fechaFin = null, $formato = 'array')
    {
        $builder = $this->select('tbl_auditoria_permisos.*, ur.nombre as responsable_nombre, ua.nombre as afectado_nombre, m.nombre as modulo_nombre, tu.nombre as tipo_usuario_nombre')
                        ->join('tbl_usuarios ur', 'tbl_auditoria_permisos.usuario_responsable_id = ur.id', 'left')
                        ->join('tbl_usuarios ua', 'tbl_auditoria_permisos.usuario_afectado_id = ua.id', 'left')
                        ->join('tbl_modulos m', 'tbl_auditoria_permisos.modulo_id = m.id', 'left')
                        ->join('tbl_tipos_usuario tu', 'tbl_auditoria_permisos.tipo_usuario_id = tu.id', 'left');

        if ($fechaInicio) {
            $builder->where('tbl_auditoria_permisos.fecha_cambio >=', $fechaInicio);
        }

        if ($fechaFin) {
            $builder->where('tbl_auditoria_permisos.fecha_cambio <=', $fechaFin);
        }

        $registros = $builder->orderBy('tbl_auditoria_permisos.fecha_cambio', 'ASC')->findAll();

        if ($formato === 'json') {
            return json_encode($registros, JSON_PRETTY_PRINT);
        }

        return $registros;
    }

    /**
     * Verificar integridad de la auditoría
     */
    public function verificarIntegridad()
    {
        $problemas = [];

        // Verificar registros huérfanos
        $huerfanos = $this->db->query("
            SELECT COUNT(*) as total
            FROM tbl_auditoria_permisos ap
            LEFT JOIN tbl_usuarios ur ON ap.usuario_responsable_id = ur.id
            WHERE ap.usuario_responsable_id IS NOT NULL AND ur.id IS NULL
        ")->getRow();

        if ($huerfanos->total > 0) {
            $problemas[] = "Existen {$huerfanos->total} registros con usuario responsable inexistente";
        }

        // Verificar JSON válido
        $jsonInvalido = $this->db->query("
            SELECT COUNT(*) as total
            FROM tbl_auditoria_permisos
            WHERE (valores_anteriores IS NOT NULL AND JSON_VALID(valores_anteriores) = 0)
            OR (valores_nuevos IS NOT NULL AND JSON_VALID(valores_nuevos) = 0)
        ")->getRow();

        if ($jsonInvalido->total > 0) {
            $problemas[] = "Existen {$jsonInvalido->total} registros con JSON inválido";
        }

        return $problemas;
    }
}