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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Crear Nueva Ronda</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('rondas') ?>">Rondas</a></li>
                            <li class="breadcrumb-item active">Crear Ronda</li>
                        </ol>
                    </div>
                </div>
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

        <!-- Formulario principal con diseño mejorado -->
        <form action="<?= base_url('rondas/crear') ?>" method="post" id="form-crear-ronda" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><i class="ri-add-circle-fill me-1"></i> Detalles de la Nueva Ronda</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre de la Ronda <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Ronda de Verificación QRO" required value="<?= old('nombre') ?>">
                                    <div class="invalid-feedback">Por favor, ingrese un nombre para la ronda.</div>
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_actividad" class="form-label">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_actividad" id="fecha_actividad" class="form-control" required value="<?= old('fecha_actividad') ?>">
                                    <div class="invalid-feedback">Por favor, seleccione una fecha.</div>
                                </div>
                                <div class="col-md-3">
                                    <label for="hora_actividad" class="form-label">Horario <span class="text-danger">*</span></label>
                                    <input type="time" name="hora_actividad" id="hora_actividad" class="form-control" required value="<?= old('hora_actividad') ?>">
                                    <div class="invalid-feedback">Por favor, seleccione un horario.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><i class="ri-user-fill me-1"></i> Responsables</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="coordinador" class="form-label">Coordinador (Brigada) <span class="text-danger">*</span></label>
                                    <select name="coordinador" id="coordinador" class="form-select select2" required>
                                        <option value="" disabled selected hidden>Seleccione brigada</option>
                                        <?php foreach($brigadas as $brigada): ?>
                                            <option value="<?= esc($brigada['id']) ?>" <?= old('coordinador') == $brigada['id'] ? 'selected' : '' ?>><?= esc($brigada['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione un coordinador de brigada.</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="encargado" class="form-label">Encargado (Operador) <span class="text-danger">*</span></label>
                                    <select name="encargado" id="encargado" class="form-select select2" required>
                                        <option value="" disabled selected hidden>Seleccione operador</option>
                                        <?php foreach($operadores as $op): ?>
                                            <option value="<?= esc($op['id']) ?>" <?= old('encargado') == $op['id'] ? 'selected' : '' ?>><?= esc($op['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione un encargado.</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="coordinador_campana" class="form-label">Coordinador de Campaña <span class="text-danger">*</span></label>
                                    <select name="coordinador_campana" id="coordinador_campana" class="form-select select2" required>
                                        <option value="" disabled selected hidden>Seleccione coordinador</option>
                                        <?php foreach($usuarios_coordinador as $usuario): ?>
                                            <option value="<?= esc($usuario['id']) ?>" <?= old('coordinador_campana') == $usuario['id'] ? 'selected' : '' ?>><?= esc($usuario['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione un coordinador de campaña.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><i class="ri-file-text-line me-1"></i> Encuesta y Segmentación</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="encuesta_ronda" class="form-label">Seleccionar encuesta para esta ronda <span class="text-danger">*</span></label>
                                <select name="encuesta_ronda" id="encuesta_ronda" class="form-select select2" required>
                                    <option value="" disabled selected hidden>Seleccione encuesta</option>
                                    <?php if (!empty($surveys)): ?>
                                        <?php foreach ($surveys as $survey): ?>
                                            <option value="<?= esc($survey['id']); ?>" <?= old('encuesta_ronda') == $survey['id'] ? 'selected' : '' ?>>#<?= esc($survey['id']); ?> <?= esc($survey['title']); ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option disabled>No hay encuestas registradas</option>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione una encuesta.</div>
                            </div>
                            <div>
                                <label for="segmentaciones" class="form-label">Seleccionar Segmentaciones</label>
                                <select name="segmentaciones[]" id="segmentaciones" class="form-select select2" multiple>
                                    <?php foreach($segmentaciones as $seg): ?>
                                        <option value="<?= esc($seg['id']) ?>" <?= in_array($seg['id'], old('segmentaciones', [])) ? 'selected' : '' ?>><?= esc($seg['descripcion']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><i class="ri-map-pin-line me-1"></i> Distribución de Puntos</h4>
                            <button type="button" class="btn btn-success btn-sm" id="btnGenerarAsignacion"><i class="ri-refresh-line me-1"></i> Generar Asignación</button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2" id="distribucion-container">
                                <?php if (!empty($distribucion)): ?>
                                    <?php foreach($distribucion as $item): ?>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text"><?= esc($item['nombre']) ?></span>
                                                <input type="number" class="form-control" value="<?= esc($item['puntos']) ?>" readonly>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <p class="text-muted">No hay puntos de distribución generados. Haga clic en "Generar Asignación".</p>
                                    </div>
                                <?php endif; ?>
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

        // Lógica para el botón "Generar Asignación" (ejemplo básico)
        document.getElementById('btnGenerarAsignacion').addEventListener('click', function() {
            const container = document.getElementById('distribucion-container');
            container.innerHTML = ''; // Limpiar contenido existente

            // Ejemplo de datos de distribución (puedes obtenerlos de una API o lógica más compleja)
            const ejemploDistribucion = [
                { nombre: 'Zona A', puntos: 15 },
                { nombre: 'Zona B', puntos: 20 },
                { nombre: 'Zona C', puntos: 10 }
            ];

            if (ejemploDistribucion.length > 0) {
                ejemploDistribucion.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'col-md-4';
                    div.innerHTML = `
                        <div class="input-group">
                            <span class="input-group-text">${item.nombre}</span>
                            <input type="number" class="form-control" value="${item.puntos}" readonly>
                        </div>
                    `;
                    container.appendChild(div);
                });
            } else {
                container.innerHTML = '<div class="col-12"><p class="text-muted">No se pudieron generar puntos de distribución.</p></div>';
            }
        });
    });
</script>
