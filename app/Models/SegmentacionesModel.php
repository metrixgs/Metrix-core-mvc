<?php
namespace App\Models;
use CodeIgniter\Model;

// Modelo para la tabla de Segmentaciones
class SegmentacionesModel extends Model {
    protected $table = 'tbl_segmentaciones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'codigo', 'descripcion', 'estado', 'fecha_creacion'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerSegmentaciones() {
        return $this->findAll();
    }
    
    public function obtenerSegmentacion($id) {
        return $this->find($id);
    }
    
    public function obtenerSegmentacionPorCodigo($codigo) {
        return $this->where('codigo', $codigo)->first();
    }
    
    public function crearSegmentacion($data) {
        return $this->insert($data);
    }
    
    public function actualizarSegmentacion($id, $data) {
        return $this->update($id, $data);
    }
    
    public function eliminarSegmentacion($id) {
        return $this->delete($id);
    }
}