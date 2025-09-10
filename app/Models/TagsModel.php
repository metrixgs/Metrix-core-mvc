<?php

namespace App\Models;

use CodeIgniter\Model;

class TagsModel extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'slug'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Método para obtener un tag por su slug o crearlo si no existe
    public function getOrCreateTag(string $tagName): int
    {
        $slug = url_title($tagName, '-', true); // Generar slug
        $tag = $this->where('slug', $slug)->first();

        if ($tag) {
            return $tag['id'];
        }

        // Si no existe, crear el tag
        $this->insert([
            'nombre' => $tagName,
            'slug'   => $slug,
        ]);

        return $this->getInsertID();
    }
}