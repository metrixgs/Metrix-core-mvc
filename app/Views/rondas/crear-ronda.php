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
                    <h4 class="mb-sm-0">Nueva Ronda</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('rondas') ?>">Rondas</a></li>
                            <li class="breadcrumb-item active">Nueva Ronda</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="<?= base_url('rondas/crear') ?>" method="post" id="form-crear-ronda">
            <div class="row g-3">

                <!-- Datos Generales -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light"><h5 class="card-title mb-0">Datos Generales</h5></div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Ronda</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha_actividad" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Horario</label>
                                <input type="time" name="hora_actividad" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light"><h5 class="card-title mb-0">Responsables</h5></div>
                        <div class="card-body row g-3">
                            <div class="col-6">
                                <label class="form-label">Coordinador (Brigada)</label>
                                <select name="coordinador" class="form-select select2" required>
                                    <option value="" disabled selected hidden>Seleccione brigada</option>
                                    <?php foreach($brigadas as $brigada): ?>
                                        <option value="<?= esc($brigada['id']) ?>"><?= esc($brigada['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Encargado (Operador)</label>
                                <select name="encargado" class="form-select select2" required>
                                    <option value="" disabled selected hidden>Seleccione operador</option>
                                    <?php foreach($operadores as $op): ?>
                                        <option value="<?= esc($op['id']) ?>"><?= esc($op['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Coordinador de Campaña</label>
                                <select name="coordinador_campana" class="form-select select2" required>
                                    <option value="" disabled selected hidden>Seleccione coordinador</option>
                                    <?php foreach($usuarios_coordinador as $usuario): ?>
                                        <option value="<?= esc($usuario['id']) ?>"><?= esc($usuario['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segmentaciones -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light"><h5 class="card-title mb-0">Segmentaciones</h5></div>
                        <div class="card-body">
                            <label class="form-label">Seleccionar Segmentaciones</label>
                            <select name="segmentaciones[]" class="form-select select2" multiple>
                                <?php foreach($segmentaciones as $seg): ?>
                                    <option value="<?= esc($seg['id']) ?>"><?= esc($seg['descripcion']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Distribución -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Distribución de Puntos</h5>
                            <button type="button" class="btn btn-success btn-sm">Generar Asignación</button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <?php foreach($distribucion as $item): ?>
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm" value="<?= esc($item['nombre']) ?> (<?= esc($item['puntos']) ?>)" readonly>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado oculto y Campaña ID -->
                <input type="hidden" name="estado" value="Programada">
                <input type="hidden" name="campana_id" value="<?= esc($_GET['campana_id'] ?? '') ?>">


                <!-- Botones -->
                <div class="col-12 text-end mt-3">
                    <a href="<?= base_url('rondas') ?>" class="btn btn-light">Cancelar</a>
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
        $('.select2').select2({ width: '100%' });
    });
</script>
