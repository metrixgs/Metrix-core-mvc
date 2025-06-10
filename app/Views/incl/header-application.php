<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="<?= base_url() . "panel/" ?>" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?= base_url() . "public/files/"; ?>images/logo-sm.png?t=<?= time(); ?>" alt="" height="auto" style="max-height: 30px">
                        </span>
                        <span class="logo-lg">
                            <img src="<?= base_url() . "public/files/"; ?>images/logo-dark.png?t=<?= time(); ?>" alt="" height="auto" style="max-height: 30px">
                        </span>
                    </a>

                    <a href="<?= base_url() . "panel/" ?>" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?= base_url() . "public/files/"; ?>images/logo-sm.png?t=<?= time(); ?>" alt="" height="auto" style="max-height: 30px">
                        </span>
                        <span class="logo-lg">
                            <img src="<?= base_url() . "public/files/"; ?>images/logo-light.png?t=<?= time(); ?>" alt="" height="auto" style="max-height: 30px">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                <form class="app-search d-none d-md-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="ID Ticket..." autocomplete="off" id="search-options" value="">
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                        <div data-simplebar style="max-height: 320px;">
                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-1 text-uppercase">Tickets</h6>
                            </div>

                            <?php
                            if (isset($tickets) && !empty($tickets)) {
                                foreach ($tickets as $ticket) {
                                    ?>
                                    <!-- item-->
                                    <a href="<?= base_url() . "tickets/detalle/{$ticket['id']}" ?>" class="dropdown-item notify-item">
                                        <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                        <span><?= $ticket['identificador']; ?></span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="<?= base_url() . "public/files/"; ?>images/logo.png?t=<?= time(); ?>" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?= strstr(session('session_data.nombre'), ' ', true) ?: session('session_data.nombre'); ?></span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                    <?= session('session_data.rol'); ?>
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">¡Bienvenido <?= strstr(session('session_data.nombre'), ' ', true) ?: session('session_data.nombre'); ?>!</h6>
                        <a class="dropdown-item" href="<?= base_url() . "perfil/"; ?>"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Perfil</span></a>
                        <a class="dropdown-item" href="<?= base_url() . "notificaciones/"; ?>"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Notificaciones</span></a>
                        <a class="dropdown-item" href="<?= base_url() . "soporte/conversaciones"; ?>"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Soporte</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url() . "logout/"; ?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Cerrar sesion</span></a>
                    </div>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <a href="<?= base_url() . "logout"; ?>" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-log-out fs-22'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>