<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de usuarios
class UsuariosModel extends Model {

    protected $table = 'tbl_usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'area_id', 'cargo', 'nombre', 'correo', 'telefono', 'contrasena', 
        'rol_id', 'fecha_registro', 'creado_por_id', 'cuenta_id', 'created_by'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

  public function obtenerUsuarios($cuenta_id_actual) {
    return $this->join('tbl_areas', 'tbl_areas.id = tbl_usuarios.area_id', 'left')
                ->join('tbl_roles', 'tbl_roles.id = tbl_usuarios.rol_id', 'left')
                ->select('tbl_usuarios.*, tbl_areas.nombre AS nombre_area, tbl_roles.nombre AS nombre_rol')
                ->where('tbl_usuarios.cuenta_id', $cuenta_id_actual) // Filtra por cuenta
                ->where('tbl_usuarios.rol_id !=', 1) // Excluye los master
                ->orderBy('tbl_usuarios.id', 'ASC')
                ->findAll();
}

    public function obtenerUsuario($id) {
        return $this->db->table('tbl_usuarios u')
            ->select('u.*, r.nombre AS rol_nombre, a.nombre AS area_nombre')
            ->join('tbl_roles r', 'r.id = u.rol_id', 'left')
            ->join('tbl_areas a', 'a.id = u.area_id', 'left')
            ->where('u.id', $id)
            ->get()
            ->getRowArray();
    }

    public function obtenerUsuarioPorCorreo($correo) {
        return $this->where('correo', $correo)->first();
    }

     public function obtenerUsuariosPorRol($rol_id) {
    return $this->where('rol_id', $rol_id)->findAll();
}


    public function obtenerUsuariosPorArea($area_id) {
        return $this->where('area_id', $area_id)->findAll();
    }

    public function crearUsuario($data) {
        if (!isset($data['fecha_registro'])) {
            $data['fecha_registro'] = date('Y-m-d H:i:s');
        }
        return $this->insert($data);
    }

    public function actualizarUsuario($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarUsuario($id) {
        return $this->delete($id);
    }

    public function obtenerRoles() {
        return $this->db->table('tbl_roles')->get()->getResultArray();
    }

    /**
     * Obtiene todos los tags asociados a un usuario.
     *
     * @param int $userId
     * @return array
     */
    public function getTagsForUser(int $userId): array
    {
        return $this->db->table('tbl_usuario_tags ut')
                        ->select('t.id, t.tag, t.slug')
                        ->join('tbl_tags t', 't.id = ut.tag_id')
                        ->where('ut.usuario_id', $userId)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Asigna tags a un usuario, eliminando los tags existentes que no estén en la lista proporcionada.
     *
     * @param int $userId
     * @param array $tagIds Array de IDs de tags a asignar.
     * @return bool
     */
    public function assignTagsToUser(int $userId, array $tagIds): bool
    {
        $this->db->transStart();

        // Eliminar tags existentes para este usuario que no estén en $tagIds
        $this->db->table('tbl_usuario_tags')
                 ->where('usuario_id', $userId)
                 ->whereNotIn('tag_id', $tagIds)
                 ->delete();

        // Insertar nuevos tags (ignorando duplicados si ya existen)
        foreach ($tagIds as $tagId) {
            $this->db->table('tbl_usuario_tags')->insert([
                'usuario_id' => $userId,
                'tag_id'     => $tagId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Remueve tags específicos de un usuario.
     *
     * @param int $userId
     * @param array $tagIds Array de IDs de tags a remover.
     * @return bool
     */
    public function removeTagsFromUser(int $userId, array $tagIds): bool
    {
        return $this->db->table('tbl_usuario_tags')
                        ->where('usuario_id', $userId)
                        ->whereIn('tag_id', $tagIds)
                        ->delete();
    }

    /**
     * Remueve todos los tags asociados a un usuario.
     *
     * @param int $userId
     * @return bool
     */
    public function removeAllTagsFromUser(int $userId): bool
    {
        return $this->db->table('tbl_usuario_tags')
                        ->where('usuario_id', $userId)
                        ->delete();
    }

    /**
     * Obtiene usuarios que tienen al menos uno de los tags dados (por slug).
     *
     * @param array $tagSlugs Array de slugs de tags.
     * @return array
     */
    public function getUsersByTags(array $tagSlugs): array
    {
        if (empty($tagSlugs)) {
            return [];
        }

        return $this->db->table('tbl_usuarios u')
                        ->distinct()
                        ->select('u.id, u.nombre, u.correo') // Selecciona los campos relevantes del usuario
                        ->join('tbl_usuario_tags ut', 'ut.usuario_id = u.id')
                        ->join('tbl_tags t', 't.id = ut.tag_id')
                        ->whereIn('t.slug', $tagSlugs)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Cuenta los usuarios que tienen al menos uno de los tags dados (por slug).
     *
     * @param array $tagSlugs Array de slugs de tags.
     * @return int
     */
    public function countUsersByTags(array $tagSlugs): int
    {
        if (empty($tagSlugs)) {
            return 0;
        }

        return $this->db->table('tbl_usuarios u')
                        ->distinct()
                        ->join('tbl_usuario_tags ut', 'ut.usuario_id = u.id')
                        ->join('tbl_tags t', 't.id = ut.tag_id')
                        ->whereIn('t.slug', $tagSlugs)
                        ->countAllResults();
    }
}