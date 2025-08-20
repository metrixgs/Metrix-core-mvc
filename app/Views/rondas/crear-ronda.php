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
                                    <select name="brigadas[]" id="brigadas" class="form-select select2" multiple>
                                        <option value="" disabled selected hidden>Seleccione una opción</option>
                                        <?php foreach($dependencias as $dep): ?>
                                            <option value="<?= esc($dep['id']) ?>" <?= in_array($dep['id'], old('brigadas', [])) ? 'selected' : '' ?>><?= esc($dep['nombre']) ?></option>
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
                                    <label for="universo" class="form-label">Universo:</label>
                                    <input type="number" name="universo" id="universo" class="form-control" placeholder="# de resultados" value="<?= old('universo') ?>">
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

<!-- Select2 JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true // Permite deseleccionar
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
            var selectedBrigadas = $(this).val(); // Obtener los IDs de las brigadas seleccionadas
            var operadoresSelect = $('#operadores');
            operadoresSelect.empty(); // Limpiar el selector de operadores

            if (selectedBrigadas && selectedBrigadas.length > 0) {
                // Iterar sobre cada brigada seleccionada y hacer una llamada AJAX
                selectedBrigadas.forEach(function(brigadaId) {
                    $.ajax({
                        url: '<?= base_url('rondas/obtenerOperadoresPorBrigada/') ?>' + brigadaId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                data.forEach(function(operador) {
                                    // Añadir solo si no existe ya para evitar duplicados si se seleccionan múltiples brigadas
                                    if (!operadoresSelect.find('option[value="' + operador.id + '"]').length) {
                                        operadoresSelect.append(new Option(operador.nombre, operador.id, false, false));
                                    }
                                });
                            }
                            operadoresSelect.trigger('change'); // Notificar a Select2 que las opciones han cambiado
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al obtener operadores:", error);
                        }
                    });
                });
            } else {
                // Si no hay brigadas seleccionadas, limpiar y notificar a Select2
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
