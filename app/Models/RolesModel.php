<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model {

    protected $table = 'tbl_roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre', 'nivel'];
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
        return $this->insert($data);
    }

    public function actualizarRol($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarRol($id) {
        return $this->delete($id);
    }
}