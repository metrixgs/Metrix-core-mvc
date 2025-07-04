<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Rondas
class RondasModel extends Model {

    protected $table = 'tbl_rondas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'campana_id', 'segmentacion_id', 'nombre', 'coordinador', 'encargado', 
        'fecha_actividad', 'hora_actividad', 'estado', 'ronda_id'
    ]; 

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function obtenerRondas() {
        return $this->findAll();
    }

    public function obtenerRonda($id) {
        return $this->find($id);
    }

    public function crearRonda($data) {
        return $this->insert($data);
    }

    public function actualizarRonda($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarRonda($id) {
        return $this->delete($id);
    }

    // ✅ Nuevo método para mostrar nombres en lugar de IDs
    public function obtenerRondasConUsuarios()
    {
        return $this->select('tbl_rondas.*, 
                              coord.nombre AS nombre_coordinador, 
                              encar.nombre AS nombre_encargado')
                    ->join('tbl_usuarios AS coord', 'coord.id = tbl_rondas.coordinador', 'left')
                    ->join('tbl_usuarios AS encar', 'encar.id = tbl_rondas.encargado', 'left')
                    ->findAll();
    }
}
