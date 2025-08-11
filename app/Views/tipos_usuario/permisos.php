<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $titulo ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.permisos-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 2rem;
}

.modulo-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.modulo-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.modulo-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0;
    padding: 1rem;
    cursor: pointer;
}

.modulo-header:hover {
    background: #e9ecef;
}

.permiso-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s ease;
}

.permiso-item:last-child {
    border-bottom: none;
}

.permiso-item:hover {
    background: #f8f9fa;
}

.permiso-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.permiso-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #00A650;
}

input:focus + .slider {
    box-shadow: 0 0 0 0.2rem rgba(0, 166, 80, 0.25);
}

input:checked + .slider:hover {
    background-color: #008C44;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.permisos-stats {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid #e9ecef;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.configuracion-especial {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 5px;
    padding: 0.5rem;
    margin-top: 0.5rem;
}

.btn-guardar-permisos {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.comparacion-permisos {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
}

.permiso-diferencia {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    margin: 0.25rem;
    display: inline-block;
}

.permiso-solo-actual {
    background: #d4edda;
    color: #155724;
}

.permiso-solo-comparado {
    background: #f8d7da;
    color: #721c24;
}

.permiso-comun {
    background: #d1ecf1;
    color: #0c5460;
}

.jerarquia-info {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    padding: 1rem;
    border-radius: 0 5px 5px 0;
    margin: 1rem 0;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header de permisos -->
    <div class="permisos-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-2"><?= $titulo ?></h1>
                <p class="mb-0 opacity-75">Configuración detallada de permisos y accesos</p>
                <div class="mt-2">
                    <span class="badge bg-light text-dark me-2">
                        <i class="fas fa-tag"></i> <?= $tipo['codigo'] ?>
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-layer-group"></i> Nivel <?= $tipo['nivel_acceso'] ?? '?' ?>
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-light" onclick="compararPermisos()">
                        <i class="fas fa-balance-scale"></i> Comparar
                    </button>
                    <button type="button" class="btn btn-light" onclick="copiarPermisos()">
                        <i class="fas fa-copy"></i> Copiar de...
                    </button>
                    <button type="button" class="btn btn-light" onclick="restablecerDefecto()">
                        <i class="fas fa-undo"></i> Por Defecto
                    </button>
                    <a href="<?= base_url('tipos-usuario') ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de permisos -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="permisos-stats">
                <div class="stat-number text-primary" id="total-modulos">0</div>
                <div class="stat-label">Módulos con Acceso</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="permisos-stats">
                <div class="stat-number text-success" id="total-permisos">0</div>
                <div class="stat-label">Permisos Activos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="permisos-stats">
                <div class="stat-number text-warning" id="permisos-especiales">0</div>
                <div class="stat-label">Configuraciones Especiales</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="permisos-stats">
                <div class="stat-number text-info" id="cobertura-permisos">0%</div>
                <div class="stat-label">Cobertura de Permisos</div>
            </div>
        </div>
    </div>

    <!-- Información jerárquica -->
    <div class="jerarquia-info">
        <h6><i class="fas fa-info-circle"></i> Información Jerárquica</h6>
        <p class="mb-2">Los permisos de este tipo de usuario están sujetos a las siguientes restricciones jerárquicas:</p>
        <div id="info-jerarquia">
            <!-- Se carga dinámicamente -->
        </div>
    </div>

    <!-- Formulario de permisos -->
    <form id="formPermisos">
        <div class="row">
            <div class="col-12">
                <div id="contenedor-modulos">
                    <?php foreach ($modulos_disponibles as $modulo): ?>
                    <div class="modulo-card" data-modulo-id="<?= $modulo['id'] ?>">
                        <div class="modulo-header" data-bs-toggle="collapse" data-bs-target="#modulo-<?= $modulo['id'] ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="<?= $modulo['icono'] ?? 'fas fa-cube' ?> fa-lg me-3 text-primary"></i>
                                    <div>
                                        <h5 class="mb-0"><?= $modulo['nombre'] ?></h5>
                                        <small class="text-muted"><?= $modulo['descripcion'] ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2 permisos-count" data-modulo="<?= $modulo['id'] ?>">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="modulo-<?= $modulo['id'] ?>">
                            <div class="modulo-body">
                                <?php foreach ($permisos_disponibles as $permiso): ?>
                                <div class="permiso-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <i class="<?= $permiso['icono'] ?? 'fas fa-key' ?> me-2 text-muted"></i>
                                                <div>
                                                    <strong><?= $permiso['nombre'] ?></strong>
                                                    <br><small class="text-muted"><?= $permiso['descripcion'] ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <label class="permiso-switch me-3">
                                                <input type="checkbox" 
                                                       name="permisos[<?= $modulo['id'] ?>][<?= $permiso['id'] ?>]" 
                                                       value="1"
                                                       data-modulo="<?= $modulo['id'] ?>"
                                                       data-permiso="<?= $permiso['id'] ?>"
                                                       onchange="actualizarContadores()">
                                                <span class="slider"></span>
                                            </label>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="configurarEspecial(<?= $modulo['id'] ?>, <?= $permiso['id'] ?>)">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="configuracion-especial d-none" id="config-<?= $modulo['id'] ?>-<?= $permiso['id'] ?>">
                                        <label class="form-label small">Configuración Especial:</label>
                                        <textarea class="form-control form-control-sm" 
                                                  name="config_especial[<?= $modulo['id'] ?>][<?= $permiso['id'] ?>]" 
                                                  rows="2" 
                                                  placeholder="JSON de configuración especial..."></textarea>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </form>

    <!-- Botón flotante para guardar -->
    <button type="button" class="btn btn-success btn-lg btn-guardar-permisos" onclick="guardarPermisos()">
        <i class="fas fa-save"></i> Guardar Permisos
    </button>
</div>

<!-- Modal para comparar permisos -->
<div class="modal fade" id="modalComparar" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comparar Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Comparar con:</label>
                        <select class="form-select" id="tipoComparar">
                            <option value="">Seleccionar tipo de usuario</option>
                            <!-- Se llena dinámicamente -->
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-primary" onclick="ejecutarComparacion()">
                            <i class="fas fa-balance-scale"></i> Comparar
                        </button>
                    </div>
                </div>
                <div id="resultado-comparacion"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para copiar permisos -->
<div class="modal fade" id="modalCopiar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copiar Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Copiar permisos desde:</label>
                    <select class="form-select" id="tipoCopiar">
                        <option value="">Seleccionar tipo de usuario</option>
                        <!-- Se llena dinámicamente -->
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atención:</strong> Esta acción reemplazará todos los permisos actuales.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="ejecutarCopia()">
                    <i class="fas fa-copy"></i> Copiar Permisos
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
<script>
const tipoId = <?= $tipo['id'] ?>;
let permisosActuales = <?= json_encode($permisos_actuales) ?>;

$(document).ready(function() {
    cargarPermisosActuales();
    cargarInformacionJerarquica();
    cargarTiposParaComparar();
    actualizarContadores();
    
    // Inicializar Select2
    $('#tipoComparar, #tipoCopiar').select2({
        theme: 'bootstrap-5'
    });
});

function cargarPermisosActuales() {
    // Marcar permisos actuales
    Object.keys(permisosActuales).forEach(moduloCodigo => {
        const modulo = permisosActuales[moduloCodigo];
        modulo.permisos.forEach(permiso => {
            const checkbox = $(`input[data-modulo="${modulo.modulo_id}"][data-permiso="${permiso.permiso_id}"]`);
            checkbox.prop('checked', true);
            
            if (permiso.configuracion_especial) {
                $(`#config-${modulo.modulo_id}-${permiso.permiso_id}`).removeClass('d-none');
                $(`textarea[name="config_especial[${modulo.modulo_id}][${permiso.permiso_id}]"]`).val(permiso.configuracion_especial);
            }
        });
    });
}

function cargarInformacionJerarquica() {
    $.get(`<?= base_url('tipos-usuario/jerarquia') ?>/${tipoId}`, function(data) {
        if (data.success) {
            mostrarInformacionJerarquica(data.jerarquia);
        }
    });
}

function mostrarInformacionJerarquica(jerarquia) {
    let html = '';
    
    if (jerarquia.superiores.length > 0) {
        html += '<p><strong>Superiores:</strong> ';
        jerarquia.superiores.forEach(superior => {
            html += `<span class="badge bg-primary me-1">${superior.nombre}</span>`;
        });
        html += '</p>';
    }
    
    if (jerarquia.subordinados.length > 0) {
        html += '<p><strong>Subordinados:</strong> ';
        jerarquia.subordinados.forEach(subordinado => {
            html += `<span class="badge bg-secondary me-1">${subordinado.nombre}</span>`;
        });
        html += '</p>';
    }
    
    if (html === '') {
        html = '<p>Este tipo no tiene relaciones jerárquicas definidas.</p>';
    }
    
    $('#info-jerarquia').html(html);
}

function cargarTiposParaComparar() {
    $.get('<?= base_url('api/tipos-usuario/lista') ?>', function(data) {
        if (data.success) {
            let options = '<option value="">Seleccionar tipo de usuario</option>';
            data.tipos.forEach(tipo => {
                if (tipo.id != tipoId) {
                    options += `<option value="${tipo.id}">${tipo.nombre} (${tipo.codigo})</option>`;
                }
            });
            $('#tipoComparar, #tipoCopiar').html(options);
        }
    });
}

function actualizarContadores() {
    let totalModulos = 0;
    let totalPermisos = 0;
    let permisosEspeciales = 0;
    
    $('.modulo-card').each(function() {
        const moduloId = $(this).data('modulo-id');
        const permisosModulo = $(this).find('input[type="checkbox"]:checked').length;
        
        $(this).find('.permisos-count').text(permisosModulo);
        
        if (permisosModulo > 0) {
            totalModulos++;
            totalPermisos += permisosModulo;
        }
        
        // Contar configuraciones especiales
        $(this).find('.configuracion-especial:not(.d-none)').each(function() {
            if ($(this).find('textarea').val().trim() !== '') {
                permisosEspeciales++;
            }
        });
    });
    
    const totalPosibles = $('.modulo-card').length * <?= count($permisos_disponibles) ?>;
    const cobertura = totalPosibles > 0 ? Math.round((totalPermisos / totalPosibles) * 100) : 0;
    
    $('#total-modulos').text(totalModulos);
    $('#total-permisos').text(totalPermisos);
    $('#permisos-especiales').text(permisosEspeciales);
    $('#cobertura-permisos').text(cobertura + '%');
}

function configurarEspecial(moduloId, permisoId) {
    const configDiv = $(`#config-${moduloId}-${permisoId}`);
    configDiv.toggleClass('d-none');
    
    if (!configDiv.hasClass('d-none')) {
        configDiv.find('textarea').focus();
    }
    
    actualizarContadores();
}

function guardarPermisos() {
    const permisos = [];
    
    $('input[type="checkbox"]:checked').each(function() {
        const moduloId = $(this).data('modulo');
        const permisoId = $(this).data('permiso');
        const configEspecial = $(`textarea[name="config_especial[${moduloId}][${permisoId}]"]`).val();
        
        permisos.push({
            modulo_id: moduloId,
            permiso_id: permisoId,
            activo: 1,
            configuracion_especial: configEspecial || null
        });
    });
    
    $.ajax({
        url: `<?= base_url('tipos-usuario/actualizar-permisos') ?>/${tipoId}`,
        method: 'POST',
        data: JSON.stringify(permisos),
        contentType: 'application/json',
        success: function(data) {
            if (data.success) {
                Swal.fire('Éxito', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });
}

function compararPermisos() {
    $('#modalComparar').modal('show');
}

function ejecutarComparacion() {
    const tipoComparar = $('#tipoComparar').val();
    
    if (!tipoComparar) {
        Swal.fire('Error', 'Debe seleccionar un tipo para comparar', 'error');
        return;
    }
    
    $.post('<?= base_url('tipos-usuario/comparar-permisos') ?>', {
        tipo1: tipoId,
        tipo2: tipoComparar
    }, function(data) {
        if (data.success) {
            mostrarResultadoComparacion(data.comparacion);
        }
    });
}

function mostrarResultadoComparacion(comparacion) {
    let html = '<div class="comparacion-permisos">';
    
    Object.keys(comparacion.diferentes).forEach(modulo => {
        if (comparacion.diferentes[modulo].length > 0) {
            html += `<h6>${modulo}</h6>`;
            
            if (comparacion.solo_rol1[modulo].length > 0) {
                html += '<p><strong>Solo en tipo actual:</strong> ';
                comparacion.solo_rol1[modulo].forEach(permiso => {
                    html += `<span class="permiso-diferencia permiso-solo-actual">${permiso}</span>`;
                });
                html += '</p>';
            }
            
            if (comparacion.solo_rol2[modulo].length > 0) {
                html += '<p><strong>Solo en tipo comparado:</strong> ';
                comparacion.solo_rol2[modulo].forEach(permiso => {
                    html += `<span class="permiso-diferencia permiso-solo-comparado">${permiso}</span>`;
                });
                html += '</p>';
            }
            
            if (comparacion.comunes[modulo].length > 0) {
                html += '<p><strong>Permisos comunes:</strong> ';
                comparacion.comunes[modulo].forEach(permiso => {
                    html += `<span class="permiso-diferencia permiso-comun">${permiso}</span>`;
                });
                html += '</p>';
            }
        }
    });
    
    html += '</div>';
    $('#resultado-comparacion').html(html);
}

function copiarPermisos() {
    $('#modalCopiar').modal('show');
}

function ejecutarCopia() {
    const tipoCopiar = $('#tipoCopiar').val();
    
    if (!tipoCopiar) {
        Swal.fire('Error', 'Debe seleccionar un tipo para copiar', 'error');
        return;
    }
    
    Swal.fire({
        title: '¿Está seguro?',
        text: 'Se reemplazarán todos los permisos actuales',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, copiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`<?= base_url('api/tipos-usuario/copiar-permisos') ?>`, {
                origen: tipoCopiar,
                destino: tipoId
            }, function(data) {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

function restablecerDefecto() {
    Swal.fire({
        title: '¿Restablecer permisos por defecto?',
        text: 'Se aplicarán los permisos según la categoría del tipo',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, restablecer',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`<?= base_url('api/tipos-usuario/restablecer-defecto') ?>/${tipoId}`, function(data) {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>