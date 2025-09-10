<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorioModel extends Model
{
    protected $table = 'directorio';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'curp', 'id_usuario', 'nombre', 'primer_apellido', 'segundo_apellido',
        'genero', 'nombre_liderazgo', 'nombre_coordinador', 'edad',
        'apellido_materno', 'fecha_nacimiento', 'telefono', 'email',
        'direccion', 'residencia', 'estado', 'municipio', 'localidad', 'colonia',
        'calle', 'numero_exterior', 'numero_interior', 'codigo_postal',
        'empresa', 'cargo', 'nivel_estudios', 'ocupacion',
        'tipo_discapacidad', 'grupo_etnico', 'acepta_avisos', 'acepta_terminos',
        'activo', 'tipo_cliente','codigo_ciudadano', 'foto_perfil', 'id_lider','tipo_red','latitud', 'longitud',
        'clave_elector', 'vigencia', 'tipo_sangre', 'servicios', 'tarifa', 'categoria', 'dias', 'horarios',
        'descuento', 'anio', 'paquete',
        'id_del', 'nom_del', 'cve_loc', 'nom_loc', 'id_col', 'nom_col',
        'cve_ageb', 'cve_mza', 'cu_mza', 'cve_cat', 'tipo', 'zona', 'sector',
        'distrito_f', 'distrito_l', 'seccion', 'circuito', 'distrito_j', 'letra',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualizacion';
}
