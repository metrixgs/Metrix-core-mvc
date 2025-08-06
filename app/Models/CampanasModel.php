<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de las Campañas
class CampanasModel extends Model {

    protected $table = 'tbl_campanas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
    'tipo_id', 'subtipo_id', 'area_id', 'nombre',
    'coordinador', 'descripcion', 'fecha_inicio',
    'fecha_fin', 'estado',
    'encuesta',           
    'entregables',       
    'universo',            
    'territorio',          
    'territorio_subtype',   
    'sectorizacion'  
];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerCampanas($id = null) {
        $builder = $this->db->table('tbl_campanas')
                ->select('tbl_campanas.*, 
                                  tbl_tipos_campanas.nombre as nombre_tipo_campana,
                                  tbl_subtipos_campanas.nombre as nombre_subtipo_campana,
                                  tbl_areas.nombre as nombre_area')
                ->join('tbl_tipos_campanas', 'tbl_tipos_campanas.id = tbl_campanas.tipo_id', 'left')
                ->join('tbl_subtipos_campanas', 'tbl_subtipos_campanas.id = tbl_campanas.subtipo_id', 'left')
                ->join('tbl_areas', 'tbl_areas.id = tbl_campanas.area_id', 'left');

        // Si se proporciona un ID, filtrar por ese ID
        if ($id !== null) {
            $builder->where('tbl_campanas.id', $id);
            return $builder->get()->getRowArray();
        }

        // Ordenar por fecha de inicio descendente (más reciente primero)
        $builder->orderBy('tbl_campanas.id', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function obtenerCampana($id) {
        if (empty($id)) {
            return null;
        }

        return $this->db->table('tbl_campanas')
                        ->select('tbl_campanas.*, 
                              tbl_tipos_campanas.nombre as nombre_tipo_campana,
                              tbl_subtipos_campanas.nombre as nombre_subtipo_campana,
                              tbl_areas.nombre as nombre_area')
                        ->join('tbl_tipos_campanas', 'tbl_tipos_campanas.id = tbl_campanas.tipo_id', 'left')
                        ->join('tbl_subtipos_campanas', 'tbl_subtipos_campanas.id = tbl_campanas.subtipo_id', 'left')
                        ->join('tbl_areas', 'tbl_areas.id = tbl_campanas.area_id', 'left')
                        ->where('tbl_campanas.id', $id)
                        ->get()
                        ->getRowArray();
    }

    public function crearCampana($data) {
        return $this->insert($data);
    }

    public function actualizarCampana($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarCampana($id) {
        return $this->delete($id);
    }
}
