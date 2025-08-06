<?php

namespace App\Models;

use CodeIgniter\Model;

class BrigadasModel extends Model
{
    protected $table            = 'tbl_brigadas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'campana_id', 'nombre', 'responsable', 'created_at'
    ];
}
