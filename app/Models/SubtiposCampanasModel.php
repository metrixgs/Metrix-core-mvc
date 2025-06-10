<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Subtipos de campañas
class SubtiposCampanasModel extends Model {

    protected $table = 'tbl_subtipos_campanas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'tipo_campana_id', 'nombre', 'descripcion', 'fecha_creacion'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerSubtiposCampanas() {
        return $this->db->table('tbl_subtipos_campanas')
                        ->select('tbl_subtipos_campanas.*, tbl_tipos_campanas.nombre as nombre_tipo_campana')
                        ->join('tbl_tipos_campanas', 'tbl_tipos_campanas.id = tbl_subtipos_campanas.tipo_campana_id')
                        ->get()
                        ->getResultArray();
    }

    public function obtenerSubtiposCampanasPorTipoCampana($tipo_campana) {
        return $this->where('tipo_campana_id', $tipo_campana)->findAll();
    }

    public function obtenerSubtiposCampana($id) {
        return $this->find($id);
    }

    public function crearSubtiposCampana($data) {
        return $this->insert($data);
    }

    public function actualizarSubtiposCampana($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarSubtiposCampana($id) {
        return $this->delete($id);
    }
}
