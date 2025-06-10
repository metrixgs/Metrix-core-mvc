<?php
namespace App\Models;
use CodeIgniter\Model;

// Modelo para la tabla de relación Ronda-Segmentación
class RondasSegmentacionesModel extends Model {
    protected $table = 'tbl_ronda_segmentacion';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'ronda_id', 'segmentacion_id'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerRelaciones() {
        return $this->findAll();
    }
    
    public function obtenerRelacionesPorRonda($ronda_id) {
        return $this->where('ronda_id', $ronda_id)->findAll();
    }
    
    public function obtenerRelacionesPorSegmentacion($segmentacion_id) {
        return $this->where('segmentacion_id', $segmentacion_id)->findAll();
    }
    
    public function crearRelacion($data) {
        return $this->insert($data);
    }
    
    public function eliminarRelacionesPorRonda($ronda_id) {
        return $this->where('ronda_id', $ronda_id)->delete();
    }
    
    public function eliminarRelacionesPorSegmentacion($segmentacion_id) {
        return $this->where('segmentacion_id', $segmentacion_id)->delete();
    }
    
    public function verificarRelacion($ronda_id, $segmentacion_id) {
        return $this->where('ronda_id', $ronda_id)
                    ->where('segmentacion_id', $segmentacion_id)
                    ->countAllResults() > 0;
    }
}