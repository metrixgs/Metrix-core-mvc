<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorioModel extends Model
{
    protected $table = 'directorio';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'curp', 'id_usuario', 'nombre', 'primer_apellido', 'segundo_apellido',
        'apellido_materno', 'fecha_nacimiento', 'telefono', 'email',
        'direccion', 'residencia', 'estado', 'municipio', 'localidad', 'colonia',
        'calle', 'numero_exterior', 'numero_interior', 'codigo_postal',
        'empresa', 'cargo', 'nivel_estudios', 'ocupacion',
        'tipo_discapacidad', 'grupo_etnico', 'acepta_avisos', 'acepta_terminos',
        'activo', 'tipo_cliente','codigo_ciudadano', 'foto_perfil', 'id_lider','tipo_red'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualizacion';
}
