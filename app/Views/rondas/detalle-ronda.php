<div class="page-content">
    <div class="container-fluid">

        <!-- Título de página y Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Detalle de Ronda #RDA-<?= str_pad($ronda['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'panel'); ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'rondas'); ?>">Rondas</a></li>
                            <li class="breadcrumb-item active">Detalle</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de alerta -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Columna izquierda: Datos de la Ronda -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-information-fill me-1"></i> Información General</h4>
                        <div class="flex-shrink-0">
                            <a href="<?= base_url(obtener_rol() . 'rondas/editar/' . ($ronda['id'] ?? 0)); ?>" class="btn btn-sm btn-primary"><i class="ri-pencil-fill me-1"></i> Editar Ronda</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-0 text-muted">Nombre de la Ronda:</p>
                                <h6 class="fs-16"><?= esc($ronda['nombre'] ?? 'N/A'); ?></h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-0 text-muted">Fecha:</p>
                                <h6 class="fs-16"><?= esc($ronda['fecha_actividad'] ?? 'N/A'); ?></h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-0 text-muted">Horario:</p>
                                <h6 class="fs-16"><?= esc($ronda['hora_actividad'] ?? 'N/A'); ?> horas</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0 text-muted">Campaña Asociada:</p>
                                <h6 class="fs-16">
                                    <?php if (!empty($ronda['campana_id'])): ?>
                                        <a href="<?= base_url(obtener_rol() . 'campanas/detalle/' . esc($ronda['campana_id'])); ?>" class="text-primary">
                                            #CAM-<?= str_pad($ronda['campana_id'], 6, '0', STR_PAD_LEFT); ?> - <?= esc($ronda['nombre_campana'] ?? 'N/A'); ?>
                                        </a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0 text-muted">Estado:</p>
                                <?php
                                $badgeClass = 'bg-secondary';
                                $estado = $ronda['estado'] ?? 'Desconocido';
                                switch ($estado) {
                                    case 'Programada': $badgeClass = 'bg-warning'; break;
                                    case 'Activa': $badgeClass = 'bg-success'; break;
                                    case 'Finalizada': $badgeClass = 'bg-info'; break;
                                    case 'Cancelada': $badgeClass = 'bg-danger'; break;
                                }
                                ?>
                                <span class="badge rounded-pill <?= $badgeClass; ?> fs-12"><?= esc($estado); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-user-fill me-1"></i> Responsables</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p class="mb-0 text-muted">Coordinador (Brigada):</p>
                                <h6 class="fs-16"><?= esc($ronda['coordinador_nombre'] ?? 'N/A'); ?></h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0 text-muted">Encargados (Operadores):</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if (!empty($ronda['puntos_operadores'])): ?>
                                        <?php foreach ($ronda['puntos_operadores'] as $puntos_op): ?>
                                            <span class="badge bg-primary-subtle text-primary fs-12"><?= esc($puntos_op['operador_nombre']); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-file-text-line me-1"></i> Encuesta y Segmentaciones</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-0 text-muted">Encuesta de Ronda:</p>
                                <h6 class="fs-16">
                                    <?php if (!empty($ronda['encuesta_ronda_id'])): ?>
                                        <a href="<?= base_url('survey/' . esc($ronda['encuesta_ronda_id'])); ?>" class="text-primary">
                                            #<?= esc($ronda['encuesta_ronda_id']); ?> - <?= esc($ronda['encuesta_ronda_titulo'] ?? 'N/A'); ?>
                                        </a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0 text-muted">Segmentaciones:</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if (!empty($ronda['segmentaciones'])): ?>
                                        <?php foreach ($ronda['segmentaciones'] as $seg): ?>
                                            <span class="badge bg-info-subtle text-info fs-12"><?= esc($seg['descripcion']); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No hay segmentaciones asignadas.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-map-pin-line me-1"></i> Distribución de Puntos</h4>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-secondary"><i class="ri-eye-line me-1"></i> Ver Asignación</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($ronda['puntos_operadores'])): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Operador</th>
                                            <th>Puntos Asignados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ronda['puntos_operadores'] as $puntos_op): ?>
                                            <tr>
                                                <td><?= esc($puntos_op['operador_nombre']); ?></td>
                                                <td><?= esc($puntos_op['puntos_asignados']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay puntos asignados a operadores para esta ronda.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <a href="<?= base_url(obtener_rol() . 'rondas'); ?>" class="btn btn-light me-2">Volver a Rondas</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarRonda"><i class="ri-delete-bin-line me-1"></i> Eliminar Ronda</button>
                </div>
            </div>

            <!-- Columna derecha: Mapa e Indicadores -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-map-2-line me-1"></i> Ubicación de la Ronda</h4>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-info" id="fullscreen-btn"><i class="ri-fullscreen-line"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 300px;"></div>
                        <div id="mapError" class="alert alert-warning d-none m-2" role="alert">
                            No se pudieron cargar los datos geográficos para esta ronda.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-bar-chart-box-line me-1"></i> Progreso de la Ronda</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="progress flex-grow-1" style="height: 20px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= esc($ronda['progreso'] ?? 0); ?>%;" aria-valuenow="<?= esc($ronda['progreso'] ?? 0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="fw-bold"><?= esc($ronda['progreso'] ?? 0); ?>%</span>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="#" class="btn btn-sm btn-light"><i class="ri-file-list-3-line me-1"></i> Registro de Actividad</a>
                            <a href="#" class="btn btn-sm btn-light"><i class="ri-add-line me-1"></i> Indicadores</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-dashboard-line me-1"></i> Indicadores Clave</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-2 flex-nowrap overflow-auto pb-2">
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-team-fill fs-3 text-primary"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['brigadas_activas'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Brigadas Activas</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-eye-line fs-3 text-success"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['visitas_realizadas'] ?? 0); ?> / <?= esc($ronda['universo'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Visitas Realizadas</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-error-warning-line fs-3 text-warning"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['incidencias_reportadas'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Incidencias</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-truck-line fs-3 text-danger"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['entregas_realizadas'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Entregas</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-archive-line fs-3 text-info"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['botagos_registrados'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Botagos</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border rounded" style="min-width: 120px;">
                                    <i class="ri-heart-line fs-3 text-purple"></i>
                                    <h6 class="mt-1 mb-0 fs-5"><?= esc($ronda['peticiones_recibidas'] ?? 0); ?></h6>
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Peticiones</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal para confirmar la eliminación -->
<div class="modal fade" id="modalEliminarRonda" tabindex="-1" aria-labelledby="modalEliminarRondaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarRondaLabel">Eliminar Ronda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url('rondas/eliminar/' . ($ronda['id'] ?? 0)); ?>">
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar la ronda <strong>#RDA-<?= str_pad($ronda['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></strong>?
                    Esta acción es irreversible y eliminará todos los datos asociados.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Incluir Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapError = document.getElementById('mapError');
        const mapElement = document.getElementById('map');

        // Inicializar el mapa
        const map = L.map('map').setView([20.5880, -100.3881], 12); // Centro por defecto en Querétaro

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Cargar datos geográficos si están disponibles
        const rondaData = <?= json_encode($ronda); ?>; // Asegúrate de que $ronda contenga datos geográficos si los hay

        if (rondaData.latitud && rondaData.longitud) {
            L.marker([rondaData.latitud, rondaData.longitud]).addTo(map)
                .bindPopup('Ubicación de la Ronda: ' + rondaData.nombre)
                .openPopup();
            map.setView([rondaData.latitud, rondaData.longitud], 15); // Centrar en la ubicación de la ronda
        } else if (rondaData.geojson_url) {
            fetch(rondaData.geojson_url)
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
                            layer.bindPopup(feature.properties.name || 'Área de Ronda');
                        }
                    }).addTo(map);
                    map.fitBounds(geoJsonLayer.getBounds());
                })
                .catch(error => {
                    mapError.classList.remove('d-none');
                    console.error('Error al cargar GeoJSON:', error);
                });
        } else {
            mapError.classList.remove('d-none');
            console.warn('No se encontraron datos geográficos para el mapa de esta ronda.');
        }

        // Manejar el botón de pantalla completa
        document.getElementById('fullscreen-btn').addEventListener('click', function () {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                mapElement.requestFullscreen();
            }
        });
    });
</script>
