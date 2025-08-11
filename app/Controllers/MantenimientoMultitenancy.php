<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;

class MantenimientoMultitenancy extends BaseController
{
    protected $usuariosModel;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
    }

    /**
     * Ejecuta la corrección automática de cuenta_id para todos los usuarios
     * Este método puede ser llamado desde un cron job o manualmente
     */
    public function corregirCuentaId()
    {
        try {
            // Verificar que el usuario tenga permisos de administrador
            $rolActual = session('session_data.rol_id');
            if (!in_array($rolActual, [1, 2, 3, 4])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No tienes permisos para ejecutar esta acción'
                ]);
            }

            // Ejecutar la corrección
            $totalCorregidos = $this->usuariosModel->validarYCorregirCuentaId();

            // Preparar respuesta
            $mensaje = $totalCorregidos > 0 
                ? "Corrección completada: {$totalCorregidos} usuarios actualizados"
                : "No se encontraron usuarios que requieran corrección";

            return $this->response->setJSON([
                'success' => true,
                'message' => $mensaje,
                'usuarios_corregidos' => $totalCorregidos
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en corrección de cuenta_id: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtiene un reporte del estado actual de cuenta_id en el sistema
     */
    public function reporteEstado()
    {
        try {
            $db = \Config\Database::connect();
            
            // Obtener resumen por cuenta_id
            $resumen = $db->query("
                SELECT 
                    COALESCE(cuenta_id, 'NULL') as cuenta_id,
                    COUNT(*) as total_usuarios,
                    GROUP_CONCAT(DISTINCT rol_id ORDER BY rol_id) as roles
                FROM tbl_usuarios 
                GROUP BY cuenta_id 
                ORDER BY cuenta_id
            ")->getResultArray();

            // Obtener usuarios problemáticos
            $problemáticos = $db->query("
                SELECT id, nombre, correo, rol_id, cuenta_id
                FROM tbl_usuarios 
                WHERE (cuenta_id = 1 OR cuenta_id IS NULL) 
                AND rol_id NOT IN (1, 2, 3, 4)
                ORDER BY id
            ")->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'resumen' => $resumen,
                'usuarios_problematicos' => $problemáticos,
                'total_problematicos' => count($problemáticos)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en reporte de estado: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar reporte'
            ]);
        }
    }

    /**
     * Página de administración para el mantenimiento de multi-tenancy
     */
    public function index()
    {
        // Verificar permisos
        $rolActual = session('session_data.rol_id');
        if (!in_array($rolActual, [1, 2, 3, 4])) {
            return redirect()->to('panel')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $data['titulo_pagina'] = 'Mantenimiento Multi-tenancy';
        
        return view('admin/mantenimiento-multitenancy', $data);
    }
}