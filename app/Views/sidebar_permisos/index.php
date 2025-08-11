<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Permisos del Sidebar
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('assets/css/datatables.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/sweetalert2.min.css') ?>" rel="stylesheet">
<style>
.permission-matrix {
    overflow-x: auto;
    max-width: 100%;
    height: 400px;
    overflow-y: scroll;
    position: relative;
    margin-top: 20px;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    display: block;
}

.permission-table {
    min-width: 800px;
    white-space: nowrap;
    font-size: 12px;
    margin-bottom: 0;
    width: 100%;
    display: table;
}

.permission-table thead tr.header-row {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #fff;
}

.permission-table tbody {
    background-color: #fff;
}

.permission-table thead tr.header-row th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.permission-table th {
    background-color: #f8f9fa;
    text-align: center;
    vertical-align: middle;
    padding: 8px 4px;
    border: 1px solid #dee2e6;
    font-weight: 600;
}

.permission-table td {
    text-align: center;
    vertical-align: middle;
    padding: 6px 4px;
    border: 1px solid #dee2e6;
}

.module-name {
    text-align: left !important;
    font-weight: 500;
    background-color: #f8f9fa;
    min-width: 150px;
}

.permission-switch {
    transform: scale(0.8);
}

/* Bootstrap Custom Switch Styles */
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #00A650 !important;
    border-color: #00A650 !important;
}

.custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(0, 166, 80, 0.25) !important;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
    background-color: #fff !important;
}

.role-header {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    min-width: 80px;
    max-width: 80px;
    height: 120px;
}

.stats-card {
    border-left: 4px solid #00A650;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-action {
    margin: 2px;
    padding: 6px 12px;
    font-size: 12px;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #00A650;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.module-description {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
}

.permission-count {
    font-size: 10px;
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 5px;
}

.role-stats {
    font-size: 10px;
    color: #6c757d;
}

