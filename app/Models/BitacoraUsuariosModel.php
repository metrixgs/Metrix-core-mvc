<?php
namespace App\Models;

use CodeIgniter\Model;

class BitacoraUsuariosModel extends Model
{
    protected $table = 'tbl_bitacora_usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fecha_creacion', 'usuario_id', 'ip_address', 'modulo', 'accion', 'detalles'];

    public function obtenerBitacorasFiltradas($filtros = [])
    {
        $builder = $this->select('tbl_bitacora_usuarios.*, tbl_usuarios.nombre as nombre_usuario')
                      ->join('tbl_usuarios', 'tbl_usuarios.id = tbl_bitacora_usuarios.usuario_id');
        
        if (!empty($filtros['search'])) {
            $builder->groupStart()
                   ->like('tbl_usuarios.nombre', $filtros['search'])
                   ->orLike('tbl_bitacora_usuarios.accion', $filtros['search'])
                   ->orLike('tbl_bitacora_usuarios.detalles', $filtros['search'])
                   ->groupEnd();
        }
        
        if (!empty($filtros['type']) && $filtros['type'] != 'Todas') {
            $builder->where('tbl_bitacora_usuarios.accion', $filtros['type']);
        }
        
        if (!empty($filtros['user_id']) && $filtros['user_id'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.usuario_id', $filtros['user_id']);
        }
        
        return $builder->orderBy('fecha_creacion', 'DESC')->findAll();
    }

    public function obtenerUsuariosParaSelect()
    {
        return $this->db->table('tbl_usuarios')
                      ->select('id, nombre')
                      ->orderBy('nombre', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    public function obtenerTiposAccion()
    {
        return $this->db->table('tbl_bitacora_usuarios')
                      ->select('accion')
                      ->distinct()
                      ->orderBy('accion', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    public function contarRegistros($filtros = [])
    {
        $builder = $this->db->table('tbl_bitacora_usuarios')
                      ->join('tbl_usuarios', 'tbl_usuarios.id = tbl_bitacora_usuarios.usuario_id');
        
        if (!empty($filtros['search'])) {
            $builder->groupStart()
                   ->like('tbl_usuarios.nombre', $filtros['search'])
                   ->orLike('tbl_bitacora_usuarios.accion', $filtros['search'])
                   ->orLike('tbl_bitacora_usuarios.detalles', $filtros['search'])
                   ->groupEnd();
        }
        
        if (!empty($filtros['type']) && $filtros['type'] != 'Todas') {
            $builder->where('tbl_bitacora_usuarios.accion', $filtros['type']);
        }
        
        if (!empty($filtros['user_id']) && $filtros['user_id'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.usuario_id', $filtros['user_id']);
        }
        
        return $builder->countAllResults();
    }
}