<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para la tabla de Archivos
class ArchivosModel extends Model {

    protected $table = 'tbl_documentos_tickets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'ticket_id', 'usuario_id', 'descripcion', 'ruta', 'tipo_mime', 'tamano', 'fecha_subida'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualizacion';
    protected $deletedField = 'fecha_eliminacion';

    public function obtenerArchivos() {
        return $this->select('tbl_documentos_tickets.*, tbl_usuarios.nombre AS nombre_usuario')
                        ->join('tbl_usuarios', 'tbl_documentos_tickets.usuario_id = tbl_usuarios.id', 'left')
                        ->findAll();
    }

    public function obtenerArchivosPorTicket($ticket_id) {
        return $this->select('tbl_documentos_tickets.*, tbl_usuarios.nombre AS nombre_usuario')
                        ->join('tbl_usuarios', 'tbl_documentos_tickets.usuario_id = tbl_usuarios.id', 'left')
                        ->where('tbl_documentos_tickets.ticket_id', $ticket_id)
                        ->findAll();
    }

    public function obtenerArchivo($id) {
        return $this->select('tbl_documentos_tickets.*, tbl_usuarios.nombre AS nombre_usuario')
                        ->join('tbl_usuarios', 'tbl_documentos_tickets.usuario_id = tbl_usuarios.id', 'left')
                        ->where('tbl_documentos_tickets.id', $id)
                        ->first();
    }

    public function crearArchivo($data) {
        $result = $this->insert($data);
        if ($result) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            $descripcion = $data['descripcion'] ?? 'Archivo sin descripción';
            log_activity($usuario_id, 'Archivos', 'Subir', [
                'descripcion' => 'Archivo subido',
                'archivo_descripcion' => $descripcion,
                'ticket_id' => $data['ticket_id'] ?? 'N/A',
                'archivo_id' => $result,
                'accion' => 'Subir archivo'
            ], 'info');
        }
        return $result;
    }

    public function actualizarArchivo($id, $data) {
        $archivoAnterior = $this->find($id);
        $result = $this->update($id, $data);
        if ($result && $archivoAnterior) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            $descripcion = $archivoAnterior['descripcion'] ?? 'ID: ' . $id;
            log_activity($usuario_id, 'Archivos', 'Actualizar', [
                'descripcion' => 'Archivo actualizado',
                'archivo_descripcion' => $descripcion,
                'archivo_id' => $id,
                'accion' => 'Actualizar archivo'
            ], 'info');
        }
        return $result;
    }

    public function eliminarArchivo($id) {
        $archivo = $this->find($id);
        $result = $this->delete($id);
        if ($result && $archivo) {
            helper('bitacora');
            $usuario_id = session('session_data.id') ?? 999;
            $descripcion = $archivo['descripcion'] ?? 'ID: ' . $id;
            log_activity($usuario_id, 'Archivos', 'Eliminar', [
                'descripcion' => 'Archivo eliminado',
                'archivo_descripcion' => $descripcion,
                'archivo_id' => $id,
                'accion' => 'Eliminar archivo'
            ], 'warning');
        }
        return $result;
    }
}
