<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsModel extends Model {

    protected $table = 'tbl_tickets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'cliente_id', 'categoria_id', 'usuario_id', 'campana_id', 'identificador', 'titulo', 'descripcion',
        'prioridad', 'latitud', 'longitud', 'estado_p', 'estado', 'municipio', 'colonia', 'df', 'dl',
        'seccion_electoral', 'codigo_postal', 'direccion_completa', 'direccion_solicitante', 'mismo_domicilio',
        'fecha_creacion', 'fecha_cierre', 'fecha_vencimiento'
    ];

 public function obtenerTickets() {
    return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
               ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
               ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
               ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
               ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
               ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
               ->join('tbl_sla', 'LOWER(tbl_prioridades.nombre) = LOWER(tbl_sla.titulo)', 'left')
                ->orderBy('tbl_tickets.id', 'DESC')
                ->findAll();
}


    public function obtenerTicketsPorCampana($campana_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.campana_id', $campana_id)
                        ->orderBy('id', 'DESC')
                        ->findAll();
    }

    public function obtenerTicket($id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.id', $id)
                        ->first();
    }

    public function obtenerTicketsPorCliente($cliente_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.cliente_id', $cliente_id)
                        ->orderBy('id', 'DESC')
                        ->findAll();
    }

    public function obtenerTicketsPorArea($area_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario,
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.categoria_id', $area_id)
                        ->orderBy('id', 'DESC')
                        ->findAll();
    }

    public function obtenerTicketsPorAreaEnRango($area_id, $fecha_inicio, $fecha_fin) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario,
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.categoria_id', $area_id)
                        ->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                        ->where('tbl_tickets.fecha_creacion <=', $fecha_fin)
                        ->orderBy('id', 'DESC')
                        ->findAll();
    }

    public function obtenerTicketsCreadosPorUsuario($usuario_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          tbl_prioridades.nombre AS nombre_prioridad,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                      ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                      ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                       ->join('tbl_sla', 'tbl_prioridades.nombre = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.usuario_id', $usuario_id)
                        ->orderBy('id', 'DESC')
                        ->findAll();
    }

    public function crearTicket($data) {
        $this->insert($data);
        return $this->insertID();
    }

    public function actualizarTicket($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarTicket($id) {
        return $this->delete($id);
    }

    public function contarIncidenciasPorCampana($campana_id)
{
    return $this->where('campana_id', $campana_id)->countAllResults();
}

    // Métodos para estadísticas del dashboard
    public function obtenerTicketsPorPrioridad($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('tbl_prioridades.nombre as prioridad, COUNT(*) as total')
                      ->join('tbl_prioridades', 'tbl_tickets.prioridad = tbl_prioridades.id_prioridad', 'left')
                      ->groupBy('tbl_prioridades.nombre');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        }
        
        return $query->findAll();
    }

    public function obtenerTicketsPorCategoria($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('tbl_categorias.nombre as categoria, COUNT(*) as total')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->groupBy('tbl_categorias.nombre');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        }
        
        return $query->findAll();
    }

    public function obtenerTicketsPorCategoriaFiltrado($fecha_inicio = null, $fecha_fin = null, $categoriaIds = null) {
        $query = $this->select('tbl_categorias.nombre as categoria, COUNT(*) as total')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->groupBy('tbl_categorias.nombre');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        }
        
        if ($categoriaIds && is_array($categoriaIds)) {
            $query->whereIn('tbl_categorias.id_categoria', $categoriaIds);
        }
        
        $result = $query->findAll();
        
        // Formatear datos para Chart.js
        $labels = [];
        $values = [];
        
        foreach ($result as $row) {
            $labels[] = $row['categoria'];
            $values[] = (int)$row['total'];
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    public function obtenerTiempoPromedioPorArea($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('tbl_areas.nombre as area, 
                               AVG(TIMESTAMPDIFF(HOUR, tbl_tickets.fecha_creacion, 
                                   COALESCE(tbl_tickets.fecha_cierre, NOW()))) as tiempo_promedio')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->where('tbl_tickets.fecha_cierre IS NOT NULL OR tbl_tickets.estado !=', 'Cerrado')
                      ->groupBy('tbl_areas.nombre');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        }
        
        return $query->findAll();
    }

    public function obtenerTendenciaMensual($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('DATE_FORMAT(fecha_creacion, "%Y-%m") as mes, COUNT(*) as total')
                      ->groupBy('DATE_FORMAT(fecha_creacion, "%Y-%m")')
                      ->orderBy('mes', 'ASC');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('fecha_creacion >=', $fecha_inicio)
                  ->where('fecha_creacion <=', $fecha_fin);
        } else {
            // Por defecto últimos 6 meses
            $query->where('fecha_creacion >=', date('Y-m-d', strtotime('-6 months')));
        }
        
        return $query->findAll();
    }

    public function obtenerTop10Colonias($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('colonia, COUNT(*) as total')
                      ->where('colonia IS NOT NULL')
                      ->where('colonia !=', '')
                      ->groupBy('colonia')
                      ->orderBy('total', 'DESC')
                      ->limit(10);
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('fecha_creacion >=', $fecha_inicio)
                  ->where('fecha_creacion <=', $fecha_fin);
        }
        
        return $query->findAll();
    }

    public function obtenerTicketsPorAreaResponsable($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('tbl_areas.nombre as area, COUNT(*) as total')
                      ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                      ->groupBy('tbl_areas.nombre')
                      ->orderBy('total', 'DESC');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        }
        
        return $query->findAll();
    }

    public function obtenerEstadisticasGenerales($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('COUNT(*) as total,
                               SUM(CASE WHEN estado = "Abierto" THEN 1 ELSE 0 END) as abiertas,
                               SUM(CASE WHEN estado = "Cerrado" THEN 1 ELSE 0 END) as cerradas,
                               AVG(CASE WHEN fecha_cierre IS NOT NULL 
                                   THEN TIMESTAMPDIFF(HOUR, fecha_creacion, fecha_cierre) 
                                   ELSE NULL END) as tiempo_promedio');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('fecha_creacion >=', $fecha_inicio)
                  ->where('fecha_creacion <=', $fecha_fin);
        }
        
        return $query->first();
    }

    public function obtenerComparativaTemporalPorCategoria($fecha_inicio = null, $fecha_fin = null) {
        $query = $this->select('DATE_FORMAT(tbl_tickets.fecha_creacion, "%Y-%m") as mes,
                               tbl_categorias.nombre as categoria,
                               COUNT(*) as total')
                      ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                      ->groupBy('DATE_FORMAT(tbl_tickets.fecha_creacion, "%Y-%m"), tbl_categorias.nombre')
                      ->orderBy('mes', 'ASC');
        
        if ($fecha_inicio && $fecha_fin) {
            $query->where('tbl_tickets.fecha_creacion >=', $fecha_inicio)
                  ->where('tbl_tickets.fecha_creacion <=', $fecha_fin);
        } else {
            // Por defecto últimos 6 meses
            $query->where('tbl_tickets.fecha_creacion >=', date('Y-m-d', strtotime('-6 months')));
        }
        
        return $query->findAll();
    }

    public function obtenerComparacionCategorias($categoriaIds, $fecha_inicio = null, $fecha_fin = null) {
        $categoriasModel = new \App\Models\CategoriasModel();
        $categorias = $categoriasModel->whereIn('id_categoria', $categoriaIds)->findAll();
        
        $datasets = [];
        $labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        $colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 205, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)',
            'rgba(83, 102, 255, 0.8)'
        ];
        
        foreach ($categorias as $index => $categoria) {
            // Primero verificar si hay tickets para esta categoría
            $totalTickets = $this->where('categoria_id', $categoria['id_categoria'])->countAllResults(false);
            
            if ($totalTickets > 0) {
                $query = $this->select('MONTH(fecha_creacion) as mes, COUNT(*) as total')
                              ->where('categoria_id', $categoria['id_categoria'])
                              ->groupBy('MONTH(fecha_creacion)')
                              ->orderBy('mes', 'ASC');
                
                if ($fecha_inicio && $fecha_fin) {
                    $query->where('fecha_creacion >=', $fecha_inicio)
                          ->where('fecha_creacion <=', $fecha_fin);
                } else {
                    // Si no hay fechas específicas, usar el año actual
                    $query->where('YEAR(fecha_creacion)', date('Y'));
                }
                
                $resultados = $query->findAll();
                
                // Inicializar datos con ceros para todos los meses
                $data = array_fill(0, 12, 0);
                
                // Llenar con los datos reales
                foreach ($resultados as $resultado) {
                    $data[$resultado['mes'] - 1] = (int)$resultado['total'];
                }
            } else {
                // Si no hay tickets, llenar con ceros
                $data = array_fill(0, 12, 0);
            }
            
            $datasets[] = [
                'label' => $categoria['nombre'],
                'data' => $data,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => str_replace('0.8', '1', $colors[$index % count($colors)]),
                'borderWidth' => 1
            ];
        }
        
        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

}
