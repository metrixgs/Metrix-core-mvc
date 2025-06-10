<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Datos de la Campaña</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . obtener_rol() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url() . obtener_rol() . "campanas/" ?>">Campañas</a></li>
                            <li class="breadcrumb-item active">Detalle Campaña</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <?= mostrar_alerta(); ?>

        <!-- Información principal y acciones -->
        <div class="row mb-4">
            <div class="col-md-5">
                <p class="text-muted mb-1">ID de Campaña: <span class="fw-medium text-dark">#CAM-<?= str_pad($campana['id'], 6, '0', STR_PAD_LEFT); ?></span></p>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <label class="form-label mb-0 me-2">Nombre de la Campaña:</label>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($campana['nombre']); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-flex align-items-center">
                    <label class="form-label mb-0 me-2">Estatus:</label>
                    <?php
                    $badgeClass = '';
                    switch ($campana['estado']) {
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
                        default:
                            $badgeClass = 'bg-secondary';
                    }
                    ?>
                    <span class="badge rounded-pill <?= $badgeClass; ?>"><?= htmlspecialchars($campana['estado']); ?></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light" id="btnExportar">Exportar</button>
                    <a href="<?= base_url() . "campanas/ficha/{$campana['id']}"; ?>" class="btn btn-sm btn-warning">Ficha Informativa</a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna izquierda - Datos de la campaña -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form id="formEditarCampana" method="post" action="<?= base_url() . obtener_rol() . "campanas/actualizar"; ?>">
                            <input type="hidden" name="campana_id" value="<?= $campana['id']; ?>">

                            <!-- Tipo de Campaña -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de Campaña:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= !empty($campana['nombre_tipo_campana']) ? htmlspecialchars($campana['nombre_tipo_campana']) : 'No especificado'; ?><?= !empty($campana['nombre_subtipo_campana']) ? ' / ' . htmlspecialchars($campana['nombre_subtipo_campana']) : ''; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalEditarCampana">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Territorio -->
                            <div class="mb-3">
                                <label class="form-label">Territorio:<span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="badge bg-light text-dark">Sección Electoral</span>
                                    <span class="badge bg-light text-dark">106 <i class="ri-close-line"></i></span>
                                    <span class="badge bg-light text-dark">113 <i class="ri-close-line"></i></span>
                                    <span class="badge bg-light text-dark">124 <i class="ri-close-line"></i></span>
                                </div>
                            </div>

                            <!-- Segmentación -->
                            <div class="mb-3">
                                <label class="form-label">Segmentación:<span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="badge bg-light text-dark">Manzanas</span>
                                    <span class="badge bg-light text-dark">M44 <i class="ri-close-line"></i></span>
                                    <span class="badge bg-light text-dark">M49 <i class="ri-close-line"></i></span>
                                    <span class="badge bg-light text-dark">M57 <i class="ri-close-line"></i></span>
                                </div>
                            </div>

                            <!-- Universo detectado -->
                            <div class="mb-3">
                                <label class="form-label">Universo detectado:</label>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="badge bg-light text-dark">Deportistas (144) <i class="ri-close-line"></i></span>
                                </div>
                            </div>

                            <hr>

                            <!-- Área Responsable -->
                            <div class="mb-3">
                                <label class="form-label">Área Responsable:</label>
                                <input type="text" class="form-control" value="<?= !empty($campana['nombre_area']) ? htmlspecialchars($campana['nombre_area']) : 'No especificada'; ?>" readonly>
                            </div>

                            <!-- Coordinador -->
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Coordinador(a):<span class="text-danger">*</span> (1/1)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="coordinador" value="<?= htmlspecialchars($campana['coordinador']); ?>" required>
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Inicio:<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= date('Y-m-d', strtotime($campana['fecha_inicio'])); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Término:<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                        <input type="date" class="form-control" name="fecha_fin" value="<?= date('Y-m-d', strtotime($campana['fecha_fin'])); ?>" required>
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
                        <div style="height: 400px; background-color: #f5f5f5; position: relative;">
                            <!-- Aquí iría el mapa real -->
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <i class="ri-map-2-line fs-1 text-muted"></i>
                                <p class="mb-0">Mapa de la zona de la campaña</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Incidencias -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lista de Incidencias</h5>
                        <a href="<?= base_url() . "tickets/nuevo"; ?>" class="btn btn-sm btn-primary">Nueva Incidencia</a>
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
                                    <?php if (isset($tickets) && !empty($tickets)) { ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <td><?= $ticket['titulo'] ?></td>
                                                <td><?= $ticket['nombre_area'] ?></td>
                                                <td>
                                                    <?php
                                                    $estado_clase = ($ticket['estado'] === 'Cerrado') ? 'text-danger' : 'text-success';
                                                    ?>
                                                    <span class="<?= $estado_clase; ?>"><?= $ticket['estado'] ?></span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($ticket['fecha_vencimiento'])) ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . obtener_rol() . 'tickets/detalle/' . $ticket['id'] ?>" class="btn btn-info btn-sm">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay incidencias disponibles.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <form id="formEditarCampanaCompleto" method="post" action="<?= base_url() . "campanas/actualizar_completo"; ?>">
                    <input type="hidden" name="campana_id" value="<?= $campana['id']; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de Campaña</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($campana['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="coordinador" class="form-label">Coordinador(a)</label>
                            <input type="text" class="form-control" id="coordinador" name="coordinador" value="<?= htmlspecialchars($campana['coordinador']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_id" class="form-label">Tipo de Campaña</label>
                            <select class="form-select" id="tipo_id" name="tipo_id" required>
                                <option value="">Seleccione un tipo</option>
                                <?php if (isset($tipos_campanas)) { ?>
                                    <?php foreach ($tipos_campanas as $tipo): ?>
                                        <option value="<?= $tipo['id']; ?>" <?= ($campana['tipo_id'] == $tipo['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($tipo['nombre']); ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="subtipo_id" class="form-label">Subtipo</label>
                            <select class="form-select" id="subtipo_id" name="subtipo_id">
                                <option value="">Seleccione un subtipo</option>
                                <!-- Los subtipos se cargarán dinámicamente según el tipo seleccionado -->
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estatus</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="Programada" <?= ($campana['estado'] == 'Programada') ? 'selected' : ''; ?>>Programada</option>
                                <option value="Activa" <?= ($campana['estado'] == 'Activa') ? 'selected' : ''; ?>>Activa</option>
                                <option value="Finalizada" <?= ($campana['estado'] == 'Finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                                <option value="Propuesta" <?= ($campana['estado'] == 'Propuesta') ? 'selected' : ''; ?>>Propuesta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="area_id" class="form-label">Área Responsable</label>
                            <select class="form-select" id="area_id" name="area_id" required>
                                <option value="">Seleccione un área</option>
                                <?php if (isset($areas)) { ?>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?= $area['id']; ?>" <?= ($campana['area_id'] == $area['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($area['nombre']); ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d', strtotime($campana['fecha_inicio'])); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Término</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= date('Y-m-d', strtotime($campana['fecha_fin'])); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?= htmlspecialchars($campana['descripcion']); ?></textarea>
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
            <form method="post" action="<?= base_url() . "campanas/eliminar"; ?>">
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta campaña? 
                    <strong>Esta acción también eliminará todos los reportes o incidencias asociados a esta campaña.</strong>
                    <!-- Campo oculto para enviar el ID de la campaña -->
                    <input type="hidden" name="campana_id" value="<?= $campana['id']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para cargar los subtipos dependiendo del tipo seleccionado -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Obtener referencias a los elementos select
        const tipoSelect = document.getElementById('tipo_id');
        const subtipoSelect = document.getElementById('subtipo_id');

        // Función para cargar los subtipos
        function cargarSubtipos(tipoId) {
            // Limpiar las opciones actuales
            subtipoSelect.innerHTML = '<option value="">Seleccione un subtipo</option>';

            if (!tipoId)
                return;

            // Realizar petición AJAX para obtener los subtipos
            fetch('<?= base_url();?>campanas/obtener/subtipos/' + tipoId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(subtipo => {
                                const option = document.createElement('option');
                                option.value = subtipo.id;
                                option.textContent = subtipo.nombre;
                                // Si el subtipo_id de la campaña coincide con este subtipo, seleccionarlo
                                if (subtipo.id == <?= json_encode($campana['subtipo_id'] ?? ''); ?>) {
                                    option.selected = true;
                                }
                                subtipoSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar subtipos:', error);
                    });
        }

        // Asignar el evento change al select de tipos
        tipoSelect.addEventListener('change', function () {
            cargarSubtipos(this.value);
        });

        // Cargar los subtipos iniciales si hay un tipo seleccionado
        if (tipoSelect.value) {
            cargarSubtipos(tipoSelect.value);
        }
    });
</script>