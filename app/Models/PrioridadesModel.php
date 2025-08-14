<?php

namespace App\Models;

use CodeIgniter\Model;

class PrioridadesModel extends Model
{
    protected $table = 'tbl_prioridades';
    protected $primaryKey = 'id_prioridad';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre'];

    public function obtenerPrioridades()
    {
        return $this->findAll();
    }

    public function obtenerPrioridadPorId($id)
    {
        return $this->find($id);
    }
}