@media (max-width: 768px) {
    .permission-table {
        font-size: 10px;
    }
    
    .role-header {
        min-width: 60px;
        max-width: 60px;
        height: 100px;
    }
    
    .btn-action {
        padding: 4px 8px;
        font-size: 10px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-shield-alt" style="color: #00A650;"></i>
                        Gestión de Permisos del Sidebar
                    </h2>
                    <p class="text-muted mb-0">Configure los permisos de acceso a los módulos del sidebar por rol de usuario</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm" style="background-color: #00A650; border-color: #00A650; color: white;" onclick="configurarDefecto()">
                        <i class="fas fa-cog"></i> Configurar por Defecto
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="verEstadisticas()">
                        <i class="fas fa-chart-bar"></i> Estadísticas
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="exportarConfiguracion()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="verHistorial()">
                        <i class="fas fa-history"></i> Historial
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #00A650;"><?= count($roles) ?></h5>
                    <p class="card-text">Roles Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #00A650;"><?= count($modulos) ?></h5>
                    <p class="card-text">Módulos del Sidebar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h5 class="card-title text-info" id="total-permisos"><?= $totalPermisos ?? 0 ?></h5>
                    <p class="card-text">Permisos Configurados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning" id="permisos-activos"><?= $permisosActivos ?? 0 ?></h5>
                    <p class="card-text">Permisos Activos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-group">
                <label for="filtro-rol">Filtrar por Rol:</label>
                <select class="form-control form-control-sm" id="filtro-rol">
                    <option value="">Todos los roles</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="filtro-modulo">Filtrar por Módulo:</label>
                <input type="text" class="form-control form-control-sm" id="filtro-modulo" placeholder="Buscar módulo...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="filtro-estado">Filtrar por Estado:</label>
                <select class="form-control form-control-sm" id="filtro-estado">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Matriz de Permisos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-table"></i>
                Matriz de Permisos del Sidebar
                <small class="text-muted">(Haga clic en los switches para cambiar permisos)</small>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="permission-matrix">
                <table class="table table-bordered table-hover permission-table mb-0">
                    <thead>
                        <tr class="header-row">
                            <th class="module-name">Módulo del Sidebar</th>
                            <?php foreach ($roles as $rol): ?>
                                <th class="role-header">
                                    <div class="d-flex flex-column align-items-center">
                                        <strong><?= $rol['nombre'] ?></strong>
                                        <div class="role-stats">
                                            <span class="permission-count" id="count-<?= $rol['id'] ?>"><?= $rol['id'] ?></span>
                                        </div>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modulos as $modulo => $titulo): ?>
                            <tr data-modulo="<?= $modulo ?>">
                                <td class="module-name">
                                    <strong><?= $titulo ?></strong>
                                    <div class="module-description"><?= $modulo ?></div>
                                </td>
                                <?php foreach ($roles as $rol): ?>
                                    <td>
                                        <?php 
                                        $tienePermiso = false;
                                        if (isset($permisos[$rol['id']][$modulo])) {
                                            $tienePermiso = $permisos[$rol['id']][$modulo];
                                        }
                                        ?>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" 
                                                   class="custom-control-input permission-switch" 
                                                   id="permiso_<?= $rol['id'] ?>_<?= $modulo ?>" 
                                                   data-rol="<?= $rol['id'] ?>" 
                                                   data-modulo="<?= $modulo ?>"
                                                   <?= $tienePermiso ? 'checked' : '' ?>
                                                   onchange="cambiarPermiso(this)">
                                            <label class="custom-control-label" 
                                                   for="permiso_<?= $rol['id'] ?>_<?= $modulo ?>"></label>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Acciones Masivas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-tools"></i>
                        Acciones Masivas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Por Rol:</h6>
                            <div class="btn-group-vertical w-100" role="group">
                                <?php foreach ($roles as $rol): ?>
                                    <div class="btn-group mb-2" role="group">
                                        <button type="button" class="btn btn-sm" 
                                                style="border-color: #00A650; color: #00A650;" 
                                                onmouseover="this.style.backgroundColor='#00A650'; this.style.color='white';" 
                                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#00A650';" 
                                                onclick="activarTodosRol(<?= $rol['id'] ?>)">
                                            <i class="fas fa-check-circle"></i> Activar Todo - <?= $rol['nombre'] ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="desactivarTodosRol(<?= $rol['id'] ?>)">
                                            <i class="fas fa-times-circle"></i> Desactivar Todo
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Por Módulo:</h6>
                            <div class="form-group">
                                <select class="form-control form-control-sm" id="modulo-masivo">
                                    <option value="">Seleccionar módulo...</option>
                                    <?php foreach ($modulos as $modulo => $titulo): ?>
                                        <option value="<?= $modulo ?>"><?= $titulo ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-sm" 
                                        style="border-color: #00A650; color: #00A650;" 
                                        onmouseover="this.style.backgroundColor='#00A650'; this.style.color='white';" 
                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#00A650';" 
                                        onclick="activarTodosModulo()">
                                    <i class="fas fa-check-circle"></i> Activar para Todos
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="desactivarTodosModulo()">
                                    <i class="fas fa-times-circle"></i> Desactivar para Todos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="spinner"></div>
</div>

<!-- Modal de Estadísticas -->
<div class="modal fade" id="modalEstadisticas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar"></i>
                    Estadísticas de Permisos
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-estadisticas">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Historial -->
<div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i>
                    Historial de Cambios
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-historial">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>
<script>
$(document).ready(function() {
    // Inicializar contadores
    actualizarContadores();
    
    // Configurar filtros
    configurarFiltros();
    
    // Mostrar mensaje de bienvenida
    mostrarMensajeBienvenida();
});

// Función para cambiar permisos
function cambiarPermiso(elemento) {
    const rolId = elemento.dataset.rol;
    const modulo = elemento.dataset.modulo;
    const valor = elemento.checked ? 1 : 0;
    
    mostrarLoading(true);
    
    fetch('<?= base_url('sidebar-permisos/actualizar') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            rol_id: rolId,
            modulo: modulo,
            valor: valor
        })
    })
    .then(response => response.json())
    .then(data => {
        mostrarLoading(false);
        
        if (data.success) {
            // Actualizar contadores
            actualizarContadores();
            
            // Mostrar notificación
            Swal.fire({
                icon: 'success',
                title: 'Permiso actualizado',
                text: data.message,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            // Revertir el cambio
            elemento.checked = !elemento.checked;
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo actualizar el permiso'
            });
        }
    })
    .catch(error => {
        mostrarLoading(false);
        elemento.checked = !elemento.checked;
        
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}

// Función para configurar permisos por defecto
function configurarDefecto() {
    Swal.fire({
        title: '¿Configurar permisos por defecto?',
        text: 'Esto establecerá los permisos predeterminados según el tipo de cada rol',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, configurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarLoading(true);
            
            fetch('<?= base_url('sidebar-permisos/configurar-defecto') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                mostrarLoading(false);
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Configuración completada',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                mostrarLoading(false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor'
                });
            });
        }
    });
}

