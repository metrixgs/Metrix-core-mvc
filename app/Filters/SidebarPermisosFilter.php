<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Helpers\SidebarPermisosHelper;

/**
 * Filtro para verificar permisos del sidebar
 * 
 * Este filtro verifica si el usuario tiene permisos para acceder
 * a los módulos del sidebar antes de permitir el acceso a las rutas.
 */
class SidebarPermisosFilter implements FilterInterface
{
    /**
     * Verificar permisos antes de ejecutar el controlador
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Verificar si el usuario está autenticado
        if (!$session->get('session_data.usuario_id')) {
            return redirect()->to(base_url('login'))
                           ->with('error', 'Debe iniciar sesión para acceder a esta página');
        }
        
        $usuarioId = $session->get('session_data.usuario_id');
        
        // Si no se especifica módulo en los argumentos, permitir acceso
        if (empty($arguments)) {
            return;
        }
        
        $modulo = $arguments[0] ?? null;
        
        if (!$modulo) {
            return;
        }
        
        // Verificar permiso específico del módulo
        if (!SidebarPermisosHelper::tieneAcceso($usuarioId, $modulo)) {
            // Registrar intento de acceso no autorizado
            SidebarPermisosHelper::registrarActividad(
                $usuarioId,
                'ACCESO_DENEGADO',
                "Intento de acceso al módulo '{$modulo}' sin permisos"
            );
            
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'success' => false,
                        'message' => 'No tiene permisos para acceder a este módulo',
                        'redirect' => base_url('dashboard')
                    ])
                    ->setStatusCode(403);
            }
            
            // Redireccionar con mensaje de error
            return redirect()->to(base_url('dashboard'))
                           ->with('error', 'No tiene permisos para acceder a este módulo del sistema');
        }
        
        // Registrar acceso exitoso
        SidebarPermisosHelper::registrarActividad(
            $usuarioId,
            'ACCESO_MODULO',
            "Acceso al módulo '{$modulo}'"
        );
    }
    
    /**
     * Ejecutar después del controlador (no se usa en este filtro)
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere procesamiento posterior
    }
}