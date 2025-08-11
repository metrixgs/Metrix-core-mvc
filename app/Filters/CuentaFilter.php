<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CuentaFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {

        // Verificar si la sesión está activa
        if (!session()->has('session_data')) {
            return redirect()->to('/login');
        }

        // Obtener el cuenta_id desde la sesión
        $cuentaId = session('session_data.cuenta_id');
        
        // Si el usuario es Máster (rol_id = 3), permitir acceso sin restricción de cuenta
        if (session('session_data.rol_id') == 3) {
            return;
        }
        
        // Verificar si el cuenta_id está definido
        if (!$cuentaId) {
            return redirect()->to('/unauthorized');
        }

        // Si hay argumentos (cuentas específicas permitidas), verificar si la cuenta actual está en la lista
        if ($arguments && !in_array($cuentaId, $arguments)) {
            return redirect()->to('/unauthorized');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // No se usa
    } 
}