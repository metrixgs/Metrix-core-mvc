<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionSistemaModel extends Model {

    protected $table = 'tbl_configuracion_sistema';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'clave', 'valor', 'descripcion', 'tipo', 'categoria', 'activo'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Obtener configuración por clave
     */
    public function obtenerConfiguracion($clave, $valorPorDefecto = null) {
        $config = $this->where('clave', $clave)
                      ->where('activo', 1)
                      ->first();
        
        return $config ? $config['valor'] : $valorPorDefecto;
    }

    /**
     * Establecer configuración
     */
    public function establecerConfiguracion($clave, $valor, $descripcion = null) {
        $existente = $this->where('clave', $clave)->first();
        
        if ($existente) {
            $valorAnterior = $existente['valor'];
            $result = $this->update($existente['id'], [
                'valor' => $valor,
                'descripcion' => $descripcion ?: $existente['descripcion']
            ]);
            if ($result && $valorAnterior !== $valor) {
                helper('bitacora');
                $usuario_id = session('session_data.id') ?? 999;
                log_activity($usuario_id, 'Configuracion Sistema', 'Actualizar', [
                    'descripcion' => 'Configuración actualizada',
                    'clave' => $clave,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $valor,
                    'accion' => 'Actualizar configuración'
                ], 'info');
            }
            return $result;
        } else {
            $result = $this->insert([
                'clave' => $clave,
                'valor' => $valor,
                'descripcion' => $descripcion,
                'tipo' => 'string',
                'categoria' => 'general',
                'activo' => 1
            ]);
            if ($result) {
                helper('bitacora');
                $usuario_id = session('session_data.id') ?? 999;
                log_activity($usuario_id, 'Configuracion Sistema', 'Crear', [
                    'descripcion' => 'Nueva configuración creada',
                    'clave' => $clave,
                    'valor' => $valor,
                    'configuracion_id' => $result,
                    'accion' => 'Crear configuración'
                ], 'info');
            }
            return $result;
        }
    }

    /**
     * Verificar si se permite login de usuarios inactivos
     */
    public function permitirLoginInactivos() {
        return (bool) $this->obtenerConfiguracion('permitir_login_inactivos', false);
    }

    /**
     * Obtener días límite de inactividad
     */
    public function obtenerDiasLimiteInactividad() {
        return (int) $this->obtenerConfiguracion('dias_limite_inactividad', 90);
    }

    /**
     * Verificar si un rol puede activar/desactivar otro rol según jerarquía
     */
    public function puedeActivarRol($rolEjecutor, $rolObjetivo) {
        // Máster (rol_id = 3) puede activar/desactivar cualquier usuario
        if ($rolEjecutor == 3) {
            return true;
        }
        
        // Administrador (rol_id = 2) puede activar/desactivar usuarios de su cuenta excepto Máster
        if ($rolEjecutor == 2 && $rolObjetivo != 3) {
            return true;
        }
        
        // Otros roles no pueden activar/desactivar usuarios
        return false;
    }

    /**
     * Obtener jerarquía de roles
     */
    public function obtenerJerarquiaRoles() {
        return [
            16 => 'Máster',
            22 => 'Admin Oficina',
            31 => 'Ciudadano',
            4 => 'Desarrollador',
            23 => 'Operador Oficina'
        ];
    }

    /**
     * Obtener configuraciones por categoría
     */
    public function obtenerConfiguracionesPorCategoria($categoria) {
        return $this->where('categoria', $categoria)
                   ->where('activo', 1)
                   ->findAll();
    }

    /**
     * Obtener todas las configuraciones activas
     */
    public function obtenerTodasConfiguraciones() {
        return $this->where('activo', 1)
                   ->orderBy('categoria', 'ASC')
                   ->orderBy('clave', 'ASC')
                   ->findAll();
    }

    /**
     * Inicializar configuraciones por defecto
     */
    public function inicializarConfiguracionesPorDefecto() {
        $configuracionesDefecto = [
            [
                'clave' => 'permitir_login_inactivos',
                'valor' => '0',
                'descripcion' => 'Permitir login de usuarios inactivos',
                'tipo' => 'boolean',
                'categoria' => 'usuarios'
            ],
            [
                'clave' => 'dias_limite_inactividad',
                'valor' => '90',
                'descripcion' => 'Días límite para considerar usuario inactivo',
                'tipo' => 'integer',
                'categoria' => 'usuarios'
            ],
            [
                'clave' => 'activacion_automatica_usuarios',
                'valor' => '1',
                'descripcion' => 'Activar usuarios automáticamente al crearlos',
                'tipo' => 'boolean',
                'categoria' => 'usuarios'
            ],
            [
                'clave' => 'notificar_cambios_estado',
                'valor' => '1',
                'descripcion' => 'Notificar por email cambios de estado de usuarios',
                'tipo' => 'boolean',
                'categoria' => 'notificaciones'
            ]
        ];

        foreach ($configuracionesDefecto as $config) {
            $existente = $this->where('clave', $config['clave'])->first();
            if (!$existente) {
                $config['activo'] = 1;
                $this->insert($config);
            }
        }
    }
}