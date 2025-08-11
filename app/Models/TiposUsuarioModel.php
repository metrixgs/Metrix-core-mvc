<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposUsuarioModel extends Model
{
    protected $table = 'tbl_tipos_usuario';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nombre', 'codigo', 'descripcion', 'categoria_id', 'activo', 
        'nivel_acceso', 'puede_crear_usuarios', 'puede_modificar_usuarios', 
        'puede_eliminar_usuarios', 'configuracion_json', 'created_at', 'updated_at'
    ];
    
    // Mantener compatibilidad con tabla legacy
    protected $tableLegacy = 'tbl_roles';
    protected $allowedFieldsLegacy = [
        'nombre', 'categoria_id', 'codigo', 'nivel_jerarquico', 'rol_padre_id',
        'limite_usuarios', 'permisos_base', 'acceso_sistema', 'acceso_campo',
        'bitacora_habilitada', 'alcance_bitacora', 'descripcion', 'activo'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'codigo' => 'required|min_length[2]|max_length[20]|alpha_dash',
        'categoria_id' => 'required|integer',
        'nivel_acceso' => 'permit_empty|integer|greater_than[0]|less_than[6]',
        'descripcion' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'codigo' => [
            'required' => 'El código es requerido',
            'min_length' => 'El código debe tener al menos 2 caracteres',
            'max_length' => 'El código no puede exceder 20 caracteres',
            'alpha_dash' => 'El código solo puede contener letras, números, guiones y guiones bajos'
        ],
        'categoria_id' => [
            'required' => 'La categoría es requerida',
            'integer' => 'La categoría debe ser un número válido'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = ['afterInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = ['afterUpdate'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = ['beforeDelete'];
    protected $afterDelete = ['afterDelete'];

    /**
     * Obtener todos los tipos con detalles
     */
    public function obtenerTodosConDetalles()
    {
        return $this->select('
                tbl_tipos_usuario.*,
                -- tbl_categorias_usuario.nombre as categoria_nombre,
                -- tbl_categorias_usuario.codigo as categoria_codigo,
                -- tbl_categorias_usuario.color as categoria_color,
                -- tbl_categorias_usuario.icono as categoria_icono,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id) as total_usuarios,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id AND tbl_usuarios.activo = 1) as usuarios_activos
            ')
            // ->join('tbl_categorias_usuario', 'tbl_categorias_usuario.id = tbl_tipos_usuario.categoria_id')
            // ->orderBy('tbl_categorias_usuario.orden', 'ASC')
            ->orderBy('tbl_tipos_usuario.nivel_acceso', 'ASC')
            ->orderBy('tbl_tipos_usuario.nombre', 'ASC')
            ->findAll();
    }
    
    /**
     * Obtener todos los tipos de usuario activos
     */
    public function obtenerTodosActivos()
    {
        return $this->select('
                tbl_tipos_usuario.*,
                -- tbl_categorias_usuario.nombre as categoria_nombre,
                -- tbl_categorias_usuario.codigo as categoria_codigo,
                -- tbl_categorias_usuario.orden,
                tp.nombre as tipo_padre,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id) as total_usuarios,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id AND tbl_usuarios.activo = 1) as usuarios_activos,
                (SELECT COUNT(*) FROM tbl_modulos) as total_funciones,
                (
                    SELECT COUNT(DISTINCT p.modulo_id) 
                    FROM tbl_permisos p 
                    JOIN tbl_usuarios u ON p.usuario_id = u.id 
                    WHERE u.tipo_usuario_id = tbl_tipos_usuario.id 
                    AND (p.lectura = 1 OR p.escritura = 1 OR p.actualizacion = 1 OR p.eliminacion = 1)
                ) as permisos_configurados
            ')
            -- ->join('tbl_categorias_usuario', 'tbl_categorias_usuario.id = tbl_tipos_usuario.categoria_id', 'left')
            ->join('tbl_mapeo_roles_tipos mrt', 'mrt.tipo_usuario_id = tbl_tipos_usuario.id', 'left')
            ->join('tbl_roles r', 'r.id = mrt.rol_id', 'left')
            ->join('tbl_roles rp', 'rp.id = r.rol_padre_id', 'left')
            ->join('tbl_mapeo_roles_tipos mrtp', 'mrtp.rol_id = rp.id', 'left')
            ->join('tbl_tipos_usuario tp', 'tp.id = mrtp.tipo_usuario_id', 'left')
            ->where('tbl_tipos_usuario.activo', 1)
            -- ->orderBy('tbl_categorias_usuario.orden', 'ASC')
            ->orderBy('tbl_tipos_usuario.nivel_acceso', 'ASC')
            ->orderBy('tbl_tipos_usuario.nombre', 'ASC')
            ->findAll();
    }
    
    /**
     * Obtener tipo específico con detalles
     */
    public function obtenerConDetalles($id)
    {
        return $this->select('
                tbl_tipos_usuario.*,
                -- tbl_categorias_usuario.nombre as categoria_nombre,
                -- tbl_categorias_usuario.codigo as categoria_codigo,
                -- tbl_categorias_usuario.color as categoria_color,
                -- tbl_categorias_usuario.icono as categoria_icono,
                -- tbl_categorias_usuario.orden,
                -- tbl_categorias_usuario.es_legacy as categoria_legacy,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id) as total_usuarios,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id AND tbl_usuarios.activo = 1) as usuarios_activos
            ')
            -- ->join('tbl_categorias_usuario', 'tbl_categorias_usuario.id = tbl_tipos_usuario.categoria_id')
            ->find($id);
    }
    
    /**
     * Mantener compatibilidad - Obtener todos los tipos de usuario con sus categorías
     */
    public function obtenerTiposUsuarioConCategoria()
    {
        return $this->obtenerTodosConDetalles();
    }

    /**
     * Obtener tipos por categoría
     */
    public function obtenerPorCategoria($categoriaId)
    {
        return $this->select('
                tbl_tipos_usuario.*,
                (SELECT COUNT(*) FROM tbl_usuarios WHERE tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id) as total_usuarios
            ')
            ->where('categoria_id', $categoriaId)
            ->orderBy('nivel_acceso', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }
    
    /**
     * Mantener compatibilidad - Obtener tipos de usuario por categoría
     */
    public function obtenerTiposPorCategoria($categoriaId)
    {
        return $this->obtenerPorCategoria($categoriaId);
    }

    /**
     * Obtener jerarquía de un tipo de usuario
     */
    public function obtenerJerarquia($rolId)
    {
        $db = \Config\Database::connect();
        
        // Obtener superiores
        $superiores = $db->query("
            WITH RECURSIVE jerarquia_superior AS (
                SELECT id, nombre, codigo, rol_padre_id, nivel_jerarquico, 0 as nivel
                FROM tbl_roles 
                WHERE id = ?
                
                UNION ALL
                
                SELECT r.id, r.nombre, r.codigo, r.rol_padre_id, r.nivel_jerarquico, js.nivel + 1
                FROM tbl_roles r
                INNER JOIN jerarquia_superior js ON r.id = js.rol_padre_id
                WHERE js.nivel < 10
            )
            SELECT * FROM jerarquia_superior WHERE nivel > 0 ORDER BY nivel DESC
        ", [$rolId])->getResultArray();

        // Obtener subordinados
        $subordinados = $db->query("
            WITH RECURSIVE jerarquia_subordinada AS (
                SELECT id, nombre, codigo, rol_padre_id, nivel_jerarquico, 0 as nivel
                FROM tbl_roles 
                WHERE id = ?
                
                UNION ALL
                
                SELECT r.id, r.nombre, r.codigo, r.rol_padre_id, r.nivel_jerarquico, js.nivel + 1
                FROM tbl_roles r
                INNER JOIN jerarquia_subordinada js ON r.rol_padre_id = js.id
                WHERE js.nivel < 10
            )
            SELECT * FROM jerarquia_subordinada WHERE nivel > 0 ORDER BY nivel ASC
        ", [$rolId])->getResultArray();

        return [
            'superiores' => $superiores,
            'subordinados' => $subordinados
        ];
    }

    /**
     * Verificar si un usuario puede gestionar otro usuario
     */
    public function puedeGestionar($rolGestor, $rolObjetivo)
    {
        $gestor = $this->find($rolGestor);
        $objetivo = $this->find($rolObjetivo);

        if (!$gestor || !$objetivo) {
            return false;
        }

        // Los roles de nivel superior pueden gestionar los de nivel inferior
        if ($gestor['nivel_jerarquico'] < $objetivo['nivel_jerarquico']) {
            return true;
        }

        // Verificar si están en la misma línea jerárquica
        $jerarquiaGestor = $this->obtenerJerarquia($rolGestor);
        $subordinados = array_column($jerarquiaGestor['subordinados'], 'id');
        
        return in_array($rolObjetivo, $subordinados);
    }

    /**
     * Obtener estadísticas por tipo de usuario
     */
    public function obtenerEstadisticasPorTipo($cuentaId = null)
    {
        $builder = $this->db->table('tbl_usuarios u')
                           ->select('r.id as rol_id, r.nombre as rol_nombre, r.codigo as rol_codigo, 
                                   c.nombre as categoria_nombre, c.codigo as categoria_codigo,
                                   COUNT(u.id) as total_usuarios,
                                   SUM(CASE WHEN u.activo = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                                   SUM(CASE WHEN u.activo = 0 THEN 1 ELSE 0 END) as usuarios_inactivos')
                           ->join('tbl_roles r', 'u.rol_id = r.id')
                           -- ->join('tbl_categorias_usuario c', 'r.categoria_id = c.id', 'left')
                           ->where('r.activo', 1);

        if ($cuentaId) {
            $builder->where('u.cuenta_id', $cuentaId);
        }

        return $builder->groupBy('r.id, r.nombre, r.codigo, c.nombre, c.codigo')
                      ->orderBy('c.orden', 'ASC')
                      ->orderBy('r.nivel_jerarquico', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Validar límites de usuarios por tipo
     */
    public function validarLimiteUsuarios($rolId, $cuentaId = null)
    {
        $rol = $this->find($rolId);
        
        if (!$rol || !$rol['limite_usuarios']) {
            return true; // Sin límite
        }

        $builder = $this->db->table('tbl_usuarios')
                           ->where('rol_id', $rolId)
                           ->where('activo', 1);

        if ($cuentaId) {
            $builder->where('cuenta_id', $cuentaId);
        }

        $usuariosActuales = $builder->countAllResults();
        
        return $usuariosActuales < $rol['limite_usuarios'];
    }

    /**
     * Obtener tipos de usuario disponibles para asignación
     */
    public function obtenerTiposDisponibles($rolGestor, $cuentaId = null)
    {
        $gestor = $this->find($rolGestor);
        
        if (!$gestor) {
            return [];
        }

        // Obtener roles que puede gestionar
        $builder = $this->select('tbl_roles.*') // , tbl_categorias_usuario.nombre as categoria_nombre')
                        -- ->join('tbl_categorias_usuario', 'tbl_roles.categoria_id = tbl_categorias_usuario.id', 'left')
                        ->where('tbl_roles.activo', 1)
                        ->where('tbl_roles.nivel_jerarquico >=', $gestor['nivel_jerarquico']);

        $roles = $builder->findAll();
        
        // Filtrar por límites de usuarios
        $rolesDisponibles = [];
        foreach ($roles as $rol) {
            if ($this->validarLimiteUsuarios($rol['id'], $cuentaId)) {
                $rolesDisponibles[] = $rol;
            }
        }

        return $rolesDisponibles;
    }

    /**
     * Crear tipo de usuario personalizado
     */
    public function crearTipoPersonalizado($datos)
    {
        // Validar que el código no exista
        $existente = $this->where('codigo', $datos['codigo'])->first();
        if ($existente) {
            return false;
        }

        // Asignar nivel jerárquico automáticamente
        if (!isset($datos['nivel_jerarquico'])) {
            $maxNivel = $this->selectMax('nivel_jerarquico')->first();
            $datos['nivel_jerarquico'] = ($maxNivel['nivel_jerarquico'] ?? 0) + 1;
        }

        return $this->insert($datos);
    }

    /**
     * Obtener permisos base por tipo de usuario
     */
    public function obtenerPermisosBase($rolId)
    {
        $rol = $this->find($rolId);
        
        if (!$rol) {
            return [];
        }

        $permisos = str_split($rol['permisos_base']);
        
        return [
            'crear' => in_array('C', $permisos),
            'editar' => in_array('E', $permisos),
            'eliminar' => in_array('D', $permisos),
            'ver' => in_array('V', $permisos),
            'bitacora' => in_array('B', $permisos)
        ];
    }

    /**
     * Migrar usuarios de roles legacy a nuevos tipos
     */
    public function migrarRolesLegacy()
    {
        $migraciones = [
            1 => 16, // Master -> Máster
            2 => 22, // Administrador -> Admin Oficina
            3 => 31, // Cliente -> Ciudadano
            4 => 4, // Sistemas -> Desarrollador
            5 => 23, // Operador -> Operador Oficina
            6 => 25, // Gerente -> Director
            7 => 27, // Auxiliar -> Auxiliar Oficina
            8 => 27, // Capturista -> Auxiliar Oficina
            9 => 26, // Coordinador -> Coordinador Oficina
            10 => 29, // Enlace -> Enlace Campo
            11 => 30, // Operativo -> Operador Campo
            12 => 31, // Ciudadano -> Ciudadano
            13 => 31, // Beneficiario -> Ciudadano
            14 => 31  // Red -> Ciudadano
        ];

        $usuariosModel = new \App\Models\UsuariosModel();
        $migrados = 0;

        foreach ($migraciones as $rolAntiguo => $rolNuevo) {
            $usuarios = $usuariosModel->where('rol_id', $rolAntiguo)->findAll();
            
            foreach ($usuarios as $usuario) {
                $usuariosModel->update($usuario['id'], ['rol_id' => $rolNuevo]);
                $migrados++;
            }
        }

        return $migrados;
    }
    
    /**
     * Crear nuevo tipo de usuario
     */
    public function crearTipoUsuario($data)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Asignar nivel de acceso automático si no se especifica
            if (!isset($data['nivel_acceso'])) {
                $data['nivel_acceso'] = $this->calcularNivelAcceso($data['categoria_id']);
            }
            
            // Configuración por defecto
            $data['activo'] = $data['activo'] ?? true;
            // $data['es_legacy'] = $data['es_legacy'] ?? false;
            
            // Configuración JSON por defecto
            if (!isset($data['configuracion_json'])) {
                $data['configuracion_json'] = json_encode([
                    'dashboard_personalizado' => false,
                    'tema_predeterminado' => 'default',
                    'notificaciones_email' => true,
                    'notificaciones_sistema' => true,
                    'sesion_multiple' => false,
                    'timeout_sesion' => 3600
                ]);
            }
            
            $tipoId = $this->insert($data);
            
            if ($tipoId) {
                // Registrar en auditoría
                $this->registrarAuditoria('crear', $tipoId, $data);
                
                $db->transComplete();
                return $tipoId;
            }
            
            $db->transRollback();
            return false;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al crear tipo de usuario: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar tipo de usuario
     */
    public function eliminarTipoUsuario($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Verificar que no tenga usuarios asociados
            $usuariosCount = $this->contarUsuariosAsociados($id);
            if ($usuariosCount > 0) {
                return false;
            }
            
            // Obtener datos antes de eliminar para auditoría
            $tipo = $this->find($id);
            
            // Permisos eliminados - sistema simplificado
            
            // Eliminar relaciones jerárquicas
            $jerarquiaModel = new \App\Models\JerarquiaUsuarioModel();
            $jerarquiaModel->eliminarRelacionesTipo($id);
            
            // Eliminar tipo
            $resultado = $this->delete($id);
            
            if ($resultado) {
                // Registrar en auditoría
                $this->registrarAuditoria('eliminar', $id, $tipo);
                
                $db->transComplete();
                return true;
            }
            
            $db->transRollback();
            return false;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar tipo de usuario: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Contar usuarios asociados a un tipo
     */
    public function contarUsuariosAsociados($tipoId)
    {
        $db = \Config\Database::connect();
        $query = $db->query(
            "SELECT COUNT(*) as total FROM tbl_usuarios WHERE tipo_usuario_id = ?",
            [$tipoId]
        );
        $result = $query->getRow();
        return $result ? $result->total : 0;
    }
    
    /**
     * Contar tipos por categoría
     */
    public function contarPorCategoria($categoriaId)
    {
        return $this->where('categoria_id', $categoriaId)->countAllResults();
    }
    
    /**
     * Verificar código único
     */
    public function verificarCodigoUnico($codigo, $excludeId = null)
    {
        $builder = $this->where('codigo', $codigo);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Calcular nivel de acceso automático
     */
    private function calcularNivelAcceso($categoriaId)
    {
        $categoriasModel = new \App\Models\CategoriasUsuarioModel();
        $categoria = $categoriasModel->find($categoriaId);
        
        if (!$categoria) {
            return 3; // Nivel por defecto
        }
        
        // Calcular basado en el nivel jerárquico de la categoría
        $nivelBase = $categoria['nivel_jerarquico'];
        
        // Obtener el siguiente nivel disponible en la categoría
        $maxNivel = $this->selectMax('nivel_acceso')
            ->where('categoria_id', $categoriaId)
            ->get()
            ->getRow();
        
        return $maxNivel && $maxNivel->nivel_acceso ? $maxNivel->nivel_acceso + 1 : $nivelBase;
    }
    
    /**
     * Migrar usuarios legacy
     */
    public function migrarUsuariosLegacy($configuracion)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $resultado = [
                'migrados' => 0,
                'errores' => 0,
                'detalles' => []
            ];
            
            // Obtener usuarios legacy
            $usuariosLegacy = $db->query(
                "SELECT * FROM tbl_usuarios WHERE tipo_usuario_legacy IS NOT NULL AND tipo_usuario_id IS NULL"
            )->getResultArray();
            
            foreach ($usuariosLegacy as $usuario) {
                try {
                    // Mapear tipo legacy a nuevo tipo
                    $nuevoTipoId = $this->mapearTipoLegacy($usuario['tipo_usuario_legacy'], $configuracion);
                    
                    if ($nuevoTipoId) {
                        // Actualizar usuario
                        $db->query(
                            "UPDATE tbl_usuarios SET tipo_usuario_id = ?, tipo_usuario_legacy = NULL WHERE id = ?",
                            [$nuevoTipoId, $usuario['id']]
                        );
                        
                        $resultado['migrados']++;
                        $resultado['detalles'][] = [
                            'usuario_id' => $usuario['id'],
                            'tipo_legacy' => $usuario['tipo_usuario_legacy'],
                            'nuevo_tipo_id' => $nuevoTipoId,
                            'estado' => 'migrado'
                        ];
                    } else {
                        $resultado['errores']++;
                        $resultado['detalles'][] = [
                            'usuario_id' => $usuario['id'],
                            'tipo_legacy' => $usuario['tipo_usuario_legacy'],
                            'estado' => 'error',
                            'mensaje' => 'No se encontró mapeo para el tipo legacy'
                        ];
                    }
                } catch (\Exception $e) {
                    $resultado['errores']++;
                    $resultado['detalles'][] = [
                        'usuario_id' => $usuario['id'],
                        'estado' => 'error',
                        'mensaje' => $e->getMessage()
                    ];
                }
            }
            
            $db->transComplete();
            return $resultado;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error en migración legacy: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mapear tipo legacy a nuevo tipo
     */
    private function mapearTipoLegacy($tipoLegacy, $configuracion)
    {
        $mapeo = $configuracion['mapeo_legacy'] ?? [];
        
        if (isset($mapeo[$tipoLegacy])) {
            return $mapeo[$tipoLegacy];
        }
        
        // Mapeo automático basado en nombres similares
        $tipoSimilar = $this->like('nombre', $tipoLegacy, 'both')
            ->orLike('codigo', $tipoLegacy, 'both')
            ->first();
        
        return $tipoSimilar ? $tipoSimilar['id'] : null;
    }
    
    /**
     * Exportar configuración completa
     */
    public function exportarConfiguracion()
    {
        $tipos = $this->obtenerTodosConDetalles();
        
        $configuracion = [
            'version' => '1.0',
            'fecha_exportacion' => date('Y-m-d H:i:s'),
            'tipos_usuario' => [],
            'categorias' => [],
            'permisos' => [],
            'jerarquia' => []
        ];
        
        // Exportar tipos
        foreach ($tipos as $tipo) {
            $configuracion['tipos_usuario'][] = [
                'codigo' => $tipo['codigo'],
                'nombre' => $tipo['nombre'],
                'descripcion' => $tipo['descripcion'],
                'categoria_codigo' => $tipo['categoria_codigo'],
                'nivel_acceso' => $tipo['nivel_acceso'],
                'activo' => $tipo['activo'],
                'configuracion_json' => $tipo['configuracion_json']
            ];
        }
        
        // Exportar categorías
        $categoriasModel = new \App\Models\CategoriasUsuarioModel();
        $configuracion['categorias'] = $categoriasModel->exportarCategorias();
        
        // Permisos no exportados - sistema simplificado
        $configuracion['permisos'] = [];
        
        // Exportar jerarquía
        $jerarquiaModel = new \App\Models\JerarquiaUsuarioModel();
        $configuracion['jerarquia'] = $jerarquiaModel->exportarJerarquiaCompleta();
        
        return $configuracion;
    }
    
    /**
     * Importar configuración
     */
    public function importarConfiguracion($configuracion)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $resultado = [
                'tipos_importados' => 0,
                'categorias_importadas' => 0,
                'permisos_importados' => 0,
                'errores' => []
            ];
            
            // Importar categorías primero
            if (isset($configuracion['categorias'])) {
                $categoriasModel = new \App\Models\CategoriasUsuarioModel();
                $resultadoCategorias = $categoriasModel->importarCategorias($configuracion['categorias']);
                $resultado['categorias_importadas'] = $resultadoCategorias['importadas'];
            }
            
            // Importar tipos
            if (isset($configuracion['tipos_usuario'])) {
                foreach ($configuracion['tipos_usuario'] as $tipoData) {
                    try {
                        // Buscar categoría por código
                        $categoriasModel = new \App\Models\CategoriasUsuarioModel();
                        $categoria = $categoriasModel->where('codigo', $tipoData['categoria_codigo'])->first();
                        
                        if ($categoria) {
                            $tipoData['categoria_id'] = $categoria['id'];
                            unset($tipoData['categoria_codigo']);
                            
                            // Verificar si ya existe
                            $tipoExistente = $this->where('codigo', $tipoData['codigo'])->first();
                            
                            if (!$tipoExistente) {
                                $this->insert($tipoData);
                                $resultado['tipos_importados']++;
                            }
                        }
                    } catch (\Exception $e) {
                        $resultado['errores'][] = 'Error al importar tipo ' . $tipoData['codigo'] . ': ' . $e->getMessage();
                    }
                }
            }
            
            // Permisos no importados - sistema simplificado
            $resultado['permisos_importados'] = 0;
            
            // Importar jerarquía
            if (isset($configuracion['jerarquia'])) {
                $jerarquiaModel = new \App\Models\JerarquiaUsuarioModel();
                $jerarquiaModel->importarJerarquia($configuracion['jerarquia']);
            }
            
            $db->transComplete();
            return $resultado;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al importar configuración: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener tipos activos
     */
    public function obtenerTiposActivos()
    {
        return $this->where('activo', true)
            ->orderBy('nivel_acceso', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }
    
    /**
     * Obtener tipos legacy
     */
    public function obtenerTiposLegacy()
    {
        // Método deshabilitado - columna 'es_legacy' no existe en la tabla
        return [];
        // return $this->where('es_legacy', true)
        //     ->orderBy('nombre', 'ASC')
        //     ->findAll();
    }
    
    /**
     * Cambiar estado de tipo
     */
    public function cambiarEstado($id, $activo)
    {
        $tipo = $this->find($id);
        if (!$tipo) {
            return false;
        }
        
        $resultado = $this->update($id, ['activo' => $activo]);
        
        if ($resultado) {
            $this->registrarAuditoria(
                $activo ? 'activar' : 'desactivar',
                $id,
                ['activo' => $activo]
            );
        }
        
        return $resultado;
    }
    
    /**
     * Registrar auditoría
     */
    private function registrarAuditoria($accion, $tipoId, $datos)
    {
        try {
            $auditoriaModel = new \App\Models\AuditoriaModel();
            $auditoriaModel->registrar([
                'tabla' => 'tipos_usuario',
                'registro_id' => $tipoId,
                'accion' => $accion,
                'datos_anteriores' => $accion === 'actualizar' ? json_encode($this->find($tipoId)) : null,
                'datos_nuevos' => json_encode($datos),
                'usuario_id' => session('usuario_id'),
                'ip' => \Config\Services::request()->getIPAddress(),
                'user_agent' => \Config\Services::request()->getUserAgent()->getAgentString()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar auditoría: ' . $e->getMessage());
        }
    }
    
    // Callbacks
    protected function beforeInsert(array $data)
    {
        return $this->validarDatos($data);
    }
    
    protected function beforeUpdate(array $data)
    {
        return $this->validarDatos($data);
    }
    
    protected function afterInsert(array $data)
    {
        if (isset($data['id'])) {
            $this->registrarAuditoria('crear', $data['id'], $data['data']);
        }
        return $data;
    }
    
    protected function afterUpdate(array $data)
    {
        if (isset($data['id'])) {
            $this->registrarAuditoria('actualizar', $data['id'][0], $data['data']);
        }
        return $data;
    }
    
    protected function beforeDelete(array $data)
    {
        // Verificar que no tenga usuarios asociados
        if (isset($data['id'])) {
            $usuariosCount = $this->contarUsuariosAsociados($data['id'][0]);
            if ($usuariosCount > 0) {
                throw new \Exception('No se puede eliminar el tipo porque tiene usuarios asociados');
            }
        }
        return $data;
    }
    
    protected function afterDelete(array $data)
    {
        if (isset($data['id'])) {
            $this->registrarAuditoria('eliminar', $data['id'][0], $data['data']);
        }
        return $data;
    }
    
    /**
     * Validar datos específicos
     */
    private function validarDatos($data)
    {
        // Validar código único
        if (isset($data['data']['codigo'])) {
            $excludeId = isset($data['id']) ? $data['id'][0] : null;
            if ($this->verificarCodigoUnico($data['data']['codigo'], $excludeId)) {
                throw new \Exception('El código ya existe');
            }
        }
        
        // Validar categoría existe
        if (isset($data['data']['categoria_id'])) {
            $categoriasModel = new \App\Models\CategoriasUsuarioModel();
            if (!$categoriasModel->find($data['data']['categoria_id'])) {
                throw new \Exception('La categoría especificada no existe');
            }
        }
        
        return $data;
    }
    
    /**
     * Obtener estadísticas generales
     */
    public function obtenerEstadisticas()
    {
        $db = \Config\Database::connect();
        
        $estadisticas = [
            'total_tipos' => $this->countAllResults(false),
            'tipos_activos' => $this->where('activo', true)->countAllResults(false),
            // 'tipos_legacy' => $this->where('es_legacy', true)->countAllResults(false),
            'por_categoria' => [],
            'usuarios_por_tipo' => []
        ];
        
        // Estadísticas por categoría
        // $query = $this->db->query("
        //     SELECT 
        //         c.id,
        //         c.nombre as categoria,
        //         COUNT(t.id) as total_tipos,
        //         SUM(CASE WHEN t.activo = 1 THEN 1 ELSE 0 END) as tipos_activos
        //     FROM tbl_categorias_usuario c
        //     LEFT JOIN tbl_tipos_usuario t ON c.id = t.categoria_id AND t.deleted_at IS NULL
        //     WHERE c.deleted_at IS NULL
        //     GROUP BY c.id, c.nombre
        //     ORDER BY c.nivel_jerarquico
        // ");
        $estadisticas['por_categoria'] = []; // $query->getResultArray();
        
        // Usuarios por tipo
        $query = $db->query("
            SELECT 
                t.nombre as tipo,
                t.codigo,
                COUNT(u.id) as total_usuarios,
                SUM(CASE WHEN u.activo = 1 THEN 1 ELSE 0 END) as usuarios_activos
            FROM tbl_tipos_usuario t
            LEFT JOIN tbl_usuarios u ON t.id = u.tipo_usuario_id
            WHERE t.deleted_at IS NULL
            GROUP BY t.id, t.nombre, t.codigo
            ORDER BY total_usuarios DESC
            LIMIT 10
        ");
        $estadisticas['usuarios_por_tipo'] = $query->getResultArray();
        
        return $estadisticas;
    }
}