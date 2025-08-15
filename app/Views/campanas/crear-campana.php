  
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
              <div class="col-md-6">
                <label class="form-label fw-semibold" style="color: #8bc34a;">Canal de difusión</label>
                <input type="text" class="form-control" name="canal_difusion" placeholder="Ej: Redes Sociales" style="border-color: #8bc34a;">
              </div>
              <div class="col-md-6">
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
      </div>     <!-- IMPACTOS -->
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

            <!-- Resumen visual -->
            <div id="universoSeleccionado" class="mt-2 text-muted small">
              Ningún universo seleccionado
            </div>
          </div>
        </div>
      </div>

      <!-- RESPONSABLES -->
      <div class="col-md-6">
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
  <label class="form-label fw-semibold" style="color: #8bc34a;">Coordinador(a) <span class="text-danger">*</span></label>
 <select class="form-select select2" id="coordinador" name="coordinador" required>
    <?php foreach ($usuarios_coordinador as $usuario): ?>
        <option value="<?= $usuario['id'] ?>"><?= esc($usuario['nombre']) ?></option>
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
                  <option value="">Seleccione una encuesta</option>
                  <?php foreach ($surveys as $survey): ?>
                    <option value="<?= $survey['id']; ?>">#<?= $survey['id']; ?> <?= htmlspecialchars($survey['title']); ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option disabled selected>No hay encuestas registradas</option>
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

      <!-- DESCRIPCIÓN -->
      <div class="col-md-12">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="color: #8bc34a;">Descripción <span class="text-danger">*</span></label>
          <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
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
        <select id="selectTagsUniverso" class="form-select" multiple>
          <?php if (!empty($catalogo_tags)): ?>
            <?php $stats = $tag_stats ?? []; ?>
            <?php foreach ($catalogo_tags as $t): ?>
              <option
                value="<?= esc($t['slug']) ?>"
                data-label="<?= esc($t['tag']) ?>"
                data-count="<?= isset($stats[$t['slug']]) ? (int)$stats[$t['slug']] : '' ?>">
                <?= esc($t['tag']) ?> (<?= esc($t['slug']) ?>)
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
<script>
  // Carga jQuery solo si no existe
  if (!window.jQuery) {
    document.write('<script src="https://code.jquery.com/jquery-3.7.1.min.js"><\/script>');
  }
 </script>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
<!-- (Opcional) Si tu layout NO carga Bootstrap JS y el modal no cierra/abre, descomenta: -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- ================================================ -->

<script>
(function($){
  var $modal   = $('#modalUniverso');
  var $select  = $('#selectTagsUniverso');
  var $hidden  = $('#universo');
  var $summary = $('#universoSeleccionado');
  var $count   = $('#universoCount');
  var $chips   = $('#chipsContainer');
  var $csv     = $('#universoCsv');
  var select2Init = false;

  // Inicializa Select2 en selects externos
  if ($.fn.select2) {
    $('.select2').select2({ width: '100%' });
  }

  // ===== Fix clave: permitir clic UNO A UNO en el select nativo (sin Ctrl) =====
  // Funciona como fallback si Select2 no se aplica (verás la lista azul nativa).
  $select.on('mousedown', 'option', function (e) {
    // Si Select2 ya transformó el select, no hacemos nada.
    if ($select.data('select2')) return;

    e.preventDefault(); // evita que el navegador reemplace toda la selección
    var $opt = $(this);
    $opt.prop('selected', !$opt.prop('selected'));
    // Disparar el change para refrescar chips / csv
    $select.trigger('change');

    // Mantener el foco en el select (evita que se cierre en algunos navegadores)
    return false;
  });

  // Abrir modal
  $modal.on('shown.bs.modal', function () {
    // Inicializa Select2 para el modal
    if (!select2Init && $.fn.select2) {
      $select.select2({
        width: '100%',
        placeholder: 'Selecciona uno o varios tags',
        dropdownParent: $modal,
        closeOnSelect: false // seguir seleccionando sin cerrar
      });
      select2Init = true;
    }

    // Precargar selección guardada
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
      // Fallback por si no hay Bootstrap JS
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
      `<span class="badge bg-light border text-dark me-1 mb-1">#${$('<div>').text(labelFor(slug)).html()}</span>`
    ).join('');
    $summary.removeClass('text-muted').addClass('text-dark').html(html);
    $count.text(slugs.length);
  }

  // Pintar estado si el hidden ya trae datos al cargar la página
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
/* Fallback AJAX: carga tags si no están en HTML */
(function(){
  var TAGS_URL = "<?= site_url('campanas/tags') ?>";
  var $ = window.jQuery;
  if (!$) return;

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
        showTagError(msg + ' Revisa la ruta <?= site_url('campanas/tags') ?> y el acceso a la DB.');
      });
  });
})();
</script>
