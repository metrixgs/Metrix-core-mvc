<?php
// Si $brigadas no existe o es vacío, definimos un valor temporal
$brigadas = $brigadas ?? [
    ['id' => 1, 'nombre' => 'Brigada Temporal']
];

$operadores = $operadores ?? [
    ['id' => 1, 'nombre' => 'Operador Temporal']
];

$territorios = $territorios ?? [
    ['id' => 1, 'nombre' => 'Territorio Temporal']
];

$distribucion = $distribucion ?? [
    ['nombre' => 'Juan Temporal', 'puntos' => 10]
];
?>


<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
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
        <!-- end page title -->

        <form action="<?= base_url('rondas/crear') ?>" method="post" id="form-crear-ronda">
            <div class="row g-3">
                <!-- Datos Generales -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Datos Generales</h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Campaña</label>
                                <input type="text" name="nombre_campana" class="form-control" value="Entrega de Tarjeta de Salud (+60)">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Horario</label>
                                <input type="time" name="horario" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables + Interacciones -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Responsables</h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-6">
                                <label class="form-label">Brigada(s)</label>
                                <select name="brigadas[]" class="form-select select2">
                                    <option value="">Seleccione brigada</option>
                                    <?php foreach($brigadas as $brigada): ?>
                                        <option value="<?= esc($brigada['id']) ?>"><?= esc($brigada['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Operadores</label>
                                <select name="operadores[]" class="form-select select2">
                                    <option value="">Seleccione operador</option>
                                    <?php foreach($operadores as $op): ?>
                                        <option value="<?= esc($op['id']) ?>"><?= esc($op['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Interacciones</h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-6">
                                <label class="form-label">Universo</label>
                                <input type="number" name="universo" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Entregable</label>
                                <input type="number" name="entregable" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delimitación Territorial -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Delimitación Territorial</h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-6">
                                <label class="form-label">Territorio</label>
                                <select name="territorio" class="form-select select2">
                                    <option value="">Seleccione territorio</option>
                                    <?php foreach($territorios as $t): ?>
                                        <option value="<?= esc($t['id']) ?>"><?= esc($t['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Nombre</label>
                                <select name="nombre_territorio" class="form-select select2">
                                    <option value="">Seleccione nombre</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sectorización</label>
                                <select name="sectorizacion" class="form-select select2">
                                    <option value="">Seleccione sectorización</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Nombre</label>
                                <select name="nombre_sectorizacion" class="form-select select2">
                                    <option value="">Seleccione nombre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución de Puntos -->
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
                                        <input type="text" class="form-control form-control-sm" value="<?= esc($item['nombre']) ?> (<?= esc($item['puntos']) ?>)">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="col-12 text-end mt-3">
                    <a href="<?= base_url('rondas') ?>" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Ronda</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.select2').select2({ width: '100%' });
    });
</script>
