<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\AuditoriaModel;

class JerarquiaUsuarioModel extends Model
{
    protected $table = 'jerarquia_usuario';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'tipo_superior_id', 'tipo_subordinado_id', 'nivel_jerarquico', 
        'puede_gestionar', 'puede_ver', 'puede_crear', 'puede_modificar', 
        'puede_eliminar', 'alcance_gestion', 'configuracion_json', 
        'activo', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'tipo_superior_id' => 'required|integer',
        'tipo_subordinado_id' => 'required|integer',
        'nivel_jerarquico' => 'required|integer|greater_than[0]',
        'alcance_gestion' => 'permit_empty|in_list[directo,descendente,completo]'
    ];
    
    protected $validationMessages = [
        'tipo_superior_id' => [
            'required' => 'El tipo superior es requerido',
            'integer' => 'El tipo superior debe ser un número válido'
        ],
        'tipo_subordinado_id' => [
            'required' => 'El tipo subordinado es requerido',
            'integer' => 'El tipo subordinado debe ser un número válido'
        ],
        'nivel_jerarquico' => [
            'required' => 'El nivel jerárquico es requerido',
            'integer' => 'El nivel jerárquico debe ser un número',
            'greater_than' => 'El nivel jerárquico debe ser mayor a 0'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = ['afterInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = ['afterUpdate'];
    protected $beforeDelete = ['beforeDelete'];
    protected $afterDelete = ['afterDelete'];
    
    /**
     * Obtener jerarquía completa
     */
    public function obtenerJerarquiaCompleta()
    {
        return $this->select('
                jerarquia_usuario.*,
                ts.nombre as tipo_superior_nombre,
                ts.codigo as tipo_superior_codigo,
                ts.categoria_id as tipo_superior_categoria,
                tsub.nombre as tipo_subordinado_nombre,
                tsub.codigo as tipo_subordinado_codigo,
                tsub.categoria_id as tipo_subordinado_categoria,
                -- cs.nombre as categoria_superior_nombre,
                -- csub.nombre as categoria_subordinado_nombre
            ')
            ->join('tbl_tipos_usuario ts', 'ts.id = tbl_jerarquia_usuario.tipo_superior_id')
            ->join('tbl_tipos_usuario tsub', 'tsub.id = tbl_jerarquia_usuario.tipo_subordinado_id')
            -- ->join('tbl_categorias_usuario cs', 'cs.id = ts.categoria_id')
            -- ->join('tbl_categorias_usuario csub', 'csub.id = tsub.categoria_id')
            ->where('tbl_jerarquia_usuario.activo', true)
            ->orderBy('tbl_jerarquia_usuario.nivel_jerarquico', 'ASC')
            ->orderBy('ts.nombre', 'ASC')
            ->findAll();
    }
    
    /**
     * Obtener subordinados de un tipo
     */
    public function obtenerSubordinados($tipoId, $incluirIndirectos = false)
    {
        $subordinados = [];
        
        // Subordinados directos
        $directos = $this->select('
                jerarquia_usuario.*,
                tsub.nombre as tipo_subordinado_nombre,
                tsub.codigo as tipo_subordinado_codigo,
                tsub.categoria_id as tipo_subordinado_categoria,
                -- csub.nombre as categoria_subordinado_nombre
            ')
            ->join('tbl_tipos_usuario tsub', 'tsub.id = tbl_jerarquia_usuario.tipo_subordinado_id')
            -- ->join('tbl_categorias_usuario csub', 'csub.id = tsub.categoria_id')
            ->where('tbl_jerarquia_usuario.tipo_superior_id', $tipoId)
            ->where('tbl_jerarquia_usuario.activo', true)
            ->orderBy('tbl_jerarquia_usuario.nivel_jerarquico', 'ASC')
            ->findAll();
        
        $subordinados = array_merge($subordinados, $directos);
        
        // Subordinados indirectos (recursivo)
        if ($incluirIndirectos) {
            foreach ($directos as $directo) {
                $indirectos = $this->obtenerSubordinados($directo['tipo_subordinado_id'], true);
                $subordinados = array_merge($subordinados, $indirectos);
            }
        }
        
        return $subordinados;
    }
    
    /**
     * Obtener superiores de un tipo
     */
    public function obtenerSuperiores($tipoId, $incluirIndirectos = false)
    {
        $superiores = [];
        
        // Superiores directos
        $directos = $this->select('
                jerarquia_usuario.*,
                ts.nombre as tipo_superior_nombre,
                ts.codigo as tipo_superior_codigo,
                ts.categoria_id as tipo_superior_categoria,
                -- cs.nombre as categoria_superior_nombre
            ')
            ->join('tbl_tipos_usuario ts', 'ts.id = tbl_jerarquia_usuario.tipo_superior_id')
            -- ->join('tbl_categorias_usuario cs', 'cs.id = ts.categoria_id')
            ->where('tbl_jerarquia_usuario.tipo_subordinado_id', $tipoId)
            ->where('tbl_jerarquia_usuario.activo', true)
            ->orderBy('tbl_jerarquia_usuario.nivel_jerarquico', 'DESC')
            ->findAll();
        
        $superiores = array_merge($superiores, $directos);
        
        // Superiores indirectos (recursivo)
        if ($incluirIndirectos) {
            foreach ($directos as $directo) {
                $indirectos = $this->obtenerSuperiores($directo['tipo_superior_id'], true);
                $superiores = array_merge($superiores, $indirectos);
            }
        }
        
        return $superiores;
    }
    
    /**
     * Verificar si un tipo puede gestionar otro
     */
    public function puedeGestionar($tipoSuperiorId, $tipoSubordinadoId)
    {
        // Verificar relación directa
        $relacion = $this->where('tipo_superior_id', $tipoSuperiorId)
            ->where('tipo_subordinado_id', $tipoSubordinadoId)
            ->where('puede_gestionar', true)
            ->where('activo', true)
            ->first();
        
        if ($relacion) {
            return $relacion;
        }
        
        // Verificar relación indirecta
        $subordinados = $this->obtenerSubordinados($tipoSuperiorId, true);
        
        foreach ($subordinados as $subordinado) {
            if ($subordinado['tipo_subordinado_id'] == $tipoSubordinadoId && 
                $subordinado['puede_gestionar']) {
                return $subordinado;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar si un tipo puede ver otro
     */
    public function puedeVer($tipoSuperiorId, $tipoSubordinadoId)
    {
        // Verificar relación directa
        $relacion = $this->where('tipo_superior_id', $tipoSuperiorId)
            ->where('tipo_subordinado_id', $tipoSubordinadoId)
            ->where('puede_ver', true)
            ->where('activo', true)
            ->first();
        
        if ($relacion) {
            return true;
        }
        
        // Verificar relación indirecta
        $subordinados = $this->obtenerSubordinados($tipoSuperiorId, true);
        
        foreach ($subordinados as $subordinado) {
            if ($subordinado['tipo_subordinado_id'] == $tipoSubordinadoId && 
                $subordinado['puede_ver']) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Crear relación jerárquica
     */
    public function crearRelacion($data)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Validar que no se cree una relación circular
            if ($this->validarCircularidad($data['tipo_superior_id'], $data['tipo_subordinado_id'])) {
                throw new \Exception('No se puede crear la relación: generaría una dependencia circular');
            }
            
            // Configuración por defecto
            $data['activo'] = $data['activo'] ?? true;
            $data['puede_gestionar'] = $data['puede_gestionar'] ?? true;
            $data['puede_ver'] = $data['puede_ver'] ?? true;
            $data['puede_crear'] = $data['puede_crear'] ?? false;
            $data['puede_modificar'] = $data['puede_modificar'] ?? false;
            $data['puede_eliminar'] = $data['puede_eliminar'] ?? false;
            $data['alcance_gestion'] = $data['alcance_gestion'] ?? 'directo';
            
            // Calcular nivel jerárquico automáticamente si no se especifica
            if (!isset($data['nivel_jerarquico'])) {
                $data['nivel_jerarquico'] = $this->calcularNivelJerarquico(
                    $data['tipo_superior_id'], 
                    $data['tipo_subordinado_id']
                );
            }
            
            $relacionId = $this->insert($data);
            
            if ($relacionId) {
                // Actualizar jerarquías dependientes
                $this->actualizarJerarquiasDependientes($data['tipo_superior_id'], $data['tipo_subordinado_id']);
                
                $db->transComplete();
                return $relacionId;
            }
            
            $db->transRollback();
            return false;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al crear relación jerárquica: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Eliminar relación jerárquica
     */
    public function eliminarRelacion($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $relacion = $this->find($id);
            if (!$relacion) {
                return false;
            }
            
            // Eliminar relación
            $resultado = $this->delete($id);
            
            if ($resultado) {
                // Actualizar jerarquías dependientes
                $this->actualizarJerarquiasDependientes(
                    $relacion['tipo_superior_id'], 
                    $relacion['tipo_subordinado_id']
                );
                
                $db->transComplete();
                return true;
            }
            
            $db->transRollback();
            return false;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar relación jerárquica: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar todas las relaciones de un tipo
     */
    public function eliminarRelacionesTipo($tipoId)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Eliminar como superior
            $this->where('tipo_superior_id', $tipoId)->delete();
            
            // Eliminar como subordinado
            $this->where('tipo_subordinado_id', $tipoId)->delete();
            
            $db->transComplete();
            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al eliminar relaciones del tipo: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validar circularidad en jerarquía
     */
    private function validarCircularidad($tipoSuperiorId, $tipoSubordinadoId)
    {
        // Si el tipo subordinado ya es superior del tipo superior, hay circularidad
        return $this->puedeGestionar($tipoSubordinadoId, $tipoSuperiorId) !== false;
    }
    
    /**
     * Calcular nivel jerárquico
     */
    private function calcularNivelJerarquico($tipoSuperiorId, $tipoSubordinadoId)
    {
        // Obtener el nivel más alto del tipo superior
        $nivelSuperior = $this->selectMax('nivel_jerarquico')
            ->where('tipo_subordinado_id', $tipoSuperiorId)
            ->get()
            ->getRow();
        
        $nivelBase = $nivelSuperior && $nivelSuperior->nivel_jerarquico ? 
            $nivelSuperior->nivel_jerarquico + 1 : 1;
        
        return $nivelBase;
    }
    
    /**
     * Actualizar jerarquías dependientes
     */
    private function actualizarJerarquiasDependientes($tipoSuperiorId, $tipoSubordinadoId)
    {
        // Recalcular niveles jerárquicos de subordinados
        $subordinados = $this->obtenerSubordinados($tipoSubordinadoId, true);
        
        foreach ($subordinados as $subordinado) {
            $nuevoNivel = $this->calcularNivelJerarquico(
                $subordinado['tipo_superior_id'],
                $subordinado['tipo_subordinado_id']
            );
            
            $this->update($subordinado['id'], ['nivel_jerarquico' => $nuevoNivel]);
        }
    }
    
    /**
     * Obtener árbol jerárquico
     */
    public function obtenerArbolJerarquico($tipoRaizId = null)
    {
        if ($tipoRaizId) {
            return $this->construirArbol($tipoRaizId);
        }
        
        // Obtener todos los tipos raíz (sin superiores)
        $tiposRaiz = $this->select('DISTINCT tipo_superior_id')
            ->where('tipo_superior_id NOT IN (SELECT DISTINCT tipo_subordinado_id FROM tbl_jerarquia_usuario WHERE deleted_at IS NULL)')
            ->findAll();
        
        $arboles = [];
        foreach ($tiposRaiz as $raiz) {
            $arboles[] = $this->construirArbol($raiz['tipo_superior_id']);
        }
        
        return $arboles;
    }
    
    /**
     * Construir árbol desde un nodo
     */
    private function construirArbol($tipoId)
    {
        $tiposModel = new \App\Models\TiposUsuarioModel();
        $tipo = $tiposModel->obtenerConDetalles($tipoId);
        
        if (!$tipo) {
            return null;
        }
        
        $nodo = [
            'tipo' => $tipo,
            'subordinados' => []
        ];
        
        $subordinados = $this->obtenerSubordinados($tipoId, false);
        
        foreach ($subordinados as $subordinado) {
            $subarbol = $this->construirArbol($subordinado['tipo_subordinado_id']);
            if ($subarbol) {
                $subarbol['relacion'] = $subordinado;
                $nodo['subordinados'][] = $subarbol;
            }
        }
        
        return $nodo;
    }
    
    /**
     * Obtener usuarios gestionables por un tipo
     */
    public function obtenerUsuariosGestionables($tipoId, $alcance = 'directo')
    {
        $db = \Config\Database::connect();
        
        $tiposGestionables = [];
        
        switch ($alcance) {
            case 'directo':
                $subordinados = $this->obtenerSubordinados($tipoId, false);
                break;
            case 'descendente':
                $subordinados = $this->obtenerSubordinados($tipoId, true);
                break;
            case 'completo':
                // Todos los tipos que puede gestionar
                $subordinados = $this->obtenerSubordinados($tipoId, true);
                break;
            default:
                $subordinados = [];
        }
        
        foreach ($subordinados as $subordinado) {
            if ($subordinado['puede_gestionar']) {
                $tiposGestionables[] = $subordinado['tipo_subordinado_id'];
            }
        }
        
        if (empty($tiposGestionables)) {
            return [];
        }
        
        // Obtener usuarios de los tipos gestionables
        $tiposIds = implode(',', $tiposGestionables);
        $query = $db->query("
            SELECT 
                u.*,
                t.nombre as tipo_nombre,
                t.codigo as tipo_codigo,
                -- c.nombre as categoria_nombre
            FROM tbl_usuarios u
            JOIN tbl_tipos_usuario t ON u.tipo_usuario_id = t.id
            -- JOIN tbl_categorias_usuario c ON t.categoria_id = c.id
            WHERE u.tipo_usuario_id IN ({$tiposIds})
            AND u.deleted_at IS NULL
            ORDER BY t.nivel_acceso, u.nombre
        ");
        
        return $query->getResultArray();
    }
    
    /**
     * Exportar jerarquía completa
     */
    public function exportarJerarquiaCompleta()
    {
        $jerarquia = $this->obtenerJerarquiaCompleta();
        
        $exportacion = [
            'version' => '1.0',
            'fecha_exportacion' => date('Y-m-d H:i:s'),
            'relaciones' => []
        ];
        
        foreach ($jerarquia as $relacion) {
            $exportacion['relaciones'][] = [
                'tipo_superior_codigo' => $relacion['tipo_superior_codigo'],
                'tipo_subordinado_codigo' => $relacion['tipo_subordinado_codigo'],
                'nivel_jerarquico' => $relacion['nivel_jerarquico'],
                'puede_gestionar' => $relacion['puede_gestionar'],
                'puede_ver' => $relacion['puede_ver'],
                'puede_crear' => $relacion['puede_crear'],
                'puede_modificar' => $relacion['puede_modificar'],
                'puede_eliminar' => $relacion['puede_eliminar'],
                'alcance_gestion' => $relacion['alcance_gestion'],
                'configuracion_json' => $relacion['configuracion_json'],
                'activo' => $relacion['activo']
            ];
        }
        
        return $exportacion;
    }
    
    /**
     * Importar jerarquía
     */
    public function importarJerarquia($datos)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $resultado = [
                'importadas' => 0,
                'errores' => []
            ];
            
            $tiposModel = new \App\Models\TiposUsuarioModel();
            
            foreach ($datos['relaciones'] as $relacionData) {
                try {
                    // Buscar tipos por código
                    $tipoSuperior = $tiposModel->where('codigo', $relacionData['tipo_superior_codigo'])->first();
                    $tipoSubordinado = $tiposModel->where('codigo', $relacionData['tipo_subordinado_codigo'])->first();
                    
                    if ($tipoSuperior && $tipoSubordinado) {
                        // Verificar si ya existe la relación
                        $relacionExistente = $this->where('tipo_superior_id', $tipoSuperior['id'])
                            ->where('tipo_subordinado_id', $tipoSubordinado['id'])
                            ->first();
                        
                        if (!$relacionExistente) {
                            $relacionData['tipo_superior_id'] = $tipoSuperior['id'];
                            $relacionData['tipo_subordinado_id'] = $tipoSubordinado['id'];
                            unset($relacionData['tipo_superior_codigo']);
                            unset($relacionData['tipo_subordinado_codigo']);
                            
                            $this->insert($relacionData);
                            $resultado['importadas']++;
                        }
                    } else {
                        $resultado['errores'][] = 'No se encontraron los tipos: ' . 
                            $relacionData['tipo_superior_codigo'] . ' -> ' . 
                            $relacionData['tipo_subordinado_codigo'];
                    }
                } catch (\Exception $e) {
                    $resultado['errores'][] = 'Error al importar relación: ' . $e->getMessage();
                }
            }
            
            $db->transComplete();
            return $resultado;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al importar jerarquía: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas de jerarquía
     */
    public function obtenerEstadisticas()
    {
        $db = \Config\Database::connect();
        
        $estadisticas = [
            'total_relaciones' => $this->countAllResults(false),
            'relaciones_activas' => $this->where('activo', true)->countAllResults(false),
            'niveles_jerarquicos' => [],
            'tipos_con_subordinados' => [],
            'tipos_sin_superiores' => []
        ];
        
        // Niveles jerárquicos
        $query = $db->query("
            SELECT 
                nivel_jerarquico,
                COUNT(*) as total_relaciones
            FROM tbl_jerarquia_usuario
            WHERE deleted_at IS NULL
            GROUP BY nivel_jerarquico
            ORDER BY nivel_jerarquico
        ");
        $estadisticas['niveles_jerarquicos'] = $query->getResultArray();
        
        // Tipos con subordinados
        $query = $db->query("
            SELECT 
                t.nombre as tipo,
                t.codigo,
                COUNT(j.id) as total_subordinados
            FROM tbl_tipos_usuario t
            JOIN tbl_jerarquia_usuario j ON t.id = j.tipo_superior_id
            WHERE t.deleted_at IS NULL AND j.deleted_at IS NULL
            GROUP BY t.id, t.nombre, t.codigo
            ORDER BY total_subordinados DESC
            LIMIT 10
        ");
        $estadisticas['tipos_con_subordinados'] = $query->getResultArray();
        
        // Tipos sin superiores (raíz)
        $query = $db->query("
            SELECT 
                t.nombre as tipo,
                t.codigo
            FROM tbl_tipos_usuario t
            WHERE t.id NOT IN (
                SELECT DISTINCT tipo_subordinado_id 
                FROM tbl_jerarquia_usuario 
                WHERE deleted_at IS NULL
            )
            AND t.deleted_at IS NULL
            ORDER BY t.nombre
        ");
        $estadisticas['tipos_sin_superiores'] = $query->getResultArray();
        
        return $estadisticas;
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
        // Validaciones antes de eliminar
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
        // Validar que no sea el mismo tipo
        if (isset($data['data']['tipo_superior_id']) && isset($data['data']['tipo_subordinado_id'])) {
            if ($data['data']['tipo_superior_id'] == $data['data']['tipo_subordinado_id']) {
                throw new \Exception('Un tipo no puede ser superior de sí mismo');
            }
        }
        
        // Validar que los tipos existan
        if (isset($data['data']['tipo_superior_id'])) {
            $tiposModel = new \App\Models\TiposUsuarioModel();
            if (!$tiposModel->find($data['data']['tipo_superior_id'])) {
                throw new \Exception('El tipo superior especificado no existe');
            }
        }
        
        if (isset($data['data']['tipo_subordinado_id'])) {
            $tiposModel = new \App\Models\TiposUsuarioModel();
            if (!$tiposModel->find($data['data']['tipo_subordinado_id'])) {
                throw new \Exception('El tipo subordinado especificado no existe');
            }
        }
        
        return $data;
    }
    
    /**
     * Registrar auditoría
     */
    private function registrarAuditoria($accion, $relacionId, $datos)
    {
        try {
            $auditoriaModel = new AuditoriaModel();
            $auditoriaModel->registrar([
                'tabla' => 'tbl_jerarquia_usuario',
                'registro_id' => $relacionId,
                'accion' => $accion,
                'datos_anteriores' => $accion === 'actualizar' ? json_encode($this->find($relacionId)) : null,
                'datos_nuevos' => json_encode($datos),
                'usuario_id' => session('usuario_id'),
                'ip' => \Config\Services::request()->getIPAddress(),
                'user_agent' => \Config\Services::request()->getUserAgent()->getAgentString()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar auditoría: ' . $e->getMessage());
        }
    }
}