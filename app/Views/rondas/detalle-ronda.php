<!-- Vista de detalle de ronda (rondas/detalle-ronda.php) -->
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-dark">Datos de la Ronda</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('rondas') ?>" class="text-primary">Rondas</a></li>
                            <li class="breadcrumb-item active text-dark">Detalle</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header border-bottom-dashed">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1 text-dark">ID Ronda: #RDA-<?= str_pad($ronda['id'], 6, '0', STR_PAD_LEFT) ?></h5>
                            <div class="d-flex gap-2">
                                <div class="flex-shrink-0">
                                    <span class="badge fs-12 <?= $ronda['estado'] == 'Activa' ? 'bg-success' : ($ronda['estado'] == 'Programada' ? 'bg-warning' : ($ronda['estado'] == 'Finalizada' ? 'bg-info' : 'bg-danger')) ?> rounded-pill px-3 py-2 text-white">
                                        Estatus: <?= esc($ronda['estado']) ?>
                                    </span>
                                </div>
                                <button type="button" class="btn btn-light btn-sm text-dark" onclick="window.print();">
                                    Exportar
                                </button>
                                <button type="button" class="btn btn-primary btn-sm text-white">
                                    Ficha Informativa
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4 g-3">
                            <div class="col-xl-6">
                                <div class="row">
                                    <div class="col-md-6 d-flex">
                                        <div class="p-2 text-center border rounded me-2">
                                            <i class="ri-calendar-line fs-3 text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1">Día:</p>
                                            <h6 class="text-dark"><?= date('l d/M', strtotime($ronda['fecha_actividad'])) ?></h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex">
                                        <div class="p-2 text-center border rounded me-2">
                                            <i class="ri-time-line fs-3 text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1">Horario:</p>
                                            <h6 class="text-dark"><?= date('H:i', strtotime($ronda['hora_actividad'])) ?>-<?= date('H:i', strtotime('+3 hours', strtotime($ronda['hora_actividad']))) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-xl-6">
                                <div class="card border shadow-none mb-4">
                                    <div class="card-header bg-soft-light border-bottom">
                                        <h6 class="card-title mb-0 text-dark">Territorio:</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            <h6 class="mb-2 text-dark">Sección Electoral</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php if (!empty($ronda['segmentaciones'])): ?>
                                                    <?php
                                                    $segElectorales = array_filter($ronda['segmentaciones'], function ($seg) {
                                                        return is_numeric($seg['codigo']) && $seg['codigo'] >= 100 && $seg['codigo'] < 200;
                                                    });
                                                    foreach ($segElectorales as $seg):
                                                        ?>
                                                        <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                            <?= esc($seg['codigo']) ?> <i class="ri-close-line align-bottom"></i>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border shadow-none mb-4">
                                    <div class="card-header bg-soft-light border-bottom">
                                        <h6 class="card-title mb-0 text-dark">Segmentación:</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <h6 class="mb-2 text-dark">Manzanas</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php if (!empty($ronda['segmentaciones'])): ?>
                                                    <?php
                                                    $manzanas = array_filter($ronda['segmentaciones'], function ($seg) {
                                                        return strpos($seg['codigo'], 'M') === 0;
                                                    });
                                                    foreach ($manzanas as $seg):
                                                        ?>
                                                        <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                            <?= esc($seg['codigo']) ?> <i class="ri-close-line align-bottom"></i>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border shadow-none mb-4">
                                    <div class="card-header bg-soft-light border-bottom">
                                        <h6 class="card-title mb-0 text-dark">Universo detectado:</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                Deportistas (144) <i class="ri-close-line align-bottom"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0 text-dark">Mapa de Zona</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="border rounded" style="height: 400px; position: relative;">
                                            <!-- Contenedor del mapa real -->
                                            <div id="map" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
                                            <div style="position: absolute; top: 10px; right: 10px; z-index: 100;">
                                                <button class="btn btn-sm btn-light text-dark" id="fullscreen-btn"><i class="ri-fullscreen-line"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Área Responsable -->
                        <div class="row mb-4">
                            <div class="col-xl-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-soft-light border-bottom">
                                        <h6 class="card-title mb-0 text-dark">Área Responsable:</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6 class="mb-2 text-dark">Secretaría de Desarrollo Social / Instituto del Deporte</h6>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <p class="text-muted mb-1">Coordinador(a): (1/1)</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge rounded-pill bg-light px-3 py-2" style="color: #333 !important;">
                                                            <?= esc($ronda['coordinador']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <p class="text-muted mb-1">Encargado(a): (1/1)</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge rounded-pill bg-light px-3 py-2" style="color: #333 !important;">
                                                            <?= esc($ronda['encargado']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-muted mb-1">Operativo(s): (0)</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                    Brigada 01 <i class="ri-close-line align-bottom"></i>
                                                </span>
                                                <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                    Diego Avilés <i class="ri-close-line align-bottom"></i>
                                                </span>
                                                <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                    Florencio Salazar <i class="ri-close-line align-bottom"></i>
                                                </span>
                                                <span class="badge rounded-pill border px-3 py-2 text-dark">
                                                    Luisito Pérez <i class="ri-close-line align-bottom"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indicadores Generales -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-soft-light border-bottom d-flex align-items-center">
                                        <h6 class="card-title mb-0 flex-grow-1 text-dark">Indicadores Generales</h6>
                                        <button type="button" class="btn btn-success btn-sm text-white">Más Indicadores</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="d-flex mb-4">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-soft-danger text-danger rounded-circle fs-2">
                                                                <i class="ri-target-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-1 text-dark">Impactos: 13/19 (68%)</h5>
                                                        <div class="d-flex gap-2">
                                                            <span class="badge bg-soft-success text-success">
                                                                <i class="ri-checkbox-circle-line"></i> 6/10 (75%)
                                                            </span>
                                                            <span class="badge bg-soft-warning text-warning">
                                                                <i class="ri-error-warning-line"></i> 5/15 (33%)
                                                            </span>
                                                            <span class="badge bg-soft-danger text-danger">
                                                                <i class="ri-error-warning-line"></i> 2/12 (17%)
                                                            </span>
                                                            <span class="badge bg-soft-primary text-primary">
                                                                <i class="ri-checkbox-circle-line"></i> 0/5 (0%)
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex mb-4">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-2">
                                                                <i class="ri-walk-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-1 text-dark">Zona Recorrida: 2/3 (67%)</h5>
                                                        <div class="d-flex gap-2">
                                                            <span class="badge bg-soft-danger text-danger">
                                                                <i class="ri-map-pin-line"></i> ESTE
                                                            </span>
                                                            <span class="badge bg-soft-success text-success">
                                                                <i class="ri-map-pin-line"></i> NORTE
                                                            </span>
                                                            <span class="badge bg-soft-warning text-warning">
                                                                <i class="ri-map-pin-line"></i> PONIENTE
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-wrap gap-4 justify-content-between">
                                                    <div class="d-flex">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                                                <i class="ri-user-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 text-dark">Encuestas</h6>
                                                            <span class="text-muted">10/30 (33%)</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                                                <i class="ri-group-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 text-dark">Personas</h6>
                                                            <span class="text-muted">251/300 (83%)</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                                                <i class="ri-calendar-check-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 text-dark">Incidencias</h6>
                                                            <span class="text-muted">5/Reportadas</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <a href="<?= base_url('rondas') ?>" class="btn btn-light text-dark">Volver a la lista</a>
                            <a href="<?= base_url('rondas/editar/' . $ronda['id']) ?>" class="btn btn-primary text-white">Editar Ronda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modal-eliminar-ronda" tabindex="-1" aria-labelledby="modal-eliminar-ronda-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="modal-eliminar-ronda-label">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark">¿Estás seguro de que deseas eliminar la ronda <strong id="nombre-ronda-eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btn-confirmar-eliminar" class="btn btn-danger text-white">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Asegurar visibilidad del texto */
    body {
        color: #333 !important;
    }
    .text-dark {
        color: #212529 !important;
    }
    .text-muted {
        color: #6c757d !important;
    }
    .badge {
        color: #fff !important;
    }
    .badge.border {
        color: #333 !important;
    }
    .btn-light {
        color: #212529 !important;
    }
    .btn-primary, .btn-success, .btn-danger, .btn-secondary {
        color: #fff !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Configurar modal de eliminación
        document.querySelectorAll('.eliminar-ronda').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');

                document.getElementById('nombre-ronda-eliminar').textContent = nombre;
                document.getElementById('btn-confirmar-eliminar').setAttribute('href', '<?= base_url('rondas/eliminar/') ?>' + id);

                var modal = new bootstrap.Modal(document.getElementById('modal-eliminar-ronda'));
                modal.show();
            });
        });
    });
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Crear el mapa con Leaflet (OpenStreetMap)
        const map = L.map('map').setView([19.432608, -99.133209], 15);

        // Añadir capa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Añadir marcador en la ubicación central
        L.marker([19.432608, -99.133209]).addTo(map)
                .bindPopup('Ubicación de la Ronda')
                .openPopup();

        // Simular área de la ronda con un polígono
        const zoneCoordinates = [
            [19.435608, -99.136209],
            [19.433608, -99.129209],
            [19.430608, -99.131209],
            [19.429608, -99.135209]
        ];

        L.polygon(zoneCoordinates, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.35
        }).addTo(map);

        // Manejar el botón de pantalla completa
        document.getElementById('fullscreen-btn').addEventListener('click', function () {
            const mapElement = document.getElementById('map').parentElement;

            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                mapElement.requestFullscreen();
            }
        });
    });
</script>