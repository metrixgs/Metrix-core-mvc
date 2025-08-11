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
    // Obtener el rol del usuario actual para determinar qué usuarios puede ver
    $rolActual = session('session_data.rol_id');
    $usuarioActualId = session('session_data.id');
    
    $query = $this->join('tbl_areas', 'tbl_areas.id = tbl_usuarios.area_id', 'left')
                ->join('tbl_roles', 'tbl_roles.id = tbl_usuarios.rol_id', 'left')
                ->select('tbl_usuarios.*, 
                         COALESCE(tbl_areas.nombre, "Sin área") AS nombre_area, 
                         COALESCE(tbl_roles.nombre, "Sin rol") AS nombre_rol');
    
    // Si es Master/Mega Admin (rol_id = 3 o 1), puede ver todos los usuarios
    if ($rolActual == 3 || $rolActual == 1) {
        // Master puede ver todos los usuarios de todas las cuentas
        return $query->orderBy('tbl_usuarios.id', 'ASC')->findAll();
    } else if ($rolActual == 2) {
        // Cliente administrador (rol_id = 2) solo ve usuarios que él creó
        return $query->where('tbl_usuarios.creado_por_id', $usuarioActualId)
                    ->orderBy('tbl_usuarios.id', 'ASC')
                    ->findAll();
    } else {
        // Otros roles solo ven usuarios de su cuenta y excluyen Master y Cliente Admin
        return $query->where('tbl_usuarios.cuenta_id', $cuenta_id_actual)
                    ->where('tbl_usuarios.rol_id !=', 3)  // Excluye rol Master (ID 3)
                    ->where('tbl_usuarios.rol_id !=', 1)  // Excluye rol Mega Admin (ID 1)
                    ->where('tbl_usuarios.rol_id !=', 2)  // Excluye rol Administrador (ID 2)
                    ->orderBy('tbl_usuarios.id', 'ASC')
                    ->findAll();
    }
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
     * Valida y corrige automáticamente el cuenta_id de usuarios
     * Los usuarios que no sean Master/Mega Admin y tengan cuenta_id = 1 o NULL
     * se actualizan automáticamente a cuenta_id = 2
     */
    public function validarYCorregirCuentaId() {
        // Obtener usuarios que necesitan corrección
        $usuariosProblema = $this->where('cuenta_id', 1)
                                ->whereNotIn('rol_id', [1, 2, 3, 4])
                                ->findAll();
        
        $usuariosNulos = $this->where('cuenta_id IS NULL')
                             ->whereNotIn('rol_id', [1, 2, 3, 4])
                             ->findAll();
        
        $totalCorregidos = 0;
        
        // Corregir usuarios con cuenta_id = 1
        if (!empty($usuariosProblema)) {
            $this->where('cuenta_id', 1)
                 ->whereNotIn('rol_id', [1, 2, 3, 4])
                 ->set(['cuenta_id' => 2])
                 ->update();
            $totalCorregidos += count($usuariosProblema);
        }
        
        // Corregir usuarios con cuenta_id = NULL
        if (!empty($usuariosNulos)) {
            $this->where('cuenta_id IS NULL')
                 ->whereNotIn('rol_id', [1, 2, 3, 4])
                 ->set(['cuenta_id' => 2])
                 ->update();
            $totalCorregidos += count($usuariosNulos);
        }
        
        if ($totalCorregidos > 0) {
            log_message('info', "Corrección automática de cuenta_id: {$totalCorregidos} usuarios actualizados a cuenta_id = 2");
        }
        
        return $totalCorregidos;
    }

    /**
     * Obtiene el cuenta_id correcto para un usuario basado en su rol
     */
    public function obtenerCuentaIdCorrecta($rolId, $cuentaIdActual) {
        // Roles globales mantienen su cuenta_id original
        if (in_array($rolId, [1, 2, 3, 4])) {
            return $cuentaIdActual;
        }
        
        // Otros usuarios deben tener cuenta_id = 2
        if (empty($cuentaIdActual) || $cuentaIdActual == 1) {
            return 2;
        }
        
        return $cuentaIdActual;
    }
}