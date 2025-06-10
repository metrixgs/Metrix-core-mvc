<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {

        // Verificar si la sesión está activa
        if (!session()->has('session_data')) {
            return redirect()->to('/login');
        }

        // Obtener el rol_id desde la sesión
        $rolId = session('session_data.rol_id');

        // Verificar si el rol_id está en la lista de roles permitidos
        if (!in_array($rolId, $arguments)) {
            return redirect()->to('/unauthorized');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // No se usa
    } 
}
