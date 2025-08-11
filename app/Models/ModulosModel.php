<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Modulos
class ModulosModel extends Model {

    
    protected $table = 'tbl_modulos';
    protected $allowedFields = ['nombre', 'ruta', 'icono', 'orden', 'padre_id'];
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    // protected $allowedFields = [
    //     'nombre', 'fecha_creacion'
    // ];
    protected $useTimestamps = false;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';
    protected $deletedField = 'fecha_eliminacion';

    public function getMenuStructure($rol_id) {
        // Obtener todos los usuarios con este rol
        $usuariosModel = new \App\Models\UsuariosModel();
        $usuarios = $usuariosModel->where('rol_id', $rol_id)->findAll();
        
        if (empty($usuarios)) {
            return [];
        }
        
        // Extraer los IDs de usuarios
        $usuario_ids = array_column($usuarios, 'id');
        
        // Obtener módulos con permisos para estos usuarios
        return $this->select('tbl_modulos.*')
            ->join('tbl_permisos', 'tbl_permisos.modulo_id = tbl_modulos.id')
            ->whereIn('tbl_permisos.usuario_id', $usuario_ids)
            ->where('tbl_permisos.lectura', 1)
            ->orderBy('orden', 'asc')
            ->findAll();
    }

    public function obtenerModulos() {
        return $this->findAll();
    }

    public function obtenerModulo($id) {
        return $this->find($id);
    }

    public function crearModulo($data) {
        return $this->insert($data);
    }

    public function actualizarModulo($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarModulo($id) {
        return $this->delete($id);
    }
}

