<?php
namespace App\Models;

use CodeIgniter\Model;

class BitacoraUsuariosModel extends Model
{
    protected $table = 'tbl_bitacora_usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fecha_creacion', 'usuario_id', 'ip_address', 'modulo', 'accion', 'detalles'];

    /**
     * Devuelve un Query Builder para aplicar paginate() en el controlador.
     */
    public function obtenerBitacorasFiltradas($filtros = [])
    {
        $builder = $this->select('tbl_bitacora_usuarios.*, tbl_usuarios.nombre as nombre_usuario')
                        ->join('tbl_usuarios', 'tbl_usuarios.id = tbl_bitacora_usuarios.usuario_id');

        if (!empty($filtros['search'])) {
            $builder->groupStart()
                ->like('tbl_usuarios.nombre', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.accion', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.detalles', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.modulo', $filtros['search'])
                ->groupEnd();
        }

        if (!empty($filtros['type']) && $filtros['type'] != 'Todas') {
            $builder->where('tbl_bitacora_usuarios.accion', $filtros['type']);
        }

        if (!empty($filtros['user_id']) && $filtros['user_id'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.usuario_id', $filtros['user_id']);
        }

        if (!empty($filtros['module']) && $filtros['module'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.modulo', $filtros['module']);
        }

        if (!empty($filtros['start_date'])) {
            $builder->where('DATE(tbl_bitacora_usuarios.fecha_creacion) >=', $filtros['start_date']);
        }
        if (!empty($filtros['end_date'])) {
            $builder->where('DATE(tbl_bitacora_usuarios.fecha_creacion) <=', $filtros['end_date']);
        }

        return $builder->orderBy('fecha_creacion', 'DESC');
    }

    public function contarRegistros($filtros = [])
    {
        $builder = $this->builder()
            ->select('tbl_bitacora_usuarios.id')
            ->join('tbl_usuarios', 'tbl_usuarios.id = tbl_bitacora_usuarios.usuario_id');

        if (!empty($filtros['search'])) {
            $builder->groupStart()
                ->like('tbl_usuarios.nombre', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.accion', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.detalles', $filtros['search'])
                ->orLike('tbl_bitacora_usuarios.modulo', $filtros['search'])
                ->groupEnd();
        }

        if (!empty($filtros['type']) && $filtros['type'] != 'Todas') {
            $builder->where('tbl_bitacora_usuarios.accion', $filtros['type']);
        }

        if (!empty($filtros['user_id']) && $filtros['user_id'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.usuario_id', $filtros['user_id']);
        }

        if (!empty($filtros['module']) && $filtros['module'] != 'Todos') {
            $builder->where('tbl_bitacora_usuarios.modulo', $filtros['module']);
        }

        if (!empty($filtros['start_date'])) {
            $builder->where('DATE(tbl_bitacora_usuarios.fecha_creacion) >=', $filtros['start_date']);
        }
        if (!empty($filtros['end_date'])) {
            $builder->where('DATE(tbl_bitacora_usuarios.fecha_creacion) <=', $filtros['end_date']);
        }

        return $builder->countAllResults();
    }

    public function obtenerModulos()
    {
        return $this->db->table('tbl_bitacora_usuarios')
                        ->select('modulo')
                        ->distinct()
                        ->orderBy('modulo', 'ASC')
                        ->get()
                        ->getResultArray();
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
}
