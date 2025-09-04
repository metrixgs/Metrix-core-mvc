  
 <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent mt-5 pt-4">
  <h4 class="mb-sm-0 text-success-salvador">Nueva Campaña</h4>
  <div class="page-title-right">
    <?php if (!empty($breadcrumb)) : ?>
      <?= $breadcrumb ?>
    <?php endif; ?>
  </div>
</div>

<form method="post" action="<?= base_url('campanas/crear'); ?>" id="formNuevaCampana">
  <?= csrf_field(); ?>
  <input type="hidden" name="estado" value="Programada">

  <div class="bg-light p-3 rounded shadow-sm">
    <div class="row g-3">

      <!-- DATOS GENERALES -->
      <div class="col-md-12">
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">📋 Datos Generales</h6>
          </div>
          <div class="card-body bg-white">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label fw-semibold text-success-salvador">Nombre de la Campaña <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-success-salvador" id="nombre" name="nombre" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-success-salvador">Vigencia <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="date" class="form-control border-success-salvador" name="fecha_inicio" id="fecha_inicio" required>
                  <input type="date" class="form-control border-success-salvador" name="fecha_fin" id="fecha_fin" required>
                </div>
              </div>
              <div class="col-md-2">
                <label class="form-label fw-semibold text-success-salvador">Tipo <span class="text-danger">*</span></label>
                <select class="form-select select2 border-success-salvador" id="tipo_id" name="tipo_id" required>
                  <?php foreach ($tipos_campanas as $tipo): ?>
                    <option value="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <label class="form-label fw-semibold text-success-salvador">Objetivo de la campaña</label>
                <textarea class="form-control border-success-salvador" name="objetivo" rows="1"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- DELIMITACIÓN TERRITORIAL -->
      <div class="col-md-6">
        <div class="card shadow border-success-salvador">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">🗺️ Delimitación Territorial</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Estado</label>
              <select class="form-select select2 border-success-salvador" id="estados_filtro" name="estados_filtro[]" multiple="multiple" disabled>
                <!-- Opciones de estados se cargarán dinámicamente con JavaScript -->
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Municipio</label>
              <select class="form-select select2 border-success-salvador" id="municipios_filtro" name="municipios_filtro[]" multiple="multiple">
                <option value="" disabled selected>Selecciona un municipio</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Delegación/Colonia</label>
              <select class="form-select select2 border-success-salvador" id="delegaciones_colonias_filtro" name="delegaciones_colonias_filtro[]" multiple="multiple" disabled>
                <option value="" disabled selected>Selecciona un municipio primero</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Sector Electoral</label>
              <select class="form-select select2 border-success-salvador" id="sector_electoral" name="sector_electoral" disabled>
                <option value="" disabled selected>No disponible</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <!-- MAPA DE MÉXICO -->
      <div class="col-md-6 mt-0">
        <div class="card shadow border-success-salvador">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">🌎 Mapa de México</h6>
          </div>
          <div class="card-body bg-white">
            <div id="mapaMexico" style="height: 300px; width: 100%;"></div> <!-- Altura reducida -->
          </div>
        </div>
      </div>

      <!-- IMPACTOS -->
      <div class="col-md-6">
        <div class="card shadow border-success-salvador">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">🎯 Impactos</h6>
          </div>
          <div class="card-body bg-white">
            <label class="form-label fw-semibold text-success-salvador">
              Universo (<span id="universoCount">0</span> usuarios) (<span id="selectedTagsCountOutside">0</span> tags)
            </label>

            <button type="button"
                    class="btn btn-outline-success-salvador w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#modalUniverso">
              Seleccionar Universo
            </button>

            <!-- Valor final CSV (slugs) -->
            <input type="hidden" id="universo" name="universo">
            <!-- Valor final del conteo de usuarios del universo -->
            <input type="hidden" id="universo_count_input" name="universo_count">

            <!-- Resumen visual -->
            <div id="universoSeleccionado" class="mt-2 text-muted small">
              Ningún universo seleccionado
            </div>
          </div>
        </div>
      </div>

      <!-- RESPONSABLES -->
      <div class="col-md-6 mt-4">
        <div class="card shadow border-success-salvador">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">👥 Responsables</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Área Responsable <span class="text-danger">*</span></label>
              <select class="form-select select2 border-success-salvador" id="area_id" name="area_id" required>
                <?php foreach ($areas as $area): ?>
                  <option value="<?= $area['id']; ?>"><?= htmlspecialchars($area['nombre']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <div class="mb-3">
  <label class="form-label fw-semibold text-success-salvador">Dependencia <span class="text-danger">*</span></label>
 <select class="form-select select2 border-success-salvador" id="dependencia" name="dependencia" required>
    <?php foreach ($brigadas as $brigada_item): ?>
        <option value="<?= $brigada_item['id'] ?>"><?= esc($brigada_item['nombre']) ?></option>
    <?php endforeach; ?>
</select>


</div>

          </div>
        </div>
      </div>

      <!-- INTERACCIONES -->
      <div class="col-md-6">
        <div class="card shadow border-success-salvador">
          <div class="card-header text-white bg-success-salvador">
            <h6 class="mb-0 fw-semibold">🔗 Interacciones</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Encuesta <span class="text-danger">*</span></label>
              <select class="form-select select2 border-success-salvador" id="encuesta" name="encuesta[]" multiple="multiple">
                <?php if (!empty($surveys)): ?>
                  <?php foreach ($surveys as $survey): ?>
                    <option value="<?= $survey['id']; ?>">#<?= $survey['id']; ?> <?= htmlspecialchars($survey['title']); ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option disabled>No hay encuestas registradas</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Entregables <span class="text-danger">*</span></label>
              <select class="form-select select2 border-success-salvador" id="entregables" name="entregables" required>
                <option value="00001">Orden # 00001</option>
                <option value="00002">Orden # 00002</option>
                <option value="00003">Orden # 00003</option>
              </select>
            </div>
          </div>
        </div>
      </div>


      <!-- BOTONES -->
      <div class="card shadow mt-4 border-success-salvador">
        <div class="card-body bg-white d-flex justify-content-end">
          <a href="<?= base_url('campanas'); ?>" class="btn btn-outline-success-salvador me-2">
            <i class="fas fa-times me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn text-white bg-success-salvador border-success-salvador">
            <i class="fas fa-plus me-1"></i> Crear Campaña
          </button>
        </div>
      </div>

    </div>
  </div>
</form>

<style>
  .text-success-salvador {
    color: #8bc34a !important;
  }
  .bg-success-salvador {
    background-color: #8bc34a !important;
  }
  .border-success-salvador {
    border-color: #8bc34a !important;
  }
  .btn-outline-success-salvador {
    color: #8bc34a !important;
    border-color: #8bc34a !important;
  }
  .btn-outline-success-salvador:hover {
    background-color: #8bc34a !important;
    color: #fff !important;
  }

  /* Estilos para chips del modal Universo */
  .chip {
    display: inline-flex; align-items: center;
    padding: .25rem .5rem; margin: .2rem;
    border: 1px solid #ced4da; border-radius: 20px;
    background: #f8f9fa; font-size: .85rem;
  }
  .chip .remove {
    margin-left: .4rem; cursor: pointer; font-weight: bold;
  }

  /* Asegura que el Select2 para selección múltiple se expanda verticalmente */
  .select2-container--default .select2-selection--multiple {
    min-height: 38px; /* Altura mínima para que se vea bien */
    height: auto; /* Permite que la altura se ajuste automáticamente */
  }
</style>

<!-- ===================== MODAL UNIVERSO ===================== -->
<div class="modal fade" id="modalUniverso" tabindex="-1" aria-labelledby="modalUniversoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-success-salvador">
      <div class="modal-header bg-success-salvador">
        <h5 class="modal-title text-white" id="modalUniversoLabel">Seleccionar Universo (Tags)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <label class="form-label fw-semibold text-success-salvador">Escribe para buscar y selecciona uno o varios</label>

        <!-- Avisos -->
        <?php if (empty($catalogo_tags)): ?>
          <div class="alert alert-warning mb-2">
            No hay etiquetas registradas en la base de datos.
          </div>
        <?php endif; ?>
        <div id="tagDebugAlert" class="alert alert-danger d-none"></div>

        <!-- Opciones renderizadas desde el servidor -->
      <select id="selectTagsUniverso" class="form-control" multiple>
    <?php if (!empty($catalogo_tags)): ?>
        <?php $stats = $tag_stats ?? []; ?>
        <?php foreach ($catalogo_tags as $t): ?>
            <option
                value="<?= esc($t['slug']) ?>"
                data-label="<?= esc($t['tag']) ?>"
                data-count="<?= isset($stats[$t['slug']]) ? (int)$stats[$t['slug']] : 0 ?>">
                <?= esc($t['tag']) ?> (<?= esc($t['slug']) ?>) [<?= isset($stats[$t['slug']]) ? (int)$stats[$t['slug']] : 0 ?> usuarios]
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled>(No hay etiquetas registradas)</option>
    <?php endif; ?>
</select>


        <!-- Chips con “x” para quitar -->
        <div class="mt-3">
          <label class="form-label mb-1">Seleccionados (<span id="selectedTagsCount">0</span>)</label>
          <div id="chipsContainer" class="chips-container border rounded p-2" style="min-height:44px;"></div>
        </div>

      

        <small class="text-muted d-block mt-2">
          Al pulsar “Aplicar”, el valor se guarda en el formulario (input hidden) y verás los badges en la tarjeta.
        </small>
      </div>

      <div class="modal-footer">
        <button type="button" id="btnClearUniverso" class="btn btn-outline-secondary">Limpiar</button>
        <button type="button" id="btnAplicarUniverso" class="btn text-white bg-success-salvador">Aplicar</button>
      </div>
    </div>
  </div>
</div>
<!-- ========================================================= -->

  <!-- ===== Loader de dependencias (solo si faltan) ===== -->
 <!-- Carga de jQuery de forma estándar -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Carga de Select2 CSS y JS -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

<!-- Carga de Leaflet CSS y JS -->
<link rel="stylesheet" href="<?= base_url('public/files/libs/leaflet/leaflet.css') ?>" />
<script src="<?= base_url('public/files/libs/leaflet/leaflet.js') ?>"></script>

<!-- Comentado temporalmente debido a SyntaxError en la consola -->
<!-- <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> -->
<!-- <script src="path/to/choices.min.js"></script> -->
<!-- <script src="path/to/flatpickr.min.js"></script> -->

<script>
jQuery(document).ready(function($) {
  try {
    var $modal   = $('#modalUniverso');
    var $select  = $('#selectTagsUniverso');
    var $hidden  = $('#universo');
    var $summary = $('#universoSeleccionado');
    var $count   = $('#universoCount');
    var $chips   = $('#chipsContainer');
    var COUNT_USERS_URL = "<?= site_url('campanas/countUsersBySelectedTags') ?>"; // Nuevo endpoint

    // Variables para el mapa
    var map;
    var currentGeoJsonLayer;
    var selectedPolygons = []; // Para almacenar los polígonos seleccionados

    if ($.fn.select2) {
      $('.select2').select2({ width: '100%' });
    }

    // Permitir selección uno a uno en select nativo
    $select.on('mousedown', 'option', function (e) {
      if ($select.data('select2')) return;
      e.preventDefault();
      var $opt = $(this);
      $opt.prop('selected', !$opt.prop('selected'));
      $select.trigger('change');
      return false;
    });

    // Abrir modal
    $modal.on('shown.bs.modal', function () {
      // Reinicializa Select2 siempre para asegurar buscador
      if ($.fn.select2) {
        if ($select.data('select2')) {
          $select.select2('destroy');
        }
        // Usar setTimeout para asegurar que el DOM esté completamente renderizado
        // antes de inicializar Select2 y establecer los valores.
        setTimeout(() => {
          $select.select2({
            width: '100%',
            placeholder: 'Escribe para buscar y selecciona uno o varios',
            dropdownParent: $modal,
            closeOnSelect: false
          });
          var slugs = parseCSV($hidden.val());
          $select.val(slugs).trigger('change');
        }, 100); // Pequeño retraso para asegurar la inicialización
      } else {
        var slugs = parseCSV($hidden.val());
        $select.val(slugs).trigger('change');
      }
    });

    // Cambio en selección
    $select.on('change', function () {
      var slugs = unique($select.val() || []);
      renderChips(slugs);
      updateUniversoCount(slugs); // Llamar a la función para actualizar el conteo de usuarios
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
    });

    // Aplicar y cerrar
    $('#btnAplicarUniverso').on('click', function () {
      var slugs = unique($select.val() || []); // Obtener los slugs directamente del select
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
      return opt.data('label') || opt.text().split(' (')[0] || slug; // Ajuste para obtener solo el nombre del tag
    }

    function userCountFor(slug) {
      var opt = $select.find(`option[value="${slug}"]`);
      var count = opt.data('count') || 0;
      console.log(`userCountFor(${slug}): ${count}`); // Depuración
      return count;
    }

    function renderChips(slugs) {
      $chips.empty();
      $('#selectedTagsCount').text(slugs.length); // Actualizar el contador de tags seleccionados
      if (!slugs.length) {
        $chips.append('<span class="text-muted">No hay seleccionados</span>');
        return;
      }
      slugs.forEach(slug => {
        var lbl = labelFor(slug);
        var count = userCountFor(slug);
        var chip = $(
          `<span class="chip" data-slug="${slug}">
             ${$('<div>').text(lbl).html()} (${count})
             <span class="remove" aria-label="Quitar" title="Quitar">&times;</span>
           </span>`
        );
        $chips.append(chip);
      });
    }
    function renderBadges(slugs) {
      if (!slugs.length) {
        $summary.removeClass('text-dark').addClass('text-muted').html('Ningún universo seleccionado');
        $('#selectedTagsCountOutside').text(0); // Actualizar el contador de tags seleccionados fuera del modal
        return;
      }
      var html = slugs.map(slug => {
        var lbl = labelFor(slug);
        var count = userCountFor(slug); // Obtener el conteo de usuarios para este tag
        return `<span class="badge bg-light border text-dark me-1 mb-1">#${$('<div>').text(lbl).html()} (${count})</span>`;
      }).join('');
      $summary.removeClass('text-muted').addClass('text-dark').html(html);
      $('#selectedTagsCountOutside').text(slugs.length); // Actualizar el contador de tags seleccionados fuera del modal
    }

    // Función para actualizar el conteo de usuarios del universo
    function updateUniversoCount(selectedSlugs) {
      let totalCount = 0;
      if (selectedSlugs.length > 0) {
        selectedSlugs.forEach(slug => {
          totalCount += userCountFor(slug);
        });
      }
      $count.text(totalCount);
      $('#universo_count_input').val(totalCount); // Actualizar el input hidden
    }

    // Cargar estado inicial
    (function initFromHiddenOnLoad(){
      var initial = parseCSV($hidden.val());
      if (initial.length) {
        renderChips(initial);
        renderBadges(initial);
        updateUniversoCount(initial); // Actualizar el conteo al cargar la página
      }
    })();

  } catch (e) {
    console.error("Error en el script de modalUniverso:", e);
  }
});
</script>

<!-- Script para cargar tags vía AJAX si es necesario -->
<script>
jQuery(document).ready(function($) {
  try {
    var TAGS_URL = "<?= site_url('campanas/tags') ?>";
    // Asegurarse de que jQuery esté disponible
    if (typeof $ === 'undefined' || !$) {
      console.error("jQuery no está disponible. El script de tags AJAX no se ejecutará.");
      return;
    }

  function showTagError(msg) {
    var $alert = $('#tagDebugAlert');
    $alert.text(msg).removeClass('d-none');
  }

  $('#modalUniverso').on('shown.bs.modal', function(){
    var $select = $('#selectTagsUniverso');
    var hasOptions = $select.find('option[value]').length > 0;
    if (hasOptions) return;

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
          // Solo añadir tags si tienen al menos un usuario asociado
          if (r.user_count > 0) {
            var userCount = r.user_count !== undefined ? r.user_count : 0;
            var text = (r.tag || r.slug) + ' (' + r.slug + ')' + (userCount > 0 ? ' [' + userCount + ' usuarios]' : '');
            var opt  = new Option(text, r.slug, false, false);
            opt.setAttribute('data-label', r.tag || r.slug);
            opt.setAttribute('data-count', userCount);
            $select.append(opt);
          }
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
        showTagError(msg + ' Revisa la ruta <?= site_url('campanas/tags') ?> y el acceso a la DB.');
      });
  } catch (e) {
    console.error("Error en el script de carga de tags AJAX:", e);
  }
});
</script>

<script>
  jQuery(document).ready(function($) {
    // URLs de las APIs de delimitación territorial
    const API_URLS = {
      estados: 'https://soymetrix.com/api/estados',
      municipios: 'https://soymetrix.com/api/municipios',
      delegaciones: 'https://soymetrix.com/api/delegaciones',
      colonias: 'https://soymetrix.com/api/colonias',
    };

    // Inicializar el mapa
    var map = L.map('mapaMexico').setView([23.6345, -102.5528], 5); // Centrado en México

    // Añadir capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Estilo por defecto para los polígonos
    var defaultStyle = {
      fillColor: '#8bc34a',
      weight: 2,
      opacity: 1,
      color: 'white',
      dashArray: '3',
      fillOpacity: 0.7
    };

    // Estilo para polígonos seleccionados
    var selectedStyle = {
      fillColor: '#ff7800',
      weight: 3,
      opacity: 1,
      color: '#666',
      dashArray: '',
      fillOpacity: 0.9
    };

    var currentGeoJsonLayer;
    var allMunicipiosData = null; // Almacenar todos los datos de municipios
    var selectedMunicipios = []; // IDs de municipios seleccionados en el mapa

    // Función para cargar y filtrar capas GeoJSON
    function loadAndFilterGeoJsonLayer(geoJsonData, filterIds = [], selectedFeatureIds = []) {
      // Si la capa ya existe, la removemos del mapa
      if (currentGeoJsonLayer) {
        map.removeLayer(currentGeoJsonLayer);
      }

      // Siempre creamos una nueva capa GeoJSON
      currentGeoJsonLayer = L.geoJSON(null, { // Inicializamos con null, los datos se añadirán con addData
        filter: function(feature) {
          // Si no hay filtros, mostrar todos. Si hay filtros, mostrar solo los que coincidan.
          return filterIds.length === 0 || filterIds.includes(feature.properties.id.toString());
        },
        style: function(feature) {
          var featureId = feature.properties.id.toString();
          if (selectedFeatureIds.includes(featureId)) {
            return selectedStyle;
          }
          return defaultStyle;
        },
        onEachFeature: function(feature, layer) {
          layer.on({
            click: function(e) {
              var clickedLayer = e.target;
              var featureId = feature.properties.id.toString();

              // La lógica de selección en el mapa debe ser independiente del dropdown
              // y puede manejar tanto municipios como colonias si se desea.
              // Por ahora, mantendremos la lógica para municipios si se hace clic en un municipio.
              // Si se hace clic en una colonia, se podría implementar una lógica similar para colonias.

              // Si el feature es un municipio (asumiendo que nom_mun existe para municipios)
              if (feature.properties && feature.properties.nom_mun) {
                if (selectedMunicipios.includes(featureId)) {
                  // Deseleccionar
                  selectedMunicipios = selectedMunicipios.filter(id => id !== featureId);
                  clickedLayer.setStyle(defaultStyle);
                } else {
                  // Seleccionar
                  selectedMunicipios.push(featureId);
                  clickedLayer.setStyle(selectedStyle);
                }
                console.log("Municipios seleccionados en el mapa (por clic):", selectedMunicipios);
              }
              // Aquí podrías añadir lógica para colonias si se hace clic en ellas
            }
          });
          // Mostrar popup con el nombre del feature (municipio o colonia)
          if (feature.properties && feature.properties.nom_mun) {
            layer.bindPopup("<b>" + feature.properties.nom_mun + "</b>");
          } else if (feature.properties && feature.properties.nom_col) {
            layer.bindPopup("<b>" + feature.properties.nom_col + "</b>");
          }
        }
      }).addTo(map);

      if (!geoJsonData || !geoJsonData.features) {
        console.warn("No se proporcionaron datos GeoJSON válidos para filtrar.");
        return;
      }

      // Añadir solo las características filtradas a la capa existente
      let filteredFeatures = {
        type: "FeatureCollection",
        features: geoJsonData.features.filter(feature => {
          // Asegurarse de que la geometría no sea nula (features inválidos)
          return feature.geometry && (filterIds.length === 0 || filterIds.includes(feature.properties.id.toString()));
        })
      };
      currentGeoJsonLayer.addData(filteredFeatures);

      if (currentGeoJsonLayer.getLayers().length > 0) {
        map.fitBounds(currentGeoJsonLayer.getBounds());
      } else {
        // Si no hay capas filtradas, centrar en México
        map.setView([23.6345, -102.5528], 5);
      }
    }

    // Variables para almacenar los datos GeoJSON de cada nivel
    var allEstadosData = null; // Se mantiene por si se decide usar en el futuro, pero no se carga inicialmente
    var allMunicipiosData = null;
    var allColoniasData = null;

    // Referencias a los selects
    var $estadosFiltro = $('#estados_filtro');
    var $municipiosFiltro = $('#municipios_filtro');
    var $delegacionesColoniasFiltro = $('#delegaciones_colonias_filtro');

    // Función genérica para inicializar Select2
    function initSelect2($selectElement, placeholderText, disabled = false) {
      if ($.fn.select2) {
        if ($selectElement.data('select2')) {
          $selectElement.select2('destroy');
        }
        $selectElement.select2({
          width: '100%',
          placeholder: placeholderText,
          allowClear: true,
          disabled: disabled
        });
      }
    }

    // Función para pre-procesar los datos GeoJSON (parsear geometría)
    function preprocessGeoJson(data) {
      if (!data || !data.features) return { type: "FeatureCollection", features: [] };

      let processedFeatures = data.features.map(f => {
        let geometry = f.geometry;
        if (typeof geometry === 'string') {
          try {
            geometry = JSON.parse(geometry);
          } catch (e) {
            console.error("Error parsing geometry string for feature:", f.properties.nom_mun || f.properties.NOM_ENT || 'Unknown', e);
            geometry = null; // Marcar como inválido
          }
        }
        return {
          type: "Feature",
          geometry: geometry,
          properties: f.properties || {}
        };
      }).filter(f => f.geometry !== null); // Filtrar features con geometría nula
      return { type: "FeatureCollection", features: processedFeatures };
    }

    // Función para cargar datos de una API y poblar un select
    function loadApiDataAndPopulateSelect(apiUrl, $selectElement, placeholderText, idProperty, nameProperty, parentId = null, parentIdProperty = null) {
      $selectElement.empty();
      $selectElement.append('<option value="" disabled selected>Cargando ' + placeholderText.toLowerCase() + '...</option>');
      initSelect2($selectElement, 'Cargando ' + placeholderText.toLowerCase() + '...', true);

      $.getJSON(apiUrl, function(data) {
        let processedData = preprocessGeoJson(data);
        let filteredData = processedData.features;

        if (parentId !== null && parentIdProperty !== null) {
          filteredData = processedData.features.filter(f => f.properties[parentIdProperty] == parentId);
        }

        $selectElement.empty();
        if (filteredData.length === 0) {
          $selectElement.append('<option value="" disabled>No hay ' + placeholderText.toLowerCase() + ' disponibles</option>');
          initSelect2($selectElement, 'No hay ' + placeholderText.toLowerCase() + ' disponibles', true);
        } else {
          $selectElement.append('<option value="">Todos los ' + placeholderText.toLowerCase() + '</option>');
          filteredData.forEach(function(feature) {
            var id = feature.properties[idProperty];
            var nombre = feature.properties[nameProperty];
            $selectElement.append(new Option(nombre, id));
          });
          initSelect2($selectElement, placeholderText, false);
        }

        // Almacenar los datos cargados globalmente
        if (apiUrl === API_URLS.estados) allEstadosData = processedData;
        if (apiUrl === API_URLS.municipios) allMunicipiosData = processedData;
        if (apiUrl === API_URLS.delegaciones) allDelegacionesData = processedData;
        if (apiUrl === API_URLS.colonias) allColoniasData = processedData;

      }).fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.error("Error al cargar " + placeholderText.toLowerCase() + " desde la API: " + err);
        alert("No se pudieron cargar los " + placeholderText.toLowerCase() + ": " + err);

        $selectElement.empty();
        $selectElement.append('<option value="" disabled>Error al cargar ' + placeholderText.toLowerCase() + '</option>');
        initSelect2($selectElement, 'Error al cargar ' + placeholderText.toLowerCase() + '', true);
      });
    }

    // Cargar municipios al inicio
    loadApiDataAndPopulateSelect(API_URLS.municipios, $municipiosFiltro, 'Municipios', 'id', 'nom_mun');

    // Manejar el cambio en la selección de estados (deshabilitado/oculto)
    $estadosFiltro.on('change', function() {
      // No hacer nada, ya que el filtrado principal es por municipio
      // Si se decide habilitar los estados en el futuro, la lógica iría aquí.
    });

    // Manejar el cambio en la selección de municipios
    $municipiosFiltro.on('change', function() {
      var selectedMunicipioIds = $(this).val();
      $delegacionesColoniasFiltro.empty().prop('disabled', true);
      initSelect2($delegacionesColoniasFiltro, 'Selecciona un municipio primero', true);

      if (selectedMunicipioIds && selectedMunicipioIds.length > 0) {
        // Cargar colonias del municipio seleccionado
        loadApiDataAndPopulateSelect(API_URLS.colonias, $delegacionesColoniasFiltro, 'Colonias', 'id', 'nom_col', selectedMunicipioIds, 'cve_mun'); // Asumiendo que cve_mun es el ID del municipio en colonias
        
        // Actualizar la variable global de municipios seleccionados para el mapa
        selectedMunicipios = selectedMunicipioIds;

        // Mostrar la capa de municipios seleccionados en el mapa
        if (allMunicipiosData) {
          let filteredMunicipios = {
            type: "FeatureCollection",
            features: allMunicipiosData.features.filter(f => selectedMunicipioIds.includes(f.properties.id.toString()))
          };
          loadAndFilterGeoJsonLayer(filteredMunicipios, selectedMunicipioIds, selectedMunicipioIds);
        }
      } else {
        // Si no hay municipios seleccionados, limpiar el mapa
        selectedMunicipios = []; // Limpiar la selección del mapa
        if (currentGeoJsonLayer) currentGeoJsonLayer.clearLayers();
        map.setView([23.6345, -102.5528], 5); // Centrar en México
      }
    });

    // Manejar el cambio en la selección de delegaciones/colonias
    $delegacionesColoniasFiltro.on('change', function() {
      var selectedColoniaIds = $(this).val();
      if (selectedColoniaIds && selectedColoniaIds.length > 0) {
        // Mostrar la capa de colonias seleccionadas en el mapa
        if (allColoniasData) {
          let filteredColonias = {
            type: "FeatureCollection",
            features: allColoniasData.features.filter(f => selectedColoniaIds.includes(f.properties.id.toString()))
          };
          loadAndFilterGeoJsonLayer(filteredColonias, selectedColoniaIds, selectedColoniaIds);
        }
      } else {
        // Si no hay colonias seleccionadas, mostrar los municipios seleccionados
        var selectedMunicipioIds = $municipiosFiltro.val();
        if (selectedMunicipioIds && selectedMunicipioIds.length > 0) {
          if (allMunicipiosData) {
            let filteredMunicipios = {
              type: "FeatureCollection",
              features: allMunicipiosData.features.filter(f => selectedMunicipioIds.includes(f.properties.id.toString()))
            };
            loadAndFilterGeoJsonLayer(filteredMunicipios, selectedMunicipioIds, selectedMunicipioIds);
          }
        } else {
          // Si no hay municipios seleccionados, limpiar el mapa
          if (currentGeoJsonLayer) currentGeoJsonLayer.clearLayers();
          map.setView([23.6345, -102.5528], 5); // Centrar en México
        }
      }
    });

    // Manejar el cambio en la selección del filtro de municipios
    $('#municipios_filtro').on('change', function() {
      var selectedMunicipioIds = $(this).val(); // Obtiene un array de IDs seleccionados
      if (allMunicipiosData) {
        loadAndFilterGeoJsonLayer(allMunicipiosData, selectedMunicipioIds);
      }
    });

    // Deshabilitar los selects de "Territorio Local" y "Sector Electoral"
    // ya que la funcionalidad de municipios los reemplaza o complementa.
    $('#territorio_local').prop('disabled', true);
    $('#sector_electoral').prop('disabled', true);

  });
</script>
