<!-- Vista de edición de segmentación (segmentaciones/editar-segmentacion.php) -->
<div class="page-content">
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-dark">Editar Segmentación</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('segmentaciones') ?>" class="text-primary">Segmentaciones</a></li>
                        <li class="breadcrumb-item active text-dark">Editar</li>
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
                    <h5 class="card-title mb-0 text-dark">Editar Segmentación: <?= esc($segmentacion['codigo']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('rondas/editar_segmentacion/' . $segmentacion['id']) ?>" method="post" id="form-editar-segmentacion">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label text-dark">Código <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" value="<?= esc($segmentacion['codigo']) ?>" required>
                                    <small class="text-muted">Ejemplo: M44, 106, etc.</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label text-dark">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="Activo" <?= $segmentacion['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="Inactivo" <?= $segmentacion['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label text-dark">Descripción <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= esc($segmentacion['descripcion']) ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <a href="<?= base_url('segmentaciones') ?>" class="btn btn-light text-dark">Cancelar</a>
                                    <button type="submit" class="btn btn-primary text-white">Actualizar Segmentación</button>
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
        // Validación de formulario
        const form = document.getElementById('form-editar-segmentacion');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar código (alfanumérico)
            const codigo = document.getElementById('codigo').value.trim();
            if (!/^[a-zA-Z0-9]+$/.test(codigo)) {
                alert('El código debe contener solo letras y números sin espacios ni caracteres especiales.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>