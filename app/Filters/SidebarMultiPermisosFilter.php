<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Helpers\SidebarPermisosHelper;

/**
 * Filtro para verificar múltiples permisos del sidebar
 * 
 * Este filtro permite verificar si el usuario tiene acceso a cualquiera
 * de los módulos especificados en los argumentos.
 */
class SidebarMultiPermisosFilter implements FilterInterface
{
    /**
     * Verificar múltiples permisos antes de ejecutar el controlador
     *
     * @param RequestInterface $request
     * @param array|null $arguments Array de módulos a verificar
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
        
        // Si no se especifican módulos, permitir acceso
        if (empty($arguments)) {
            return;
        }
        
        $tieneAcceso = false;
        $modulosVerificados = [];
        
        // Verificar cada módulo especificado
        foreach ($arguments as $modulo) {
            $modulosVerificados[] = $modulo;
            
            if (SidebarPermisosHelper::tieneAcceso($usuarioId, $modulo)) {
                $tieneAcceso = true;
                break; // Con que tenga acceso a uno es suficiente
            }
        }
        
        if (!$tieneAcceso) {
            // Registrar intento de acceso no autorizado
            SidebarPermisosHelper::registrarActividad(
                $usuarioId,
                'ACCESO_MULTI_DENEGADO',
                "Intento de acceso sin permisos a ninguno de los módulos: " . implode(', ', $modulosVerificados)
            );
            
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON([
                        'success' => false,
                        'message' => 'No tiene permisos para acceder a esta funcionalidad',
                        'redirect' => base_url('dashboard')
                    ])
                    ->setStatusCode(403);
            }
            
            // Redireccionar con mensaje de error
            return redirect()->to(base_url('dashboard'))
                           ->with('error', 'No tiene permisos para acceder a esta funcionalidad del sistema');
        }
        
        // Registrar acceso exitoso
        SidebarPermisosHelper::registrarActividad(
            $usuarioId,
            'ACCESO_MULTI_EXITOSO',
            "Acceso exitoso a funcionalidad que requiere uno de los módulos: " . implode(', ', $modulosVerificados)
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