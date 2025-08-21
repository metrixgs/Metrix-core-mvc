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
                                                <label class="form-label fw-semibold">
                                                    Universo (<span id="universoCount">0</span>)
                                                </label>
                                                <button type="button"
                                                        class="btn btn-outline-primary w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalUniverso">
                                                    Seleccionar Universo
                                                </button>
                                                <!-- Valor final CSV (slugs) -->
                                                <input type="hidden" id="universo" name="universo" value="<?= esc($universo_ronda ?? ''); ?>">
                                                <!-- Resumen visual -->
                                                <div id="universoSeleccionado" class="mt-2 text-muted small">
                                                    Ningún universo seleccionado
                                                </div>
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
<!-- ===== Loader de dependencias (solo si faltan) ===== -->
<script>
  if (!window.jQuery) {
    document.write('<script src="https://code.jquery.com/jquery-3.7.1.min.js"><\/script>');
  }
</script>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

<!-- Incluir Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Select2 para los selectores que lo necesiten
        // Por ejemplo, si hay selectores que no son parte del modal Universo
        // y necesitan Select2, se inicializarían aquí.
        // $('#some_other_select').select2({ width: '100%' });

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

<!-- ===================== MODAL UNIVERSO ===================== -->
<div class="modal fade" id="modalUniverso" tabindex="-1" aria-labelledby="modalUniversoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUniversoLabel">Seleccionar Universo (Tags)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <label class="form-label fw-semibold">Escribe para buscar y selecciona uno o varios</label>

        <!-- Avisos -->
        <div id="tagDebugAlert" class="alert alert-danger d-none"></div>

        <!-- Opciones renderizadas desde el servidor -->
        <select id="selectTagsUniverso" class="form-control" multiple>
            <!-- Los tags se cargarán dinámicamente vía AJAX -->
        </select>

        <!-- Chips con “x” para quitar -->
        <div class="mt-3">
          <label class="form-label mb-1">Seleccionados</label>
          <div id="chipsContainer" class="chips-container border rounded p-2" style="min-height:44px;"></div>
        </div>

        <!-- CSV de slugs (solo lectura) -->
        <div class="mt-2">
          <label class="form-label">Slugs (separados por comas)</label>
          <input id="universoCsv" class="form-control" type="text" readonly>
        </div>

        <small class="text-muted d-block mt-2">
          Al pulsar “Aplicar”, el valor se guarda en el formulario (input hidden) y verás los badges en la tarjeta.
        </small>
      </div>

      <div class="modal-footer">
        <button type="button" id="btnClearUniverso" class="btn btn-outline-secondary">Limpiar</button>
        <button type="button" id="btnAplicarUniverso" class="btn btn-primary">Aplicar</button>
      </div>
    </div>
  </div>
</div>
<!-- ========================================================= -->

<style>
  .chip {
    display: inline-flex; align-items: center;
    padding: .25rem .5rem; margin: .2rem;
    border: 1px solid #ced4da; border-radius: 20px;
    background: #f8f9fa; font-size: .85rem;
  }
  .chip .remove {
    margin-left: .4rem; cursor: pointer; font-weight: bold;
  }
</style>

<style>
  /* Asegura que el Select2 para selección múltiple se expanda verticalmente */
  .select2-container--default .select2-selection--multiple {
    min-height: 38px; /* Altura mínima para que se vea bien */
    height: auto; /* Permite que la altura se ajuste automáticamente */
  }
</style>

