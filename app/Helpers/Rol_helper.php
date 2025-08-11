<?php

 if (!function_exists('obtener_rol')) {

    /**
     * Retorna el prefijo de ruta basado en el rol_id.
     *
     * @return string
     */
    function obtener_rol() {
        $rolId = session('session_data.rol_id');

        $roles = [
            16 => 'master/',
            2 => 'administrador/',
            3 => 'cliente/',
            4 => 'sistemas/',
            5 => 'operador/',
        ];

        return isset($roles[$rolId]) ? $roles[$rolId] : '';
    }
}



