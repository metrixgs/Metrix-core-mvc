<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= base_url() . "panel/"; ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?= base_url() . "public/files/"; ?>images/logo-sm.png?t=<?= time(); ?>" alt="" style="width: 80%">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url() . 'public/files/images/logo-dark.png'; ?>?t=<?= time(); ?>" alt="" style="width: 100%">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= base_url() . "panel/" ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?= base_url() . "public/files/"; ?>images/logo-sm.png?t=<?= time(); ?>" alt="" style="width: 80%">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url() . "public/files/"; ?>images/logo-light.png?t=<?= time(); ?>" alt="" style="width: 100%">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="<?= base_url() . "public/files/"; ?>images/logo.png?t=<?= time(); ?>" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text"><?= strstr(session('session_data.nombre'), ' ', true) ?: session('session_data.nombre'); ?></span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text">
                        <i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> 
                        <!-- Mostrar el tipo de usuario dependiendo del rol -->
                        <?php
                        $role = session('session_data.rol_id');
                        if ($role == 1) {
                            $roleName = 'Administrador';
                        } elseif ($role == 2) {
                            $roleName = 'Usuario';
                        } elseif ($role == 3) {
                            $roleName = 'Cliente';
                        } else {
                            $roleName = 'Desconocido';
                        }
                        ?>
                        <span class="align-middle"><?= $roleName; ?></span>
                    </span>
                </span>
            </span>
        </button>

        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">¡Bienvenido <?= strstr(session('session_data.nombre'), ' ', true) ?: session('session_data.nombre'); ?>!</h6>
            <a class="dropdown-item" href="<?= base_url() . "perfil/"; ?>"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Perfil</span></a>
            <a class="dropdown-item" href="<?= base_url() . "notificaciones/"; ?>"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Notificaciones</span></a>
            <a class="dropdown-item" href="<?= base_url() . "soporte/"; ?>"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Soporte</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= base_url() . 'salir'; ?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Cerrar sesion</span></a>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <!-- Inicio -->
                <li class="menu-title"><span data-key="t-menu">Menu Principal</span></li>
                <li class="nav-item">
                    <a id="inicio" class="nav-link menu-link" href="<?= base_url() . "panel/"; ?>">
                        <i class="ri ri-home-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <hr>

                <!-- Operación -->
                <a class="nav-link menu-link" href="#sidebarOperacion" data-bs-toggle="collapse" data-bs-parent="#navbar-nav" role="button" aria-expanded="false" aria-controls="sidebarOperacion">
                    <i class="ri ri-settings-2-line"></i> <span>Operación</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarOperacion" data-bs-parent="#navbar-nav">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="#sidebarDependencias" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#sidebarOperacion">Dependencias</a>
                            <div class="collapse menu-dropdown" id="sidebarDependencias" data-bs-parent="#sidebarOperacion">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'dependencias/'; ?>" class="nav-link">Lista de Dependencias</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'clientes/'; ?>" class="nav-link">Clientes</a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url() . "reportes/requerimientos"; ?>" class="nav-link">Reportes / Incidencias</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . "reportes/tareas"; ?>" class="nav-link">Reporte / Tareas</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'notificaciones/'; ?>" class="nav-link">Notificaciones</a>
                        </li>
                    </ul>
                </div>
                <hr>

                <!-- Módulos -->
                <a class="nav-link menu-link" href="#sidebarModulos" data-bs-toggle="collapse" data-bs-parent="#navbar-nav" role="button" aria-expanded="false" aria-controls="sidebarModulos">
                    <i class="ri ri-stack-line"></i> <span>Módulos</span>
                </a>


                <div class="collapse menu-dropdown" id="sidebarModulos" data-bs-parent="#navbar-nav">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="#sidebarMapas" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#sidebarMapas">Mapa</a>
                            <div class="collapse menu-dropdown" id="sidebarMapas" data-bs-parent="#sidebarMapas">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="<?= base_url('mapa'); ?>" class="nav-link">Incidencias</a>
                                    </li>
                                </ul>  
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('surveys'); ?>" class="nav-link">Encuestas</a>
                        </li>


                        <li class="nav-item">
                            <a href="<?= base_url() . 'usuarios/'; ?>" class="nav-link">Usuarios</a>
                        </li>
