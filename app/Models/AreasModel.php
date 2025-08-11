<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Areas
class AreasModel extends Model {

    protected $table = 'tbl_areas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nombre', 'descripcion'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualizacion';
    protected $deletedField = 'fecha_eliminacion';

    public function obtenerAreas() {
        return $this->findAll();
    }

    public function obtenerArea($id) {
        return $this->find($id);
    }

    public function crearArea($data) {
        $result = $this->insert($data);
        if ($result) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Areas', 'Crear', [
                'descripcion' => 'Área creada',
                'nombre_area' => $data['nombre'] ?? 'N/A',
                'area_id' => $result,
                'accion' => 'Crear área'
            ], 'info');
        }
        return $result;
    }

    public function actualizarArea($id, $data) {
        $areaAnterior = $this->find($id);
        $result = $this->update($id, $data);
        if ($result && $areaAnterior) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Areas', 'Actualizar', [
                'descripcion' => 'Área actualizada',
                'nombre_area' => $areaAnterior['nombre'] ?? 'ID: ' . $id,
                'area_id' => $id,
                'accion' => 'Actualizar área'
            ], 'info');
        }
        return $result;
    }

    public function eliminarArea($id) {
        $area = $this->find($id);
        $result = $this->delete($id);
        if ($result && $area) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            log_activity($usuario_id, 'Areas', 'Eliminar', [
                'descripcion' => 'Área eliminada',
                'nombre_area' => $area['nombre'] ?? 'ID: ' . $id,
                'area_id' => $id,
                'accion' => 'Eliminar área'
            ], 'warning');
        }
        return $result;
    }
}