<script>
(function($){
  var $modal   = $('#modalUniverso');
  var $select  = $('#selectTagsUniverso');
  var $hidden  = $('#universo');
  var $summary = $('#universoSeleccionado');
  var $count   = $('#universoCount');
  var $chips   = $('#chipsContainer');
  var $csv     = $('#universoCsv');

  // Abrir modal
  $modal.on('shown.bs.modal', function () {
    // Reinicializa Select2 siempre para asegurar buscador
    if ($.fn.select2) {
      if ($select.data('select2')) {
        $select.select2('destroy');
      }
      $select.select2({
        width: '100%',
        placeholder: 'Escribe para buscar y selecciona uno o varios',
        dropdownParent: $modal,
        closeOnSelect: false
      });
    }
    var slugs = parseCSV($hidden.val());
    $select.val(slugs).trigger('change');
  });

  // Cambio en selección
  $select.on('change', function () {
    var slugs = unique($select.val() || []);
    renderChips(slugs);
    $csv.val(slugs.join(','));
  });

  // Quitar chip
  $chips.on('click', '.remove', function () {
    var slug = $(this).closest('.chip').data('slug');
    var current = unique($select.val() || []);
    var next = current.filter(s => s !== slug);
    $select.val(next).trigger('change');
  });

  // Limpiar selección
  $('#btnClearUniverso').on('click', function () {
    $select.val(null).trigger('change');
    $csv.val('');
  });

  // Aplicar y cerrar
  $('#btnAplicarUniverso').on('click', function () {
    var slugs = parseCSV($csv.val());
    $hidden.val(slugs.join(','));
    renderBadges(slugs);
    if (window.bootstrap && bootstrap.Modal) {
      bootstrap.Modal.getInstance($modal[0]).hide();
    } else {
      $modal.removeClass('show').attr('aria-hidden','true').hide();
      $('.modal-backdrop').remove();
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
    }
  });

  // Helpers
  function parseCSV(str) {
    return (str || '').split(',').map(s => s.trim()).filter(Boolean);
  }
  function unique(arr) {
    return [...new Set(arr.map(s => s.trim()).filter(Boolean))];
  }
  function labelFor(slug) {
    var opt = $select.find(`option[value="${slug}"]`);
    return opt.data('label') || opt.text() || slug;
  }
  function renderChips(slugs) {
    $chips.empty();
    if (!slugs.length) {
      $chips.append('<span class="text-muted">No hay seleccionados</span>');
      return;
    }
    slugs.forEach(slug => {
      var lbl = labelFor(slug);
      var chip = $(
        `<span class="chip" data-slug="${slug}">
           ${$('<div>').text(lbl).html()}
           <span class="remove" aria-label="Quitar" title="Quitar">&times;</span>
         </span>`
      );
      $chips.append(chip);
    });
  }
  function renderBadges(slugs) {
    if (!slugs.length) {
      $summary.removeClass('text-dark').addClass('text-muted').html('Ningún universo seleccionado');
      $count.text('0');
      return;
    }
    var html = slugs.map(slug =>
      `<span class="badge bg-light border text-primary me-1 mb-1">#${$('<div>').text(labelFor(slug)).html()}</span>`
    ).join('');
    $summary.removeClass('text-muted').addClass('text-primary').html(html);
    $count.text(slugs.length);
  }

  // Cargar estado inicial
  (function initFromHiddenOnLoad(){
    var initial = parseCSV($hidden.val());
    if (initial.length) {
      renderChips(initial);
      renderBadges(initial);
      $csv.val(initial.join(','));
    }
  })();

})(jQuery);
</script>

<script>
(function(){
  var TAGS_URL = "<?= site_url('rondas/tags') ?>"; // Cambiado a la ruta de rondas
  var $ = window.jQuery;
  if (!$) return;

  function showTagError(msg) {
    var $alert = $('#tagDebugAlert');
    $alert.text(msg).removeClass('d-none');
  }

  $('#modalUniverso').on('shown.bs.modal', function(){
    var $select = $('#selectTagsUniverso');
    // var hasOptions = $select.find('option[value]').length > 0; // Eliminado para forzar recarga
    // if (hasOptions) return; // Eliminado para forzar recarga

    $.getJSON(TAGS_URL + '?debug=1')
      .done(function(resp){
        if (resp && resp.ok === false) {
          showTagError('Error obteniendo tags' + (resp.exception ? (': ' + resp.exception) : '.'));
          return;
        }
        var rows = resp && resp.data ? resp.data : resp;
        if (!Array.isArray(rows)) {
          showTagError('Respuesta inesperada del servidor.');
          return;
        }
        if (rows.length === 0) {
          showTagError('No hay tags para mostrar.');
          return;
        }
        rows.forEach(function(r){
          var text = (r.tag || r.slug) + ' (' + r.slug + ')';
          var opt  = new Option(text, r.slug, false, false);
          opt.setAttribute('data-label', r.tag || r.slug);
          $select.append(opt);
        });
        if ($.fn.select2 && $select.data('select2')) {
          $select.trigger('change');
        }
      })
      .fail(function(xhr){
        var msg = 'Falló la llamada AJAX (' + xhr.status + ').';
        if (xhr.responseJSON && xhr.responseJSON.exception) {
          msg += ' ' + xhr.responseJSON.exception;
        }
        showTagError(msg + ' Revisa la ruta <?= site_url('rondas/tags') ?> y el acceso a la DB.');
      });
  });
})();
</script>