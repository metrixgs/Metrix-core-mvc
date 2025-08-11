<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $titulo ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/datatables.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.categoria-card {
    transition: all 0.3s ease;
    border-left: 4px solid #dee2e6;
}
.categoria-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.categoria-card.metrix { border-left-color: #dc3545; }
.categoria-card.cliente-sistema { border-left-color: #007bff; }
.categoria-card.cliente-campo { border-left-color: #28a745; }
.categoria-card.otros { border-left-color: #ffc107; }
.categoria-card.legacy { border-left-color: #6c757d; }

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.progress-ring {
    transform: rotate(-90deg);
}

.badge-categoria {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.jerarquia-nivel {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e9ecef;
    text-align: center;
    line-height: 20px;
    font-size: 0.7rem;
    margin-right: 5px;
}

.tipo-activo .jerarquia-nivel { background: #28a745; color: white; }
.tipo-inactivo .jerarquia-nivel { background: #dc3545; color: white; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header con estadísticas generales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0"><?= $titulo ?></h1>
                    <p class="text-muted">Gestión completa del sistema de tipos de usuario por categorías</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="crearTipoUsuario()">
                        <i class="fas fa-plus"></i> Nuevo Tipo
                    </button>
                    <button type="button" class="btn btn-info" onclick="migrarLegacy()">
                        <i class="fas fa-sync-alt"></i> Migrar Legacy
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="exportarConfiguracion()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-layer-group fa-2x mb-2"></i>
                    <h3 class="mb-0"><?= $estadisticas_generales['total_categorias'] ?></h3>
                    <small>Categorías Activas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-user-tag fa-2x mb-2"></i>
                    <h3 class="mb-0"><?= $estadisticas_generales['total_tipos'] ?></h3>
                    <small>Tipos de Usuario</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h3 class="mb-0"><?= $estadisticas_generales['total_usuarios'] ?></h3>
                    <small>Usuarios Totales</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-pie fa-2x mb-2"></i>
                    <h3 class="mb-0"><?= $resumen_migracion['porcentaje_migrado'] ?>%</h3>
                    <small>Migración Completada</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de migración -->
    <?php if ($resumen_migracion['usuarios_legacy'] > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Migración Pendiente</h5>
                        <p class="mb-2">Hay <?= $resumen_migracion['usuarios_legacy'] ?> usuarios en roles legacy que requieren migración.</p>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $resumen_migracion['porcentaje_migrado'] ?>%"></div>
                        </div>
                        <small><?= $resumen_migracion['usuarios_nuevos'] ?> usuarios ya migrados de <?= $resumen_migracion['usuarios_legacy'] + $resumen_migracion['usuarios_nuevos'] ?> totales</small>
                    </div>
                    <button class="btn btn-warning" onclick="migrarLegacy()">
                        <i class="fas fa-sync-alt"></i> Migrar Ahora
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Categorías de usuario -->
    <div class="row">
        <?php foreach ($categorias as $categoria): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card categoria-card <?= strtolower(str_replace('_', '-', $categoria['codigo'])) ?>" 
                 onclick="verCategoria(<?= $categoria['id'] ?>)" style="cursor: pointer;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="<?= $categoria['icono'] ?? 'fas fa-users' ?> fa-lg me-2" 
                           style="color: <?= $categoria['color'] ?? '#6c757d' ?>"></i>
                        <h5 class="mb-0"><?= $categoria['nombre'] ?></h5>
                    </div>
                    <span class="badge badge-categoria" 
                          style="background-color: <?= $categoria['color'] ?? '#6c757d' ?>">
                        <?= $categoria['codigo'] ?>
                    </span>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3"><?= $categoria['descripcion'] ?></p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="mb-0 text-primary"><?= $categoria['tipos_activos'] ?></h4>
                            <small class="text-muted">Tipos Activos</small>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0 text-info"><?= $categoria['total_tipos'] ?></h4>
                            <small class="text-muted">Total Tipos</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-users"></i> 
                            <?php 
                            $usuarios_categoria = 0;
                            foreach ($estadisticas_generales['tipos_por_categoria'] as $stat) {
                                if ($stat['categoria_codigo'] == $categoria['codigo']) {
                                    $usuarios_categoria += $stat['total_usuarios'];
                                }
                            }
                            echo $usuarios_categoria;
                            ?> usuarios
                        </small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="event.stopPropagation(); verCategoria(<?= $categoria['id'] ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" 
                                    onclick="event.stopPropagation(); configurarCategoria(<?= $categoria['id'] ?>)">
                                <i class="fas fa-cog"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tabla resumen por tipo -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-table"></i> Resumen Detallado por Tipo de Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-tipos" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Código</th>
                                    <th>Nivel</th>
                                    <th>Usuarios Activos</th>
                                    <th>Usuarios Inactivos</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estadisticas_generales['tipos_por_categoria'] as $tipo): ?>
                                <tr class="<?= $tipo['usuarios_activos'] > 0 ? 'tipo-activo' : 'tipo-inactivo' ?>">
                                    <td>
                                        <span class="badge" style="background-color: <?= $tipo['categoria_codigo'] == 'METRIX' ? '#dc3545' : ($tipo['categoria_codigo'] == 'CLIENTE_SISTEMA' ? '#007bff' : ($tipo['categoria_codigo'] == 'CLIENTE_CAMPO' ? '#28a745' : ($tipo['categoria_codigo'] == 'OTROS' ? '#ffc107' : '#6c757d'))) ?>">
                                            <?= $tipo['categoria_nombre'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="jerarquia-nivel"><?= $tipo['rol_id'] ?></span>
                                            <?= $tipo['rol_nombre'] ?>
                                        </div>
                                    </td>
                                    <td><code><?= $tipo['rol_codigo'] ?></code></td>
                                    <td>
                                        <span class="badge badge-secondary">Nivel <?= $tipo['rol_id'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success"><?= $tipo['usuarios_activos'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger"><?= $tipo['usuarios_inactivos'] ?></span>
                                    </td>
                                    <td>
                                        <strong><?= $tipo['total_usuarios'] ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($tipo['usuarios_activos'] > 0): ?>
                                            <span class="badge badge-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Sin usuarios</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="verPermisos(<?= $tipo['rol_id'] ?>)" 
                                                    title="Ver permisos">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button class="btn btn-outline-info" 
                                                    onclick="verJerarquia(<?= $tipo['rol_id'] ?>)" 
                                                    title="Ver jerarquía">
                                                <i class="fas fa-sitemap"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" 
                                                    onclick="editarTipo(<?= $tipo['rol_id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar tipo de usuario -->
<div class="modal fade" id="modalTipoUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Tipo de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTipoUsuario">
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
                                <label class="form-label">Categoría *</label>
                                <select class="form-select" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nivel de Acceso</label>
                                <input type="number" class="form-control" name="nivel_acceso" min="1" max="10">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Límite de Usuarios</label>
                                <input type="number" class="form-control" name="limite_usuarios" min="0">
                                <small class="text-muted">0 = Sin límite</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alcance de Bitácora</label>
                                <select class="form-select" name="alcance_bitacora">
                                    <option value="ninguno">Ninguno</option>
                                    <option value="propio">Solo propios</option>
                                    <option value="descendente">Descendentes</option>
                                    <option value="cuenta">Toda la cuenta</option>
                                    <option value="todas">Todas las cuentas</option>
                                </select>
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
                                <input class="form-check-input" type="checkbox" name="acceso_sistema" value="1">
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
                                <input class="form-check-input" type="checkbox" name="bitacora_habilitada" value="1">
                                <label class="form-check-label">Bitácora Habilitada</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver jerarquía -->
<div class="modal fade" id="modalJerarquia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Jerarquía del Tipo de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenido-jerarquia"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#tabla-tipos').DataTable({
        language: {
            url: '<?= base_url('assets/js/datatables-es.json') ?>'
        },
        order: [[0, 'asc'], [3, 'asc']],
        pageLength: 25,
        responsive: true
    });
    
    // Inicializar Select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalTipoUsuario')
    });
});

// Funciones para gestión de tipos de usuario
function verCategoria(categoriaId) {
    window.location.href = `<?= base_url('tipos-usuario/categoria') ?>/${categoriaId}`;
}

function crearTipoUsuario() {
    $('#modalTipoUsuario .modal-title').text('Crear Tipo de Usuario');
    $('#formTipoUsuario')[0].reset();
    $('#modalTipoUsuario').modal('show');
}

function editarTipo(tipoId) {
    // Cargar datos del tipo y mostrar modal
    $.get(`<?= base_url('api/tipos-usuario') ?>/${tipoId}`, function(data) {
        if (data.success) {
            const tipo = data.tipo;
            $('#modalTipoUsuario .modal-title').text('Editar Tipo de Usuario');
            
            // Llenar formulario
            Object.keys(tipo).forEach(key => {
                const input = $(`[name="${key}"]`);
                if (input.attr('type') === 'checkbox') {
                    input.prop('checked', tipo[key] == 1);
                } else {
                    input.val(tipo[key]);
                }
            });
            
            $('#formTipoUsuario').data('tipo-id', tipoId);
            $('#modalTipoUsuario').modal('show');
        }
    });
}

function verPermisos(tipoId) {
    window.location.href = `<?= base_url('tipos-usuario/permisos') ?>/${tipoId}`;
}

function verJerarquia(tipoId) {
    $.get(`<?= base_url('tipos-usuario/jerarquia') ?>/${tipoId}`, function(data) {
        if (data.success) {
            mostrarJerarquia(data.jerarquia);
            $('#modalJerarquia').modal('show');
        }
    });
}

function mostrarJerarquia(jerarquia) {
    let html = '<div class="text-center">';
    
    // Superiores
    if (jerarquia.superiores.length > 0) {
        html += '<h6>Superiores</h6>';
        jerarquia.superiores.forEach(superior => {
            html += `<div class="badge bg-primary m-1">${superior.nombre} (Nivel ${superior.nivel_jerarquico})</div>`;
        });
        html += '<hr>';
    }
    
    html += '<h6>Tipo Actual</h6><div class="badge bg-success m-1">Seleccionado</div>';
    
    // Subordinados
    if (jerarquia.subordinados.length > 0) {
        html += '<hr><h6>Subordinados</h6>';
        jerarquia.subordinados.forEach(subordinado => {
            html += `<div class="badge bg-secondary m-1">${subordinado.nombre} (Nivel ${subordinado.nivel_jerarquico})</div>`;
        });
    }
    
    html += '</div>';
    $('#contenido-jerarquia').html(html);
}

function migrarLegacy() {
    if (confirm('¿Está seguro de migrar todos los usuarios de roles legacy? Esta acción no se puede deshacer.')) {
        $.post('<?= base_url('tipos-usuario/migrar-legacy') ?>', function(data) {
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

function exportarConfiguracion() {
    window.location.href = '<?= base_url('tipos-usuario/exportar') ?>';
}

function configurarCategoria(categoriaId) {
    // Implementar configuración específica de categoría
    window.location.href = `<?= base_url('tipos-usuario/categoria') ?>/${categoriaId}?config=1`;
}

// Manejar envío del formulario
$('#formTipoUsuario').on('submit', function(e) {
    e.preventDefault();
    
    const tipoId = $(this).data('tipo-id');
    const url = tipoId ? `<?= base_url('tipos-usuario/editar') ?>/${tipoId}` : '<?= base_url('tipos-usuario/crear') ?>';
    const formData = new FormData(this);
    
    $.ajax({
        url: url,
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