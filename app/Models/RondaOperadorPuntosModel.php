<?php

namespace App\Models;

use CodeIgniter\Model;

class RondaOperadorPuntosModel extends Model
{
    protected $table = 'tbl_ronda_operador_puntos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['ronda_id', 'operador_id', 'puntos_asignados'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Reglas de validación (opcional, pero buena práctica)
    protected $validationRules = [
        'ronda_id'         => 'required|integer',
        'operador_id'      => 'required|integer',
        'puntos_asignados' => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'ronda_id' => [
            'required' => 'El ID de la ronda es obligatorio.',
            'integer'  => 'El ID de la ronda debe ser un número entero.'
        ],
        'operador_id' => [
            'required' => 'El ID del operador es obligatorio.',
            'integer'  => 'El ID del operador debe ser un número entero.'
        ],
        'puntos_asignados' => [
            'required'             => 'Los puntos asignados son obligatorios.',
            'integer'              => 'Los puntos asignados deben ser un número entero.',
            'greater_than_equal_to' => 'Los puntos asignados no pueden ser negativos.'
        ]
    ];
}