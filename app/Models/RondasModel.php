<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Rondas
class RondasModel extends Model {

    protected $table = 'tbl_rondas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'campana_id', 'segmentacion_id', 'nombre', 'coordinador', 'encargado',
        'coordinador_campana',
        'encuesta_ronda', // Nuevo campo
        'fecha_actividad', 'hora_actividad', 'estado', 'ronda_id', 'universo' // Añadido 'universo'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Asegúrate de que SurveyResponseModel esté cargado en el controlador que usa este método
    // o instáncialo aquí si es necesario.
    // protected $surveyResponseModel;

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->surveyResponseModel = new \App\Models\SurveyResponseModel();
    // }

    public function obtenerRondas() {
        return $this->findAll();
    }

    public function obtenerRonda($id) {
        return $this->find($id);
    }

    public function crearRonda($data) {
        return $this->insert($data);
    }

    public function actualizarRonda($id, $data) {
        return $this->update($id, $data);
    }

    public function eliminarRonda($id) {
        return $this->delete($id);
    }

    // ✅ Nuevo método para mostrar nombres en lugar de IDs
    public function obtenerRondasConUsuarios()
    {
        return $this->select('tbl_rondas.*,
                              coord.nombre AS nombre_coordinador,
                              encar.nombre AS nombre_encargado')
                    ->join('tbl_usuarios AS coord', 'coord.id = tbl_rondas.coordinador', 'left')
                    ->join('tbl_usuarios AS encar', 'encar.id = tbl_rondas.encargado', 'left')
                    ->findAll();
    }

    public function contarBrigadasActivas($ronda_id)
    {
        $ronda = $this->find($ronda_id);
        if (!$ronda) {
            return 0;
        }
        $count = 0;
        if (!empty($ronda['coordinador'])) {
            $count++;
        }
        if (!empty($ronda['encargado'])) {
            $count++;
        }
        return $count;
    }

    public function contarVisitasRealizadas($ronda_id)
    {
        // Instanciar el modelo si no está ya disponible (ej. inyectado por el controlador)
        $surveyResponseModel = new \App\Models\SurveyResponseModel();
        return $surveyResponseModel->where('id_ronda', $ronda_id)->countAllResults();
    }
}
