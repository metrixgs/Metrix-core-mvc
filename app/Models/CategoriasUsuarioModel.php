<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasUsuarioModel extends Model
{
    protected $table = 'tbl_categorias_usuario';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nombre', 'codigo', 'descripcion', 'color', 'icono', 'orden', 'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'codigo' => 'required|max_length[20]|is_unique[tbl_categorias_usuario.codigo]',
        'color' => 'permit_empty|max_length[7]',
        'icono' => 'permit_empty|max_length[50]',
        'orden' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la categoría es obligatorio',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'codigo' => [
            'required' => 'El código de la categoría es obligatorio',
            'max_length' => 'El código no puede exceder 20 caracteres',
            'is_unique' => 'El código ya existe'
        ]
    ];

    /**
     * Obtener todas las categorías activas con estadísticas
     */
    public function obtenerCategoriasConEstadisticas()
    {
        return $this->select('tbl_categorias_usuario.*, 
                             COUNT(tbl_tipos_usuario.id) as total_tipos,
                             SUM(CASE WHEN tbl_tipos_usuario.activo = 1 THEN 1 ELSE 0 END) as tipos_activos')
                    ->join('tbl_tipos_usuario', 'tbl_categorias_usuario.id = tbl_tipos_usuario.categoria_id', 'left')
                    ->where('tbl_categorias_usuario.activo', 1)
                    ->groupBy('tbl_categorias_usuario.id')
                    ->orderBy('tbl_categorias_usuario.orden', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener categoría con sus tipos de usuario
     */
    public function obtenerCategoriaConTipos($categoriaId)
    {
        $categoria = $this->find($categoriaId);
        
        if (!$categoria) {
            return null;
        }

        $tiposModel = new \App\Models\TiposUsuarioModel();
        $tipos = $tiposModel->obtenerTiposPorCategoria($categoriaId);
        
        $categoria['tipos'] = $tipos;
        
        return $categoria;
    }

    /**
     * Obtener estadísticas detalladas por categoría
     */
    public function obtenerEstadisticasDetalladas($cuentaId = null)
    {
        $builder = $this->db->table('tbl_categorias_usuario c')
                           ->select('c.id, c.nombre, c.codigo, c.color, c.icono,
                                   COUNT(DISTINCT r.id) as total_tipos,
                                   COUNT(DISTINCT u.id) as total_usuarios,
                                   SUM(CASE WHEN u.activo = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                                   SUM(CASE WHEN u.activo = 0 THEN 1 ELSE 0 END) as usuarios_inactivos')
                           ->join('tbl_tipos_usuario r', 'c.id = r.categoria_id', 'left')
                           ->join('tbl_usuarios u', 'r.id = u.tipo_usuario_id', 'left')
                           ->where('c.activo', 1);

        if ($cuentaId) {
            $builder->where('u.cuenta_id', $cuentaId);
        }

        return $builder->groupBy('c.id, c.nombre, c.codigo, c.color, c.icono')
                      ->orderBy('c.orden', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtener configuración de permisos por categoría
     */
    public function obtenerConfiguracionPermisos($categoriaId)
    {
        $categoria = $this->find($categoriaId);
        
        if (!$categoria) {
            return null;
        }

        // Obtener permisos específicos de la categoría desde la matriz
        $matrizPermisos = $this->obtenerMatrizPermisos();
        
        return $matrizPermisos[$categoria['codigo']] ?? [];
    }

    /**
     * Matriz de permisos por categoría según documentación
     */
    private function obtenerMatrizPermisos()
    {
        return [
            'METRIX' => [
                'sistema' => ['crear', 'editar', 'eliminar', 'ver', 'bitacora'],
                'usuarios' => ['crear', 'editar', 'eliminar', 'ver'],
                'cuentas' => ['crear', 'editar', 'eliminar', 'ver'],
                'configuracion' => ['crear', 'editar', 'eliminar', 'ver'],
                'reportes' => ['crear', 'editar', 'ver', 'exportar'],
                'bitacora' => ['ver', 'exportar', 'todas']
            ],
            'CLIENTE_SISTEMA' => [
                'usuarios' => ['crear', 'editar', 'ver'],
                'configuracion' => ['editar', 'ver'],
                'reportes' => ['ver', 'exportar'],
                'bitacora' => ['ver', 'cuenta']
            ],
            'CLIENTE_CAMPO' => [
                'captura' => ['crear', 'editar', 'ver'],
                'consulta' => ['ver'],
                'reportes' => ['ver'],
                'bitacora' => ['ver', 'propio']
            ],
            'OTROS' => [
                'consulta' => ['ver'],
                'reportes' => ['ver'],
                'bitacora' => ['ninguno']
            ],
            'LEGACY' => [
                'sistema' => ['migrar'],
                'usuarios' => ['migrar'],
                'bitacora' => ['ninguno']
            ]
        ];
    }

    /**
     * Validar permisos de categoría para un módulo
     */
    public function validarPermisoCategoria($categoriaId, $modulo, $accion)
    {
        $categoria = $this->find($categoriaId);
        
        if (!$categoria) {
            return false;
        }

        $permisos = $this->obtenerConfiguracionPermisos($categoriaId);
        
        return isset($permisos[$modulo]) && in_array($accion, $permisos[$modulo]);
    }

    /**
     * Obtener categorías disponibles para migración
     */
    public function obtenerCategoriasParaMigracion()
    {
        return $this->where('activo', 1)
                    ->where('codigo !=', 'LEGACY')
                    ->orderBy('orden', 'ASC')
                    ->findAll();
    }

    /**
     * Crear nueva categoría con configuración por defecto
     */
    public function crearCategoriaConDefaults($datos)
    {
        // Asignar orden automáticamente
        if (!isset($datos['orden'])) {
            $maxOrden = $this->selectMax('orden')->first();
            $datos['orden'] = ($maxOrden['orden'] ?? 0) + 1;
        }

        // Color por defecto
        if (!isset($datos['color'])) {
            $datos['color'] = '#6c757d';
        }

        // Icono por defecto
        if (!isset($datos['icono'])) {
            $datos['icono'] = 'fas fa-users';
        }

        return $this->insert($datos);
    }

    /**
     * Reordenar categorías
     */
    public function reordenarCategorias($ordenamiento)
    {
        $this->db->transStart();
        
        foreach ($ordenamiento as $orden => $categoriaId) {
            $this->update($categoriaId, ['orden' => $orden + 1]);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Activar/Desactivar categoría y sus tipos
     */
    public function cambiarEstadoCategoria($categoriaId, $activo)
    {
        $this->db->transStart();
        
        // Cambiar estado de la categoría
        $this->update($categoriaId, ['activo' => $activo]);
        
        // Cambiar estado de todos los tipos de la categoría
        $tiposModel = new \App\Models\TiposUsuarioModel();
        $tiposModel->where('categoria_id', $categoriaId)
                   ->set(['activo' => $activo])
                   ->update();
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Obtener resumen de migración legacy
     */
    public function obtenerResumenMigracion()
    {
        $usuariosModel = new \App\Models\UsuariosModel();
        
        // Usuarios en tipos legacy
        $usuariosLegacy = $usuariosModel->select('COUNT(*) as total')
                                       ->join('tbl_tipos_usuario', 'tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id')
                                       ->join('tbl_categorias_usuario', 'tbl_tipos_usuario.categoria_id = tbl_categorias_usuario.id')
                                       ->where('tbl_categorias_usuario.codigo', 'LEGACY')
                                       ->first();

        // Usuarios en nuevos tipos
        $usuariosNuevos = $usuariosModel->select('COUNT(*) as total')
                                       ->join('tbl_tipos_usuario', 'tbl_usuarios.tipo_usuario_id = tbl_tipos_usuario.id')
                                       ->join('tbl_categorias_usuario', 'tbl_tipos_usuario.categoria_id = tbl_categorias_usuario.id')
                                       ->where('tbl_categorias_usuario.codigo !=', 'LEGACY')
                                       ->first();

        return [
            'usuarios_legacy' => $usuariosLegacy['total'] ?? 0,
            'usuarios_nuevos' => $usuariosNuevos['total'] ?? 0,
            'porcentaje_migrado' => $this->calcularPorcentajeMigracion($usuariosLegacy['total'] ?? 0, $usuariosNuevos['total'] ?? 0)
        ];
    }

    /**
     * Calcular porcentaje de migración
     */
    private function calcularPorcentajeMigracion($legacy, $nuevos)
    {
        $total = $legacy + $nuevos;
        
        if ($total == 0) {
            return 0;
        }
        
        return round(($nuevos / $total) * 100, 2);
    }

    /**
     * Obtener configuración de interfaz por categoría
     */
    public function obtenerConfiguracionInterfaz($categoriaId)
    {
        $categoria = $this->find($categoriaId);
        
        if (!$categoria) {
            return null;
        }

        $configuraciones = [
            'METRIX' => [
                'dashboard' => 'admin_dashboard',
                'menu' => 'admin_menu',
                'tema' => 'admin_theme',
                'widgets' => ['estadisticas', 'graficos', 'alertas', 'actividad']
            ],
            'CLIENTE_SISTEMA' => [
                'dashboard' => 'cliente_dashboard',
                'menu' => 'cliente_menu',
                'tema' => 'cliente_theme',
                'widgets' => ['estadisticas', 'reportes', 'usuarios']
            ],
            'CLIENTE_CAMPO' => [
                'dashboard' => 'campo_dashboard',
                'menu' => 'campo_menu',
                'tema' => 'campo_theme',
                'widgets' => ['captura', 'consulta', 'reportes']
            ],
            'OTROS' => [
                'dashboard' => 'publico_dashboard',
                'menu' => 'publico_menu',
                'tema' => 'publico_theme',
                'widgets' => ['consulta']
            ],
            'LEGACY' => [
                'dashboard' => 'legacy_dashboard',
                'menu' => 'legacy_menu',
                'tema' => 'legacy_theme',
                'widgets' => ['migracion']
            ]
        ];

        return $configuraciones[$categoria['codigo']] ?? $configuraciones['OTROS'];
    }
}