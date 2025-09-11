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

    /**
     * Obtiene todos los tags ordenados por nombre.
     *
     * @return array
     */
    public function allOrdered(): array
    {
        return $this->select('id, nombre as tag, slug')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene el conteo de usuarios por cada tag.
     *
     * @return array Un array asociativo donde la clave es el slug del tag y el valor es el conteo de usuarios.
     */
    public function getUsersCountByTag(): array
    {
        $builder = $this->db->table('directorio_tags');
        $builder->select('tags.slug, COUNT(DISTINCT directorio_tags.directorio_id) as user_count');
        $builder->join('tags', 'tags.id = directorio_tags.tag_id');
        $builder->groupBy('tags.slug');
        $query = $builder->get();

        $counts = [];
        foreach ($query->getResultArray() as $row) {
            $counts[$row['slug']] = (int) $row['user_count'];
        }
        return $counts;
    }

    /**
     * Obtiene tags con conteo de usuarios filtrados por territorio.
     *
     * @param string $territoryType Tipo de territorio (ej. 'municipio', 'delegacion').
     * @param array $territoryIds IDs de los territorios seleccionados.
     * @return array
     */
    public function getTagsWithUserCountsByTerritory(string $territoryType, array $territoryIds): array
    {
        if (empty($territoryIds)) {
            return [];
        }

        $builder = $this->db->table('tags');
        $builder->select('tags.id, tags.nombre as tag, tags.slug, COUNT(DISTINCT directorio_tags.directorio_id) as user_count');
        $builder->join('directorio_tags', 'directorio_tags.tag_id = tags.id');
        $builder->join('directorio', 'directorio.id = directorio_tags.directorio_id');
        
        // Condición dinámica para filtrar por tipo de territorio
        if ($territoryType === 'municipio') {
            $builder->whereIn('directorio.municipio', $territoryIds);
        } elseif ($territoryType === 'delegacion') {
            $builder->whereIn('directorio.id_del', $territoryIds);
        }
        // Puedes añadir más casos para otros tipos de territorio si es necesario
        
        $builder->groupBy('tags.id, tags.nombre, tags.slug');
        $builder->orderBy('tags.nombre', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}