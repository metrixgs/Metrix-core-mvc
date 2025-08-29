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
                                    <p class="form-control-plaintext" id="nombre_campana"><?= esc($campana['nombre'] ?? 'Campaña no encontrada') ?></p>
                                    <input type="hidden" name="campana_id" value="<?= esc($campana['id'] ?? '') ?>">
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
                                    <select name="brigada_id" id="brigadas" class="form-select select2">
                                        <option value="" disabled selected hidden>Seleccione una brigada</option>
                                        <?php foreach($brigadas as $brigada): ?>
                                            <option value="<?= esc($brigada['id']) ?>" <?= (old('brigada_id') == $brigada['id']) ? 'selected' : '' ?>><?= esc($brigada['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="operadores" class="form-label">Operadores:</label>
                                    <select name="operadores[]" id="operadores" class="form-select select2" multiple>
                                        <!-- Los operadores se cargarán dinámicamente vía AJAX -->
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="coordinador" class="form-label">Coordinador:</label>
                                    <select name="coordinador" id="coordinador" class="form-select select2" required>
                                        <option value="" disabled selected hidden>Seleccione un coordinador</option>
                                        <?php foreach($usuarios_coordinador as $coord): ?>
                                            <option value="<?= esc($coord['id']) ?>" <?= (old('coordinador') == $coord['id']) ? 'selected' : '' ?>><?= esc($coord['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, seleccione un coordinador.
                                    </div>
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
                                <div class="col-md-12">
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
                                    <label for="sectorizacion" class="form-label">Sectorización:</label>
                                    <select name="sectorizacion" id="sectorizacion" class="form-select select2">
                                        <option value="" disabled selected hidden>Seleccione sectorización</option>
                                        <?php foreach($segmentaciones as $seg): ?>
                                            <option value="<?= esc($seg['id']) ?>" <?= old('sectorizacion') == $seg['id'] ? 'selected' : '' ?>><?= esc($seg['descripcion']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-semibold">
                                        Universo (<span id="universoCount"><?= esc($campana['universo_count'] ?? 0) ?></span>)
                                    </label>
                                    <!-- Resumen visual de los tags de la campaña -->
                                    <div id="universoSeleccionado" class="mt-2 text-muted small">
                                        <?php
                                        $universoSlugs = $campana['universo'] ?? '';
                                        $universoTags = explode(',', $universoSlugs);
                                        $universoTags = array_filter(array_map('trim', $universoTags)); // Limpiar y filtrar vacíos

                                        if (!empty($universoTags)) {
                                            foreach ($universoTags as $tagSlug) {
                                                // Filtrar tags específicos que se consideran "quemados" o no deseados en la visualización
                                                if (in_array($tagSlug, ['salvadorlista', 'joven', 'deportista'])) {
                                                    continue; // Saltar estos tags
                                                }
                                                echo '<span class="badge bg-light border text-primary me-1 mb-1">#' . esc($tagSlug) . '</span>';
                                            }
                                        } else {
                                            echo 'Ningún universo seleccionado en la campaña';
                                        }
                                        ?>
                                    </div>
                                    <!-- Campo oculto para mantener el universo de la campaña si se necesita en el POST de la ronda -->
                                    <input type="hidden" name="universo_campana" value="<?= esc($campana['universo'] ?? '') ?>">
                                    <input type="hidden" name="universo_count_campana" value="<?= esc($campana['universo_count'] ?? 0) ?>">
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

        // Lógica para el botón "Generar Asignación"
        document.getElementById('btnGenerarAsignacion').addEventListener('click', function() {
            var totalUsers = parseInt($('#universoCount').text()) || 0; // Obtener el universo real de la campaña
            var selectedOperators = $('#operadores').select2('data');
            var numOperators = selectedOperators.length;
            var distribucionContainer = $('#distribucion-container');

            if (numOperators === 0) {
                alert('Por favor, seleccione al menos un operador para generar la asignación.');
                return;
            }

            if (totalUsers === 0) {
                alert('El universo de usuarios es 0. No se pueden asignar puntos.');
                return;
            }

            var basePointsPerOperator = Math.floor(totalUsers / numOperators);
            var remainder = totalUsers % numOperators;

            distribucionContainer.empty(); // Limpiar el contenedor actual

            selectedOperators.forEach(function(operator, index) {
                var operatorName = operator.text.split(' (#')[0];
                var operatorId = operator.id;
                var points = basePointsPerOperator;

                if (index < remainder) {
                    points++; // Distribuir el resto entre los primeros operadores
                }


                var html = `
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">${operatorName}</span>
                            <input type="number" name="puntos_operador[${operatorId}]" class="form-control" value="${points}" min="0">
                        </div>
                    </div>
                `;
                distribucionContainer.append(html);
            });
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
                                <input type="number" name="puntos_operador[${operatorId}]" class="form-control" value="0" min="0">
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

        // Lógica para el botón "Generar Asignación"
        document.getElementById('btnGenerarAsignacion').addEventListener('click', function() {
            var totalUsers = parseInt($('#universoCount').text()) || 0; // Obtener el universo real de la campaña
            var selectedOperators = $('#operadores').select2('data');
            var numOperators = selectedOperators.length;
            var distribucionContainer = $('#distribucion-container');

            if (numOperators === 0) {
                alert('Por favor, seleccione al menos un operador para generar la asignación.');
                return;
            }

            if (totalUsers === 0) {
                alert('El universo de usuarios es 0. No se pueden asignar puntos.');
                return;
            }

            var basePointsPerOperator = Math.floor(totalUsers / numOperators);
            var remainder = totalUsers % numOperators;

            distribucionContainer.empty(); // Limpiar el contenedor actual

            selectedOperators.forEach(function(operator, index) {
                var operatorName = operator.text.split(' (#')[0];
                var operatorId = operator.id;
                var points = basePointsPerOperator;

                if (index < remainder) {
                    points++; // Distribuir el resto entre los primeros operadores
                }

                var html = `
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">${operatorName}</span>
                            <input type="number" name="puntos_operador[${operatorId}]" class="form-control" value="${points}" min="0">
                        </div>
                    </div>
                `;
                distribucionContainer.append(html);
            });
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
                                <input type="number" name="puntos_operador[${operatorId}]" class="form-control" value="0" min="0">
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
