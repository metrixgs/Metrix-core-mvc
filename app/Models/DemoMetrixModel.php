<?php

namespace App\Models;

use CodeIgniter\Model;

class DemoMetrixModel extends Model
{
    protected $table = 'demo_metrix';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'ESTADO', 'MUNICIPIO', 'DISTRITO_FEDERAL', 'DISTRITO_LOCAL', 'SECCION',
        'GENERO', 'COLONIA', 'EDAD', 'GRUPO_POBLACIONAL', 'LIDERAZGO',
        'LONGITUD', 'LATITUD', 'NUM_APOYOS'
    ];
    protected $useTimestamps = false;

    // Obtener programas sociales únicos (basado en GRUPO_POBLACIONAL)
    public function getProgramasSociales()
    {
        return $this->select('GRUPO_POBLACIONAL')
                    ->distinct()
                    ->where('GRUPO_POBLACIONAL IS NOT NULL')
                    ->orderBy('GRUPO_POBLACIONAL')
                    ->findAll();
    }

    // Contador de beneficiarios totales
    public function getTotalBeneficiarios($programa = null)
    {
        $builder = $this->builder();
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        return $builder->countAllResults();
    }

    // Contador de beneficiarios únicos (por SECCION)
    public function getBeneficiariosUnicos($programa = null)
    {
        $builder = $this->builder();
        $builder->select('SECCION');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->distinct();
        return $builder->countAllResults();
    }

    // Contador de beneficiarios recurrentes
    public function getBeneficiariosRecurrentes($programa = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('SECCION, COUNT(*) as total');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->groupBy('SECCION');
        $builder->having('total > 1');
        return $builder->countAllResults();
    }

    // Contador por género
    public function getBeneficiariosPorGenero($programa = null)
    {
        $builder = $this->builder();
        $builder->select('GENERO, COUNT(*) as total');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('GENERO IS NOT NULL');
        $builder->groupBy('GENERO');
        return $builder->get()->getResultArray();
    }

    // Contador por rangos de edad
    public function getBeneficiariosPorEdad($programa = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select("CASE 
            WHEN EDAD BETWEEN 0 AND 17 THEN 'Menores (0-17)'
            WHEN EDAD BETWEEN 18 AND 29 THEN 'Jóvenes (18-29)'
            WHEN EDAD BETWEEN 30 AND 59 THEN 'Adultos (30-59)'
            WHEN EDAD >= 60 THEN 'Adultos Mayores (60+)'
            ELSE 'Sin especificar'
        END as rango_edad, COUNT(*) as total");
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('EDAD IS NOT NULL');
        $builder->groupBy('rango_edad');
        return $builder->get()->getResultArray();
    }

    // Top 5 municipios
    public function getTop5Municipios($programa = null)
    {
        $builder = $this->builder();
        $builder->select('MUNICIPIO, COUNT(*) as total');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('MUNICIPIO IS NOT NULL');
        $builder->groupBy('MUNICIPIO');
        $builder->orderBy('total', 'DESC');
        $builder->limit(5);
        return $builder->get()->getResultArray();
    }

    // Obtener DFs únicos
    public function getDistritosFederales($programa = null)
    {
        $builder = $this->builder();
        $builder->select('DISTRITO_FEDERAL');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('DISTRITO_FEDERAL IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('DISTRITO_FEDERAL');
        return $builder->get()->getResultArray();
    }

    // Comparativa DFs
    public function getComparativaDFs($dfs = [], $programa = null)
    {
        if (empty($dfs)) return [];
        
        $builder = $this->builder();
        $builder->select('DISTRITO_FEDERAL, COUNT(*) as total');
        $builder->whereIn('DISTRITO_FEDERAL', $dfs);
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->groupBy('DISTRITO_FEDERAL');
        return $builder->get()->getResultArray();
    }

    // Obtener DLs únicos
    public function getDistritosLocales($programa = null)
    {
        $builder = $this->builder();
        $builder->select('DISTRITO_LOCAL');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('DISTRITO_LOCAL IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('DISTRITO_LOCAL');
        return $builder->get()->getResultArray();
    }

    // Comparativa DLs (para gráfico bubble)
    public function getComparativaDLs($dls = [], $programa = null)
    {
        if (empty($dls)) return [];
        
        $builder = $this->db->table($this->table);
        $builder->select('DISTRITO_LOCAL, COUNT(*) as beneficiarios, AVG(NUM_APOYOS) as promedio_apoyos, SUM(NUM_APOYOS) as total_apoyos');
        $builder->whereIn('DISTRITO_LOCAL', $dls);
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('DISTRITO_LOCAL IS NOT NULL');
        $builder->groupBy('DISTRITO_LOCAL');
        return $builder->get()->getResultArray();
    }

    // Top 15 secciones electorales
    public function getTop15Secciones($programa = null)
    {
        $builder = $this->builder();
        $builder->select('SECCION, COUNT(*) as total');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('SECCION IS NOT NULL');
        $builder->groupBy('SECCION');
        $builder->orderBy('total', 'DESC');
        $builder->limit(15);
        return $builder->get()->getResultArray();
    }

    // Obtener liderazgos únicos
    public function getLiderazgos($programa = null)
    {
        $builder = $this->builder();
        $builder->select('LIDERAZGO');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('LIDERAZGO IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('LIDERAZGO');
        return $builder->get()->getResultArray();
    }

    // Comparativa liderazgos
    public function getComparativaLiderazgos($liderazgos = [], $programa = null)
    {
        if (empty($liderazgos)) return [];
        
        $builder = $this->builder();
        $builder->select('LIDERAZGO, COUNT(*) as total');
        $builder->whereIn('LIDERAZGO', $liderazgos);
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->groupBy('LIDERAZGO');
        return $builder->get()->getResultArray();
    }

    // Obtener secciones electorales para comparar
    public function getSecciones($programa = null)
    {
        $builder = $this->builder();
        $builder->select('SECCION');
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->where('SECCION IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('SECCION');
        return $builder->get()->getResultArray();
    }

    // Comparativa secciones electorales
    public function getComparativaSecciones($secciones = [], $programa = null)
    {
        if (empty($secciones)) return [];
        
        $builder = $this->builder();
        $builder->select('SECCION, COUNT(*) as total');
        $builder->whereIn('SECCION', $secciones);
        if ($programa) {
            $builder->where('GRUPO_POBLACIONAL', $programa);
        }
        $builder->groupBy('SECCION');
        $builder->orderBy('total', 'DESC');
        return $builder->get()->getResultArray();
    }
}