<li class="nav-item">
    <a href="<?= base_url('cuentas/create'); ?>" class="nav-link">
        Crear y Administrar Clientes
    </a>
</li>
<li class="nav-item">
    <a href="<?= base_url('directorio'); ?>" class="nav-link">
        Directorio(CRM)
    </a>
</li>
<li class="nav-item">
    <a href="<?= base_url('campanas/'); ?>" class="nav-link">
        Lista de Campañas
    </a>
</li>

                        <li class="nav-item">
                            <a href="#sidebarIncidencias" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#sidebarModulos">Incidencias</a>
                            <div class="collapse menu-dropdown" id="sidebarIncidencias" data-bs-parent="#sidebarModulos">
                                <ul class="nav nav-sm flex-column">
                                    
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'dashboard-metrix'; ?>" class="nav-link">Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tickets'; ?>" class="nav-link">Todas las Incidencias</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tickets/tipos'; ?>" class="nav-link">Tipos de Incidencias</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tickets/creados'; ?>" class="nav-link">Incidencias Creadas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tickets/asignados'; ?>" class="nav-link">Incidencias Asignadas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tickets/nuevo'; ?>" class="nav-link">Nueva Incidencia</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'tareas/'; ?>" class="nav-link">Mis Tareas</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#sidebarEventos" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#sidebarModulos">Dashboard Eventos</a>
                            <div class="collapse menu-dropdown" id="sidebarEventos" data-bs-parent="#sidebarModulos">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="<?= base_url() . 'dashboard-eventos'; ?>" class="nav-link">Dashboard Eventos</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
    <a href="#sidebarCampanasRondas" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#sidebarModulos">
        Campañas & Rondas
    </a>
    <div class="collapse menu-dropdown" id="sidebarCampanasRondas" data-bs-parent="#sidebarModulos">
        <ul class="nav nav-sm flex-column">
            <!-- Campañas -->
            <li class="nav-item">
                <a href="<?= base_url() . 'campanas'; ?>" class="nav-link">Lista de Campañas</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() . 'campanas/tipos'; ?>" class="nav-link">Tipos de Campañas</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() . 'campanas/subtipos'; ?>" class="nav-link">Sub-Tipos de Campañas</a>
            </li>
            <!-- Rondas -->
            <li class="nav-item">
                <a href="<?= base_url() . 'rondas'; ?>" class="nav-link">Lista de Rondas</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() . 'rondas/zonas'; ?>" class="nav-link">Zonas de Rondas</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() . 'rondas/puntos'; ?>" class="nav-link">Puntos de Rondas</a>
            </li>
        </ul>
    </div>
