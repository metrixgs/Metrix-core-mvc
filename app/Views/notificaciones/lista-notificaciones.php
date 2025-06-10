<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Notificaciones</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Mis Notificaciones</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para crear una nueva notificacion-->
                                    <div class="search-box ms-2">
                                        <a href="<?= base_url() . "notificaciones/nueva"; ?>" class="btn btn-primary">Nueva</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <?php if (empty($notificaciones)): ?>
                            <div class="alert alert-info" role="alert">
                                No tienes notificaciones.
                            </div>
                        <?php else: ?>
                            <?php foreach ($notificaciones as $notif): ?>
                                <div class="text-reset notification-item d-block dropdown-item position-relative" style="margin-bottom: 10px; border-bottom: 1px solid #ddd;">
                                    <div class="d-flex">
                                        <!-- Avatar icon or user picture -->
                                        <div class="avatar-xs me-3 flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                <i class="bx bx-badge-check"></i>
                                            </span>
                                        </div>

                                        <!-- Notification content -->
                                        <div class="flex-grow-1">
                                            <a href="#!" class="" style="text-decoration: none; color: inherit;">
                                                <h6 class="mt-0 mb-2 lh-base" style="font-size: 14px; color: #333;">
                                                    <?= $notif['titulo']; ?>
                                                </h6>
                                                <p class="mb-0" style="font-size: 12px; color: #555;">
                                                    <?= $notif['descripcion']; ?>
                                                </p>
                                            </a>
                                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted" style="font-size: 12px; color: #888;">
                                                <span>
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?php
                                                    $timestamp = strtotime($notif['fecha_creacion']);
                                                    $current_time = time();
                                                    $time_difference = $current_time - $timestamp;
                                                    $seconds = $time_difference;

                                                    $minutes = round($seconds / 60);
                                                    $hours = round($seconds / 3600);
                                                    $days = round($seconds / 86400);
                                                    $weeks = round($seconds / 604800);
                                                    $months = round($seconds / 2629440);
                                                    $years = round($seconds / 31553280);

                                                    if ($seconds <= 60) {
                                                        echo "Hace $seconds segundos";
                                                    } else if ($minutes <= 60) {
                                                        echo "Hace $minutes minutos";
                                                    } else if ($hours <= 24) {
                                                        echo "Hace $hours horas";
                                                    } else if ($days <= 7) {
                                                        echo "Hace $days días";
                                                    } else if ($weeks <= 4.3) {
                                                        echo "Hace $weeks semanas";
                                                    } else if ($months <= 12) {
                                                        echo "Hace $months meses";
                                                    } else {
                                                        echo "Hace $years años";
                                                    }
                                                    ?>
                                                </span>
                                            </p>
                                        </div>

                                        <!-- Icono de eliminación (fuera del contenedor de stretched-link) -->
                                        <div class="ms-2">
                                            <a href="<?= base_url() . 'notificaciones/eliminar/' . $notif['id']; ?>" class="text-danger" style="font-size: 18px;">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
