<div class="page-content">
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Crear Ronda</h4>
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
    <!-- end page title -->   

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Nueva Ronda</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('rondas/crear') ?>" method="post" id="form-crear-ronda">
                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Ronda <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>

                            <!-- Campaña ID (oculto) -->
                            <div class="col-lg-6">
                                <input type="hidden" name="campana_id" value="<?= esc($_GET['campana_id'] ?? '') ?>">
                            </div>

                            <!-- Coordinador -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="coordinador" class="form-label">Coordinador(a) <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="coordinador" name="coordinador" required>
                                        <option value="">Seleccione un coordinador</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= esc($usuario['id']) ?>"><?= esc($usuario['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Encargado -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="encargado" class="form-label">Encargado(a) <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="encargado" name="encargado" required>
                                        <option value="">Seleccione un encargado</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= esc($usuario['id']) ?>"><?= esc($usuario['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="fecha_actividad" class="form-label">Fecha de Actividad <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_actividad" name="fecha_actividad" required>
                                </div>
                            </div>

                            <!-- Hora -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="hora_actividad" class="form-label">Hora de Actividad <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="hora_actividad" name="hora_actividad" required>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="Programada" selected>Programada</option>
                                        <option value="Activa">Activa</option>
                                        <option value="Finalizada">Finalizada</option>
                                        <option value="Pospuesta">Pospuesta</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Segmentaciones -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Segmentaciones</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php if (!empty($segmentaciones)): ?>
                                                    <?php foreach ($segmentaciones as $seg): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="segmentaciones[]" value="<?= $seg['id'] ?>" id="seg-<?= $seg['id'] ?>">
                                                            <label class="form-check-label" for="seg-<?= $seg['id'] ?>">
                                                                <?= esc($seg['codigo']) ?> - <?= esc($seg['descripcion']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <p class="text-muted">No hay segmentaciones disponibles</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <a href="<?= base_url('rondas') ?>" class="btn btn-light">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Guardar Ronda</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col-lg-12 -->
    </div> <!-- /.row -->

</div> <!-- /.container-fluid -->
</div> <!-- /.page-content -->

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });

        // Prellenar fecha con hoy
        document.getElementById('fecha_actividad').valueAsDate = new Date();

        // Validar al menos una segmentación
        document.getElementById('form-crear-ronda').addEventListener('submit', function(e) {
            const segmentaciones = document.querySelectorAll('input[name="segmentaciones[]"]:checked');
            if (segmentaciones.length === 0) {
                alert('Por favor, selecciona al menos una segmentación.');
                e.preventDefault();
            }
        });
    });
    
</script>
