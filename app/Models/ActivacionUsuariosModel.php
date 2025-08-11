<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivacionUsuariosModel extends Model {

    protected $table = 'tbl_bitacora_activacion_usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'usuario_afectado_id', 'usuario_ejecutor_id', 'accion', 
        'estado_anterior', 'estado_nuevo', 'motivo', 'ip_address'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_hora';
    protected $updatedField = '';

    protected $usuariosModel;
    protected $configuracionModel;

    public function __construct() {
        parent::__construct();
        $this->usuariosModel = new \App\Models\UsuariosModel();
        $this->configuracionModel = new \App\Models\ConfiguracionSistemaModel();
    }

    /**
     * Activar un usuario
     */
    public function activarUsuario($usuarioId, $ejecutorId, $motivo = null, $ipAddress = null) {
        return $this->cambiarEstadoUsuario($usuarioId, $ejecutorId, 'activar', $motivo, $ipAddress);
    }

    /**
     * Desactivar un usuario
     */
    public function desactivarUsuario($usuarioId, $ejecutorId, $motivo = null, $ipAddress = null) {
        return $this->cambiarEstadoUsuario($usuarioId, $ejecutorId, 'desactivar', $motivo, $ipAddress);
    }

    /**
     * Cambiar estado de un usuario (activar/desactivar)
     */
    private function cambiarEstadoUsuario($usuarioId, $ejecutorId, $accion, $motivo = null, $ipAddress = null) {
        // Verificar que el usuario existe
        $usuario = $this->usuariosModel->find($usuarioId);
        if (!$usuario) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        // Verificar que el ejecutor existe
        $ejecutor = $this->usuariosModel->find($ejecutorId);
        if (!$ejecutor) {
            return [
                'success' => false,
                'message' => 'Usuario ejecutor no encontrado'
            ];
        }

        // Verificar permisos según jerarquía
        if (!$this->configuracionModel->puedeActivarRol($ejecutor['rol_id'], $usuario['rol_id'])) {
            return [
                'success' => false,
                'message' => 'No tiene permisos para ejecutar esta acción sobre este usuario'
            ];
        }

        // Obtener estado actual
        $estadoAnterior = isset($usuario['activo']) ? (int) $usuario['activo'] : 1;
        $estadoNuevo = ($accion === 'activar') ? 1 : 0;

        // Verificar si ya está en el estado deseado
        if ($estadoAnterior === $estadoNuevo) {
            return [
                'success' => false,
                'message' => 'El usuario ya está ' . ($estadoNuevo ? 'activo' : 'inactivo')
            ];
        }

        // Iniciar transacción
        $this->db->transStart();

        try {
            // Actualizar estado del usuario
            $updateData = ['fecha_actualizacion' => date('Y-m-d H:i:s')];
            
            // Si la tabla tiene el campo 'activo', lo actualizamos
            if ($this->usuariosModel->db->fieldExists('activo', 'tbl_usuarios')) {
                $updateData['activo'] = $estadoNuevo;
            }
            
            $this->usuariosModel->update($usuarioId, $updateData);

            // Registrar en bitácora
            $bitacoraData = [
                'usuario_afectado_id' => $usuarioId,
                'usuario_ejecutor_id' => $ejecutorId,
                'accion' => $accion,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'motivo' => $motivo,
                'ip_address' => $ipAddress ?: $this->obtenerIpCliente()
            ];

            $this->insert($bitacoraData);

            // Registrar en bitácora general si existe
            if (function_exists('log_activity')) {
                log_activity(
                    $ejecutorId,
                    'usuarios',
                    $accion . '_usuario',
[
    'description' => "Usuario {$usuario['nombre']} {$accion}do por {$ejecutor['nombre']}"
]
                );
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return [
                    'success' => false,
                    'message' => 'Error al actualizar el estado del usuario'
                ];
            }

            return [
                'success' => true,
                'message' => 'Usuario ' . ($estadoNuevo ? 'activado' : 'desactivado') . ' correctamente',
                'data' => [
                    'usuario_id' => $usuarioId,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $estadoNuevo,
                    'ejecutor' => $ejecutor['nombre']
                ]
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verificar si un usuario puede iniciar sesión
     */
    public function puedeIniciarSesion($usuarioId) {
        $usuario = $this->usuariosModel->find($usuarioId);
        if (!$usuario) {
            return false;
        }

        $cuentaActivada = isset($usuario['cuenta_activada']) ? (bool) $usuario['cuenta_activada'] : true;
        $usuarioActivo = isset($usuario['activo']) ? (bool) $usuario['activo'] : true;
        $permitirInactivos = $this->configuracionModel->permitirLoginInactivos();

        // Si se permiten usuarios inactivos, solo verificar cuenta activada
        if ($permitirInactivos) {
            return $cuentaActivada;
        }

        // Caso normal: usuario debe estar activo Y cuenta activada
        return $usuarioActivo && $cuentaActivada;
    }

    /**
     * Obtener usuarios activos
     */
    public function obtenerUsuariosActivos($cuentaId = null) {
        $builder = $this->usuariosModel->select('tbl_usuarios.*, tbl_roles.nombre as rol_nombre')
                                      ->join('tbl_roles', 'tbl_roles.id = tbl_usuarios.rol_id', 'left');
        
        // Filtrar por campo 'activo' si existe
        if ($this->usuariosModel->db->fieldExists('activo', 'tbl_usuarios')) {
            $builder->where('tbl_usuarios.activo', 1);
        }
        
        $builder->where('tbl_usuarios.cuenta_activada', 1);
        
        if ($cuentaId) {
            $builder->where('tbl_usuarios.cuenta_id', $cuentaId);
        }
        
        return $builder->findAll();
    }

    /**
     * Obtener usuarios inactivos
     */
    public function obtenerUsuariosInactivos($cuentaId = null) {
        $builder = $this->usuariosModel->select('tbl_usuarios.*, tbl_roles.nombre as rol_nombre')
                                      ->join('tbl_roles', 'tbl_roles.id = tbl_usuarios.rol_id', 'left');
        
        // Si existe el campo 'activo', filtrar por él
        if ($this->usuariosModel->db->fieldExists('activo', 'tbl_usuarios')) {
            $builder->groupStart()
                   ->where('tbl_usuarios.activo', 0)
                   ->orWhere('tbl_usuarios.cuenta_activada', 0)
                   ->groupEnd();
        } else {
            $builder->where('tbl_usuarios.cuenta_activada', 0);
        }
        
        if ($cuentaId) {
            $builder->where('tbl_usuarios.cuenta_id', $cuentaId);
        }
        
        return $builder->findAll();
    }

    /**
     * Obtener estadísticas de usuarios por estado
     */
    public function obtenerEstadisticasUsuarios($cuentaId = null) {
        $builder = $this->usuariosModel->select('COUNT(*) as total');
        
        if ($cuentaId) {
            $builder->where('cuenta_id', $cuentaId);
        }
        
        $total = $builder->get()->getRow()->total;
        
        // Contar activos
        $builderActivos = $this->usuariosModel->select('COUNT(*) as activos');
        if ($this->usuariosModel->db->fieldExists('activo', 'tbl_usuarios')) {
            $builderActivos->where('activo', 1);
        }
        $builderActivos->where('cuenta_activada', 1);
        
        if ($cuentaId) {
            $builderActivos->where('cuenta_id', $cuentaId);
        }
        
        $activos = $builderActivos->get()->getRow()->activos;
        
        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $total - $activos,
            'porcentaje_activos' => $total > 0 ? round(($activos / $total) * 100, 2) : 0
        ];
    }

    /**
     * Obtener historial de activaciones/desactivaciones
     */
    public function obtenerHistorialActivaciones($usuarioId = null, $limit = 50) {
        $builder = $this->select('tbl_bitacora_activacion_usuarios.*, 
                                 ua.nombre as usuario_afectado_nombre,
                                 ue.nombre as usuario_ejecutor_nombre')
                       ->join('tbl_usuarios ua', 'ua.id = tbl_bitacora_activacion_usuarios.usuario_afectado_id', 'left')
                       ->join('tbl_usuarios ue', 'ue.id = tbl_bitacora_activacion_usuarios.usuario_ejecutor_id', 'left')
                       ->orderBy('fecha_hora', 'DESC');
        
        if ($usuarioId) {
            $builder->where('usuario_afectado_id', $usuarioId);
        }
        
        return $builder->limit($limit)->findAll();
    }

    /**
     * Obtener IP del cliente
     */
    private function obtenerIpCliente() {
        $request = \Config\Services::request();
        return $request->getIPAddress();
    }

    /**
     * Desactivar usuarios inactivos automáticamente
     */
    public function desactivarUsuariosInactivosAutomaticamente() {
        $diasLimite = $this->configuracionModel->obtenerDiasLimiteInactividad();
        
        if ($diasLimite <= 0) {
            return ['success' => false, 'message' => 'Desactivación automática deshabilitada'];
        }
        
        $fechaLimite = date('Y-m-d H:i:s', strtotime("-{$diasLimite} days"));
        
        // Buscar usuarios que no han tenido actividad reciente
        $usuariosInactivos = $this->usuariosModel
            ->where('fecha_actualizacion <', $fechaLimite)
            ->where('activo', 1)
            ->findAll();
        
        $desactivados = 0;
        foreach ($usuariosInactivos as $usuario) {
            $resultado = $this->desactivarUsuario(
                $usuario['id'], 
                999, // Usuario del sistema
                "Desactivación automática por inactividad de {$diasLimite} días",
                '127.0.0.1'
            );
            
            if ($resultado['success']) {
                $desactivados++;
            }
        }
        
        return [
            'success' => true,
            'message' => "Se desactivaron {$desactivados} usuarios por inactividad",
            'desactivados' => $desactivados
        ];
    }
}