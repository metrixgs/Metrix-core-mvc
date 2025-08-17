<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketsModel;

class TestDashboard extends BaseController {

    public function index() {
        $tickets = new TicketsModel();
        
        echo "<h2>Prueba de métodos del dashboard</h2>";
        
        try {
            echo "<h3>1. Verificar conexión a base de datos:</h3>";
            $db = \Config\Database::connect();
            echo "<p style='color: green;'>Conexión exitosa</p>";
            
            echo "<h3>2. Conteo de tickets:</h3>";
            $query = $db->query("SELECT COUNT(*) as total FROM tbl_tickets");
            $result = $query->getResultArray();
            echo "<pre>" . print_r($result, true) . "</pre>";
            
            echo "<h3>3. Verificar tablas relacionadas:</h3>";
            $tables = ['tbl_prioridades', 'tbl_categorias', 'tbl_areas'];
            foreach ($tables as $table) {
                try {
                    $query = $db->query("SELECT COUNT(*) as total FROM $table");
                    $result = $query->getResultArray();
                    echo "<p>$table: " . $result[0]['total'] . " registros</p>";
                } catch (\Exception $e) {
                    echo "<p style='color: red;'>Error en $table: " . $e->getMessage() . "</p>";
                }
            }
            
            echo "<h3>4. Tickets por Prioridad (método directo):</h3>";
            try {
                $prioridad = $tickets->obtenerTicketsPorPrioridad();
                echo "<pre>" . print_r($prioridad, true) . "</pre>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<h3>5. Tickets por Categoría (método directo):</h3>";
            try {
                $categoria = $tickets->obtenerTicketsPorCategoria();
                echo "<pre>" . print_r($categoria, true) . "</pre>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<h3>6. Consulta SQL directa - Prioridades:</h3>";
            try {
                $query = $db->query("
                    SELECT p.nombre as nombre_prioridad, COUNT(*) as total 
                    FROM tbl_tickets t 
                    LEFT JOIN tbl_prioridades p ON t.prioridad = p.id_prioridad 
                    GROUP BY p.nombre
                ");
                $result = $query->getResultArray();
                echo "<pre>" . print_r($result, true) . "</pre>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>Error en consulta SQL: " . $e->getMessage() . "</p>";
            }
            
            echo "<h3>7. Consulta SQL directa - Categorías:</h3>";
            try {
                $query = $db->query("
                    SELECT c.nombre as nombre_categoria, COUNT(*) as total 
                    FROM tbl_tickets t 
                    LEFT JOIN tbl_categorias c ON t.categoria_id = c.id_categoria 
                    GROUP BY c.nombre
                ");
                $result = $query->getResultArray();
                echo "<pre>" . print_r($result, true) . "</pre>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>Error en consulta SQL: " . $e->getMessage() . "</p>";
            }
            
            echo "<h3>8. Estructura de tabla tbl_tickets:</h3>";
            try {
                $query = $db->query("DESCRIBE tbl_tickets");
                $result = $query->getResultArray();
                echo "<pre>" . print_r($result, true) . "</pre>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            }
            
        } catch (\Exception $e) {
            echo "<p style='color: red;'>Error general: " . $e->getMessage() . "</p>";
            echo "<p>Trace: " . $e->getTraceAsString() . "</p>";
        }
    }
}