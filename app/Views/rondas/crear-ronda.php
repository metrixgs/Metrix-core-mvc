<?php
// $brigadas se pasa desde el controlador ahora, no necesita inicialización temporal aquí
// $operadores se pasa desde el controlador ahora, no necesita inicialización temporal aquí
$segmentaciones = $segmentaciones ?? [['id' => 1, 'descripcion' => 'Segmentación Temporal']];
$territorios = $territorios ?? [['id' => 1, 'nombre' => 'Territorio Temporal']];
$distribucion = $distribucion ?? [['nombre' => 'Juan Temporal', 'puntos' => 10]];
?>

<div class="page-content">
    <div class="container-fluid">

        <!-- Título de página -->
<div class="row">
    <div class="col-12">
        <!-- No hay título de página aquí según la imagen, solo breadcrumbs abajo -->
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
        <?php if (session()->has('validation')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <ul>
                    <?php foreach (session('validation') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('rondas/crear') ?>" method="post" id="form-crear-ronda" class="needs-validation" novalidate>
            <div class="row">
                <!-- Datos Generales -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Datos Generales <i class="ri-information-fill text-muted align-bottom"></i></h4>
                            <div class="flex-shrink-0">
                                <span class="text-muted">(ID Ronda: #RDA-29302-102)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre_campana" class="form-label">Nombre de la Campaña:</label>
                                    <input type="text" id="nombre_campana" class="form-control" value="<?= esc($campana['nombre'] ?? 'Campaña no encontrada') ?>" readonly>
                                    <input type="hidden" name="campana_id" value="<?= esc($campana['id'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_ronda" class="form-label">Nombre de la Ronda:</label>
                                    <input type="text" name="nombre_ronda" id="nombre_ronda" class="form-control" placeholder="Ingrese el nombre de la ronda" value="<?= old('nombre_ronda') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_actividad" class="form-label">Fecha:</label>
                                    <input type="date" name="fecha_actividad" id="fecha_actividad" class="form-control" value="<?= old('fecha_actividad') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="hora_actividad" class="form-label">Horario:</label>
                                    <input type="time" name="hora_actividad" id="hora_actividad" class="form-control" value="<?= old('hora_actividad') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Responsables</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="brigadas" class="form-label">Brigada(s):</label>
                                    <select name="brigadas" id="brigadas" class="form-select select2">
                                        <!-- El placeholder se maneja con Select2 -->
                                        <?php foreach($dependencias as $dep): ?>
                                            <option value="<?= esc($dep['id']) ?>" <?= (old('brigadas') == $dep['id']) ? 'selected' : '' ?>><?= esc($dep['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="operadores" class="form-label">Operadores:</label>
                                    <select name="operadores[]" id="operadores" class="form-select select2" multiple>
                                        <!-- Los operadores se cargarán dinámicamente vía AJAX -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interacciones -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Interacciones</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
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
                                    <input type="hidden" id="universo" name="universo" value="<?= old('universo', '') ?>">
                                    <!-- Resumen visual -->
                                    <div id="universoSeleccionado" class="mt-2 text-muted small">
                                        Ningún universo seleccionado
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="entregable" class="form-label">Entregable:</label>
                                    <input type="number" name="entregable" id="entregable" class="form-control" placeholder="# de orden de trabajo" value="<?= old('entregable') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delimitación Territorial -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Delimitación Territorial</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="territorio" class="form-label">Territorio:</label>
                                    <select name="territorio" id="territorio" class="form-select select2">
                                        <option value="" disabled selected hidden>Seleccione territorio</option>
                                        <?php foreach($territorios as $terr): ?>
                                            <option value="<?= esc($terr['id']) ?>" <?= old('territorio') == $terr['id'] ? 'selected' : '' ?>><?= esc($terr['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_territorio" class="form-label">Nombre:</label>
                                    <input type="text" name="nombre_territorio" id="nombre_territorio" class="form-control" placeholder="Nombre del Territorio" value="<?= old('nombre_territorio') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="sectorizacion" class="form-label">Sectorización:</label>
                                    <select name="sectorizacion" id="sectorizacion" class="form-select select2">
                                        <option value="" disabled selected hidden>Seleccione sectorización</option>
                                        <?php foreach($segmentaciones as $seg): ?>
                                            <option value="<?= esc($seg['id']) ?>" <?= old('sectorizacion') == $seg['id'] ? 'selected' : '' ?>><?= esc($seg['descripcion']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_sectorizacion" class="form-label">Nombre:</label>
                                    <input type="text" name="nombre_sectorizacion" id="nombre_sectorizacion" class="form-control" placeholder="Nombre de la Sectorización" value="<?= old('nombre_sectorizacion') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución de Puntos -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Distribución de Puntos</h4>
                            <button type="button" class="btn btn-success btn-sm" id="btnGenerarAsignacion">Generar Asignación</button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2" id="distribucion-container">
                                <div class="col-12" id="no-operadores-message">
                                    <p class="text-muted">Seleccione operadores para ver la distribución de puntos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado oculto y Campaña ID -->
                <input type="hidden" name="estado" value="Programada">
                <input type="hidden" name="campana_id" value="<?= esc($_GET['campana_id'] ?? '') ?>">

                <!-- Botones de acción -->
                <div class="col-12 text-end mb-4">
                    <a href="<?= base_url('rondas') ?>" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Ronda</button>
                </div>

            </div>
        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Select2 para Brigadas (selección única)
        $('#brigadas').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true // Permite deseleccionar
        });

        // Inicializar Select2 para Operadores (selección múltiple)
        $('#operadores').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true, // Permite deseleccionar
            multiple: true // Habilita la selección múltiple
        });

        // Inicializar Select2 para otros selectores genéricos (si los hay y necesitan configuración por defecto)
        // Por ejemplo, para 'territorio' y 'sectorizacion'
        $('#territorio, #sectorizacion').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });

        // Validación de formulario Bootstrap
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Lógica para el botón "Generar Asignación" (se mantiene sin funcionalidad específica por ahora)
        document.getElementById('btnGenerarAsignacion').addEventListener('click', function() {
            // Aquí se podría añadir lógica para recalcular o reasignar puntos si fuera necesario
            // Por ahora, la tarea se enfoca en la aparición dinámica al seleccionar operadores.
            console.log('Botón Generar Asignación clickeado.');
        });

        // Lógica para cargar operadores al seleccionar una brigada
        $('#brigadas').on('change', function() {
            var selectedBrigadaId = $(this).val(); // Obtener el ID de la brigada seleccionada
            var operadoresSelect = $('#operadores');
            operadoresSelect.empty(); // Limpiar el selector de operadores

            if (selectedBrigadaId) {
                $.ajax({
                    url: '<?= base_url('rondas/obtenerOperadoresPorBrigada/') ?>' + selectedBrigadaId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(operador) {
                                operadoresSelect.append(new Option(operador.nombre, operador.id, false, false));
                            });
                        }
                        operadoresSelect.trigger('change'); // Notificar a Select2 que las opciones han cambiado
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener operadores:", error);
                    }
                });
            } else {
                // Si no hay brigada seleccionada, limpiar y notificar a Select2
                operadoresSelect.trigger('change');
            }
        });

        // Lógica para actualizar la distribución de puntos al seleccionar operadores
        $('#operadores').on('change', function() {
            var selectedOperators = $(this).select2('data');
            var distribucionContainer = $('#distribucion-container');
            distribucionContainer.empty(); // Limpiar el contenedor actual

            if (selectedOperators.length === 0) {
                distribucionContainer.append('<div class="col-12" id="no-operadores-message"><p class="text-muted">Seleccione operadores para ver la distribución de puntos.</p></div>');
            } else {
                selectedOperators.forEach(function(operator) {
                    var operatorName = operator.text.split(' (#')[0]; // Obtener solo el nombre
                    var operatorId = operator.id;
                    var html = `
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">${operatorName}</span>
                                <input type="number" name="puntos_operador[${operatorId}]" class="form-control" value="0">
                            </div>
                        </div>
                    `;
                    distribucionContainer.append(html);
                });
            }
        }).trigger('change'); // Disparar el evento al cargar la página para mostrar los operadores preseleccionados

        // Disparar el evento change en #brigadas al cargar la página si hay brigadas preseleccionadas
        // Esto es útil si se está editando una ronda y ya hay brigadas seleccionadas
        if ($('#brigadas').val() && $('#brigadas').val().length > 0) {
            $('#brigadas').trigger('change');
        }

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
