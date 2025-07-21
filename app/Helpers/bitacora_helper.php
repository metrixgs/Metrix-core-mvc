<?php

use CodeIgniter\I18n\Time;

if (! function_exists('log_activity')) {
    /**
     * Registra la actividad del usuario en la base de datos.
     *
     * @param int    $usuario_id  El ID del usuario que realiza la acción.
     * @param string $modulo      El módulo donde ocurrió la acción.
     * @param string $accion      Una descripción de la acción realizada.
     * @param array  $detalles    (Opcional) Un array de detalles sobre la acción.
     * @param string $priority (Optional) Prioridad de la accion
     * @param string $file_copy_path (Optional) Ruta de copia del archivo
     *
     * @return bool True si tiene éxito, false si falla.
     */
    function log_activity(int $usuario_id, string $modulo, string $accion, ?array $detalles = null, ?string $priority = 'baja', ?string $file_copy_path = null): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_bitacora_usuarios'); // Nombre de la tabla actualizado

        $data = [
            'fecha_creacion' => Time::now()->toDateTimeString(),
            'usuario_id'   => $usuario_id,
            'cuenta_id'    => session('session_data.cuenta_id') ?? null,
            'ip_address'   => request()->getIPAddress(),
            'modulo'       => $modulo,
            'accion'       => $accion,
            'detalles'     => $detalles ? json_encode($detalles) : null,
            'priority'     => $priority,
            'file_copy_path' => $file_copy_path,
        ];

        try {
            return $builder->insert($data);
        } catch (\Exception $e) {
            // Registrar el error o manejarlo apropiadamente.
            error_log('Error logging activity: ' . $e->getMessage());
            return false;
        }
    }
}