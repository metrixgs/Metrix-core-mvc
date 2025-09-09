<?php

namespace App\Models;

use CodeIgniter\Model;

class EventosModel extends Model
{
    protected $table = 'dashboard_demo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'nombre_pila', 'apellido_paterno', 'apellido_materno', 'genero', 'liderazgo',
        'coordinador', 'nivel_lider', 'sector', 'edad', 'colonia',
        'distrito_l', 'seccion', 'num_apoyos', 'num_eventos', 'anio_alta',
        'tag', 'latitud', 'longitud', 'created_at'
    ];
    protected $useTimestamps = false;

    // Aplicar filtros a un builder
    private function applyFilters($builder, $filtros = [])
    {
        if (!empty($filtros['liderazgo'])) {
            $builder->whereIn('liderazgo', is_array($filtros['liderazgo']) ? $filtros['liderazgo'] : [$filtros['liderazgo']]);
        }
        if (!empty($filtros['coordinador'])) {
            $builder->whereIn('coordinador', is_array($filtros['coordinador']) ? $filtros['coordinador'] : [$filtros['coordinador']]);
        }
        if (!empty($filtros['seccion_electoral'])) {
            $builder->whereIn('seccion', is_array($filtros['seccion_electoral']) ? $filtros['seccion_electoral'] : [$filtros['seccion_electoral']]);
        }
        if (!empty($filtros['colonia'])) {
            $builder->whereIn('colonia', is_array($filtros['colonia']) ? $filtros['colonia'] : [$filtros['colonia']]);
        }
        if (!empty($filtros['sector'])) {
            $builder->whereIn('sector', is_array($filtros['sector']) ? $filtros['sector'] : [$filtros['sector']]);
        }
        if (!empty($filtros['anio_alta'])) {
            $builder->whereIn('anio_alta', is_array($filtros['anio_alta']) ? $filtros['anio_alta'] : [$filtros['anio_alta']]);
        }
        if (!empty($filtros['edad_min']) && !empty($filtros['edad_max'])) {
            $builder->where('edad >=', $filtros['edad_min']);
            $builder->where('edad <=', $filtros['edad_max']);
        }
        return $builder;
    }

    // KPIs principales
    public function getTotalApoyos($filtros = [])
    {
        $builder = $this->builder();
        $builder->selectSum('num_apoyos', 'total');
        $this->applyFilters($builder, $filtros);
        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    public function getTotalEventos($filtros = [])
    {
        $builder = $this->builder();
        $builder->selectSum('num_eventos', 'total');
        $this->applyFilters($builder, $filtros);
        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    public function getPromedioApoyos($filtros = [])
    {
        $builder = $this->builder();
        $builder->selectAvg('num_apoyos', 'promedio');
        $this->applyFilters($builder, $filtros);
        $result = $builder->get()->getRowArray();
        return round($result['promedio'] ?? 0, 2);
    }

    public function getPromedioEventos($filtros = [])
    {
        $builder = $this->builder();
        $builder->selectAvg('num_eventos', 'promedio');
        $this->applyFilters($builder, $filtros);
        $result = $builder->get()->getRowArray();
        return round($result['promedio'] ?? 0, 2);
    }

    public function getPorcentajeApoyos($filtros = [])
    {
        $totalFiltrado = $this->getTotalApoyos($filtros);
        $totalGeneral = $this->getTotalApoyos();
        return $totalGeneral > 0 ? round(($totalFiltrado / $totalGeneral) * 100, 2) : 0;
    }

    public function getPorcentajeEventos($filtros = [])
    {
        $totalFiltrado = $this->getTotalEventos($filtros);
        $totalGeneral = $this->getTotalEventos();
        return $totalGeneral > 0 ? round(($totalFiltrado / $totalGeneral) * 100, 2) : 0;
    }

    public function getTotalCiudadanos($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('COUNT(*) as total');
        $this->applyFilters($builder, $filtros);
        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    // Demografía
    public function getDistribucionGenero($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('genero, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos');
        $this->applyFilters($builder, $filtros);
        $builder->where('genero IS NOT NULL');
        $builder->groupBy('genero');
        return $builder->get()->getResultArray();
    }

    public function getDistribucionEdades($filtros = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select("CASE 
            WHEN edad BETWEEN 0 AND 17 THEN '0-17'
            WHEN edad BETWEEN 18 AND 29 THEN '18-29'
            WHEN edad BETWEEN 30 AND 39 THEN '30-39'
            WHEN edad BETWEEN 40 AND 49 THEN '40-49'
            WHEN edad BETWEEN 50 AND 59 THEN '50-59'
            WHEN edad >= 60 THEN '60+'
            ELSE 'Sin especificar'
        END as rango_edad, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos");
        $this->applyFilters($builder, $filtros);
        $builder->where('edad IS NOT NULL');
        $builder->groupBy('rango_edad');
        $builder->orderBy('MIN(edad)');
        return $builder->get()->getResultArray();
    }

    // Liderazgo y Organización
    public function getNivelesLider($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('liderazgo as nivel_lider, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos');
        $this->applyFilters($builder, $filtros);
        $builder->where('liderazgo IS NOT NULL');
        $builder->where('liderazgo !=', '');
        $builder->where('liderazgo !=', ' ');
        $builder->groupBy('liderazgo');
        $builder->orderBy('total_apoyos', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getTopLideres($filtros = [], $topN = 10)
    {
        $builder = $this->builder();
        $builder->select('liderazgo, SUM(num_apoyos) as total_apoyos, SUM(num_eventos) as total_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('liderazgo IS NOT NULL');
        $builder->groupBy('liderazgo');
        $builder->orderBy('total_apoyos', 'DESC');
        $builder->limit($topN);
        return $builder->get()->getResultArray();
    }

    public function getLiderazgoPolar($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('liderazgo, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos, SUM(num_eventos) as total_eventos, AVG(num_apoyos) as promedio_apoyos, AVG(num_eventos) as promedio_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('liderazgo IS NOT NULL');
        $builder->where('liderazgo !=', '');
        $builder->where('TRIM(liderazgo) !=', '');
        $builder->groupBy('liderazgo');
        $builder->orderBy('total_personas', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getSectoresTreemap($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('sector, SUM(num_apoyos) as total_apoyos, SUM(num_eventos) as total_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('sector IS NOT NULL');
        $builder->groupBy('sector');
        $builder->orderBy('total_apoyos', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Apoyos
    public function getApoyosPorSector($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('sector, SUM(num_apoyos) as total_apoyos');
        $this->applyFilters($builder, $filtros);
        $builder->where('sector IS NOT NULL');
        $builder->groupBy('sector');
        $builder->orderBy('total_apoyos', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPromedioApoyosPorSector($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('sector, AVG(num_apoyos) as promedio_apoyos');
        $this->applyFilters($builder, $filtros);
        $builder->where('sector IS NOT NULL');
        $builder->groupBy('sector');
        $builder->orderBy('promedio_apoyos', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Eventos
    public function getEventosPorAnio($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('anio_alta, COUNT(*) as total_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('anio_alta IS NOT NULL');
        $builder->groupBy('anio_alta');
        $builder->orderBy('anio_alta');
        return $builder->get()->getResultArray();
    }

    public function getEventosPorSector($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('sector, SUM(num_eventos) as total_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('sector IS NOT NULL');
        $builder->groupBy('sector');
        $builder->orderBy('total_eventos', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getEventosPorLider($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('liderazgo, AVG(num_eventos) as promedio_eventos, COUNT(*) as total_personas');
        $this->applyFilters($builder, $filtros);
        $builder->where('liderazgo IS NOT NULL');
        $builder->where('liderazgo != ""');
        $builder->where('TRIM(liderazgo) != ""');
        $builder->where('num_eventos > 0');
        $builder->groupBy('liderazgo');
        $builder->orderBy('promedio_eventos', 'DESC');
        return $builder->get()->getResultArray();
    }



    // Selectores para filtros
    public function getSectores($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('sector');
        $this->applyFilters($builder, $filtros);
        $builder->where('sector IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('sector');
        return $builder->get()->getResultArray();
    }

    public function getCoordinadores($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('coordinador');
        $this->applyFilters($builder, $filtros);
        $builder->where('coordinador IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('coordinador');
        return $builder->get()->getResultArray();
    }

    public function getLiderazgos($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('liderazgo');
        $this->applyFilters($builder, $filtros);
        $builder->where('liderazgo IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('liderazgo');
        return $builder->get()->getResultArray();
    }

    public function getDistritosLocales($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('distrito_l');
        $this->applyFilters($builder, $filtros);
        $builder->where('distrito_l IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('distrito_l');
        return $builder->get()->getResultArray();
    }

    public function getColonias($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('colonia');
        $this->applyFilters($builder, $filtros);
        $builder->where('colonia IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('colonia');
        return $builder->get()->getResultArray();
    }

    public function getSeccionesElectorales($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('seccion');
        $this->applyFilters($builder, $filtros);
        $builder->where('seccion IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('seccion');
        return $builder->get()->getResultArray();
    }

    public function getAniosAlta($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('anio_alta');
        $this->applyFilters($builder, $filtros);
        $builder->where('anio_alta IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('anio_alta');
        return $builder->get()->getResultArray();
    }

    public function getCoordinadorData($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('coordinador, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos, SUM(num_eventos) as total_eventos, AVG(num_apoyos) as promedio_apoyos, AVG(num_eventos) as promedio_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('coordinador IS NOT NULL');
        $builder->where('coordinador !=', '');
        $builder->groupBy('coordinador');
        $builder->orderBy('total_personas', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getColoniasData($filtros = [])
    {
        $builder = $this->builder();
        $builder->select('colonia, COUNT(*) as total_personas, SUM(num_apoyos) as total_apoyos, SUM(num_eventos) as total_eventos, AVG(num_apoyos) as promedio_apoyos, AVG(num_eventos) as promedio_eventos');
        $this->applyFilters($builder, $filtros);
        $builder->where('colonia IS NOT NULL');
        $builder->where('colonia !=', '');
        $builder->groupBy('colonia');
        $builder->orderBy('total_personas', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Top 10 Secciones Electorales
    public function getTopApoyosPorSeccion($filtros = [], $topN = 10)
    {
        $builder = $this->builder();
        $builder->select('seccion, SUM(num_apoyos) as total_apoyos, COUNT(*) as total_personas');
        $this->applyFilters($builder, $filtros);
        $builder->where('seccion IS NOT NULL');
        $builder->where('seccion !=', '');
        $builder->groupBy('seccion');
        $builder->orderBy('total_apoyos', 'DESC');
        $builder->limit($topN);
        return $builder->get()->getResultArray();
    }

    public function getTopEventosPorSeccion($filtros = [], $topN = 10)
    {
        $builder = $this->builder();
        $builder->select('seccion, SUM(num_eventos) as total_eventos, COUNT(*) as total_personas');
        $this->applyFilters($builder, $filtros);
        $builder->where('seccion IS NOT NULL');
        $builder->where('seccion !=', '');
        $builder->groupBy('seccion');
        $builder->orderBy('total_eventos', 'DESC');
        $builder->limit($topN);
        return $builder->get()->getResultArray();
    }
}