// Función para ver estadísticas
function verEstadisticas() {
    mostrarLoading(true);
    
    fetch('<?= base_url('sidebar-permisos/estadisticas') ?>')
    .then(response => response.text())
    .then(html => {
        mostrarLoading(false);
        document.getElementById('contenido-estadisticas').innerHTML = html;
        $('#modalEstadisticas').modal('show');
    })
    .catch(error => {
        mostrarLoading(false);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las estadísticas'
        });
    });
}

// Función para exportar configuración
function exportarConfiguracion() {
    window.open('<?= base_url('sidebar-permisos/exportar') ?>', '_blank');
}

// Función para ver historial
function verHistorial() {
    mostrarLoading(true);
    
    fetch('<?= base_url('sidebar-permisos/historial') ?>')
    .then(response => response.text())
    .then(html => {
        mostrarLoading(false);
        document.getElementById('contenido-historial').innerHTML = html;
        $('#modalHistorial').modal('show');
    })
    .catch(error => {
        mostrarLoading(false);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar el historial'
        });
    });
}

// Funciones para acciones masivas
function activarTodosRol(rolId) {
    accionMasivaRol(rolId, 1, 'activar');
}

function desactivarTodosRol(rolId) {
    accionMasivaRol(rolId, 0, 'desactivar');
}

function accionMasivaRol(rolId, valor, accion) {
    Swal.fire({
        title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} todos los permisos?`,
        text: `Esto ${accion}á todos los permisos del sidebar para este rol`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarLoading(true);
            
            // Actualizar todos los switches del rol
            const switches = document.querySelectorAll(`input[data-rol="${rolId}"]`);
            switches.forEach(sw => {
                sw.checked = valor === 1;
                cambiarPermiso(sw);
            });
            
            setTimeout(() => {
                mostrarLoading(false);
                actualizarContadores();
            }, 1000);
        }
    });
}

function activarTodosModulo() {
    const modulo = document.getElementById('modulo-masivo').value;
    if (!modulo) {
        Swal.fire({
            icon: 'warning',
            title: 'Seleccione un módulo',
            text: 'Debe seleccionar un módulo para realizar la acción masiva'
        });
        return;
    }
    accionMasivaModulo(modulo, 1, 'activar');
}

function desactivarTodosModulo() {
    const modulo = document.getElementById('modulo-masivo').value;
    if (!modulo) {
        Swal.fire({
            icon: 'warning',
            title: 'Seleccione un módulo',
            text: 'Debe seleccionar un módulo para realizar la acción masiva'
        });
        return;
    }
    accionMasivaModulo(modulo, 0, 'desactivar');
}

function accionMasivaModulo(modulo, valor, accion) {
    Swal.fire({
        title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} módulo para todos?`,
        text: `Esto ${accion}á el acceso a este módulo para todos los roles`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarLoading(true);
            
            // Actualizar todos los switches del módulo
            const switches = document.querySelectorAll(`input[data-modulo="${modulo}"]`);
            switches.forEach(sw => {
                sw.checked = valor === 1;
                cambiarPermiso(sw);
            });
            
            setTimeout(() => {
                mostrarLoading(false);
                actualizarContadores();
            }, 1000);
        }
    });
}

