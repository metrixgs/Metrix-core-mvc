<?php

namespace App\Models;

use CodeIgniter\Model;

class CuentasModel extends Model {

    protected $table = 'tbl_cuentas'; // Ajusta el nombre de la tabla según tu estructura
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre', 'descripcion', 'activo']; // Campos permitidos para inserción/actualización
    protected $useTimestamps = true;
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

    public function obtenerCuentasActivas() {
        return $this->where('activo', 1)->findAll();
    }

    public function activarCuenta($id) {
        return $this->update($id, ['activo' => 1]);
    }

    public function desactivarCuenta($id) {
        return $this->update($id, ['activo' => 0]);
    }

    public function crearCuenta($data) {
        $data['activo'] = 1; // Por defecto activa
        return $this->insert($data);
    }

    public function actualizarCuenta($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarCuenta($id) {
        return $this->delete($id);
    }

    public function existeNombreCuenta($nombre, $excludeId = null) {
        $builder = $this->where('nombre', $nombre);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->first() !== null;
    }
}