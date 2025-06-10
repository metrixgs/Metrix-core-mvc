<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Tipos de Campañas
class TiposCampanasModel extends Model {

    protected $table = 'tbl_tipos_campanas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id', 'nombre', 'descripcion', 'fecha_creacion'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerTiposCampanas() {
        return $this->findAll();
    }

    public function obtenerTiposCampana($id) {
        return $this->find($id);
    }

    public function crearTiposCampana($data) {
        return $this->insert($data);
    }

    public function actualizarTiposCampana($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarTiposCampana($id) {
        return $this->delete($id);
    }
}

