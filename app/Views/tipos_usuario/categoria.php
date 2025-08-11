<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $titulo ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/datatables.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.categoria-header {
    background: linear-gradient(135deg, <?= $categoria['color'] ?? '#6c757d' ?>22, <?= $categoria['color'] ?? '#6c757d' ?>44);
    border-left: 5px solid <?= $categoria['color'] ?? '#6c757d' ?>;
    border-radius: 10px;
}

.tipo-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.tipo-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.tipo-card.activo {
    border-left: 4px solid #28a745;
}

.tipo-card.inactivo {
    border-left: 4px solid #dc3545;
    opacity: 0.7;
}

.jerarquia-visual {
    position: relative;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}

.nivel-jerarquico {
    display: flex;
    align-items: center;
    margin: 10px 0;
    padding: 10px;
    background: white;
    border-radius: 8px;
    border-left: 4px solid <?= $categoria['color'] ?? '#6c757d' ?>;
}

.nivel-numero {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: <?= $categoria['color'] ?? '#6c757d' ?>;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
}

.permisos-matrix {
    font-size: 0.8rem;
}

.permiso-activo {
    color: #28a745;
}

.permiso-inactivo {
    color: #dc3545;
}

.stat-widget {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-widget:hover {
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

.progress-circular {
    width: 60px;
    height: 60px;
    margin: 0 auto 10px;
}

.btn-categoria {
    background-color: <?= $categoria['color'] ?? '#6c757d' ?>;
    border-color: <?= $categoria['color'] ?? '#6c757d' ?>;
    color: white;
}

.btn-categoria:hover {
    background-color: <?= $categoria['color'] ?? '#6c757d' ?>dd;
    border-color: <?= $categoria['color'] ?? '#6c757d' ?>dd;
    color: white;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header de la categoría -->
    <div class="categoria-header p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <i class="<?= $categoria['icono'] ?? 'fas fa-users' ?> fa-3x me-3" 
                       style="color: <?= $categoria['color'] ?? '#6c757d' ?>"></i>
                    <div>
                        <h1 class="h2 mb-1"><?= $categoria['nombre'] ?></h1>
                        <p class="mb-0 text-muted"><?= $categoria['descripcion'] ?></p>
                        <span class="badge mt-2" style="background-color: <?= $categoria['color'] ?? '#6c757d' ?>">
                            <?= $categoria['codigo'] ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-categoria" onclick="crearTipoEnCategoria()">
                        <i class="fas fa-plus"></i> Nuevo Tipo
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="configurarCategoria()">
                        <i class="fas fa-cog"></i> Configurar
                    </button>
                    <a href="<?= base_url('tipos-usuario') ?>" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de la categoría -->
    <div class="row mb-4">
        <?php 
        $stats = null;
        foreach ($estadisticas as $stat) {
            if ($stat['categoria_codigo'] == $categoria['codigo']) {
                $stats = $stat;
                break;
            }
        }
        ?>
        <div class="col-md-3">
            <div class="stat-widget">
                <div class="progress-circular">
                    <i class="fas fa-user-tag fa-2x" style="color: <?= $categoria['color'] ?>"></i>
                </div>
                <h3 class="mb-0"><?= count($categoria['tipos']) ?></h3>
                <small class="text-muted">Tipos Definidos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget">
                <div class="progress-circular">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <h3 class="mb-0"><?= $stats['usuarios_activos'] ?? 0 ?></h3>
                <small class="text-muted">Usuarios Activos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget">
                <div class="progress-circular">
                    <i class="fas fa-user-slash fa-2x text-danger"></i>
                </div>
                <h3 class="mb-0"><?= $stats['usuarios_inactivos'] ?? 0 ?></h3>
                <small class="text-muted">Usuarios Inactivos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget">
                <div class="progress-circular">
                    <i class="fas fa-chart-pie fa-2x text-info"></i>
                </div>
                <h3 class="mb-0"><?= $stats['total_usuarios'] ?? 0 ?></h3>
                <small class="text-muted">Total Usuarios</small>
            </div>
        </div>
    </div>

    <!-- Jerarquía visual -->
    <?php if (!empty($categoria['tipos'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sitemap"></i> Jerarquía de Tipos en <?= $categoria['nombre'] ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="jerarquia-visual">
                        <?php 
                        // Ordenar tipos por nivel jerárquico
                        usort($categoria['tipos'], function($a, $b) {
                            return ($a['nivel_acceso'] ?? 999) - ($b['nivel_acceso'] ?? 999);
                        });
                        
                        foreach ($categoria['tipos'] as $tipo): 
                        ?>
                        <div class="nivel-jerarquico <?= $tipo['activo'] ? 'activo' : 'inactivo' ?>">
                            <div class="nivel-numero"><?= $tipo['nivel_acceso'] ?? '?' ?></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $tipo['nombre'] ?></h6>
                                        <small class="text-muted">
                                            <code><?= $tipo['codigo'] ?></code>
                                            <?php if ($tipo['limite_usuarios']): ?>
                                            | Límite: <?= $tipo['limite_usuarios'] ?> usuarios
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="verPermisosTipo(<?= $tipo['id'] ?>)">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <button class="btn btn-outline-info" onclick="verUsuariosTipo(<?= $tipo['id'] ?>)">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <?php if ($puede_editar): ?>
                                        <button class="btn btn-outline-secondary" onclick="editarTipo(<?= $tipo['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($tipo['descripcion']): ?>
                                <p class="mb-0 mt-2 small text-muted"><?= $tipo['descripcion'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Grid de tipos de usuario -->
    <div class="row">
        <?php if (empty($categoria['tipos'])): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-plus fa-4x text-muted mb-3"></i>
                    <h4>No hay tipos de usuario definidos</h4>
                    <p class="text-muted">Esta categoría no tiene tipos de usuario configurados.</p>
                    <?php if ($puede_editar): ?>
                    <button class="btn btn-categoria" onclick="crearTipoEnCategoria()">
                        <i class="fas fa-plus"></i> Crear Primer Tipo
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($categoria['tipos'] as $tipo): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card tipo-card <?= $tipo['activo'] ? 'activo' : 'inactivo' ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0"><?= $tipo['nombre'] ?></h6>
                        <small class="text-muted"><code><?= $tipo['codigo'] ?></code></small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="verPermisosTipo(<?= $tipo['id'] ?>)">
                                <i class="fas fa-key"></i> Ver Permisos
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="verUsuariosTipo(<?= $tipo['id'] ?>)">
                                <i class="fas fa-users"></i> Ver Usuarios
                            </a></li>
                            <?php if ($puede_editar): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="editarTipo(<?= $tipo['id'] ?>)">
                                <i class="fas fa-edit"></i> Editar
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="duplicarTipo(<?= $tipo['id'] ?>)">
                                <i class="fas fa-copy"></i> Duplicar
                            </a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="eliminarTipo(<?= $tipo['id'] ?>)">
                                <i class="fas fa-trash"></i> Eliminar
                            </a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($tipo['descripcion']): ?>
                    <p class="text-muted small mb-3"><?= $tipo['descripcion'] ?></p>
                    <?php endif; ?>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="mb-0 text-primary"><?= $tipo['nivel_acceso'] ?? '?' ?></h5>
                                <small class="text-muted">Nivel</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0 text-info"><?= $tipo['limite_usuarios'] ?? '∞' ?></h5>
                            <small class="text-muted">Límite</small>
                        </div>
                    </div>
                    
                    <!-- Permisos base -->
                    <div class="permisos-matrix">
                        <h6 class="mb-2">Accesos:</h6>
                        <div class="d-flex justify-content-between">
                            <span class="<?= $tipo['acceso_sistema'] ? 'permiso-activo' : 'permiso-inactivo' ?>">
                                <i class="fas fa-desktop"></i> Sistema
                            </span>
                            <span class="<?= $tipo['acceso_campo'] ? 'permiso-activo' : 'permiso-inactivo' ?>">
                                <i class="fas fa-mobile-alt"></i> Campo
                            </span>
                            <span class="<?= $tipo['bitacora_habilitada'] ? 'permiso-activo' : 'permiso-inactivo' ?>">
                                <i class="fas fa-history"></i> Bitácora
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <?php if ($tipo['alcance_bitacora']): ?>
                            Alcance: <?= ucfirst($tipo['alcance_bitacora']) ?>
                            <?php else: ?>
                            Sin configurar
                            <?php endif; ?>
                        </small>
                        <span class="badge <?= $tipo['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $tipo['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Matriz de permisos por categoría -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-table"></i> Matriz de Permisos para <?= $categoria['nombre'] ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Configuración de Permisos por Categoría:</strong>
                        Los permisos se asignan automáticamente según la categoría del tipo de usuario.
                    </div>
                    
                    <div id="matriz-permisos">
                        <!-- La matriz se cargará dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear tipo en categoría -->
<div class="modal fade" id="modalTipoCategoria" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Tipo en <?= $categoria['nombre'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTipoCategoria">
                <input type="hidden" name="categoria_id" value="<?= $categoria['id'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Código *</label>
                                <input type="text" class="form-control" name="codigo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nivel de Acceso</label>
                                <input type="number" class="form-control" name="nivel_acceso" min="1" max="10" value="<?= count($categoria['tipos']) + 1 ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Límite de Usuarios</label>
                                <input type="number" class="form-control" name="limite_usuarios" min="0">
                                <small class="text-muted">0 = Sin límite</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="acceso_sistema" value="1" checked>
                                <label class="form-check-label">Acceso al Sistema</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="acceso_campo" value="1">
                                <label class="form-check-label">Acceso de Campo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bitacora_habilitada" value="1" checked>
                                <label class="form-check-label">Bitácora Habilitada</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-categoria">Crear Tipo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
<script>
$(document).ready(function() {
    cargarMatrizPermisos();
});

function crearTipoEnCategoria() {
    $('#formTipoCategoria')[0].reset();
    $('#modalTipoCategoria').modal('show');
}

function editarTipo(tipoId) {
    window.location.href = `<?= base_url('tipos-usuario/editar') ?>/${tipoId}`;
}

function verPermisosTipo(tipoId) {
    window.location.href = `<?= base_url('tipos-usuario/permisos') ?>/${tipoId}`;
}

function verUsuariosTipo(tipoId) {
    window.location.href = `<?= base_url('usuarios') ?>?tipo=${tipoId}`;
}

function duplicarTipo(tipoId) {
    if (confirm('¿Desea duplicar este tipo de usuario?')) {
        $.post(`<?= base_url('tipos-usuario/duplicar') ?>/${tipoId}`, function(data) {
            if (data.success) {
                Swal.fire('Éxito', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }
}

function eliminarTipo(tipoId) {
    Swal.fire({
        title: '¿Está seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `<?= base_url('tipos-usuario/eliminar') ?>/${tipoId}`,
                method: 'DELETE',
                success: function(data) {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }
            });
        }
    });
}

function configurarCategoria() {
    // Implementar configuración específica
    Swal.fire('Información', 'Función de configuración en desarrollo', 'info');
}

function cargarMatrizPermisos() {
    const categoriaId = <?= $categoria['id'] ?>;
    
    $.get(`<?= base_url('api/categorias/matriz-permisos') ?>/${categoriaId}`, function(data) {
        if (data.success) {
            mostrarMatrizPermisos(data.matriz);
        }
    });
}

function mostrarMatrizPermisos(matriz) {
    let html = '<div class="table-responsive">';
    html += '<table class="table table-sm table-bordered">';
    html += '<thead class="table-light">';
    html += '<tr><th>Módulo</th><th>Permisos Disponibles</th></tr>';
    html += '</thead><tbody>';
    
    Object.keys(matriz).forEach(modulo => {
        html += `<tr>`;
        html += `<td><strong>${modulo}</strong></td>`;
        html += `<td>`;
        matriz[modulo].forEach(permiso => {
            html += `<span class="badge bg-primary me-1">${permiso}</span>`;
        });
        html += `</td>`;
        html += `</tr>`;
    });
    
    html += '</tbody></table></div>';
    $('#matriz-permisos').html(html);
}

// Manejar envío del formulario
$('#formTipoCategoria').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    $.ajax({
        url: '<?= base_url('tipos-usuario/crear') ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                Swal.fire('Éxito', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });
});
</script>
<?= $this->endSection() ?>