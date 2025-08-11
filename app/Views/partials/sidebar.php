<?php
// Obtener permisos del usuario actual
use App\Helpers\SidebarPermisosHelper;

$session = session();
$usuarioId = $session->get('session_data.usuario_id');
$sidebarItems = [];

if ($usuarioId) {
    $sidebarItems = SidebarPermisosHelper::generarSidebarUsuario($usuarioId);
}
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="sidebar-logo">
            <span class="brand-text">Salvador</span>
        </div>
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="sidebar-content">
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <?php if (!empty($sidebarItems)): ?>
                    <?php foreach ($sidebarItems as $item): ?>
                        <?php if (isset($item['submenu']) && !empty($item['submenu'])): ?>
                            <!-- Elemento con submenu -->
                            <li class="nav-item has-submenu <?= isset($item['activo']) && $item['activo'] ? 'active' : '' ?>">
                                <a href="#" class="nav-link submenu-toggle" data-toggle="submenu">
                                    <i class="<?= $item['icono'] ?? 'fas fa-circle' ?>"></i>
                                    <span class="nav-text"><?= $item['titulo'] ?></span>
                                    <i class="fas fa-chevron-down submenu-arrow"></i>
                                </a>
                                <ul class="submenu">
                                    <?php foreach ($item['submenu'] as $subitem): ?>
                                        <li class="submenu-item">
                                            <a href="<?= $subitem['url'] ?? '#' ?>" class="submenu-link">
                                                <i class="fas fa-dot-circle submenu-icon"></i>
                                                <span class="submenu-text"><?= $subitem['titulo'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <!-- Elemento simple -->
                            <li class="nav-item <?= isset($item['activo']) && $item['activo'] ? 'active' : '' ?>">
                                <a href="<?= $item['url'] ?? '#' ?>" class="nav-link">
                                    <i class="<?= $item['icono'] ?? 'fas fa-circle' ?>"></i>
                                    <span class="nav-text"><?= $item['titulo'] ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Mensaje cuando no hay permisos -->
                    <li class="nav-item">
                        <div class="nav-link text-muted">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="nav-text">Sin permisos asignados</span>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    
    <!-- Información del usuario -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <div class="user-name"><?= $session->get('session_data.nombre') ?? 'Usuario' ?></div>
                <div class="user-role"><?= $session->get('session_data.rol_id') ?? 'Sin rol' ?></div>
            </div>
            <div class="user-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-up">
                        <?php if (tiene_acceso_sidebar('perfil')): ?>
                            <a class="dropdown-item" href="<?= base_url('perfil') ?>">
                                <i class="fas fa-user"></i> Mi Perfil
                            </a>
                        <?php endif; ?>
                        
                        <?php if (es_administrador_sistema()): ?>
                            <div class="dropdown-divider"></div>
                            <?php if (tiene_acceso_sidebar('configuracion')): ?>
                                <a class="dropdown-item" href="<?= base_url('configuracion') ?>">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                            <?php endif; ?>
                            
                            <?php if (tiene_acceso_sidebar('permisos_sidebar')): ?>
                                <a class="dropdown-item" href="<?= base_url('sidebar-permisos') ?>">
                                    <i class="fas fa-shield-alt"></i> Permisos Sidebar
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay para móviles -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 280px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    z-index: 1000;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-logo {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.brand-text {
    font-size: 20px;
    font-weight: bold;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .brand-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.sidebar-toggle:hover {
    background-color: rgba(255,255,255,0.1);
}

.sidebar-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px 0;
}

.sidebar-nav .nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 0 25px 25px 0;
    margin-right: 20px;
    position: relative;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    color: white;
    text-decoration: none;
}

.nav-item.active .nav-link {
    background-color: rgba(255,255,255,0.2);
    color: white;
}

.nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 15px;
    font-size: 16px;
}

.nav-text {
    transition: opacity 0.3s ease;
    flex: 1;
}

.sidebar.collapsed .nav-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.submenu-arrow {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.has-submenu.open .submenu-arrow {
    transform: rotate(180deg);
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background-color: rgba(0,0,0,0.1);
}

.has-submenu.open .submenu {
    max-height: 500px;
}

.submenu-item {
    margin: 0;
}

.submenu-link {
    display: flex;
    align-items: center;
    padding: 10px 20px 10px 55px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.submenu-link:hover {
    background-color: rgba(255,255,255,0.1);
    color: white;
    text-decoration: none;
}

.submenu-icon {
    width: 16px;
    text-align: center;
    margin-right: 10px;
    font-size: 12px;
}

.submenu-text {
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .submenu {
    display: none;
}

.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding: 15px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    font-size: 24px;
    color: rgba(255,255,255,0.8);
}

.user-details {
    flex: 1;
    min-width: 0;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .user-details {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.user-name {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-actions {
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .user-actions {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.dropdown-menu-up {
    bottom: 100%;
    top: auto;
    margin-bottom: 2px;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 999;
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .sidebar-overlay.show {
        display: block;
    }
}

/* Tooltip para sidebar colapsado */
.sidebar.collapsed .nav-link {
    position: relative;
}

.sidebar.collapsed .nav-link::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 70px;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0,0,0,0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1001;
}

.sidebar.collapsed .nav-link:hover::after {
    opacity: 1;
    visibility: visible;
}

/* Scrollbar personalizado */
.sidebar-content::-webkit-scrollbar {
    width: 6px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}
</style>

<script>
$(document).ready(function() {
    // Toggle sidebar
    $('#sidebar-toggle').on('click', function() {
        $('#sidebar').toggleClass('collapsed');
        
        // Guardar estado en localStorage
        const isCollapsed = $('#sidebar').hasClass('collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed);
    });
    
    // Restaurar estado del sidebar
    const sidebarCollapsed = localStorage.getItem('sidebar_collapsed');
    if (sidebarCollapsed === 'true') {
        $('#sidebar').addClass('collapsed');
    }
    
    // Toggle submenu
    $('.submenu-toggle').on('click', function(e) {
        e.preventDefault();
        const parent = $(this).closest('.has-submenu');
        parent.toggleClass('open');
        
        // Cerrar otros submenus
        $('.has-submenu').not(parent).removeClass('open');
    });
    
    // Overlay para móviles
    $('#sidebar-overlay').on('click', function() {
        $('#sidebar').removeClass('show');
        $(this).removeClass('show');
    });
    
    // Botón de menú para móviles (si existe)
    $(document).on('click', '.mobile-menu-toggle', function() {
        $('#sidebar').addClass('show');
        $('#sidebar-overlay').addClass('show');
    });
    
    // Marcar elemento activo según URL actual
    const currentUrl = window.location.pathname;
    $('.nav-link, .submenu-link').each(function() {
        const linkUrl = new URL($(this).attr('href'), window.location.origin).pathname;
        if (linkUrl === currentUrl) {
            $(this).closest('.nav-item').addClass('active');
            $(this).closest('.has-submenu').addClass('open');
        }
    });
    
    // Agregar tooltips para sidebar colapsado
    $('.nav-link').each(function() {
        const text = $(this).find('.nav-text').text().trim();
        $(this).attr('data-tooltip', text);
    });
});
</script>