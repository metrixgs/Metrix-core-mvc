<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= esc($titulo_pagina ?? 'Bitácora'); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('bitacora') ?>">Bitácora</a></li>
                            <li class="breadcrumb-item active">Bitácora</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Tarjetas de estadísticas -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Total Actividades</span>
                            <i class="bi bi-activity text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $total_actividades ?></div>
                        <small class="text-muted">Desde el inicio</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Hoy</span>
                            <i class="bi bi-clock text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $actividades_hoy ?></div>
                        <small class="text-muted">Actividades de hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Inicios de Sesión</span>
                            <i class="bi bi-box-arrow-in-right text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $inicios_sesion ?></div>
                        <small class="text-muted">Total de accesos</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Creaciones</span>
                            <i class="bi bi-plus-circle text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $creaciones ?></div>
                        <small class="text-muted">Elementos creados</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de actividades -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <i class="bi bi-list"></i> Registro de Actividades
                </div>
                <form id="filtrosForm" method="get" action="<?= base_url('bitacora') ?>" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Buscar actividades..." value="<?= esc($filtros_actuales['search'] ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <select name="type" class="form-select form-select-sm">
                            <option value="Todas" <?= ($filtros_actuales['type'] ?? '') == 'Todas' ? 'selected' : '' ?>>Todas</option>
                            <?php foreach ($tipos_accion as $tipo): ?>
                                <option value="<?= esc($tipo->accion ?? $tipo['accion']) ?>" <?= ($filtros_actuales['type'] ?? '') == ($tipo->accion ?? $tipo['accion']) ? 'selected' : '' ?>>
                                    <?= esc($tipo->accion ?? $tipo['accion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="Todos">Todos los usuarios</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= esc($usuario->id ?? $usuario['id']) ?>" <?= ($filtros_actuales['user_id'] ?? '') == ($usuario->id ?? $usuario['id']) ? 'selected' : '' ?>>
                                    <?= esc($usuario->nombre ?? $usuario['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                        <a href="<?= base_url('bitacora') ?>" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Actividad</th>
                            <th scope="col">Módulo</th>
                            <th scope="col">Detalles</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bitacoras)) : ?>
                            <?php foreach ($bitacoras as $index => $bitacora) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($bitacora->nombre_usuario ?? $bitacora['nombre_usuario']) ?></td>
                                    <td><?= esc($bitacora->accion ?? $bitacora['accion']) ?></td>
                                    <td><?= esc($bitacora->modulo ?? $bitacora['modulo']) ?></td>
                                    <td><?= esc($bitacora->detalles ?? $bitacora['detalles']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($bitacora->fecha_creacion ?? $bitacora['fecha_creacion'])) ?></td>
                                    <td><?= date('H:i:s', strtotime($bitacora->fecha_creacion ?? $bitacora['fecha_creacion'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron actividades.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">Mostrando <?= count($bitacoras) ?> registros</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                        <li class="page-item active"><a class="page-link">1</a></li>
                        <li class="page-item"><a class="page-link">2</a></li>
                        <li class="page-item"><a class="page-link">3</a></li>
                        <li class="page-item"><a class="page-link">Siguiente</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>