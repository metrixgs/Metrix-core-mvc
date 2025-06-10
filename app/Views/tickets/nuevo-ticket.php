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
                            <li class="breadcrumb-item active">Tickets</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <?= mostrar_alerta(); ?>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Nuevo Requerimiento</h4>
                                </div>
                            </div>
                            <!-- Botón para activar la selección de imagen -->
                            <div class="col text-end">
                                <button class="btn btn-secondary me-2"
                                    onclick="document.getElementById('imagen').click()">
                                    <i class="ri-folder-5-line"></i>&nbsp;Archivo
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url() . "tickets/crear"; ?>" method="POST" enctype="multipart/form-data">

                            <?= csrf_field(); ?>

                            <!-- Nuevo campo para adjuntar imagen -->
                            <input type="file" name="imagen" id="imagen" class="form-control"
                                accept=".txt,.pdf,.jpeg,.jpg,.png,.xls,.xlsx,.doc,.docx" style="display: none">

                            <div class="row">

                                <div class="col-md-12 col-lg-12 col-sm-12 mb-3">
                                    <label for="area_id" class="form-label">¿Quién reporta?</label>
                                    <select name="cliente_id"
                                        class="form-select js-example-basic-single <?= session('validation.cliente_id') ? 'is-invalid' : '' ?>"
                                        id="cliente_id" required>
                                        <option value="">Seleccione un cliente</option>
                                        <!-- Asegúrate de cargar las áreas desde tu base de datos -->
                                        <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= $cliente['id']; ?>"
                                            <?= old('cliente_id') == $cliente['id'] ? 'selected' : ''; ?>>
                                            <?= $cliente['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('validation.cliente_id')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.cliente_id') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                                    <label for="area_id" class="form-label">Área</label>
                                    <select name="area_id"
                                        class="form-select js-example-basic-single <?= session('validation.area_id') ? 'is-invalid' : '' ?>"
                                        id="area_id" required>
                                        <option value="">Seleccione un área</option>
                                        <!-- Asegúrate de cargar las áreas desde tu base de datos -->
                                        <?php foreach ($areas as $area): ?>
                                        <option value="<?= $area['id']; ?>"
                                            <?= old('area_id') == $area['id'] ? 'selected' : ''; ?>>
                                            <?= $area['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('validation.area_id')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.area_id') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-4 mb-3">
                                    <label for="area_id" class="form-label">¿Qué Campaña?</label>
                                    <select name="campana_id"
                                        class="form-select js-example-basic-single <?= session('validation.campana_id') ? 'is-invalid' : '' ?>"
                                        id="campana_id" required>
                                        <option value="">Seleccione una campaña</option>
                                        <?php if (!empty($campanas)): ?>
                                        <?php foreach ($campanas as $campana): ?>
                                        <option value="<?= $campana['id']; ?>"
                                            <?= old('campana_id') == $campana['id'] ? 'selected' : ''; ?>>
                                            <?= esc($campana['nombre']); ?></option>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <option value="">No hay campañas disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (session('validation.campana_id')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.campana_id') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                                    <label for="prioridad" class="form-label">Prioridad</label>
                                    <select name="prioridad"
                                        class="form-select js-example-basic-single <?= session('validation.prioridad') ? 'is-invalid' : '' ?>"
                                        id="prioridad" required>
                                        <?php foreach ($lista_sla as $sla): ?>
                                        <option value="<?= $sla['titulo'] ?>"
                                            <?= old('prioridad') == $sla['titulo'] ? 'selected' : ''; ?>>
                                            <?= $sla['titulo']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('validation.prioridad')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.prioridad') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" name="titulo" value="<?= old('titulo'); ?>" autocomplete="off"
                                    class="form-control <?= session('validation.titulo') ? 'is-invalid' : '' ?>"
                                    id="titulo" placeholder="Título del requerimiento" required>
                                <?php if (session('validation.titulo')): ?>
                                <div class="text-danger">
                                    <?= session('validation.titulo') ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea name="descripcion"
                                    class="form-control <?= session('validation.descripcion') ? 'is-invalid' : '' ?>"
                                    rows="10" id="descripcion"
                                    placeholder="Descripción del requerimiento"><?= old('descripcion'); ?></textarea>
                                <?php if (session('validation.descripcion')): ?>
                                <div class="text-danger">
                                    <?= session('validation.descripcion') ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Add this right after "aqui poner mapa con los datos obtenidos por el scrip" -->
                            <div class="row mb-3">
                                <!-- Map on the left -->
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3"
                                    style="border-color: black; border-style: dotted">
                                    <div id="map" style="height: 450px; width: 100%; border-radius: 5px;"></div>
                                    <span class="text-info">Ubicación en Mapa (puedes arrastrar el marcador para cambiar
                                        la ubicación)</span>
                                </div>

                                <!-- Form fields on the right -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="latitud" class="form-label">Latitud</label>
                                            <input readonly="" type="text" name="latitud" value="<?= old('latitud'); ?>"
                                                autocomplete="off"
                                                class="form-control <?= session('validation.latitud') ? 'is-invalid' : '' ?>"
                                                id="latitud" placeholder="Latitud" required>
                                            <?php if (session('validation.latitud')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.latitud') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="longitud" class="form-label">Longitud</label>
                                            <input readonly="" type="text" name="longitud"
                                                value="<?= old('longitud'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.longitud') ? 'is-invalid' : '' ?>"
                                                id="longitud" placeholder="Longitud" required>
                                            <?php if (session('validation.longitud')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.longitud') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="estado" class="form-label">Estado</label>
                                            <input readonly="" type="text" name="estado" value="<?= old('estado'); ?>"
                                                autocomplete="off"
                                                class="form-control <?= session('validation.estado') ? 'is-invalid' : '' ?>"
                                                id="estado" placeholder="Estado">
                                            <?php if (session('validation.estado')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.estado') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="municipio" class="form-label">Municipio</label>
                                            <input readonly="" type="text" name="municipio"
                                                value="<?= old('municipio'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.municipio') ? 'is-invalid' : '' ?>"
                                                id="municipio" placeholder="Municipio">
                                            <?php if (session('validation.municipio')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.municipio') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="colonia" class="form-label">Colonia</label>
                                            <input readonly="" type="text" name="colonia" value="<?= old('colonia'); ?>"
                                                autocomplete="off"
                                                class="form-control <?= session('validation.colonia') ? 'is-invalid' : '' ?>"
                                                id="colonia" placeholder="Colonia">
                                            <?php if (session('validation.colonia')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.colonia') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="df" class="form-label">Distrito Federal</label>
                                            <input readonly="" type="text" name="df" value="<?= old('df'); ?>"
                                                autocomplete="off"
                                                class="form-control <?= session('validation.df') ? 'is-invalid' : '' ?>"
                                                id="df" placeholder="Distrito Federal">
                                            <?php if (session('validation.df')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.df') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="dl" class="form-label">Distrito Local</label>
                                            <input readonly="" type="text" name="dl" value="<?= old('dl'); ?>"
                                                autocomplete="off"
                                                class="form-control <?= session('validation.dl') ? 'is-invalid' : '' ?>"
                                                id="dl" placeholder="Distrito Local">
                                            <?php if (session('validation.dl')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.dl') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="seccion_electoral" class="form-label">Sección Electoral</label>
                                            <input readonly="" type="text" name="seccion_electoral"
                                                value="<?= old('seccion_electoral'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.seccion_electoral') ? 'is-invalid' : '' ?>"
                                                id="seccion_electoral" placeholder="Sección Electoral">
                                            <?php if (session('validation.seccion_electoral')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.seccion_electoral') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="codigo_postal" class="form-label">Código Postal</label>
                                            <input readonly="" type="text" name="codigo_postal"
                                                value="<?= old('codigo_postal'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.codigo_postal') ? 'is-invalid' : '' ?>"
                                                id="codigo_postal" placeholder="Código Postal">
                                            <?php if (session('validation.codigo_postal')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.codigo_postal') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-6 mb-3">
                                            <label for="direccion_completa" class="form-label">Dirección
                                                Completa</label>
                                            <input readonly="" type="text" name="direccion_completa"
                                                value="<?= old('direccion_completa'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.direccion_completa') ? 'is-invalid' : '' ?>"
                                                id="direccion_completa" placeholder="Dirección Completa">
                                            <?php if (session('validation.direccion_completa')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.direccion_completa') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 mb-3">
                                            <label for="direccion_completa" class="form-label">Dirección
                                                Solicitante</label>
                                            <input type="text" name="direccion_solicitante"
                                                value="<?= old('direccion_solicitante'); ?>" autocomplete="off"
                                                class="form-control <?= session('validation.direccion_solicitante') ? 'is-invalid' : '' ?>"
                                                id="direccion_solicitante" placeholder="Dirección del Solicitante">
                                            <?php if (session('validation.direccion_solicitante')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.direccion_solicitante') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-6 mb-3">
                                            <label for="mismo_domicilio" class="form-label">
                                                ¿Es el mismo domicilio del cual se levanta el reporte?
                                            </label>
                                            <select class="form-select" id="mismo_domicilio" name="mismo_domicilio"
                                                required>
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="Si">Sí</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mb-3">
                                <button type="submit" class="btn btn-primary">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>

<script>
let map;
let marker;

function initMap(lat, lng) {
    // Initialize the map if not already initialized
    if (!map) {
        map = L.map('map').setView([lat, lng], 15);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create a draggable marker
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Update form fields when the marker is dragged
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            updateLocationFields(position.lat, position.lng);
        });
    } else {
        // Update existing map view and marker position
        map.setView([lat, lng], 15);
        marker.setLatLng([lat, lng]);
    }
}

function updateLocationFields(lat, lng) {
    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lng;

    // Get location info from Nominatim for the new position
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            const address = data.address || {};
            document.getElementById('estado').value = address.state || '';
            document.getElementById('municipio').value = address.city || address.town || address.county || '';
            document.getElementById('colonia').value = address.neighbourhood || address.suburb || '';
            document.getElementById('df').value = address.state_district || '';
            document.getElementById('dl').value = address.district || '';
            document.getElementById('seccion_electoral').value = address.political || '';
            document.getElementById('codigo_postal').value = address.postcode || '';

            // Concatenar la dirección completa
            const direccionCompleta = [
                address.house_number || '',
                address.road || '',
                address.neighbourhood || '',
                address.city || address.town || address.county || '',
                address.state || '',
                address.postcode || ''
            ].filter(Boolean).join(', ');

            document.getElementById('direccion_completa').value = direccionCompleta;

            // Create popup with address info
            marker.bindPopup(`
                    <strong>Dirección:</strong> ${direccionCompleta}<br>
                    <strong>Estado:</strong> ${address.state || 'N/A'}<br>
                    <strong>Municipio:</strong> ${address.city || address.town || address.county || 'N/A'}<br>
                    <strong>Colonia:</strong> ${address.neighbourhood || address.suburb || 'N/A'}<br>
                    <strong>CP:</strong> ${address.postcode || 'N/A'}
                `).openPopup();
        })
        .catch(error => console.error('Error obteniendo ubicación:', error));
}

function handleGeolocationSuccess(position) {
    const latitud = position.coords.latitude;
    const longitud = position.coords.longitude;

    document.getElementById('latitud').value = latitud;
    document.getElementById('longitud').value = longitud;

    // Initialize map with geolocation position
    initMap(latitud, longitud);

    // Get location info from Nominatim
    updateLocationFields(latitud, longitud);
}

function handleGeolocationError(error) {
    console.error('Error obteniendo geolocalización:', error.message);
    // Initialize map with default position (Mexico City as fallback)
    initMap(19.4326, -99.1332);
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(handleGeolocationSuccess, handleGeolocationError, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
    } else {
        console.error('Geolocalización no es soportada por este navegador');
        initMap(19.4326, -99.1332); // Default position
    }
}

document.addEventListener('DOMContentLoaded', getLocation);
// No need for setInterval since we're using the draggable marker for updates
</script>