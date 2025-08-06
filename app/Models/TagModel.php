<?php
namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $DBGroup       = 'default';    // usa tu grupo real si no es 'default'
    protected $table         = 'tbl_tags';   // este es el nombre exacto en tu dump
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['tag','slug','created_at'];
    protected $useTimestamps = false;

    public function allOrdered(): array
    {
        return $this->select('id, tag, slug')
                    ->orderBy('tag','ASC')
                    ->findAll();
    }
}
