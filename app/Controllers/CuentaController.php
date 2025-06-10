<?php

namespace App\Controllers;

use App\Models\CuentasModel;
use App\Models\UsuariosModel;

class CuentaController extends BaseController
{
    public function create()
    {
        // Solo usuarios con rol Master pueden acceder
        if (session('session_data.rol_id') != 1) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $data['titulo_pagina'] = 'Metrix | Crear cuenta nueva';

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('cuentas/crear', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function store()
    {
        // Solo usuarios Master pueden crear cuentas
        if (session('session_data.rol_id') != 1) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $cuentasModel = new CuentasModel();
        $usuariosModel = new UsuariosModel();

        // Validar que el correo no exista
        $correo = $this->request->getPost('admin_email');
        if ($usuariosModel->obtenerUsuarioPorCorreo($correo)) {
            return redirect()->back()->withInput()->with('message', 'El correo ya está registrado.');
        }

        // Crear la cuenta
        $cuentaId = $cuentasModel->insert([
            'nombre' => $this->request->getPost('nombre'),
        ]);

        if (!$cuentaId) {
            return redirect()->back()->withInput()->with('message', 'Error al crear la cuenta.');
        }

        // Crear el usuario administrador para esa cuenta
        $adminCreado = $usuariosModel->insert([
            'area_id'        => null,
            'cargo'          => 'Administrador',
            'nombre'         => $this->request->getPost('admin_nombre'),
            'correo'         => $correo,
            'telefono'       => null,
            'contrasena'     => password_hash($this->request->getPost('admin_password'), PASSWORD_DEFAULT),
            'rol_id'         => 2, // ID del rol 'Administrador'
            'fecha_registro' => date('Y-m-d H:i:s'),
            'creado_por_id'  => session('session_data.id'),
            'cuenta_id'      => $cuentaId,
            'created_by'     => 'sistema'
        ]);

        if (!$adminCreado) {
            return redirect()->back()->withInput()->with('message', 'Error al crear el usuario administrador.');
        }

        return redirect()->to('/cuentas/create')->with('message', 'Cuenta y administrador creados correctamente.');
    }
}
