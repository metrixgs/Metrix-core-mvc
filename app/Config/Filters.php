<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig {

    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'role' => \App\Filters\RoleFilter::class,
        'maintenance' => \App\Filters\MaintenanceFilter::class,
        'permisos'       => \App\Filters\PermisosFilter::class,
        'cuenta'       => \App\Filters\CuentaFilter::class,
        'sidebar_permisos' => \App\Filters\SidebarPermisosFilter::class,
        'sidebar_admin' => \App\Filters\SidebarAdminFilter::class,
        'sidebar_multi' => \App\Filters\SidebarMultiPermisosFilter::class,
    ];
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
            // 'maintenance', // Esto aplica el filtro globalmente (comentar si no es necesario)
        ],
        'after' => [
            'toolbar',
        // 'honeypot',
        // 'secureheaders',
        ],
    ];
    
    // Filters can be applied to specific routes
    // The cuenta filter is applied to routes in the Routes.php file
    public array $methods = [];
    public $filters = [
        # Rutas en mantenimiento...
        'maintenance' => [
//            'before' => [
//                "*/programar",
//            ],
        ],
    ];
}
