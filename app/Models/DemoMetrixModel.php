<?php

namespace App\Models;

use CodeIgniter\Model;

class DemoMetrixModel extends Model
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

    // Obtener sectores únicos
    public function getProgramasSociales()
    {
        return $this->select('sector')
                    ->distinct()
                    ->where('sector IS NOT NULL')
                    ->orderBy('sector')
                    ->findAll();
    }

    // Contador de beneficiarios totales
    public function getTotalBeneficiarios($programa = null)
    {
        $builder = $this->builder();
        if ($programa) {
            $builder->where('sector', $programa);
        }
        return $builder->countAllResults();
    }

    // Contador de beneficiarios únicos (por seccion)
    public function getBeneficiariosUnicos($programa = null)
    {
        $builder = $this->builder();
        $builder->select('seccion');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->distinct();
        return $builder->countAllResults();
    }

    // Contador de beneficiarios recurrentes
    public function getBeneficiariosRecurrentes($programa = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('seccion, COUNT(*) as total');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->groupBy('seccion');
        $builder->having('total > 1');
        return $builder->countAllResults();
    }

    // Contador por género
    public function getBeneficiariosPorGenero($programa = null)
    {
        $builder = $this->builder();
        $builder->select('genero, COUNT(*) as total');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('genero IS NOT NULL');
        $builder->groupBy('genero');
        return $builder->get()->getResultArray();
    }

    // Contador por rangos de edad
    public function getBeneficiariosPorEdad($programa = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select("CASE 
            WHEN edad BETWEEN 0 AND 17 THEN 'Menores (0-17)'
            WHEN edad BETWEEN 18 AND 29 THEN 'Jóvenes (18-29)'
            WHEN edad BETWEEN 30 AND 59 THEN 'Adultos (30-59)'
            WHEN edad >= 60 THEN 'Adultos Mayores (60+)'
            ELSE 'Sin especificar'
        END as rango_edad, COUNT(*) as total");
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('edad IS NOT NULL');
        $builder->groupBy('rango_edad');
        return $builder->get()->getResultArray();
    }

    // Top 5 colonias
    public function getTop5Municipios($programa = null)
    {
        $builder = $this->builder();
        $builder->select('colonia, COUNT(*) as total');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('colonia IS NOT NULL');
        $builder->groupBy('colonia');
        $builder->orderBy('total', 'DESC');
        $builder->limit(5);
        return $builder->get()->getResultArray();
    }

    // Obtener distritos locales únicos
    public function getDistritosFederales($programa = null)
    {
        $builder = $this->builder();
        $builder->select('distrito_l');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('distrito_l IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('distrito_l');
        return $builder->get()->getResultArray();
    }

    // Comparativa distritos locales
    public function getComparativaDFs($dfs = [], $programa = null)
    {
        if (empty($dfs)) return [];
        
        $builder = $this->builder();
        $builder->select('distrito_l, COUNT(*) as total');
        $builder->whereIn('distrito_l', $dfs);
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->groupBy('distrito_l');
        return $builder->get()->getResultArray();
    }

    // Obtener distritos locales únicos
    public function getDistritosLocales($programa = null)
    {
        $builder = $this->builder();
        $builder->select('distrito_l');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('distrito_l IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('distrito_l');
        return $builder->get()->getResultArray();
    }

    // Comparativa distritos locales (para gráfico bubble)
    public function getComparativaDLs($dls = [], $programa = null)
    {
        if (empty($dls)) return [];
        
        $builder = $this->db->table($this->table);
        $builder->select('distrito_l, COUNT(*) as beneficiarios, AVG(num_apoyos) as promedio_apoyos, SUM(num_apoyos) as total_apoyos');
        $builder->whereIn('distrito_l', $dls);
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('distrito_l IS NOT NULL');
        $builder->groupBy('distrito_l');
        return $builder->get()->getResultArray();
    }

    // Top 15 secciones electorales
    public function getTop15Secciones($programa = null)
    {
        $builder = $this->builder();
        $builder->select('seccion, COUNT(*) as total');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('seccion IS NOT NULL');
        $builder->groupBy('seccion');
        $builder->orderBy('total', 'DESC');
        $builder->limit(15);
        return $builder->get()->getResultArray();
    }

    // Obtener liderazgos únicos
    public function getLiderazgos($programa = null)
    {
        $builder = $this->builder();
        $builder->select('liderazgo');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('liderazgo IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('liderazgo');
        return $builder->get()->getResultArray();
    }

    // Comparativa liderazgos
    public function getComparativaLiderazgos($liderazgos = [], $programa = null)
    {
        if (empty($liderazgos)) return [];
        
        $builder = $this->builder();
        $builder->select('liderazgo, COUNT(*) as total');
        $builder->whereIn('liderazgo', $liderazgos);
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->groupBy('liderazgo');
        return $builder->get()->getResultArray();
    }

    // Obtener secciones electorales para comparar
    public function getSecciones($programa = null)
    {
        $builder = $this->builder();
        $builder->select('seccion');
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->where('seccion IS NOT NULL');
        $builder->distinct();
        $builder->orderBy('seccion');
        return $builder->get()->getResultArray();
    }

    // Comparativa secciones electorales
    public function getComparativaSecciones($secciones = [], $programa = null)
    {
        if (empty($secciones)) return [];
        
        $builder = $this->builder();
        $builder->select('seccion, COUNT(*) as total');
        $builder->whereIn('seccion', $secciones);
        if ($programa) {
            $builder->where('sector', $programa);
        }
        $builder->groupBy('seccion');
        $builder->orderBy('total', 'DESC');
        return $builder->get()->getResultArray();
    }
}