// Función para actualizar contadores
function actualizarContadores() {
    <?php foreach ($roles as $rol): ?>
    const switches<?= $rol['id'] ?> = document.querySelectorAll('input[data-rol="<?= $rol['id'] ?>"]:checked');
    document.getElementById('count-<?= $rol['id'] ?>').textContent = switches<?= $rol['id'] ?>.length;
    <?php endforeach; ?>
    
    // Actualizar contadores generales
    const totalSwitches = document.querySelectorAll('.permission-switch').length;
    const activeSwitches = document.querySelectorAll('.permission-switch:checked').length;
    
    document.getElementById('total-permisos').textContent = totalSwitches;
    document.getElementById('permisos-activos').textContent = activeSwitches;
}

// Función para configurar filtros
function configurarFiltros() {
    // Filtro por rol
    document.getElementById('filtro-rol').addEventListener('change', function() {
        aplicarFiltros();
    });
    
    // Filtro por módulo
    document.getElementById('filtro-modulo').addEventListener('input', function() {
        aplicarFiltros();
    });
    
    // Filtro por estado
    document.getElementById('filtro-estado').addEventListener('change', function() {
        aplicarFiltros();
    });
}

// Función para aplicar filtros
function aplicarFiltros() {
    const rolFiltro = document.getElementById('filtro-rol').value;
    const moduloFiltro = document.getElementById('filtro-modulo').value.toLowerCase();
    const estadoFiltro = document.getElementById('filtro-estado').value;
    
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por módulo
        if (moduloFiltro) {
            const moduloTexto = fila.querySelector('.module-name').textContent.toLowerCase();
            if (!moduloTexto.includes(moduloFiltro)) {
                mostrar = false;
            }
        }
        
        // Filtro por estado (si hay un rol seleccionado)
        if (estadoFiltro !== '' && rolFiltro) {
            const switch_ = fila.querySelector(`input[data-rol="${rolFiltro}"]`);
            if (switch_) {
                const estaActivo = switch_.checked;
                if ((estadoFiltro === '1' && !estaActivo) || (estadoFiltro === '0' && estaActivo)) {
                    mostrar = false;
                }
            }
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
    
    // Ocultar/mostrar columnas de roles
    if (rolFiltro) {
        // Ocultar todas las columnas de roles excepto la seleccionada
        <?php foreach ($roles as $index => $rol): ?>
        const columnas<?= $rol['id'] ?> = document.querySelectorAll('th:nth-child(<?= $index + 2 ?>), td:nth-child(<?= $index + 2 ?>)');
        columnas<?= $rol['id'] ?>.forEach(col => {
            col.style.display = rolFiltro === '<?= $rol['id'] ?>' ? '' : 'none';
        });
        <?php endforeach; ?>
    } else {
        // Mostrar todas las columnas
        <?php foreach ($roles as $index => $rol): ?>
        const columnas<?= $rol['id'] ?> = document.querySelectorAll('th:nth-child(<?= $index + 2 ?>), td:nth-child(<?= $index + 2 ?>)');
        columnas<?= $rol['id'] ?>.forEach(col => {
            col.style.display = '';
        });
        <?php endforeach; ?>
    }
}

// Función para mostrar/ocultar loading
function mostrarLoading(mostrar) {
    const overlay = document.getElementById('loading-overlay');
    overlay.style.display = mostrar ? 'flex' : 'none';
}

// Función para mostrar mensaje de bienvenida
function mostrarMensajeBienvenida() {
    if (!localStorage.getItem('sidebar_permisos_bienvenida')) {
        Swal.fire({
            icon: 'info',
            title: 'Gestión de Permisos del Sidebar',
            html: `
                <p>Bienvenido al sistema de gestión de permisos del sidebar.</p>
                <ul style="text-align: left; margin: 10px 0;">
                    <li>Use los switches para activar/desactivar permisos</li>
                    <li>Utilice los filtros para encontrar configuraciones específicas</li>
                    <li>Las acciones masivas le permiten configurar múltiples permisos a la vez</li>
                    <li>Todos los cambios se registran en el historial de auditoría</li>
                </ul>
            `,
            confirmButtonText: 'Entendido',
            showCancelButton: true,
            cancelButtonText: 'No mostrar de nuevo'
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                localStorage.setItem('sidebar_permisos_bienvenida', 'true');
            }
        });
    }
}
</script>
<?= $this->endSection() ?>