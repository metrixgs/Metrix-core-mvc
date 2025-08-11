<?php

namespace App\Controllers;

use App\Models\CuentasModel;
use App\Models\UsuariosModel;

class CuentaController extends BaseController
{
    public function index()
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden acceder
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $cuentasModel = new CuentasModel();
        $data['titulo_pagina'] = 'Metrix | Administrar Cuentas';
        $data['cuentas'] = $cuentasModel->findAll();

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('cuentas/index', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function create()
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden acceder
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
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
        // Solo usuarios Mega Admin (1) o Master (3) pueden crear cuentas
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
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
        $nombreCuenta = $this->request->getPost('nombre');
        $cuentaId = $cuentasModel->insert([
            'nombre' => $nombreCuenta,
        ]);

        if (!$cuentaId) {
            return redirect()->back()->withInput()->with('message', 'Error al crear la cuenta.');
        }
        
        // Registrar en bitácora
        helper('bitacora');
        $usuario_id = session('session_data.id') ?? 999;
        log_activity($usuario_id, 'Cuentas', 'Crear', [
            'descripcion' => 'Cuenta creada',
            'nombre_cuenta' => $nombreCuenta,
            'cuenta_id' => $cuentaId,
            'accion' => 'Crear cuenta'
        ], 'info');

        // Crear el usuario administrador para esa cuenta
        $adminCreado = $usuariosModel->insert([
            'area_id'        => null,
            'cargo'          => 'Administrador',
            'nombre'         => $this->request->getPost('admin_nombre'),
            'correo'         => $correo,
            'telefono'       => $this->request->getPost('admin_telefono'),
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

        return redirect()->to(base_url('cuentas/'))->with('message', 'Cuenta y administrador creados correctamente.');
    }

    public function edit($id)
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden acceder
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $cuentasModel = new CuentasModel();
        $cuenta = $cuentasModel->find($id);

        if (!$cuenta) {
            return redirect()->to(base_url('cuentas/'))->with('message', 'Cuenta no encontrada.');
        }

        $data['titulo_pagina'] = 'Metrix | Editar Cuenta';
        $data['cuenta'] = $cuenta;

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('cuentas/editar', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function update($id)
    {
        // Solo usuarios Mega Admin (1) o Master (3) pueden actualizar cuentas
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return redirect()->to('/')->with('message', 'Acceso no autorizado');
        }

        $cuentasModel = new CuentasModel();
        $cuenta = $cuentasModel->find($id);

        if (!$cuenta) {
            return redirect()->to(base_url('cuentas/'))->with('message', 'Cuenta no encontrada.');
        }

        // Validar que el nombre no exista en otra cuenta
        $nombre = $this->request->getPost('nombre');
        if ($cuentasModel->existeNombreCuenta($nombre, $id)) {
            return redirect()->back()->withInput()->with('message', 'Ya existe una cuenta con ese nombre.');
        }

        $data = [
            'nombre' => $nombre,
            'descripcion' => $this->request->getPost('descripcion')
        ];

        if ($cuentasModel->actualizarCuenta($id, $data)) {
            // Registrar en bitácora
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Cuentas', 'Actualizar', [
                'descripcion' => 'Cuenta actualizada',
                'nombre_cuenta' => $nombre,
                'cuenta_id' => $id,
                'accion' => 'Actualizar cuenta'
            ], 'info');
            return redirect()->to(base_url('cuentas/'))->with('message', 'Cuenta actualizada correctamente.');
        } else {
            return redirect()->back()->withInput()->with('message', 'Error al actualizar la cuenta.');
        }
    }

    public function delete($id)
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden eliminar cuentas
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $cuentasModel = new CuentasModel();
        $usuariosModel = new UsuariosModel();

        // Verificar si la cuenta tiene usuarios asociados
        $usuariosAsociados = $usuariosModel->where('cuenta_id', $id)->where('activo', 1)->countAllResults();
        
        if ($usuariosAsociados > 0) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No se puede eliminar la cuenta porque tiene usuarios activos asociados.'
            ]);
        }

        if ($cuentasModel->eliminarCuenta($id)) {
            // Registrar en bitácora
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Cuentas', 'Eliminar', [
                'descripcion' => 'Cuenta eliminada',
                'cuenta_id' => $id,
                'accion' => 'Eliminar cuenta'
            ], 'warning');
            return $this->response->setJSON(['success' => true, 'message' => 'Cuenta eliminada correctamente.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la cuenta.']);
        }
    }

    public function activate($id)
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden activar cuentas
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $cuentasModel = new CuentasModel();
        
        if ($cuentasModel->activarCuenta($id)) {
            // Registrar en bitácora
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Cuentas', 'Activar', [
                'descripcion' => 'Cuenta activada',
                'cuenta_id' => $id,
                'accion' => 'Activar cuenta'
            ], 'info');
            return $this->response->setJSON(['success' => true, 'message' => 'Cuenta activada correctamente.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al activar la cuenta.']);
        }
    }

    public function deactivate($id)
    {
        // Solo usuarios con rol Mega Admin (1) o Master (3) pueden desactivar cuentas
        if (!in_array(session('session_data.rol_id'), [1, 3])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $cuentasModel = new CuentasModel();
        $usuariosModel = new UsuariosModel();

        // Desactivar también todos los usuarios de la cuenta
        $usuariosModel->where('cuenta_id', $id)->set(['activo' => 0])->update();
        
        if ($cuentasModel->desactivarCuenta($id)) {
            // Registrar en bitácora
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Cuentas', 'Desactivar', [
                'descripcion' => 'Cuenta y usuarios desactivados',
                'cuenta_id' => $id,
                'accion' => 'Desactivar cuenta'
            ], 'warning');
            return $this->response->setJSON(['success' => true, 'message' => 'Cuenta y usuarios desactivados correctamente.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al desactivar la cuenta.']);
        }
    }
}
