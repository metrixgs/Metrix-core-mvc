<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= esc($titulo_pagina ?? 'Datos de la Campaña'); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'panel'); ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'campanas'); ?>">Campañas</a></li>
                            <li class="breadcrumb-item active">Detalle Campaña</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <?php if (session()->has('titulo')): ?>
            <div class="alert alert-<?= esc(session('tipo')); ?>" role="alert">
                <strong><?= esc(session('titulo')); ?></strong> <?= esc(session('mensaje')); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($campana) && is_array($campana)): ?>
            <!-- Información principal y acciones -->
            <div class="row mb-4">
                <div class="col-md-5">
                    <p class="text-muted mb-1">ID de Campaña: <span class="fw-medium text-dark">#CAM-<?= str_pad($campana['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></span></p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 me-2">Nombre de la Campaña:</label>
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" class="form-control form-control-sm" value="<?= esc($campana['nombre'] ?? 'No especificado'); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="d-flex align-items-center">
                        <label class="form-label mb-0 me-2">Estatus:</label>
                        <?php
                        $badgeClass = 'bg-secondary';
                        $estado = $campana['estado'] ?? 'Desconocido';
                        switch ($estado) {
                            case 'Programada':
                                $badgeClass = 'bg-warning';
                                break;
                            case 'Activa':
                                $badgeClass = 'bg-success';
                                break;
                            case 'Finalizada':
                                $badgeClass = 'bg-info';
                                break;
                            case 'Propuesta':
                                $badgeClass = 'bg-danger';
                                break;
                        }
                        ?>
                        <span class="badge rounded-pill <?= $badgeClass; ?>"><?= esc($estado); ?></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light" id="btnExportar">Exportar</button>
                        <a href="<?= base_url('campanas/ficha/' . ($campana['id'] ?? 0)); ?>" class="btn btn-sm btn-warning">Ficha Informativa</a>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalRondasCampana">Ver Rondas</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Columna izquierda - Datos de la campaña -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="formEditarCampana" method="post" action="<?= base_url('campanas/actualizar'); ?>">
                                <input type="hidden" name="campana_id" value="<?= $campana['id'] ?? 0; ?>">

                                <!-- Tipo de Campaña -->
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Campaña:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= !empty($campana['nombre_tipo_campana']) ? esc($campana['nombre_tipo_campana']) : 'No especificado'; ?><?= !empty($campana['nombre_subtipo_campana']) ? ' / ' . esc($campana['nombre_subtipo_campana']) : ''; ?>" readonly>
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalEditarCampana">
                                            <i class="ri-pencil-fill"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Territorio -->
                                <div class="mb-3">
                                    <label class="form-label">Territorio:<span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge bg-light text-dark"><?= esc($campana['territorio'] ?? 'No especificado'); ?></span>
                                        <?php if (!empty($campana['sectorizacion'])) {
                                            $sectorizaciones = json_decode($campana['sectorizacion'], true);
                                            if (is_array($sectorizaciones) && ($campana['territorio'] ?? '') === 'geograficos') {
                                                if (isset($sectorizaciones['level'])) {
                                                    echo '<span class="badge bg-light text-dark">Nivel: ' . esc($sectorizaciones['level']) . '</span>';
                                                }
                                                if (isset($sectorizaciones['subtype'])) {
                                                    echo '<span class="badge bg-light text-dark">' . esc($sectorizaciones['subtype']) . '</span>';
                                                }
                                            } else {
                                                echo '<span class="badge bg-light text-dark">' . esc($campana['sectorizacion']) . '</span>';
                                            }
                                        } ?>
                                    </div>
                                </div>

                                <!-- Segmentación -->
                                <div class="mb-3">
                                    <label class="form-label">Segmentación:<span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <?php if (!empty($campana['sectorizacion'])) {
                                            $sectorizaciones = json_decode($campana['sectorizacion'], true);
                                            if (is_array($sectorizaciones) && ($campana['territorio'] ?? '') === 'geograficos' && isset($sectorizaciones['subtype'])) {
                                                echo '<span class="badge bg-light text-dark">' . esc($sectorizaciones['subtype']) . '</span>';
                                            } else {
                                                echo '<span class="badge bg-light text-dark">' . esc($campana['sectorizacion']) . '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-light text-dark">No especificado</span>';
                                        } ?>
                                    </div>
                                </div>

                                <!-- Universo detectado -->
                                <div class="mb-3">
                                    <label class="form-label">Universo detectado:</label>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge bg-light text-dark"><?= esc($campana['universo'] ?? 'No especificado'); ?></span>
                                    </div>
                                </div>

                                <hr>

                                <!-- área Responsable -->
                                <div class="mb-3">
                                    <label class="form-label">Área Responsable:</label>
                                    <input type="text" class="form-control" value="<?= !empty($campana['nombre_area']) ? esc($campana['nombre_area']) : 'No especificada'; ?>" readonly>
                                </div>

                                <!-- Coordinador -->
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Coordinador(a):<span class="text-danger">*</span> (1/1)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="coordinador" value="<?= esc($campana['coordinador'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <!-- Fechas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                            <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($campana['fecha_inicio']) ? date('Y-m-d', strtotime($campana['fecha_inicio'])) : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fecha de Término:<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                            <input type="date" class="form-control" name="fecha_fin" value="<?= isset($campana['fecha_fin']) ? date('Y-m-d', strtotime($campana['fecha_fin'])) : ''; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCampana">Eliminar Campaña</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sección de Indicadores Generales -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Indicadores Generales</h5>
                            <button type="button" class="btn btn-sm btn-success">Más Indicadores</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Impactos -->
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title rounded-circle bg-light text-danger">
                                                <i class="ri-target-line fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Impactos: 73/144 (51%)</h6>
                                            <div class="d-flex gap-2">
                                                <small class="text-success"><i class="ri-arrow-up-line"></i> 73/144 (51%)</small>
                                                <small class="text-warning"><i class="ri-arrow-right-line"></i> 22/144 (15%)</small>
                                                <small class="text-danger"><i class="ri-arrow-down-line"></i> 15/144 (10%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Zona Recorrida -->
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                <i class="ri-map-pin-line fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Zona Recorrida: 17/41 (41%)</h6>
                                            <div class="d-flex gap-2">
                                                <small>106 • 113 • 124 • <span class="badge bg-success">+3 más</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos de Actividad -->
                                <div class="col-12">
                                    <div class="row mt-2">
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-2">
                                                <i class="ri-calendar-check-line text-muted"></i>
                                                <h6 class="mb-0">Encuestas</h6>
                                                <p class="mb-0 fw-medium">41/100 (41%)</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-2">
                                                <i class="ri-user-follow-line text-muted"></i>
                                                <h6 class="mb-0">Personas</h6>
                                                <p class="mb-0 fw-medium">120/300 (40%)</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-2">
                                                <i class="ri-user-voice-line text-muted"></i>
                                                <h6 class="mb-0">Individuos</h6>
                                                <p class="mb-0 fw-medium">250 registros</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - Mapa y Tabla de Incidencias -->
                <div class="col-lg-6">
                    <!-- Mapa -->
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <div id="campaignMap" style="height: 400px;"></div>
                            <div id="mapError" class="alert alert-warning d-none m-2" role="alert">
                                No se pudieron cargar los datos geográficos para la campaña.
                            </div>
                        </div>
                    </div>

                    <!-- Incidencias -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Lista de Incidencias</h5>
                            <a href="<?= base_url('tickets/nuevo'); ?>" class="btn btn-sm btn-primary">Nueva Incidencia</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable display table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Área</th>
                                            <th>Estado</th>
                                            <th>Vencimiento</th>
                                            <th class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($tickets) && !empty($tickets)): ?>
                                            <?php foreach ($tickets as $ticket): ?>
                                                <tr>
                                                    <td><?= esc($ticket['titulo'] ?? ''); ?></td>
                                                    <td><?= esc($ticket['nombre_area'] ?? ''); ?></td>
                                                    <td>
                                                        <?php
                                                        $estado_clase = ($ticket['estado'] ?? '') === 'Cerrado' ? 'text-danger' : 'text-success';
                                                        ?>
                                                        <span class="<?= $estado_clase; ?>"><?= esc($ticket['estado'] ?? ''); ?></span>
                                                    </td>
                                                    <td><?= isset($ticket['fecha_vencimiento']) ? date('d/m/Y', strtotime($ticket['fecha_vencimiento'])) : ''; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url(obtener_rol() . 'tickets/detalle/' . ($ticket['id'] ?? '')); ?>" class="btn btn-info btn-sm">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No hay incidencias disponibles.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                Campaña no encontrada.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para editar datos completos de la campaña -->
<div class="modal fade" id="modalEditarCampana" tabindex="-1" aria-labelledby="modalEditarCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarCampanaLabel">Editar Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCampanaCompleto" method="post" action="<?= base_url('campanas/actualizar'); ?>">
                    <input type="hidden" name="campana_id" value="<?= $campana['id'] ?? 0; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de Campaña</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($campana['nombre'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="coordinador" class="form-label">Coordinador(a)</label>
                            <input type="text" class="form-control" id="coordinador" name="coordinador" value="<?= esc($campana['coordinador'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_id" class="form-label">Tipo de Campaña</label>
                            <select class="form-select" id="tipo_id" name="tipo_id" required>
                                <option value="">Seleccione un tipo</option>
                                <?php if (isset($tipos_campanas) && !empty($tipos_campanas)): ?>
                                    <?php foreach ($tipos_campanas as $tipo): ?>
                                        <option value="<?= esc($tipo['id']); ?>" <?= ($campana['tipo_id'] ?? '') == $tipo['id'] ? 'selected' : ''; ?>><?= esc($tipo['nombre']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="subtipo_id" class="form-label">Subtipo</label>
                            <select class="form-select" id="subtipo_id" name="subtipo_id">
                                <option value="">Seleccione un subtipo</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estatus</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="Programada" <?= ($campana['estado'] ?? '') == 'Programada' ? 'selected' : ''; ?>>Programada</option>
                                <option value="Activa" <?= ($campana['estado'] ?? '') == 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                <option value="Finalizada" <?= ($campana['estado'] ?? '') == 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                                <option value="Propuesta" <?= ($campana['estado'] ?? '') == 'Propuesta' ? 'selected' : ''; ?>>Propuesta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="area_id" class="form-label">Área Responsable</label>
                            <select class="form-select" id="area_id" name="area_id" required>
                                <option value="">Seleccione un área</option>
                                <?php if (isset($areas) && !empty($areas)): ?>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?= esc($area['id']); ?>" <?= ($campana['area_id'] ?? '') == $area['id'] ? 'selected' : ''; ?>><?= esc($area['nombre']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= isset($campana['fecha_inicio']) ? date('Y-m-d', strtotime($campana['fecha_inicio'])) : ''; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Término</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= isset($campana['fecha_fin']) ? date('Y-m-d', strtotime($campana['fecha_fin'])) : ''; ?>" required>
                        </div>
                    </div>

                    <!-- Nuevos Campos -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="territorio" class="form-label">Territorio</label>
                            <select class="form-select" id="territorio" name="territorio">
                                <option value="">Seleccione un territorio</option>
                                <option value="electorales" <?= ($campana['territorio'] ?? '') == 'electorales' ? 'selected' : ''; ?>>Electorales</option>
                                <option value="geograficos" <?= ($campana['territorio'] ?? '') == 'geograficos' ? 'selected' : ''; ?>>Geográficos</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="universo" class="form-label">Universo Detectado</label>
                            <input type="text" class="form-control" id="universo" name="universo" value="<?= esc($campana['universo'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="encuesta" class="form-label">Encuesta</label>
                            <select class="form-select" id="encuesta" name="encuesta">
                                <option value="">Seleccione una encuesta</option>
                                <?php if (isset($surveys) && !empty($surveys)): ?>
                                    <?php foreach ($surveys as $survey): ?>
                                        <option value="<?= esc($survey['id']); ?>" <?= ($campana['encuesta'] ?? '') == $survey['id'] ? 'selected' : ''; ?>><?= esc($survey['title']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sectorizacion" class="form-label">Sectorización</label>
                            <select class="form-select" id="sectorizacion" name="sectorizacion[]" multiple>
                                <?php if (isset($todas_segmentaciones) && !empty($todas_segmentaciones)): ?>
                                    <?php 
                                    $selected_sectors = !empty($campana['sectorizacion']) ? json_decode($campana['sectorizacion'], true) : [];
                                    foreach ($todas_segmentaciones as $segmentacion): ?>
                                        <option value="<?= esc($segmentacion['id']); ?>" <?= in_array($segmentacion['id'], $selected_sectors) ? 'selected' : ''; ?>><?= esc($segmentacion['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="entregables" class="form-label">Entregables</label>
                            <select class="form-select" id="entregables" name="entregables">
                                <option value="">Seleccione un entregable</option>
                                <option value="00001" <?= ($campana['entregables'] ?? '') == '00001' ? 'selected' : ''; ?>>Orden # 00001</option>
                                <option value="00002" <?= ($campana['entregables'] ?? '') == '00002' ? 'selected' : ''; ?>>Orden # 00002</option>
                                <option value="00003" <?= ($campana['entregables'] ?? '') == '00003' ? 'selected' : ''; ?>>Orden # 00003</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?= esc($campana['descripcion'] ?? ''); ?></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar la eliminación -->
<div class="modal fade" id="modalEliminarCampana" tabindex="-1" aria-labelledby="modalEliminarCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarCampanaLabel">Eliminar Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url('campanas/eliminar'); ?>">
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta campaña? 
                    <strong>Esta acción también eliminará todos los reportes o incidencias asociados a esta campaña.</strong>
                    <input type="hidden" name="campana_id" value="<?= $campana['id'] ?? 0; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver y gestionar rondas vinculadas -->
<div class="modal fade" id="modalRondasCampana" tabindex="-1" aria-labelledby="modalRondasCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRondasCampanaLabel">Rondas Vinculadas a la Campaña #CAM-<?= str_pad($campana['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h6>Lista de Rondas</h6>
                    <a href="<?= base_url('rondas/crear?campana_id=' . ($campana['id'] ?? 0)); ?>" class="btn btn-sm btn-success">Crear Nueva Ronda</a>
                </div>
                <div class="table-responsive">
                    <table class="datatable display table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>ID Ronda</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Brigada</th>
                                <th>Enlace</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="rondasTableBody">
                            <!-- Rondas will be loaded dynamically via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Script para cargar los subtipos, el mapa y las rondas -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Obtener referencias a los elementos select
    const tipoSelect = document.getElementById('tipo_id');
    const subtipoSelect = document.getElementById('subtipo_id');
    const mapError = document.getElementById('mapError');

    // Función para cargar los subtipos
    function cargarSubtipos(tipoId) {
        subtipoSelect.innerHTML = '<option value="">Seleccione un subtipo</option>';
        if (!tipoId) return;

        fetch('<?= base_url(); ?>campanas/obtener/subtipos/' + tipoId, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                data.forEach(subtipo => {
                    const option = document.createElement('option');
                    option.value = subtipo.id;
                    option.textContent = subtipo.nombre;
                    if (subtipo.id == <?= json_encode($campana['subtipo_id'] ?? ''); ?>) {
                        option.selected = true;
                    }
                    subtipoSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error al cargar subtipos:', error));
    }

    // Asignar el evento change al select de tipos
    tipoSelect.addEventListener('change', function () {
        cargarSubtipos(this.value);
    });

    // Cargar los subtipos iniciales si hay un tipo seleccionado
    if (tipoSelect.value) {
        cargarSubtipos(tipoSelect.value);
    }

    // Inicializar el mapa
    const map = L.map('campaignMap').setView([19.4326, -99.1332], 10); // Centro por defecto
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Cargar datos de la campaña para el mapa
    fetch('<?= base_url('campanas/detalle/' . ($campana['id'] ?? 0)); ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta del servidor: ' + response.status);
        return response.json();
    })
    .then(data => {
        mapError.classList.add('d-none'); // Ocultar mensaje de error por defecto
        if (data.map_data && data.map_data.type === 'geojson' && data.map_data.url) {
            // Cargar el archivo GeoJSON
            fetch(data.map_data.url)
                .then(response => {
                    if (!response.ok) throw new Error('Error al cargar GeoJSON: ' + response.status);
                    return response.json();
                })
                .then(geojson => {
                    const geoJsonLayer = L.geoJSON(geojson, {
                        style: {
                            color: 'blue',
                            fillColor: 'blue',
                            fillOpacity: 0.5
                        },
                        onEachFeature: function (feature, layer) {
                            layer.bindPopup(data.map_data.popup);
                        }
                    }).addTo(map);
                    // Ajustar el mapa a los límites de la capa
                    map.fitBounds(geoJsonLayer.getBounds());
                })
                .catch(error => {
                    mapError.classList.remove('d-none');
                    console.error('Error al cargar GeoJSON:', error);
                });
        } else {
            mapError.classList.remove('d-none');
            console.warn('No se encontraron datos para el mapa:', data);
        }
    })
    .catch(error => {
        mapError.classList.remove('d-none');
        console.error('Error al cargar datos del mapa:', error);
    });

    // Función para cargar las rondas vinculadas
    function cargarRondas() {
        const campanaId = <?= json_encode($campana['id'] ?? 0); ?>;
        fetch('<?= base_url('rondas/listar/'); ?>' + campanaId, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar rondas: ' + response.status);
            return response.json();
        })
        .then(data => {
            const rondasTableBody = document.getElementById('rondasTableBody');
            rondasTableBody.innerHTML = '';
            if (data && data.length > 0) {
                data.forEach(ronda => {
                    const estadoClase = ronda.estado === 'Cerrada' ? 'text-danger' : 'text-success';
                    const row = `
                        <tr>
                            <td>#RDA-${ronda.id.toString().padStart(6, '0')}</td>
                            <td>${ronda.fecha_actividad || 'N/A'}</td>
                            <td>${ronda.hora_actividad || 'N/A'}</td>
                            <td>${ronda.brigada_nombre || 'N/A'}</td>
                            <td>${ronda.encargado || 'N/A'}</td>
                            <td><span class="${estadoClase}">${ronda.estado || 'N/A'}</span></td>
                            <td class="text-center">
                                <a href="<?= base_url(obtener_rol() . 'rondas/detalle/'); ?>${ronda.id}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="<?= base_url(obtener_rol() . 'rondas/editar/'); ?>${ronda.id}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="ri-pencil-fill"></i>
                                </a>
                                <button class="btn btn-danger btn-sm btn-eliminar-ronda" data-id="${ronda.id}" title="Eliminar">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    rondasTableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                rondasTableBody.innerHTML = '<tr><td colspan="7" class="text-center">No hay rondas vinculadas.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al cargar rondas:', error);
            document.getElementById('rondasTableBody').innerHTML = '<tr><td colspan="7" class="text-center">Error al cargar las rondas.</td></tr>';
        });
    }

    // Cargar rondas cuando se abre el modal
    const modalRondasCampana = document.getElementById('modalRondasCampana');
    modalRondasCampana.addEventListener('shown.bs.modal', cargarRondas);

    // Asignar evento al botón de eliminar ronda
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar-ronda')) {
            const rondaId = e.target.closest('.btn-eliminar-ronda').dataset.id;
            if (confirm('¿Estás seguro de que deseas eliminar esta ronda?')) {
                fetch('<?= base_url('rondas/eliminar/'); ?>' + rondaId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al eliminar ronda: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Ronda eliminada correctamente.');
                        cargarRondas();
                    } else {
                        alert('Error al eliminar la ronda: ' + (data.error || 'Desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar ronda:', error);
                    alert('Error al eliminar la ronda.');
                });
            }
        }
    });
});
</script>