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
     * @param string|null $polygonGeoJson Geometría del polígono en formato GeoJSON (cadena JSON).
     * @return array
     */
    public function getTagsWithUserCountsByTerritory(string $territoryType, array $territoryIds, ?string $polygonGeoJson = null): array
    {
        if (empty($territoryIds) && empty($polygonGeoJson)) {
            return [];
        }

        $builder = $this->db->table('tags');
        $builder->select('tags.id, tags.nombre as tag, tags.slug, COUNT(DISTINCT directorio_tags.directorio_id) as user_count');
        $builder->join('directorio_tags', 'directorio_tags.tag_id = tags.id');
        $builder->join('directorio', 'directorio.id = directorio_tags.directorio_id');
        
        // Filtrar por territorio si se proporcionan IDs
        if (!empty($territoryIds)) {
            if ($territoryType === 'municipio') {
                $builder->whereIn('directorio.municipio', $territoryIds);
            } elseif ($territoryType === 'delegacion') {
                $builder->whereIn('directorio.id_del', $territoryIds);
            }
            // Puedes añadir más casos para otros tipos de territorio si es necesario
        }

        // Filtrar por geometría del polígono si se proporciona
        if (!empty($polygonGeoJson)) {
            $geojsonObject = json_decode(urldecode($polygonGeoJson), true);
            if ($geojsonObject && isset($geojsonObject['features'][0]['geometry'])) {
                $geometry = $geojsonObject['features'][0]['geometry'];
                $wktPolygon = $this->_convertGeoJsonToWKT($geometry);
                if ($wktPolygon) {
                    $builder->where("ST_CONTAINS(ST_GeomFromText('{$wktPolygon}'), POINT(directorio.longitud, directorio.latitud))");
                }
            }
        }
        
        $builder->groupBy('tags.id, tags.nombre, tags.slug');
        $builder->orderBy('tags.nombre', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Convierte un objeto GeoJSON de tipo Polygon o MultiPolygon a formato WKT.
     *
     * @param array $geometry Objeto GeoJSON de geometría.
     * @return string|null WKT del polígono o null si el formato no es soportado.
     */
    private function _convertGeoJsonToWKT(array $geometry): ?string
    {
        $type = $geometry['type'] ?? '';
        $coordinates = $geometry['coordinates'] ?? [];

        if ($type === 'Polygon' && !empty($coordinates)) {
            $rings = [];
            foreach ($coordinates as $ring) {
                $points = [];
                foreach ($ring as $coord) {
                    $points[] = "{$coord[0]} {$coord[1]}"; // Longitud Latitud
                }
                $rings[] = '(' . implode(', ', $points) . ')';
            }
            return 'POLYGON(' . implode(', ', $rings) . ')';
        } elseif ($type === 'MultiPolygon' && !empty($coordinates)) {
            $polygons = [];
            foreach ($coordinates as $polygon) {
                $rings = [];
                foreach ($polygon as $ring) {
                    $points = [];
                    foreach ($ring as $coord) {
                        $points[] = "{$coord[0]} {$coord[1]}"; // Longitud Latitud
                    }
                    $rings[] = '(' . implode(', ', $points) . ')';
                }
                $polygons[] = '(' . implode(', ', $rings) . ')';
            }
            return 'MULTIPOLYGON(' . implode(', ', $polygons) . ')';
        }

        return null;
    }
}