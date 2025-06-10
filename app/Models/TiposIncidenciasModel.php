<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Tipos de Incidencias...
class TiposIncidenciasModel extends Model {

    protected $table = 'tbl_tipos_incidencias';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'titulo', 'fecha_creacion', 'estado'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerTiposIncidencias() {
        return $this->findAll();
    }

    public function obtenerTiposIncidencia($id) {
        return $this->find($id);
    }

    public function crearTiposIncidencia($data) {
        return $this->insert($data);
    }

    public function actualizarTiposIncidencia($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarTiposIncidencia($id) {
        return $this->delete($id);
    }
}

