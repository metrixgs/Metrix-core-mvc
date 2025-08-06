<?php
$session = session();
$flashdata = $session->getFlashdata();
?>

<?php if ($flashdata && isset($flashdata['titulo'], $flashdata['mensaje'], $flashdata['tipo'])): ?>
    <div class="alert alert-<?= esc($flashdata['tipo']); ?> alert-dismissible fade show" role="alert">
        <strong><?= esc($flashdata['titulo']); ?></strong> <?= esc($flashdata['mensaje']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php endif; ?>

<div class="page-content" style="background-color: #f8f9fa;">
    <div class="container-fluid">

        <!-- Título de página -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between p-3 rounded shadow-sm" style="background-color: #1f2d3d;">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="bi bi-people-fill me-2"></i> <?= esc($titulo_pagina); ?>
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-light">
                            <li class="breadcrumb-item"><a class="text-light" href="<?= base_url('panel'); ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="text-light" href="<?= base_url('brigadas'); ?>">Brigadas</a></li>
                            <li class="breadcrumb-item active text-light">Detalles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles -->
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Detalles de la Brigada: <?= esc($brigada['nombre']); ?>
                        </h5>
                        <a href="<?= base_url('brigadas'); ?>" class="btn btn-primary d-none d-md-inline-block">
                            <i class="bi bi-arrow-left-circle me-1"></i> Volver
                        </a>
                    </div>
                    <div class="card-body bg-light">
                        <p><strong><i class="bi bi-building me-1"></i> Área:</strong> <?= esc($brigada['area_nombre'] ?: 'Sin área'); ?></p>
                        <p><strong><i class="bi bi-person-check me-1"></i> Coordinador:</strong> <?= esc($brigada['coordinador_nombre'] ?: 'Sin coordinador'); ?></p>
                        <p><strong><i class="bi bi-person-badge me-1"></i> Enlace:</strong> <?= esc($brigada['enlace_nombre'] ?: 'Sin enlace'); ?></p>
                        <p><strong><i class="bi bi-people me-1"></i> Integrantes:</strong></p>
                        <?php if (!empty($brigada['operativos'])): ?>
                            <?php foreach ($brigada['operativos'] as $integrante): ?>
                                <span class="badge rounded-pill bg-dark me-1 mb-1"><?= esc($integrante); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">Sin integrantes</span>
                        <?php endif; ?>
                        <p class="mt-3"><strong><i class="bi bi-calendar-check me-1"></i> Fecha de Creación:</strong> <?= date('d/m/Y H:i:s', strtotime($brigada['created_at'])); ?></p>
                    </div>
                </div>

                <!-- Registro de Actividad -->
                <div class="card shadow border-0">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Registro de Actividad
                        </h5>
                    </div>
                    <div class="card-body bg-light">
                        <?php if (!empty($brigada['actividades'])): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($brigada['actividades'] as $actividad): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><?= esc($actividad['tipo_actividad']); ?></strong>
                                            <p class="mb-0 text-muted"><?= esc($actividad['descripcion']); ?></p>
                                        </div>
                                        <span class="text-muted small"><?= date('d/m/Y H:i:s', strtotime($actividad['fecha'])); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No hay actividades registradas para esta brigada.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>