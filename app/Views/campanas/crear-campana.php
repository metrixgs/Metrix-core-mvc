  
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
            <!-- Selector de Territorio -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Territorio <span class="text-danger">*</span></label>
              <select class="form-select select2 border-success-salvador" id="territorio_selector" name="territorio_selector" required>
                <option value="">Selecciona un tipo de territorio</option>
                <option value="municipio">Municipio</option>
                <option value="delegacion">Delegación</option>
                <option value="distrito_federal">Distrito Federal</option>
                <option value="distrito_local">Distrito Local</option>
              </select>
            </div>

            <!-- Contenedores dinámicos para los selectores de territorio -->
            <div id="territorio_municipio_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Municipio</label>
              <select class="form-select select2 border-success-salvador" id="municipios_filtro" name="municipios_filtro[]" multiple="multiple">
                <option value="" disabled selected>Selecciona un municipio</option>
              </select>
            </div>

            <div id="territorio_delegacion_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Delegación</label>
              <select class="form-select select2 border-success-salvador" id="delegaciones_filtro" name="delegaciones_filtro[]" multiple="multiple">
                <option value="" disabled selected>Selecciona una delegación</option>
              </select>
            </div>

            <div id="territorio_distrito_federal_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Distrito Federal</label>
              <select class="form-select select2 border-success-salvador" id="distrito_federal_filtro" name="distrito_federal_filtro[]" multiple="multiple">
                <option value="" disabled selected>API en construcción</option>
              </select>
            </div>

            <div id="territorio_distrito_local_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Distrito Local</label>
              <select class="form-select select2 border-success-salvador" id="distrito_local_filtro" name="distrito_local_filtro[]" multiple="multiple">
                <option value="" disabled selected>API en construcción</option>
              </select>
            </div>

            <!-- Selector de Segmentación -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-success-salvador">Segmentación <span class="text-danger">*</span></label>
              <select class="form-select select2 border-success-salvador" id="segmentacion_selector" name="segmentacion_selector" required disabled>
                <option value="">Selecciona un tipo de segmentación</option>
                <option value="colonia">Colonia/Localidad</option>
                <option value="seccion_electoral">Sección Electoral</option>
              </select>
            </div>

            <!-- Contenedores dinámicos para los selectores de segmentación -->
            <div id="segmentacion_colonia_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Colonia/Localidad</label>
              <select class="form-select select2 border-success-salvador" id="delegaciones_colonias_filtro" name="delegaciones_colonias_filtro[]" multiple="multiple">
                <option value="" disabled selected>Selecciona un territorio primero</option>
              </select>
            </div>

            <div id="segmentacion_seccion_electoral_container" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold text-success-salvador">Sección Electoral</label>
              <select class="form-select select2 border-success-salvador" id="seccion_electoral_filtro" name="seccion_electoral_filtro[]" multiple="multiple">
                <option value="" disabled selected>API en construcción</option>
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
      delegaciones: 'https://soymetrix.com/api/delegaciones', // Asumiendo que existe una API para delegaciones
      colonias: 'https://soymetrix.com/api/colonias',
      distrito_federal: 'API_EN_CONSTRUCCION_DISTRITO_FEDERAL', // Placeholder
      distrito_local: 'API_EN_CONSTRUCCION_DISTRITO_LOCAL',     // Placeholder
      seccion_electoral: 'API_EN_CONSTRUCCION_SECCION_ELECTORAL', // Placeholder
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
    var allMunicipiosData = null;
    var allDelegacionesData = null; // Nuevo para delegaciones
    var allColoniasData = null;
    var allDistritoFederalData = null; // Nuevo
    var allDistritoLocalData = null;   // Nuevo
    var allSeccionElectoralData = null; // Nuevo

    var selectedTerritorioIds = []; // IDs de elementos seleccionados en el mapa (municipios, delegaciones, etc.)

    // Función para cargar y filtrar capas GeoJSON
    function loadAndFilterGeoJsonLayer(geoJsonData, filterIds = [], selectedFeatureIds = [], idProperty = 'id', nameProperty = 'nom_mun') {
      if (currentGeoJsonLayer) {
        map.removeLayer(currentGeoJsonLayer);
      }

      currentGeoJsonLayer = L.geoJSON(null, {
        filter: function(feature) {
          return filterIds.length === 0 || filterIds.includes(feature.properties[idProperty].toString());
        },
        style: function(feature) {
          var featureId = feature.properties[idProperty].toString();
          if (selectedFeatureIds.includes(featureId)) {
            return selectedStyle;
          }
          return defaultStyle;
        },
        onEachFeature: function(feature, layer) {
          layer.on({
            click: function(e) {
              var clickedLayer = e.target;
              var featureId = feature.properties[idProperty].toString();

              if (selectedTerritorioIds.includes(featureId)) {
                selectedTerritorioIds = selectedTerritorioIds.filter(id => id !== featureId);
                clickedLayer.setStyle(defaultStyle);
              } else {
                selectedTerritorioIds.push(featureId);
                clickedLayer.setStyle(selectedStyle);
              }
              console.log("Elementos seleccionados en el mapa (por clic):", selectedTerritorioIds);
            }
          });
          if (feature.properties && feature.properties[nameProperty]) {
            layer.bindPopup("<b>" + feature.properties[nameProperty] + "</b>");
          }
        }
      }).addTo(map);

      if (!geoJsonData || !geoJsonData.features) {
        console.warn("No se proporcionaron datos GeoJSON válidos para filtrar.");
        return;
      }

      let filteredFeatures = {
        type: "FeatureCollection",
        features: geoJsonData.features.filter(feature => {
          return feature.geometry && (filterIds.length === 0 || filterIds.includes(feature.properties[idProperty].toString()));
        })
      };
      currentGeoJsonLayer.addData(filteredFeatures);

      if (currentGeoJsonLayer.getLayers().length > 0) {
        map.fitBounds(currentGeoJsonLayer.getBounds());
      } else {
        map.setView([23.6345, -102.5528], 5);
      }
    }

    // Referencias a los nuevos selects y contenedores
    var $territorioSelector = $('#territorio_selector');
    var $segmentacionSelector = $('#segmentacion_selector');

    var $territorioMunicipioContainer = $('#territorio_municipio_container');
    var $territorioDelegacionContainer = $('#territorio_delegacion_container');
    var $territorioDistritoFederalContainer = $('#territorio_distrito_federal_container');
    var $territorioDistritoLocalContainer = $('#territorio_distrito_local_container');

    var $segmentacionColoniaContainer = $('#segmentacion_colonia_container');
    var $segmentacionSeccionElectoralContainer = $('#segmentacion_seccion_electoral_container');

    var $municipiosFiltro = $('#municipios_filtro');
    var $delegacionesFiltro = $('#delegaciones_filtro');
    var $distritoFederalFiltro = $('#distrito_federal_filtro');
    var $distritoLocalFiltro = $('#distrito_local_filtro');
    var $delegacionesColoniasFiltro = $('#delegaciones_colonias_filtro'); // Ahora para Colonia/Localidad
    var $seccionElectoralFiltro = $('#seccion_electoral_filtro');

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
      
      if (apiUrl.startsWith('API_EN_CONSTRUCCION')) {
        $selectElement.append('<option value="" disabled selected>API en construcción</option>');
        initSelect2($selectElement, 'API en construcción', true);
        return;
      }

      $selectElement.append('<option value="" disabled selected>Cargando ' + placeholderText.toLowerCase() + '...</option>');
      initSelect2($selectElement, 'Cargando ' + placeholderText.toLowerCase() + '...', true);

      let finalApiUrl = apiUrl;
      if (parentId !== null && parentIdProperty !== null) {
        if (Array.isArray(parentId) && parentId.length > 0) {
          // Asumiendo que la API puede filtrar por múltiples IDs de padres
          finalApiUrl += `?${parentIdProperty}=${parentId.join(',')}`;
        } else if (!Array.isArray(parentId)) {
          finalApiUrl += `?${parentIdProperty}=${parentId}`;
        }
      }

      $.getJSON(finalApiUrl, function(data) {
        let processedData = preprocessGeoJson(data);
        let filteredData = processedData.features;

        // El filtrado en el cliente ya no es necesario si la API filtra por parentId
        // Sin embargo, lo mantengo como fallback si la API no filtra o si hay otros filtros
        if (parentId !== null && parentIdProperty !== null && !finalApiUrl.includes(`?${parentIdProperty}=`)) {
          if (Array.isArray(parentId)) {
            filteredData = processedData.features.filter(f => parentId.includes(f.properties[parentIdProperty].toString()));
          } else {
            filteredData = processedData.features.filter(f => f.properties[parentIdProperty].toString() == parentId.toString());
          }
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
        if (apiUrl === API_URLS.municipios) allMunicipiosData = processedData;
        if (apiUrl === API_URLS.delegaciones) allDelegacionesData = processedData;
        if (apiUrl === API_URLS.colonias) allColoniasData = processedData;
        if (apiUrl === API_URLS.distrito_federal) allDistritoFederalData = processedData;
        if (apiUrl === API_URLS.distrito_local) allDistritoLocalData = processedData;
        if (apiUrl === API_URLS.seccion_electoral) allSeccionElectoralData = processedData;

      }).fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.error("Error al cargar " + placeholderText.toLowerCase() + " desde la API: " + err);
        alert("No se pudieron cargar los " + placeholderText.toLowerCase() + ": " + err);

        $selectElement.empty();
        $selectElement.append('<option value="" disabled>Error al cargar ' + placeholderText.toLowerCase() + '</option>');
        initSelect2($selectElement, 'Error al cargar ' + placeholderText.toLowerCase() + '', true);
      });
    }

    // Función para ocultar todos los contenedores de territorio y segmentación
    function hideAllTerritorioAndSegmentacionContainers() {
      $territorioMunicipioContainer.hide();
      $territorioDelegacionContainer.hide();
      $territorioDistritoFederalContainer.hide();
      $territorioDistritoLocalContainer.hide();
      $segmentacionColoniaContainer.hide();
      $segmentacionSeccionElectoralContainer.hide();
      // Limpiar el mapa cuando se cambian los filtros principales
      if (currentGeoJsonLayer) currentGeoJsonLayer.clearLayers();
      map.setView([23.6345, -102.5528], 5); // Centrar en México
    }

    // Manejar el cambio en el selector de Territorio
    $territorioSelector.on('change', function() {
      var selectedTerritorioType = $(this).val();
      hideAllTerritorioAndSegmentacionContainers();
      $segmentacionSelector.val('').trigger('change').prop('disabled', true); // Limpiar y deshabilitar segmentación

      // Limpiar todos los selects de territorio y segmentación
      $municipiosFiltro.empty().prop('disabled', true);
      $delegacionesFiltro.empty().prop('disabled', true);
      $distritoFederalFiltro.empty().prop('disabled', true);
      $distritoLocalFiltro.empty().prop('disabled', true);
      $delegacionesColoniasFiltro.empty().prop('disabled', true);
      $seccionElectoralFiltro.empty().prop('disabled', true);

      initSelect2($municipiosFiltro, 'Selecciona un municipio', true);
      initSelect2($delegacionesFiltro, 'Selecciona una delegación', true);
      initSelect2($distritoFederalFiltro, 'Selecciona un distrito federal', true);
      initSelect2($distritoLocalFiltro, 'Selecciona un distrito local', true);
      initSelect2($delegacionesColoniasFiltro, 'Selecciona un territorio primero', true);
      initSelect2($seccionElectoralFiltro, 'Selecciona un territorio primero', true);


      if (selectedTerritorioType) {
        $segmentacionSelector.prop('disabled', false); // Habilitar segmentación
        switch (selectedTerritorioType) {
          case 'municipio':
            $territorioMunicipioContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.municipios, $municipiosFiltro, 'Municipios', 'id', 'nom_mun');
            break;
          case 'delegacion':
            $territorioDelegacionContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.delegaciones, $delegacionesFiltro, 'Delegaciones', 'id_del', 'nom_del');
            break;
          case 'distrito_federal':
            $territorioDistritoFederalContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.distrito_federal, $distritoFederalFiltro, 'Distrito Federal', 'id', 'nombre'); // Asumiendo 'nombre'
            break;
          case 'distrito_local':
            $territorioDistritoLocalContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.distrito_local, $distritoLocalFiltro, 'Distrito Local', 'id', 'nombre'); // Asumiendo 'nombre'
            break;
        }
      }
    });

    // Manejar el cambio en el selector de Segmentación
    $segmentacionSelector.on('change', function() {
      var selectedSegmentacionType = $(this).val();
      $segmentacionColoniaContainer.hide();
      $segmentacionSeccionElectoralContainer.hide();

      // Limpiar selects de segmentación
      $delegacionesColoniasFiltro.empty().prop('disabled', true);
      $seccionElectoralFiltro.empty().prop('disabled', true);
      initSelect2($delegacionesColoniasFiltro, 'Selecciona un territorio primero', true);
      initSelect2($seccionElectoralFiltro, 'Selecciona un territorio primero', true);

      var selectedTerritorioType = $territorioSelector.val();
      var parentIds = [];

      // Obtener los IDs seleccionados del filtro de territorio activo
      switch (selectedTerritorioType) {
        case 'municipio':
          parentIds = $municipiosFiltro.val();
          break;
        case 'delegacion':
          parentIds = $delegacionesFiltro.val();
          break;
        case 'distrito_federal':
          parentIds = $distritoFederalFiltro.val();
          break;
        case 'distrito_local':
          parentIds = $distritoLocalFiltro.val();
          break;
      }

      if (selectedSegmentacionType && parentIds && parentIds.length > 0) {
        switch (selectedSegmentacionType) {
          case 'colonia':
            $segmentacionColoniaContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.colonias, $delegacionesColoniasFiltro, 'Colonias/Localidades', 'id', 'nom_col', parentIds, 'cve_mun'); // Asumiendo cve_mun como parentIdProperty
            // Si ya hay municipios seleccionados, forzar la actualización de colonias
            if ($municipiosFiltro.val() && $municipiosFiltro.val().length > 0) {
              $municipiosFiltro.trigger('change');
            }
            break;
          case 'seccion_electoral':
            $segmentacionSeccionElectoralContainer.show();
            loadApiDataAndPopulateSelect(API_URLS.seccion_electoral, $seccionElectoralFiltro, 'Sección Electoral', 'id', 'nombre', parentIds, 'parent_id'); // Asumiendo 'nombre' y 'parent_id'
            break;
        }
      } else if (selectedSegmentacionType && (!parentIds || parentIds.length === 0)) {
        // Si no hay territorio seleccionado, pero se intenta seleccionar segmentación
        // Mostrar mensaje de "Selecciona un territorio primero"
        if (selectedSegmentacionType === 'colonia') {
          $segmentacionColoniaContainer.show();
          $delegacionesColoniasFiltro.empty().append('<option value="" disabled selected>Selecciona un territorio primero</option>');
          initSelect2($delegacionesColoniasFiltro, 'Selecciona un territorio primero', true);
        } else if (selectedSegmentacionType === 'seccion_electoral') {
          $segmentacionSeccionElectoralContainer.show();
          $seccionElectoralFiltro.empty().append('<option value="" disabled selected>Selecciona un territorio primero</option>');
          initSelect2($seccionElectoralFiltro, 'Selecciona un territorio primero', true);
        }
      }
    });

    // Manejar el cambio en los selectores de territorio para cargar segmentación
    // Esto es crucial para que al cambiar un municipio, se actualicen las colonias si "Colonia" está seleccionada en Segmentación
    $municipiosFiltro.on('change', function() {
      var selectedTerritorioType = $territorioSelector.val();
      var selectedSegmentacionType = $segmentacionSelector.val();
      var selectedMunicipioIds = $(this).val();

      if (selectedTerritorioType === 'municipio' && selectedSegmentacionType === 'colonia') {
        $delegacionesColoniasFiltro.empty().prop('disabled', true);
        initSelect2($delegacionesColoniasFiltro, 'Cargando Colonias/Localidades...', true);
        if (selectedMunicipioIds && selectedMunicipioIds.length > 0) {
          loadApiDataAndPopulateSelect(API_URLS.colonias, $delegacionesColoniasFiltro, 'Colonias/Localidades', 'id', 'nom_col', selectedMunicipioIds, 'cve_mun');
        } else {
          initSelect2($delegacionesColoniasFiltro, 'Selecciona un municipio primero', true);
        }
      }
      // Actualizar el mapa con los municipios seleccionados
      if (allMunicipiosData) {
        loadAndFilterGeoJsonLayer(allMunicipiosData, selectedMunicipioIds, selectedMunicipioIds, 'id', 'nom_mun');
      } else {
        if (currentGeoJsonLayer) currentGeoJsonLayer.clearLayers();
        map.setView([23.6345, -102.5528], 5);
      }
    });

    // Manejar el cambio en el selector de Delegaciones para actualizar el mapa
    $delegacionesFiltro.on('change', function() {
      var selectedDelegacionIds = $(this).val();
      if (allDelegacionesData) {
        loadAndFilterGeoJsonLayer(allDelegacionesData, selectedDelegacionIds, selectedDelegacionIds, 'id_del', 'nom_del');
      } else {
        if (currentGeoJsonLayer) currentGeoJsonLayer.clearLayers();
        map.setView([23.6345, -102.5528], 5);
      }
    });

    // Inicialización: Ocultar todos los contenedores al cargar la página
    hideAllTerritorioAndSegmentacionContainers();
    initSelect2($territorioSelector, 'Selecciona un tipo de territorio');
    initSelect2($segmentacionSelector, 'Selecciona un tipo de segmentación', true); // Deshabilitado inicialmente

    // Cargar municipios al inicio si "Municipio" es el valor por defecto o el primero seleccionado
    // Esto ya no es necesario aquí, ya que se maneja con el evento change del $territorioSelector
    // loadApiDataAndPopulateSelect(API_URLS.municipios, $municipiosFiltro, 'Municipios', 'id', 'nom_mun');

  });
</script>
