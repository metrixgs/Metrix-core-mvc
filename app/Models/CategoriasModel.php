<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
    protected $table = 'tbl_categorias';
    protected $primaryKey = 'id_categoria';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombre'];

    public function obtenerCategorias()
    {
        return $this->findAll();
    }

    public function obtenerCategoriaPorId($id)
    {
        return $this->find($id);
    }
}