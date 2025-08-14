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
                          IFNULL(tbl_sla.color, "") AS color_sla')
               ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
               ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
               ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
               ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                ->join('tbl_sla', 'LOWER(tbl_tickets.prioridad) = LOWER(tbl_sla.titulo)', 'left')
                ->orderBy('tbl_tickets.id', 'DESC')
                ->findAll();
}


    public function obtenerTicketsPorCampana($campana_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
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
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
                        ->where('tbl_tickets.id', $id)
                        ->first();
    }

    public function obtenerTicketsPorCliente($cliente_id) {
        return $this->select('tbl_tickets.*, 
                          u1.nombre AS nombre_usuario, 
                          u2.nombre AS nombre_cliente,
                          tbl_categorias.nombre AS nombre_categoria,
                          tbl_areas.nombre AS nombre_area_responsable,
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
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
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
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
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
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
                          IFNULL(tbl_sla.color, "") AS color_sla')
                       ->join('tbl_usuarios AS u1', 'tbl_tickets.usuario_id = u1.id', 'left')
                       ->join('tbl_usuarios AS u2', 'tbl_tickets.cliente_id = u2.id', 'left')
                       ->join('tbl_categorias', 'tbl_tickets.categoria_id = tbl_categorias.id_categoria', 'left')
                       ->join('tbl_areas', 'tbl_tickets.area_id = tbl_areas.id', 'left')
                        ->join('tbl_sla', 'tbl_tickets.prioridad = tbl_sla.titulo', 'left')
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

}
