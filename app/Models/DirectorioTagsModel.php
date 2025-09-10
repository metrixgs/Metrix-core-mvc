<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorioTagsModel extends Model
{
    protected $table = 'directorio_tags';
    protected $primaryKey = ['directorio_id', 'tag_id'];
    protected $allowedFields = ['directorio_id', 'tag_id'];
    protected $useTimestamps = false; // Esta tabla pivote no necesita timestamps

    // Método para asociar un tag a un ciudadano
    public function addTagToDirectorio(int $directorioId, int $tagId): bool
    {
        // Verificar si la relación ya existe para evitar duplicados
        $existing = $this->where('directorio_id', $directorioId)
                         ->where('tag_id', $tagId)
                         ->first();

        if ($existing) {
            return true; // La relación ya existe, no hacer nada
        }

        return $this->insert([
            'directorio_id' => $directorioId,
            'tag_id'        => $tagId,
        ]);
    }
}