</li>

                    </ul>
                </div>
                <hr>

                <!-- Ajustes & Soporte -->
                <a class="nav-link menu-link" href="#sidebarAjustes" data-bs-toggle="collapse" role="button" aria-expanded="false">
                    <i class="ri ri-settings-3-line"></i> <span>Ajustes & Soporte</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarAjustes">
                    <ul class="nav nav-sm flex-column">
                        <li><a href="<?= base_url('bitacora'); ?>" class="nav-link">Bitácora de Actividad</a></li>
                        <li>
                            <a href="#sidebarUsuarios" class="nav-link" data-bs-toggle="collapse">Control de Usuarios</a>
                            <div class="collapse menu-dropdown" id="sidebarUsuarios">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="<?= base_url('usuarios/tipos'); ?>" class="nav-link">Tipos de Usuarios</a></li>
                                    <li><a href="<?= base_url('usuarios/permisos'); ?>" class="nav-link">Permisos & Roles</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a href="<?= base_url('notificaciones'); ?>" class="nav-link">Notificaciones</a></li>
                        <li>
                            <a href="#sidebarSoporte" class="nav-link" data-bs-toggle="collapse">Ticket de Soporte</a>
                            <div class="collapse menu-dropdown" id="sidebarSoporte">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="<?= base_url('soporte/nuevo'); ?>" class="nav-link">Nuevo Ticket</a></li>
                                    <!-- Elemento añadido del segundo menú -->
                                    <li><a href="<?= base_url('soporte/conversaciones'); ?>" class="nav-link">Conversaciones</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <hr>

                <!-- Configuración -->
                <a class="nav-link menu-link" href="#sidebarConfiguracion" data-bs-toggle="collapse" data-bs-parent="#navbar-nav" role="button" aria-expanded="false" aria-controls="sidebarConfiguracion">
                    <i class="ri ri-settings-3-line"></i> <span>Configuración</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarConfiguracion" data-bs-parent="#navbar-nav">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'perfil/'; ?>" class="nav-link">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'configuracion/smtp'; ?>" class="nav-link">SMTP</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'configuracion/sla'; ?>" class="nav-link">TDR</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'soporte/conversaciones'; ?>" class="nav-link">Soporte</a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Obtenemos la URL actual para identificar la página activa
        const currentUrl = window.location.pathname;

        // Guardamos todos los enlaces del menú
        const menuLinks = document.querySelectorAll('.nav-link');

        // Verificamos si hay alguna ruta guardada en localStorage
        const lastActivePath = localStorage.getItem('lastActivePath');
        const lastOpenMenus = localStorage.getItem('lastOpenMenus') ? JSON.parse(localStorage.getItem('lastOpenMenus')) : [];

        // Función para abrir un menú colapsable
        function openCollapse(target) {
            const collapse = new bootstrap.Collapse(target, {toggle: false});
            collapse.show();
        }

        // Función para guardar los menús abiertos
        function saveOpenMenus() {
            const openMenuIds = [];
            document.querySelectorAll('.menu-dropdown.collapse.show').forEach(openMenu => {
                openMenuIds.push(openMenu.id);
            });
            localStorage.setItem('lastOpenMenus', JSON.stringify(openMenuIds));
        }

        // Función para limpiar todas las selecciones activas
        function clearAllActiveLinks() {
            menuLinks.forEach(link => {
                link.classList.remove('active');
            });
        }

        // Primero, limpiamos cualquier selección anterior
        clearAllActiveLinks();

        // Verificamos si hay un enlace activo basado en la URL actual
        let exactMatch = null;
        let bestMatch = null;
        let bestMatchLength = 0;

        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.includes('#')) {
                // Comprobamos si es una coincidencia exacta
                if (currentUrl === href) {
                    exactMatch = link;
                }
                // O si es la mejor coincidencia parcial
                else if (currentUrl.includes(href) && href.length > bestMatchLength) {
                    bestMatch = link;
                    bestMatchLength = href.length;
                }
            }
        });

        // Usamos la coincidencia exacta o la mejor coincidencia, en ese orden
        const activeLink = exactMatch || bestMatch;

        // Si encontramos un enlace activo, lo marcamos y abrimos todos sus menús padres
        if (activeLink) {
            // Marcamos el enlace como activo
            activeLink.classList.add('active');

            // Guardamos la ruta activa en localStorage
            localStorage.setItem('lastActivePath', currentUrl);

            // Abrimos todos los menús padres
            let parent = activeLink.closest('.collapse');
            while (parent) {
                openCollapse(parent);
                parent = parent.closest('.collapse');
            }

            // Guardamos los menús abiertos
            saveOpenMenus();
        }
        // Si no hay enlace activo pero hay menús guardados, los abrimos
        else if (lastOpenMenus.length > 0 && lastActivePath) {
            lastOpenMenus.forEach(menuId => {
                const menuElement = document.getElementById(menuId);
                if (menuElement) {
                    openCollapse(menuElement);
                }
            });

            // Intentamos encontrar y activar el enlace guardado
            menuLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.includes('#') && href === lastActivePath) {
                    link.classList.add('active');
                }
            });
        }

        // Agregamos eventos a los enlaces del menú
        menuLinks.forEach(link => {
            // Para los enlaces que llevan a páginas (no colapsables)
            if (link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
                link.addEventListener('click', () => {
                    // Limpiamos todas las selecciones
                    clearAllActiveLinks();

                    // Marcamos solo este enlace como activo
                    link.classList.add('active');

                    // Guardamos la ruta activa
                    localStorage.setItem('lastActivePath', link.getAttribute('href'));
                });
            }
            // Para los enlaces colapsables
            else if (link.getAttribute('data-bs-toggle') === 'collapse') {
                link.addEventListener('click', () => {
                    // Solo guardamos el estado de los menús
                    setTimeout(saveOpenMenus, 300);
                });
            }
        });
    });
</script>

<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">