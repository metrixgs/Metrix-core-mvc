<?php

namespace App\Models;

use CodeIgniter\Model;

class RondaOperadorPuntosModel extends Model
{
    protected $table         = 'tbl_ronda_operador_puntos';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['ronda_id', 'operador_id', 'puntos_asignados', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Puedes añadir métodos adicionales si son necesarios para consultar o manipular estos datos
}