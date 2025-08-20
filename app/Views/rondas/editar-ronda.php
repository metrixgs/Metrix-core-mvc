<!-- Vista de edición de ronda (rondas/editar-ronda.php) -->
<div class="page-content">
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Editar Ronda</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('rondas') ?>">Rondas</a></li>
                        <li class="breadcrumb-item active">Editar Ronda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Editar Ronda: <?= esc($ronda['nombre']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url(obtener_rol() . 'rondas/editar/' . ($ronda['id'] ?? 0)); ?>" method="post" id="form-editar-ronda">
                        <div class="row">
                            <!-- Columna izquierda: Datos de la Ronda -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-information-fill me-1"></i> Información General</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nombre" class="form-label">Nombre de la Ronda:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($ronda['nombre'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fecha_actividad" class="form-label">Fecha:</label>
                                                <input type="date" class="form-control" id="fecha_actividad" name="fecha_actividad" value="<?= date('Y-m-d', strtotime($ronda['fecha_actividad'] ?? 'now')); ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="hora_actividad" class="form-label">Horario:</label>
                                                <input type="time" class="form-control" id="hora_actividad" name="hora_actividad" value="<?= date('H:i', strtotime($ronda['hora_actividad'] ?? 'now')); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="campana_id" class="form-label">Campaña Asociada:</label>
                                                <select class="form-select" id="campana_id" name="campana_id">
                                                    <option value="">Seleccione una campaña</option>
                                                    <?php if (!empty($campanas)): ?>
                                                        <?php foreach ($campanas as $campana): ?>
                                                            <option value="<?= esc($campana['id']); ?>" <?= ($ronda['campana_id'] ?? '') == $campana['id'] ? 'selected' : ''; ?>>
                                                                #CAM-<?= str_pad($campana['id'], 6, '0', STR_PAD_LEFT); ?> - <?= esc($campana['nombre']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="estado" class="form-label">Estado:</label>
                                                <select class="form-select" id="estado" name="estado" required>
                                                    <option value="Programada" <?= ($ronda['estado'] ?? '') == 'Programada' ? 'selected' : ''; ?>>Programada</option>
                                                    <option value="Activa" <?= ($ronda['estado'] ?? '') == 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                                    <option value="Finalizada" <?= ($ronda['estado'] ?? '') == 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                                                    <option value="Cancelada" <?= ($ronda['estado'] ?? '') == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="universo" class="form-label">Universo:</label>
                                                <input type="number" class="form-control" id="universo" name="universo" value="<?= esc($ronda['universo'] ?? 0); ?>">
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
                                                <label for="coordinador" class="form-label">Coordinador (Brigada):</label>
                                                <select class="form-select" id="coordinador" name="coordinador">
                                                    <option value="">Seleccione un coordinador</option>
                                                    <?php if (!empty($brigadas)): ?>
                                                        <?php foreach ($brigadas as $user): ?>
                                                            <option value="<?= esc($user['id']); ?>" <?= ($ronda['coordinador'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                                                                <?= esc($user['nombre']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="encargado" class="form-label">Encargado (Operador):</label>
                                                <select class="form-select" id="encargado" name="encargado">
                                                    <option value="">Seleccione un encargado</option>
                                                    <?php if (!empty($operadores)): ?>
                                                        <?php foreach ($operadores as $user): ?>
                                                            <option value="<?= esc($user['id']); ?>" <?= ($ronda['encargado'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                                                                <?= esc($user['nombre']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="coordinador_campana" class="form-label">Coordinador de Campaña:</label>
                                                <select class="form-select" id="coordinador_campana" name="coordinador_campana">
                                                    <option value="">Seleccione un coordinador</option>
                                                    <?php if (!empty($usuarios_coordinador_campana)): ?>
                                                        <?php foreach ($usuarios_coordinador_campana as $user): ?>
                                                            <option value="<?= esc($user['id']); ?>" <?= ($ronda['coordinador_campana'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                                                                <?= esc($user['nombre']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
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
                                                <label for="encuesta_ronda" class="form-label">Encuesta de Ronda:</label>
                                                <select class="form-select" id="encuesta_ronda" name="encuesta_ronda">
                                                    <option value="">Seleccione una encuesta</option>
                                                    <?php if (!empty($surveys)): ?>
                                                        <?php foreach ($surveys as $survey): ?>
                                                            <option value="<?= esc($survey['id']); ?>" <?= ($ronda['encuesta_ronda'] ?? '') == $survey['id'] ? 'selected' : ''; ?>>
                                                                <?= esc($survey['title']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Segmentaciones:</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php if (!empty($segmentaciones)): ?>
                                                        <?php foreach ($segmentaciones as $seg): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="segmentaciones[]"
                                                                    value="<?= $seg['id'] ?>" id="seg-<?= $seg['id'] ?>"
                                                                    <?= in_array($seg['id'], $segmentaciones_asignadas) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="seg-<?= $seg['id'] ?>">
                                                                    <?= esc($seg['descripcion']) ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <p class="text-muted">No hay segmentaciones disponibles.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-map-pin-line me-1"></i> Distribución de Puntos</h4>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">Aquí se mostrará la distribución de puntos o zonas asignadas a esta ronda.</p>
                                        <!-- Aquí podrías cargar dinámicamente la tabla o mapa de distribución -->
                                    </div>
                                </div>

                                <div class="text-end mb-4">
                                    <a href="<?= base_url(obtener_rol() . 'rondas'); ?>" class="btn btn-light me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Ronda</button>
                                </div>
                            </div>

                            <!-- Columna derecha: Mapa e Indicadores (solo visualización, no editable) -->
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
                    </form>
                </div>
            </div>
        </div>
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

        // Validación de formulario (mantener la lógica existente)
        const form = document.getElementById('form-editar-ronda');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar que al menos una segmentación esté seleccionada
            const segmentaciones = document.querySelectorAll('input[name="segmentaciones[]"]:checked');
            if (segmentaciones.length === 0) {
                alert('Por favor, selecciona al menos una segmentación.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>