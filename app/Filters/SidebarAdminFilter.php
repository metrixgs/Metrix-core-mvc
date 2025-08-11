<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Helpers\SidebarPermisosHelper;

/**
 * Filtro específico para administradores del sidebar
 * 
 * Este filtro verifica que solo usuarios con permisos de administración
 * puedan acceder a la gestión de permisos del sidebar.
 */
class SidebarAdminFilter implements FilterInterface
{
    /**
     * Verificar permisos de administración antes de ejecutar el controlador
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
        
        // Verificar si es administrador del sistema
        if (!SidebarPermisosHelper::esAdministrador($usuarioId)) {
            // Registrar intento de acceso no autorizado
            SidebarPermisosHelper::registrarActividad(
                $usuarioId,
                'ACCESO_ADMIN_DENEGADO',
                'Intento de acceso a administración de permisos del sidebar sin privilegios'
            );
            
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'success' => false,
                        'message' => 'Solo los administradores pueden acceder a esta funcionalidad',
                        'redirect' => base_url('dashboard')
                    ])
                    ->setStatusCode(403);
            }
            
            // Redireccionar con mensaje de error
            return redirect()->to(base_url('dashboard'))
                           ->with('error', 'Solo los administradores pueden gestionar los permisos del sidebar');
        }
        
        // Verificar permiso específico para gestión de permisos del sidebar
        if (!SidebarPermisosHelper::tieneAcceso($usuarioId, 'permisos_sidebar')) {
            // Registrar intento de acceso
            SidebarPermisosHelper::registrarActividad(
                $usuarioId,
                'ACCESO_PERMISOS_DENEGADO',
                'Intento de acceso a gestión de permisos del sidebar sin el permiso específico'
            );
            
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'success' => false,
                        'message' => 'No tiene permisos para gestionar los permisos del sidebar',
                        'redirect' => base_url('dashboard')
                    ])
                    ->setStatusCode(403);
            }
            
            // Redireccionar con mensaje de error
            return redirect()->to(base_url('dashboard'))
                           ->with('error', 'No tiene permisos para gestionar los permisos del sidebar');
        }
        
        // Registrar acceso exitoso a administración
        SidebarPermisosHelper::registrarActividad(
            $usuarioId,
            'ACCESO_ADMIN_PERMISOS',
            'Acceso a administración de permisos del sidebar'
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