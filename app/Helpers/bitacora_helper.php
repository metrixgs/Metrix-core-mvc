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
     * @param int    $usuario_afectado_id (Optional) ID del usuario afectado por la acción
     *
     * @return bool True si tiene éxito, false si falla.
     */
    function log_activity(int $usuario_id, string $modulo, string $accion, ?array $detalles = null, ?string $priority = 'baja', ?string $file_copy_path = null, ?int $usuario_afectado_id = null): bool
    {
        $db = \Config\Database::connect();
        
        // ✅ VALIDAR QUE EL USUARIO EXISTE ANTES DE INSERTAR
        if (!validar_usuario_existe($usuario_id)) {
            // Si el usuario no existe, intentar crear usuario genérico
            crear_usuario_generico();
            // Asignar el log al usuario genérico (ID 999)
            $usuario_id = 999;
        }
        
        $builder = $db->table('tbl_bitacora_usuarios'); // Nombre de la tabla actualizado

        $data = [
            'fecha_creacion' => Time::now()->toDateTimeString(),
            'usuario_id'   => $usuario_id,
            'usuario_afectado_id' => $usuario_afectado_id,
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

if (! function_exists('validar_usuario_existe')) {
    /**
     * Valida si un usuario existe en la base de datos.
     *
     * @param int $usuario_id El ID del usuario a validar.
     * @return bool True si el usuario existe, false si no.
     */
    function validar_usuario_existe(int $usuario_id): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_usuarios');
        
        $usuario = $builder->where('id', $usuario_id)->get()->getRow();
        
        return $usuario !== null;
    }
}

if (! function_exists('crear_usuario_generico')) {
    /**
     * Crea un usuario genérico con ID 999 si no existe.
     * Este usuario se usa para asignar logs huérfanos.
     *
     * @return bool True si se creó o ya existía, false si falló.
     */
    function crear_usuario_generico(): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_usuarios');
        
        // Verificar si ya existe el usuario genérico
        $usuarioExiste = $builder->where('id', 999)->get()->getRow();
        
        if ($usuarioExiste) {
            return true; // Ya existe
        }
        
        // Crear el usuario genérico
        $data = [
            'id' => 999,
            'rol_id' => 31, // Rol Ciudadano por defecto
            'cargo' => 'Usuario Genérico',
            'nombre' => 'Usuario Eliminado/Huérfano',
            'correo' => 'usuario.generico@sistema.local',
            'telefono' => '0000000000',
            'contrasena' => '$2y$10$dummy.hash.for.generic.user.account.placeholder',
            'fecha_registro' => date('Y-m-d H:i:s'),
            'cuenta_id' => null
        ];
        
        try {
            return $builder->insert($data);
        } catch (\Exception $e) {
            error_log('Error creando usuario genérico: ' . $e->getMessage());
            return false;
        }
    }
}

if (! function_exists('limpiar_bitacora_huerfanos')) {
    /**
     * Función de mantenimiento para limpiar registros huérfanos
     * en la bitácora y asignarlos al usuario genérico.
     *
     * @return array Resultado de la operación con estadísticas.
     */
    function limpiar_bitacora_huerfanos(): array
    {
        $db = \Config\Database::connect();
        
        // Contar registros huérfanos
        $query = $db->query("
            SELECT COUNT(*) as total 
            FROM tbl_bitacora_usuarios b 
            LEFT JOIN tbl_usuarios u ON b.usuario_id = u.id 
            WHERE u.id IS NULL
        ");
        
        $huerfanos = $query->getRow()->total;
        
        if ($huerfanos == 0) {
            return [
                'success' => true,
                'message' => 'No se encontraron registros huérfanos',
                'huerfanos_encontrados' => 0,
                'huerfanos_corregidos' => 0
            ];
        }
        
        // Crear usuario genérico si no existe
        crear_usuario_generico();
        
        // Actualizar registros huérfanos
        $result = $db->query("
            UPDATE tbl_bitacora_usuarios b 
            LEFT JOIN tbl_usuarios u ON b.usuario_id = u.id 
            SET b.usuario_id = 999 
            WHERE u.id IS NULL
        ");
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Registros huérfanos corregidos exitosamente',
                'huerfanos_encontrados' => $huerfanos,
                'huerfanos_corregidos' => $db->affectedRows()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al corregir registros huérfanos',
                'huerfanos_encontrados' => $huerfanos,
                'huerfanos_corregidos' => 0
            ];
        }
    }
}