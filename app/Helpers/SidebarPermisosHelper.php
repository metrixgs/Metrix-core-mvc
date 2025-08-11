<?php

namespace App\Helpers;

use App\Models\PermisosModel;
use CodeIgniter\Database\ConnectionInterface;

/**
 * Helper para gestión de permisos del sidebar
 * 
 * Esta clase proporciona métodos estáticos para verificar permisos
 * del sidebar de manera eficiente en toda la aplicación.
 */
class SidebarPermisosHelper
{
    /**
     * Cache de permisos para evitar consultas repetitivas
     * @var array
     */
    private static $cachePermisos = [];
    
    /**
     * Tiempo de vida del cache en segundos (5 minutos)
     * @var int
     */
    private static $tiempoCache = 300;
    
    /**
     * Instancia del modelo de permisos
     * @var PermisosModel
     */
    private static $permisosModel = null;
    
    /**
     * Verificar si un usuario tiene acceso a un módulo del sidebar
     * 
     * @param int $usuarioId ID del usuario
     * @param string $modulo Nombre del módulo
     * @return bool
     */
    public static function tieneAcceso(int $usuarioId, string $modulo): bool
    {
        try {
            // Verificar cache
            $cacheKey = "usuario_{$usuarioId}_modulo_{$modulo}";
            
            if (isset(self::$cachePermisos[$cacheKey])) {
                $cacheData = self::$cachePermisos[$cacheKey];
                
                // Verificar si el cache no ha expirado
                if (time() - $cacheData['timestamp'] < self::$tiempoCache) {
                    return $cacheData['valor'];
                }
            }
            
            // CORREGIDO: Consultar directamente tbl_sidebar_permisos
            $db = \Config\Database::connect();
            
            $query = $db->query("
                SELECT sp.activo 
                FROM tbl_usuarios u 
                INNER JOIN tbl_sidebar_permisos sp ON u.rol_id = sp.rol_id 
                WHERE u.id = ? AND sp.modulo_nombre = ? AND u.activo = 1
            ", [$usuarioId, $modulo]);
            
            $resultado = $query->getRow();
            $tienePermiso = $resultado ? (bool)$resultado->activo : false;
            
            // Guardar en cache
            self::$cachePermisos[$cacheKey] = [
                'valor' => $tienePermiso,
                'timestamp' => time()
            ];
            
            return $tienePermiso;
            
        } catch (\Exception $e) {
            // En caso de error, denegar acceso por seguridad
            log_message('error', 'Error verificando permiso sidebar: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todos los permisos de un usuario para el sidebar
     * 
     * @param int $usuarioId ID del usuario
     * @return array Array con módulos como keys y boolean como values
     */
    public static function obtenerPermisosUsuario(int $usuarioId): array
    {
        try {
            $cacheKey = "permisos_usuario_{$usuarioId}";
            
            // Verificar cache
            if (isset(self::$cachePermisos[$cacheKey])) {
                $cacheData = self::$cachePermisos[$cacheKey];
                
                if (time() - $cacheData['timestamp'] < self::$tiempoCache) {
                    return $cacheData['valor'];
                }
            }
            
            // Obtener permisos desde la base de datos
            $permisosModel = self::getPermisosModel();
            $permisos = $permisosModel->obtenerPermisosUsuario($usuarioId);
            
            // Formatear permisos
            $permisosFormateados = [];
            foreach ($permisos as $permiso) {
                $permisosFormateados[$permiso['modulo_nombre']] = (bool)$permiso['activo'];
            }
            
            // Guardar en cache
            self::$cachePermisos[$cacheKey] = [
                'valor' => $permisosFormateados,
                'timestamp' => time()
            ];
            
            return $permisosFormateados;
            
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo permisos usuario: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Filtrar elementos del sidebar según permisos del usuario
     * 
     * @param array $elementosSidebar Array de elementos del sidebar
     * @param int $usuarioId ID del usuario
     * @return array Array filtrado de elementos
     */
    public static function filtrarSidebar(array $elementosSidebar, int $usuarioId): array
    {
        $permisos = self::obtenerPermisosUsuario($usuarioId);
        $elementosFiltrados = [];
        
        foreach ($elementosSidebar as $elemento) {
            // Si el elemento tiene un módulo definido, verificar permiso
            if (isset($elemento['modulo'])) {
                if (isset($permisos[$elemento['modulo']]) && $permisos[$elemento['modulo']]) {
                    $elementosFiltrados[] = $elemento;
                }
            } else {
                // Si no tiene módulo definido, incluir por defecto
                $elementosFiltrados[] = $elemento;
            }
        }
        
        return $elementosFiltrados;
    }
    
    /**
     * Generar estructura del sidebar para un usuario específico
     * 
     * @param int $usuarioId ID del usuario
     * @return array Estructura del sidebar filtrada
     */
    public static function generarSidebarUsuario(int $usuarioId): array
    {
        $permisos = self::obtenerPermisosUsuario($usuarioId);
        
        // Estructura base del sidebar
        $sidebar = [
            [
                'titulo' => 'Dashboard',
                'icono' => 'fas fa-tachometer-alt',
                'url' => base_url('dashboard'),
                'modulo' => 'dashboard',
                'activo' => true
            ],
            [
                'titulo' => 'Operación',
                'icono' => 'fas fa-cogs',
                'modulo' => 'operacion',
                'submenu' => [
                    [
                        'titulo' => 'Dependencias',
                        'url' => base_url('dependencias'),
                        'modulo' => 'dependencias'
                    ],
                    [
                        'titulo' => 'Clientes',
                        'url' => base_url('clientes'),
                        'modulo' => 'clientes'
                    ]
                ]
            ],
            [
                'titulo' => 'Reportes',
                'icono' => 'fas fa-chart-bar',
                'url' => base_url('reportes'),
                'modulo' => 'reportes'
            ],
            [
                'titulo' => 'Tareas',
                'icono' => 'fas fa-tasks',
                'url' => base_url('tareas'),
                'modulo' => 'tareas'
            ],
            [
                'titulo' => 'Notificaciones',
                'icono' => 'fas fa-bell',
                'url' => base_url('notificaciones'),
                'modulo' => 'notificaciones_menu'
            ],
            [
                'titulo' => 'Módulos',
                'icono' => 'fas fa-th-large',
                'url' => base_url('modulos'),
                'modulo' => 'modulos'
            ],
            [
                'titulo' => 'Mapa',
                'icono' => 'fas fa-map',
                'url' => base_url('mapa'),
                'modulo' => 'mapa'
            ],
            [
                'titulo' => 'Encuestas',
                'icono' => 'fas fa-poll',
                'url' => base_url('encuestas'),
                'modulo' => 'encuestas'
            ],
            [
                'titulo' => 'Usuarios',
                'icono' => 'fas fa-users',
                'url' => base_url('usuarios'),
                'modulo' => 'usuarios'
            ],
            [
                'titulo' => 'Cuentas',
                'icono' => 'fas fa-user-circle',
                'url' => base_url('cuentas'),
                'modulo' => 'cuentas'
            ],
            [
                'titulo' => 'Directorio',
                'icono' => 'fas fa-address-book',
                'url' => base_url('directorio'),
                'modulo' => 'directorio'
            ],
            [
                'titulo' => 'Campañas',
                'icono' => 'fas fa-bullhorn',
                'modulo' => 'campanas',
                'submenu' => [
                    [
                        'titulo' => 'Gestión de Campañas',
                        'url' => base_url('campanas'),
                        'modulo' => 'campanas'
                    ],
                    [
                        'titulo' => 'Rondas',
                        'url' => base_url('campanas-rondas'),
                        'modulo' => 'campanas_rondas'
                    ]
                ]
            ],
            [
                'titulo' => 'Incidencias',
                'icono' => 'fas fa-exclamation-triangle',
                'url' => base_url('incidencias'),
                'modulo' => 'incidencias'
            ],
            [
                'titulo' => 'Soporte',
                'icono' => 'fas fa-life-ring',
                'modulo' => 'soporte_menu',
                'submenu' => [
                    [
                        'titulo' => 'Tickets',
                        'url' => base_url('soporte'),
                        'modulo' => 'soporte'
                    ],
                    [
                        'titulo' => 'Nuevo Ticket',
                        'url' => base_url('soporte/nuevo'),
                        'modulo' => 'soporte_nuevo'
                    ],
                    [
                        'titulo' => 'Conversaciones',
                        'url' => base_url('soporte/conversaciones'),
                        'modulo' => 'soporte_conversaciones'
                    ],
                    [
                        'titulo' => 'Configuración',
                        'url' => base_url('ajustes-soporte'),
                        'modulo' => 'ajustes_soporte'
                    ]
                ]
            ],
            [
                'titulo' => 'Configuración',
                'icono' => 'fas fa-cog',
                'modulo' => 'configuracion',
                'submenu' => [
                    [
                        'titulo' => 'General',
                        'url' => base_url('configuracion'),
                        'modulo' => 'configuracion'
                    ],
                    [
                        'titulo' => 'SMTP',
                        'url' => base_url('smtp'),
                        'modulo' => 'smtp'
                    ],
                    [
                        'titulo' => 'TDR',
                        'url' => base_url('tdr'),
                        'modulo' => 'tdr'
                    ],
                    [
                        'titulo' => 'Permisos Sidebar',
                        'url' => base_url('sidebar-permisos'),
                        'modulo' => 'permisos_sidebar'
                    ]
                ]
            ],
            [
                'titulo' => 'Perfil',
                'icono' => 'fas fa-user',
                'url' => base_url('perfil'),
                'modulo' => 'perfil'
            ]
        ];
        
        // Filtrar sidebar según permisos
        return self::filtrarElementosRecursivo($sidebar, $permisos);
    }
    
    /**
     * Filtrar elementos del sidebar de manera recursiva
     * 
     * @param array $elementos Array de elementos
     * @param array $permisos Array de permisos del usuario
     * @return array Array filtrado
     */
    private static function filtrarElementosRecursivo(array $elementos, array $permisos): array
    {
        $elementosFiltrados = [];
        
        foreach ($elementos as $elemento) {
            $incluirElemento = true;
            
            // Verificar permiso del elemento principal
            if (isset($elemento['modulo'])) {
                $incluirElemento = isset($permisos[$elemento['modulo']]) && $permisos[$elemento['modulo']];
            }
            
            // Si el elemento tiene submenu, filtrar recursivamente
            if (isset($elemento['submenu']) && is_array($elemento['submenu'])) {
                $submenuFiltrado = self::filtrarElementosRecursivo($elemento['submenu'], $permisos);
                
                // Si el submenu filtrado no está vacío, incluir el elemento
                if (!empty($submenuFiltrado)) {
                    $elemento['submenu'] = $submenuFiltrado;
                    $incluirElemento = true;
                } else {
                    // Si el submenu está vacío y el elemento principal no tiene permiso, excluir
                    if (isset($elemento['modulo']) && (!isset($permisos[$elemento['modulo']]) || !$permisos[$elemento['modulo']])) {
                        $incluirElemento = false;
                    }
                }
            }
            
            if ($incluirElemento) {
                $elementosFiltrados[] = $elemento;
            }
        }
        
        return $elementosFiltrados;
    }
    
    /**
     * Limpiar cache de permisos
     * 
     * @param int|null $usuarioId ID específico del usuario o null para limpiar todo
     */
    public static function limpiarCache(?int $usuarioId = null): void
    {
        if ($usuarioId !== null) {
            // Limpiar cache específico del usuario
            $keys = array_keys(self::$cachePermisos);
            foreach ($keys as $key) {
                if (strpos($key, "usuario_{$usuarioId}_") === 0 || $key === "permisos_usuario_{$usuarioId}") {
                    unset(self::$cachePermisos[$key]);
                }
            }
        } else {
            // Limpiar todo el cache
            self::$cachePermisos = [];
        }
    }
    
    /**
     * Verificar si un usuario es administrador del sistema
     * 
     * @param int $usuarioId ID del usuario
     * @return bool
     */
    public static function esAdministrador(int $usuarioId): bool
    {
        try {
            $db = \Config\Database::connect();
            
            $query = $db->query("
                SELECT r.nombre, r.nivel 
                FROM tbl_usuarios u 
                INNER JOIN tbl_roles r ON u.rol_id = r.id 
                WHERE u.id = ? AND u.activo = 1
            ", [$usuarioId]);
            
            $resultado = $query->getRow();
            
            if ($resultado) {
                $rolNombre = strtoupper($resultado->nombre);
                // Verificar por nombre de rol o por nivel (1 y 2 son administrativos)
                return in_array($rolNombre, ['MASTER', 'ADMIN', 'ADMINISTRADOR']) || 
                       $resultado->nivel <= 2;
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', 'Error verificando si es administrador: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas de permisos del sistema
     * 
     * @return array
     */
    public static function obtenerEstadisticas(): array
    {
        try {
            $db = \Config\Database::connect();
            
            // Total de roles activos
            $totalRoles = $db->query("SELECT COUNT(*) as total FROM tbl_roles")->getRow()->total;
            
            // Total de módulos disponibles
            $permisosModel = self::getPermisosModel();
            $modulos = $permisosModel->obtenerModulosSidebar();
            $totalModulos = count($modulos);
            
            // Total de permisos configurados
            $totalPermisos = $db->query("SELECT COUNT(*) as total FROM tbl_sidebar_permisos")->getRow()->total;
            
            // Permisos activos
            $permisosActivos = $db->query("SELECT COUNT(*) as total FROM tbl_sidebar_permisos WHERE activo = 1")->getRow()->total;
            
            // Usuarios activos
            $usuariosActivos = $db->query("SELECT COUNT(*) as total FROM tbl_usuarios WHERE activo = 1")->getRow()->total;
            
            return [
                'total_roles' => $totalRoles,
                'total_modulos' => $totalModulos,
                'total_permisos' => $totalPermisos,
                'permisos_activos' => $permisosActivos,
                'usuarios_activos' => $usuariosActivos,
                'porcentaje_activos' => $totalPermisos > 0 ? round(($permisosActivos / $totalPermisos) * 100, 2) : 0
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo estadísticas: ' . $e->getMessage());
            return [
                'total_roles' => 0,
                'total_modulos' => 0,
                'total_permisos' => 0,
                'permisos_activos' => 0,
                'usuarios_activos' => 0,
                'porcentaje_activos' => 0
            ];
        }
    }
    
    /**
     * Obtener instancia del modelo de permisos
     * 
     * @return PermisosModel
     */
    private static function getPermisosModel(): PermisosModel
    {
        if (self::$permisosModel === null) {
            self::$permisosModel = new PermisosModel();
        }
        
        return self::$permisosModel;
    }
    
    /**
     * Registrar actividad de permisos en el log
     * 
     * @param int $usuarioId ID del usuario que realiza la acción
     * @param string $accion Acción realizada
     * @param string $detalle Detalle de la acción
     */
    public static function registrarActividad(int $usuarioId, string $accion, string $detalle): void
    {
        try {
            $db = \Config\Database::connect();
            
            $datos = [
                'usuario_id' => $usuarioId,
                'modulo' => 'sidebar_permisos',
                'accion' => $accion,
                'detalles' => $detalle,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];
            
            // Intentar insertar en tabla de actividad si existe
            $db->table('tbl_actividad_usuarios')->insert($datos);
            
        } catch (\Exception $e) {
            // Si no existe la tabla o hay error, solo registrar en log
            log_message('info', "Actividad permisos - Usuario: {$usuarioId}, Acción: {$accion}, Detalle: {$detalle}");
        }
    }
}

/**
 * Funciones helper globales para usar en las vistas
 */

if (!function_exists('tiene_acceso_sidebar')) {
    /**
     * Verificar si el usuario actual tiene acceso a un módulo del sidebar
     * 
     * @param string $modulo Nombre del módulo
     * @return bool
     */
    function tiene_acceso_sidebar(string $modulo): bool
    {
        $session = session();
        $usuarioId = $session->get('session_data.usuario_id');
        
        if (!$usuarioId) {
            return false;
        }
        
        return \App\Helpers\SidebarPermisosHelper::tieneAcceso($usuarioId, $modulo);
    }
}

if (!function_exists('generar_sidebar_usuario')) {
    /**
     * Generar sidebar para el usuario actual
     * 
     * @return array
     */
    function generar_sidebar_usuario(): array
    {
        $session = session();
        $usuarioId = $session->get('session_data.usuario_id');
        
        if (!$usuarioId) {
            return [];
        }
        
        return \App\Helpers\SidebarPermisosHelper::generarSidebarUsuario($usuarioId);
    }
}

if (!function_exists('es_administrador_sistema')) {
    /**
     * Verificar si el usuario actual es administrador del sistema
     * 
     * @return bool
     */
    function es_administrador_sistema(): bool
    {
        $session = session();
        $usuarioId = $session->get('session_data.usuario_id');
        
        if (!$usuarioId) {
            return false;
        }
        
        return \App\Helpers\SidebarPermisosHelper::esAdministrador($usuarioId);
    }
}