  
 <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent mt-5 pt-4">
  <h4 class="mb-sm-0" style="color: #8bc34a;">Nueva Campaña</h4>
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
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold">📋 Datos Generales</h6>
          </div>
          <div class="card-body bg-white">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label fw-semibold" style="color: #8bc34a;">Nombre de la Campaña <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" required style="border-color: #8bc34a;">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold" style="color: #8bc34a;">Vigencia <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required style="border-color: #8bc34a;">
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required style="border-color: #8bc34a;">
                </div>
              </div>
              <div class="col-md-2">
                <label class="form-label fw-semibold" style="color: #8bc34a;">Tipo <span class="text-danger">*</span></label>
                <select class="form-select select2" id="tipo_id" name="tipo_id" required style="border-color: #8bc34a;">
                  <?php foreach ($tipos_campanas as $tipo): ?>
                    <option value="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <label class="form-label fw-semibold" style="color: #8bc34a;">Objetivo de la campaña</label>
                <textarea class="form-control" name="objetivo" rows="1" style="border-color: #8bc34a;"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- DELIMITACIÓN TERRITORIAL -->
      <div class="col-md-6">
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold">🗺️ Delimitación Territorial</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="color: #8bc34a;">Territorio Local</label>
              <select class="form-select select2" id="territorio_local" name="territorio_local" style="border-color: #8bc34a;">
                 
                <option value="municipio">Municipio</option>
                <option value="delegacion">Delegación</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" style="color: #8bc34a;">Sector Electoral</label>
              <select class="form-select select2" id="sector_electoral" name="sector_electoral" style="border-color: #8bc34a;">
                
                <option value="distrito">Distrito</option>
                <option value="seccion">Sección</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <!-- MAPA DE MÉXICO -->
      <div class="col-md-6 mt-0">
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold"></h6> <!-- Título eliminado -->
          </div>
          <div class="card-body bg-white">
            <div id="mapaMexico" style="height: 300px; width: 100%;"></div> <!-- Altura reducida -->
          </div>
        </div>
      </div>

      <!-- IMPACTOS -->
      <div class="col-md-6">
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold">🎯 Impactos</h6>
          </div>
          <div class="card-body bg-white">
            <label class="form-label fw-semibold" style="color: #8bc34a;">
              Universo (<span id="universoCount">0</span>)
            </label>

            <button type="button"
                    class="btn btn-outline-success w-100"
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
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold">👥 Responsables</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="color: #8bc34a;">Área Responsable <span class="text-danger">*</span></label>
              <select class="form-select select2" id="area_id" name="area_id" required style="border-color: #8bc34a;">
                <?php foreach ($areas as $area): ?>
                  <option value="<?= $area['id']; ?>"><?= htmlspecialchars($area['nombre']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <div class="mb-3">
  <label class="form-label fw-semibold" style="color: #8bc34a;">Dependencia <span class="text-danger">*</span></label>
 <select class="form-select select2" id="dependencia" name="dependencia" required>
    <?php foreach ($dependencias as $dependencia_item): ?>
        <option value="<?= $dependencia_item['id'] ?>"><?= esc($dependencia_item['nombre']) ?></option>
    <?php endforeach; ?>
</select>


</div>

          </div>
        </div>
      </div>

      <!-- INTERACCIONES -->
      <div class="col-md-6">
        <div class="card shadow" style="border-color: #8bc34a;">
          <div class="card-header text-white" style="background-color: #8bc34a;">
            <h6 class="mb-0 fw-semibold">🔗 Interacciones</h6>
          </div>
          <div class="card-body bg-white">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="color: #8bc34a;">Encuesta <span class="text-danger">*</span></label>
              <select class="form-select select2" id="encuesta" name="encuesta[]" multiple="multiple" style="border-color: #8bc34a;">
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
              <label class="form-label fw-semibold" style="color: #8bc34a;">Entregables <span class="text-danger">*</span></label>
              <select class="form-select select2" id="entregables" name="entregables" required style="border-color: #8bc34a;">
                <option value="00001">Orden # 00001</option>
                <option value="00002">Orden # 00002</option>
                <option value="00003">Orden # 00003</option>
              </select>
            </div>
          </div>
        </div>
      </div>


      <!-- BOTONES -->
      <div class="card shadow mt-4" style="border-color: #8bc34a;">
        <div class="card-body bg-white d-flex justify-content-end">
          <a href="<?= base_url('campanas'); ?>" class="btn btn-outline me-2" style="border-color: #8bc34a; color: #8bc34a;">
            <i class="fas fa-times me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn text-white" style="background-color: #8bc34a; border-color: #8bc34a;">
            <i class="fas fa-plus me-1"></i> Crear Campaña
          </button>
        </div>
      </div>

    </div>
  </div>
</form>


<!-- ===================== MODAL UNIVERSO ===================== -->
<div class="modal fade" id="modalUniverso" tabindex="-1" aria-labelledby="modalUniversoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" style="border-color:#8bc34a;">
      <div class="modal-header" style="background-color:#8bc34a;">
        <h5 class="modal-title text-white" id="modalUniversoLabel">Seleccionar Universo (Tags)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <label class="form-label fw-semibold" style="color:#8bc34a;">Escribe para buscar y selecciona uno o varios</label>

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
          <label class="form-label mb-1">Seleccionados</label>
          <div id="chipsContainer" class="chips-container border rounded p-2" style="min-height:44px;"></div>
        </div>

      

        <small class="text-muted d-block mt-2">
          Al pulsar “Aplicar”, el valor se guarda en el formulario (input hidden) y verás los badges en la tarjeta.
        </small>
      </div>

      <div class="modal-footer">
        <button type="button" id="btnClearUniverso" class="btn btn-outline-secondary">Limpiar</button>
        <button type="button" id="btnAplicarUniverso" class="btn text-white" style="background-color:#8bc34a;">Aplicar</button>
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
        // No tocar $count aquí, ya se maneja en updateUniversoCount
        return;
      }
      var html = slugs.map(slug => {
        var lbl = labelFor(slug);
        var count = userCountFor(slug); // Obtener el conteo de usuarios para este tag
        return `<span class="badge bg-light border text-dark me-1 mb-1">#${$('<div>').text(lbl).html()} (${count})</span>`;
      }).join('');
      $summary.removeClass('text-muted').addClass('text-dark').html(html);
      // No tocar $count aquí, ya se maneja en updateUniversoCount
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
    // Inicializar el mapa
    map = L.map('mapaMexico').setView([23.6345, -102.5528], 5); // Centrado en México

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

    // Función para cargar capas GeoJSON
    function loadGeoJsonLayer(geoJsonUrl) {
      if (currentGeoJsonLayer) {
        map.removeLayer(currentGeoJsonLayer);
      }
      selectedPolygons = []; // Limpiar selección al cambiar de capa

      $.getJSON(geoJsonUrl, function(data) {
        selectedPolygons = []; // Limpiar selección anterior

        currentGeoJsonLayer = L.geoJSON(data, {
          style: function(feature) {
            // Aplicar selectedStyle a todos los polígonos al cargar la capa
            var featureId = feature.properties.id || feature.properties.CVE_ENT || feature.properties.CVE_MUN;
            selectedPolygons.push(featureId); // Añadir todos los IDs a la selección
            return selectedStyle; // Estilo por defecto para todos los polígonos al cargar
          },
          onEachFeature: function(feature, layer) {
            layer.on({
              click: function(e) {
                var clickedLayer = e.target;
                var featureId = feature.properties.id || feature.properties.CVE_ENT || feature.properties.CVE_MUN;

                if (selectedPolygons.includes(featureId)) {
                  // Deseleccionar
                  selectedPolygons = selectedPolygons.filter(id => id !== featureId);
                  clickedLayer.setStyle(defaultStyle);
                } else {
                  // Seleccionar
                  selectedPolygons.push(featureId);
                  clickedLayer.setStyle(selectedStyle);
                }
                console.log("Polígonos seleccionados:", selectedPolygons);
                // Aquí podrías actualizar un input hidden con los IDs seleccionados
              }
            });
            // Opcional: Añadir tooltip o popup
            if (feature.properties && feature.properties.NOM_ENT) {
              layer.bindPopup(feature.properties.NOM_ENT);
            } else if (feature.properties && feature.properties.NOM_MUN) {
              layer.bindPopup(feature.properties.NOM_MUN);
            } else if (feature.properties && feature.properties.NOM_LOC) { // Para delegaciones/localidades
              layer.bindPopup(feature.properties.NOM_LOC);
            } else if (feature.properties && feature.properties.NOM_DIST) { // Para distritos
              layer.bindPopup(feature.properties.NOM_DIST);
            } else if (feature.properties && feature.properties.SECCION) { // Para secciones
              layer.bindPopup('Sección: ' + feature.properties.SECCION);
            }
          }
        }).addTo(map);

        map.fitBounds(currentGeoJsonLayer.getBounds());
      }).fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.error("Request Failed: " + err);
        alert("No se pudo cargar la capa GeoJSON: " + err);
      });
    }

    // Cargar la capa de estados de México por defecto (ejemplo con GeoJSON)
    // Necesitarás un endpoint que sirva GeoJSON para estados, municipios, etc.
    // Por ahora, usaré un placeholder. Deberás reemplazar esto con tus URLs reales.
    loadGeoJsonLayer('<?= base_url('mapa/data/estados.geojson') ?>'); // Placeholder

    // Manejar el cambio en la selección de delimitación territorial
    $('#territorio_local').on('change', function() {
      var selectedValue = $(this).val();
      if (selectedValue === 'municipio') {
        loadGeoJsonLayer('<?= base_url('mapa/data/municipios.geojson') ?>'); // Placeholder
      } else if (selectedValue === 'delegacion') {
        loadGeoJsonLayer('<?= base_url('mapa/data/delegaciones.geojson') ?>'); // Placeholder
      } else {
        loadGeoJsonLayer('<?= base_url('mapa/data/estados.geojson') ?>'); // Por defecto
      }
    });

    // Manejar el cambio en la selección de sector electoral
    $('#sector_electoral').on('change', function() {
      var selectedValue = $(this).val();
      if (selectedValue === 'distrito') {
        loadGeoJsonLayer('<?= base_url('mapa/data/distritos.geojson') ?>'); // Placeholder
      } else if (selectedValue === 'seccion') {
        loadGeoJsonLayer('<?= base_url('mapa/data/secciones.geojson') ?>'); // Placeholder
      } else {
        loadGeoJsonLayer('<?= base_url('mapa/data/estados.geojson') ?>'); // Por defecto
      }
    });

  });
</script>
