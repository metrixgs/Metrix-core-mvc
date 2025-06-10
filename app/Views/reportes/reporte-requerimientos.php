<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Reportes</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Gestion de Reportes</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Formulario para crear una nueva notificación -->
                        <form action="<?= base_url() . 'reportes/resultado-requerimientos'; ?>" method="POST">
                            <div class="row">
                                <!-- Área -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="area_id" class="form-label">Área</label>
                                        <select id="area_id" name="area_id" class="form-control js-example-basic-single <?= session('validation.area_id') ? 'is-invalid' : '' ?>" required>
                                            <option value="" disabled selected>Seleccione un área</option>
                                            <?php foreach ($areas as $area): ?>
                                                <option value="<?= $area['id']; ?>" <?= old('area_id') == $area['id'] ? 'selected' : '' ?>>
                                                    <?= $area['nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (session('validation.area_id')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.area_id'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Fecha de Inicio -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control <?= session('validation.fecha_inicio') ? 'is-invalid' : '' ?>" value="<?= old('fecha_inicio'); ?>" required>
                                        <?php if (session('validation.fecha_inicio')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.fecha_inicio'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Fecha de Fin -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control <?= session('validation.fecha_fin') ? 'is-invalid' : '' ?>" value="<?= old('fecha_fin'); ?>" required>
                                        <?php if (session('validation.fecha_fin')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.fecha_fin'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-end">
                                    <button type="submit" class="btn btn-primary">Generar Informe</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>