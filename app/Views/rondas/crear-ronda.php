<!-- Vista de creación de ronda (rondas/crear-ronda.php) -->
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
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Ronda <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="campana_id" class="form-label">Campaña</label>
                                    <input type="number" class="form-control" id="campana_id" name="campana_id" value="1">
                                    <small class="text-muted">ID de la campaña a la que pertenece esta ronda</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="coordinador" class="form-label">Coordinador(a) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="coordinador" name="coordinador" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="encargado" class="form-label">Encargado(a) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="encargado" name="encargado" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="fecha_actividad" class="form-label">Fecha de Actividad <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_actividad" name="fecha_actividad" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="hora_actividad" class="form-label">Hora de Actividad <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="hora_actividad" name="hora_actividad" required>
                                </div>
                            </div>
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
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <a href="<?= base_url('rondas') ?>" class="btn btn-light">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Guardar Ronda</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer la fecha actual como valor por defecto
        document.getElementById('fecha_actividad').valueAsDate = new Date();
        
        // Validación de formulario
        const form = document.getElementById('form-crear-ronda');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar que al menos una segmentación esté seleccionada
            const segmentaciones = document.querySelectorAll('input[name="segmentaciones[]"]:checked');
            if (segmentaciones.length === 0) {
                alert('Por favor, selecciona al menos una segmentación.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>