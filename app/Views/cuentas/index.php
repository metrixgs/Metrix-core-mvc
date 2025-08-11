<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="header-title">Administrar Cuentas</h2>
        <a href="<?= base_url('cuentas/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Cuenta
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Lista de Cuentas</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($cuentas)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre de la Cuenta</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <?php if (isset($cuentas[0]['created_at'])): ?>
                                <th>Fecha de Creación</th>
                                <?php endif; ?>
                                <?php if (isset($cuentas[0]['updated_at'])): ?>
                                <th>Fecha de Actualización</th>
                                <?php endif; ?>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cuentas as $cuenta): ?>
                                <tr id="cuenta-<?= $cuenta['id'] ?>">
                                    <td><?= $cuenta['id'] ?></td>
                                    <td><?= esc($cuenta['nombre']) ?></td>
                                    <td><?= esc($cuenta['descripcion'] ?? 'Sin descripción') ?></td>
                                    <td>
                                        <?php if (isset($cuenta['activo']) && $cuenta['activo'] == 1): ?>
                                            <span class="badge bg-success">Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isset($cuenta['created_at'])): ?>
                                    <td><?= $cuenta['created_at'] ? date('d/m/Y H:i', strtotime($cuenta['created_at'])) : 'N/A' ?></td>
                                    <?php endif; ?>
                                    <?php if (isset($cuenta['updated_at'])): ?>
                                    <td><?= $cuenta['updated_at'] ? date('d/m/Y H:i', strtotime($cuenta['updated_at'])) : 'N/A' ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('cuentas/edit/' . $cuenta['id']) ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (isset($cuenta['activo']) && $cuenta['activo'] == 1): ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-deactivate" 
                                                        data-id="<?= $cuenta['id'] ?>" title="Desactivar">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-activate" 
                                                        data-id="<?= $cuenta['id'] ?>" title="Activar">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" 
                                                    data-id="<?= $cuenta['id'] ?>" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay cuentas registradas</h5>
                    <p class="text-muted">Comienza creando tu primera cuenta</p>
                    <a href="<?= base_url('cuentas/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Cuenta
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Estilos mejorados para la tabla */
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

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
    font-size: 0.9rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.text-muted {
    color: #6c757d !important;
}

.fa-inbox {
    opacity: 0.5;
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
        margin-top: 15px;
    }
}
</style>

<script>
// JavaScript para manejar las acciones CRUD
document.addEventListener('DOMContentLoaded', function() {
    // Activar cuenta
    document.querySelectorAll('.btn-activate').forEach(button => {
        button.addEventListener('click', function() {
            const cuentaId = this.getAttribute('data-id');
            
            if (confirm('¿Está seguro de que desea activar esta cuenta?')) {
                fetch(`<?= base_url('cuentas/activate/') ?>${cuentaId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Error al activar la cuenta');
                });
            }
        });
    });

    // Desactivar cuenta
    document.querySelectorAll('.btn-deactivate').forEach(button => {
        button.addEventListener('click', function() {
            const cuentaId = this.getAttribute('data-id');
            
            if (confirm('¿Está seguro de que desea desactivar esta cuenta? Esto también desactivará todos los usuarios asociados.')) {
                fetch(`<?= base_url('cuentas/deactivate/') ?>${cuentaId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Error al desactivar la cuenta');
                });
            }
        });
    });

    // Eliminar cuenta
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const cuentaId = this.getAttribute('data-id');
            
            if (confirm('¿Está seguro de que desea eliminar esta cuenta? Esta acción no se puede deshacer.')) {
                fetch(`<?= base_url('cuentas/delete/') ?>${cuentaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Error al eliminar la cuenta');
                });
            }
        });
    });

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>