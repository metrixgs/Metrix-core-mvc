<div class="container mt-5" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="header-title">Editar Cuenta</h2>
        <a href="<?= base_url('cuentas/') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Información de la Cuenta</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('cuentas/update/' . $cuenta['id']) ?>" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Cuenta <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= esc($cuenta['nombre']) ?>" required>
                    <div class="invalid-feedback">Por favor, ingresa el nombre de la cuenta.</div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                              placeholder="Descripción opcional de la cuenta"><?= esc($cuenta['descripcion'] ?? '') ?></textarea>
                    <div class="form-text">Proporciona una descripción opcional para esta cuenta.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado Actual</label>
                    <div>
                        <?php if (isset($cuenta['activo']) && $cuenta['activo'] == 1): ?>
                            <span class="badge bg-success fs-6">Cuenta Activa</span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">Cuenta Inactiva</span>
                        <?php endif; ?>
                    </div>
                    <div class="form-text">El estado se puede cambiar desde la lista de cuentas.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Información del Sistema</label>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">ID: <?= $cuenta['id'] ?></small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Creada: <?= $cuenta['created_at'] ? date('d/m/Y H:i', strtotime($cuenta['created_at'])) : 'N/A' ?></small>
                        </div>
                    </div>
                    <?php if (isset($cuenta['updated_at']) && $cuenta['updated_at']): ?>
                    <div class="row mt-1">
                        <div class="col-12">
                            <small class="text-muted">Última actualización: <?= date('d/m/Y H:i', strtotime($cuenta['updated_at'])) ?></small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= base_url('cuentas/') ?>" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos mejorados para el formulario de edición */
.container {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.header-title {
    color: #2c3e50;
    font-weight: 600;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.badge {
    padding: 8px 12px;
}

@media (max-width: 768px) {
    .container {
        margin-top: 20px;
        padding: 20px;
    }
    
    .d-flex {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .d-flex .btn {
        margin-top: 10px;
    }
}
</style>

<script>
// Validación del formulario
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>