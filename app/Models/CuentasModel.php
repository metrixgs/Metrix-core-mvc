<?php

namespace App\Models;

use CodeIgniter\Model;

class CuentasModel extends Model {

    protected $table = 'tbl_cuentas'; // Ajusta el nombre de la tabla según tu estructura
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre']; // Ajusta los campos permitidos según tu estructura
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerCuentas() {
        return $this->findAll();
    }

    public function obtenerCuenta($id) {
        return $this->find($id);
    }

    public function obtenerCuentaPorNombre($nombre) {
        return $this->where('nombre', $nombre)->first();
    }
}