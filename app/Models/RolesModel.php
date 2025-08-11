<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model {

    protected $table = 'tbl_roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre', 'categoria', 'descripcion'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerRoles() {
        return $this->findAll();
    }

    public function obtenerRol($id) {
        return $this->find($id);
    }

    public function obtenerRolPorNombre($nombre) {
        return $this->where('nombre', $nombre)->first();
    }

    public function crearRol($data) {
        $result = $this->insert($data);
        if ($result) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Roles', 'Crear', [
                'descripcion' => 'Rol creado',
                'nombre_rol' => $data['nombre'] ?? 'N/A',
                'rol_id' => $result,
                'accion' => 'Crear rol'
            ], 'info');
        }
        return $result;
    }

    public function actualizarRol($id, $data) {
        $rolAnterior = $this->find($id);
        $result = $this->update($id, $data);
        if ($result && $rolAnterior) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Roles', 'Actualizar', [
                'descripcion' => 'Rol actualizado',
                'nombre_rol' => $rolAnterior['nombre'] ?? 'ID: ' . $id,
                'rol_id' => $id,
                'accion' => 'Actualizar rol'
            ], 'info');
        }
        return $result;
    }

    public function eliminarRol($id) {
        $rol = $this->find($id);
        $result = $this->delete($id);
        if ($result && $rol) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Roles', 'Eliminar', [
                'descripcion' => 'Rol eliminado',
                'nombre_rol' => $rol['nombre'] ?? 'ID: ' . $id,
                'rol_id' => $id,
                'accion' => 'Eliminar rol'
            ], 'warning');
        }
        return $result;
    }
}