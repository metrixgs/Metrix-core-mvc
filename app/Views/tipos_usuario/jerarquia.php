<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $titulo ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.jerarquia-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 2rem;
}

.jerarquia-visual {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    border: 1px solid #e9ecef;
    min-height: 400px;
    position: relative;
    overflow: auto;
}

.nivel-jerarquico {
    margin: 2rem 0;
    text-align: center;
}

.nivel-titulo {
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 1rem;
    font-weight: bold;
    color: #495057;
}

.tipos-nivel {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.tipo-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    min-width: 200px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.tipo-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.tipo-card.activo {
    border-color: #28a745;
    background: #f8fff9;
}

.tipo-card.inactivo {
    border-color: #dc3545;
    background: #fff5f5;
    opacity: 0.7;
}

.tipo-card.legacy {
    border-color: #ffc107;
    background: #fffbf0;
}

.tipo-nombre {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #495057;
}

.tipo-codigo {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.tipo-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 0.5rem;
    font-size: 0.7rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-weight: bold;
    display: block;
}

.conexion-linea {
    position: absolute;
    background: #dee2e6;
    z-index: 1;
}

.conexion-vertical {
    width: 2px;
    left: 50%;
    transform: translateX(-50%);
}

.conexion-horizontal {
    height: 2px;
    top: 50%;
    transform: translateY(-50%);
}

.panel-configuracion {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.relacion-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #007bff;
}

.relacion-item.superior {
    border-left-color: #28a745;
}

.relacion-item.subordinado {
    border-left-color: #dc3545;
}

.btn-agregar-relacion {
    border: 2px dashed #dee2e6;
    background: transparent;
    color: #6c757d;
    padding: 1rem;
    border-radius: 8px;
    width: 100%;
    transition: all 0.3s ease;
}

.btn-agregar-relacion:hover {
    border-color: #007bff;
    color: #007bff;
    background: #f8f9fa;
}

.categoria-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.nivel-badge {
    position: absolute;
    top: -8px;
    left: -8px;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    background: #007bff;
    color: white;
    border-radius: 10px;
}

.filtros-jerarquia {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.vista-toggle {
    background: white;
    border-radius: 8px;
    padding: 0.5rem;
    display: inline-flex;
}

.vista-toggle .btn {
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.vista-toggle .btn.active {
    background: #007bff;
    color: white;
}

.matriz-permisos {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e9ecef;
    overflow-x: auto;
}

.tabla-jerarquia {
    width: 100%;
    border-collapse: collapse;
}

.tabla-jerarquia th,
.tabla-jerarquia td {
    padding: 0.75rem;
    text-align: center;
    border: 1px solid #dee2e6;
    vertical-align: middle;
}

.tabla-jerarquia th {
    background: #f8f9fa;
    font-weight: bold;
    position: sticky;
    top: 0;
    z-index: 10;
}

.tabla-jerarquia .tipo-header {
    background: #e9ecef;
    font-weight: bold;
    writing-mode: vertical-rl;
    text-orientation: mixed;
    min-width: 40px;
}

.relacion-cell {
    width: 40px;
    height: 40px;
}

.relacion-superior {
    background: #d4edda;
    color: #155724;
}

.relacion-subordinado {
    background: #f8d7da;
    color: #721c24;
}

.relacion-igual {
    background: #d1ecf1;
    color: #0c5460;
}

.sin-relacion {
    background: #f8f9fa;
    color: #6c757d;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header de jerarquía -->
    <div class="jerarquia-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-2"><?= $titulo ?></h1>
                <p class="mb-0 opacity-75">Gestión de la estructura organizacional y relaciones jerárquicas</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-light" onclick="exportarJerarquia()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <button type="button" class="btn btn-light" onclick="validarJerarquia()">
                        <i class="fas fa-check-circle"></i> Validar
                    </button>
                    <a href="<?= base_url('tipos-usuario') ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y controles -->
    <div class="filtros-jerarquia">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="form-label">Filtrar por categoría:</label>
                <select class="form-select" id="filtroCategoria" onchange="filtrarPorCategoria()">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Mostrar:</label>
                <select class="form-select" id="filtroEstado" onchange="filtrarPorEstado()">
                    <option value="">Todos los estados</option>
                    <option value="activo">Solo activos</option>
                    <option value="inactivo">Solo inactivos</option>
                    <option value="legacy">Solo legacy</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Vista:</label>
                <div class="vista-toggle">
                    <button type="button" class="btn active" onclick="cambiarVista('visual')">
                        <i class="fas fa-sitemap"></i> Visual
                    </button>
                    <button type="button" class="btn" onclick="cambiarVista('tabla')">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                    <button type="button" class="btn" onclick="cambiarVista('matriz')">
                        <i class="fas fa-th"></i> Matriz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vista visual de jerarquía -->
    <div id="vista-visual" class="vista-contenido">
        <div class="jerarquia-visual">
            <div id="contenedor-jerarquia">
                <!-- Se genera dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Vista de tabla -->
    <div id="vista-tabla" class="vista-contenido d-none">
        <div class="panel-configuracion">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Configuración de Relaciones</h5>
                <button class="btn btn-primary" onclick="agregarRelacion()">
                    <i class="fas fa-plus"></i> Agregar Relación
                </button>
            </div>
            <div id="lista-relaciones">
                <!-- Se carga dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Vista de matriz -->
    <div id="vista-matriz" class="vista-contenido d-none">
        <div class="matriz-permisos">
            <h5 class="mb-3">Matriz de Relaciones Jerárquicas</h5>
            <div class="table-responsive">
                <table class="tabla-jerarquia" id="tablaMatriz">
                    <thead>
                        <tr>
                            <th>Tipo / Tipo</th>
                            <!-- Se genera dinámicamente -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se genera dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar relación -->
<div class="modal fade" id="modalRelacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Relación Jerárquica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRelacion">
                    <input type="hidden" id="relacionId">
                    <div class="mb-3">
                        <label class="form-label">Tipo Superior:</label>
                        <select class="form-select" id="tipoSuperior" required>
                            <option value="">Seleccionar tipo superior</option>
                            <!-- Se llena dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo Subordinado:</label>
                        <select class="form-select" id="tipoSubordinado" required>
                            <option value="">Seleccionar tipo subordinado</option>
                            <!-- Se llena dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel de Autoridad:</label>
                        <select class="form-select" id="nivelAutoridad">
                            <option value="1">Supervisión Directa</option>
                            <option value="2">Supervisión Indirecta</option>
                            <option value="3">Coordinación</option>
                            <option value="4">Consulta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="puedeDelegar">
                            <label class="form-check-label" for="puedeDelegar">
                                Puede delegar permisos
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="heredaPermisos">
                            <label class="form-check-label" for="heredaPermisos">
                                Hereda permisos automáticamente
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones:</label>
                        <textarea class="form-control" id="observaciones" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarRelacion()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para validación de jerarquía -->
<div class="modal fade" id="modalValidacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validación de Jerarquía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="resultado-validacion">
                    <!-- Se carga dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
<script>
let tiposUsuario = <?= json_encode($tipos_usuario) ?>;
let relacionesJerarquicas = <?= json_encode($relaciones_jerarquicas) ?>;
let vistaActual = 'visual';

$(document).ready(function() {
    cargarTiposParaSelects();
    generarVistaVisual();
    cargarRelaciones();
    generarMatrizRelaciones();
    
    // Inicializar Select2
    $('#tipoSuperior, #tipoSubordinado, #filtroCategoria, #filtroEstado').select2({
        theme: 'bootstrap-5'
    });
});

function cargarTiposParaSelects() {
    let options = '<option value="">Seleccionar tipo</option>';
    tiposUsuario.forEach(tipo => {
        options += `<option value="${tipo.id}">${tipo.nombre} (${tipo.codigo})</option>`;
    });
    $('#tipoSuperior, #tipoSubordinado').html(options);
}

function generarVistaVisual() {
    // Agrupar tipos por nivel jerárquico
    const nivelesMap = new Map();
    
    tiposUsuario.forEach(tipo => {
        const nivel = tipo.nivel_acceso || 0;
        if (!nivelesMap.has(nivel)) {
            nivelesMap.set(nivel, []);
        }
        nivelesMap.get(nivel).push(tipo);
    });
    
    // Ordenar niveles
    const nivelesOrdenados = Array.from(nivelesMap.keys()).sort((a, b) => a - b);
    
    let html = '';
    nivelesOrdenados.forEach(nivel => {
        const tipos = nivelesMap.get(nivel);
        html += `
            <div class="nivel-jerarquico" data-nivel="${nivel}">
                <div class="nivel-titulo">Nivel ${nivel}</div>
                <div class="tipos-nivel">
        `;
        
        tipos.forEach(tipo => {
            const estadoClass = tipo.activo ? 'activo' : 'inactivo';
            const legacyClass = tipo.es_legacy ? 'legacy' : '';
            
            html += `
                <div class="tipo-card ${estadoClass} ${legacyClass}" 
                     data-tipo-id="${tipo.id}" 
                     onclick="seleccionarTipo(${tipo.id})">
                    <div class="nivel-badge">${nivel}</div>
                    <div class="categoria-badge badge bg-secondary">${tipo.categoria_nombre}</div>
                    <div class="tipo-nombre">${tipo.nombre}</div>
                    <div class="tipo-codigo">${tipo.codigo}</div>
                    <div class="tipo-stats">
                        <div class="stat-item">
                            <span class="stat-number">${tipo.usuarios_count || 0}</span>
                            <span>Usuarios</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${tipo.permisos_count || 0}</span>
                            <span>Permisos</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `
                </div>
            </div>
        `;
    });
    
    $('#contenedor-jerarquia').html(html);
    dibujarConexiones();
}

function dibujarConexiones() {
    // Limpiar conexiones existentes
    $('.conexion-linea').remove();
    
    relacionesJerarquicas.forEach(relacion => {
        const superior = $(`.tipo-card[data-tipo-id="${relacion.tipo_superior_id}"]`);
        const subordinado = $(`.tipo-card[data-tipo-id="${relacion.tipo_subordinado_id}"]`);
        
        if (superior.length && subordinado.length) {
            dibujarLinea(superior, subordinado);
        }
    });
}

function dibujarLinea(elemento1, elemento2) {
    const pos1 = elemento1.offset();
    const pos2 = elemento2.offset();
    const container = $('#contenedor-jerarquia').offset();
    
    // Calcular posiciones relativas
    const x1 = pos1.left - container.left + elemento1.outerWidth() / 2;
    const y1 = pos1.top - container.top + elemento1.outerHeight();
    const x2 = pos2.left - container.left + elemento2.outerWidth() / 2;
    const y2 = pos2.top - container.top;
    
    // Crear línea vertical desde superior
    const lineaVertical = $('<div class="conexion-linea conexion-vertical"></div>');
    lineaVertical.css({
        left: x1 + 'px',
        top: y1 + 'px',
        height: Math.abs(y2 - y1) / 2 + 'px'
    });
    
    // Crear línea horizontal
    const lineaHorizontal = $('<div class="conexion-linea conexion-horizontal"></div>');
    const midY = y1 + Math.abs(y2 - y1) / 2;
    lineaHorizontal.css({
        left: Math.min(x1, x2) + 'px',
        top: midY + 'px',
        width: Math.abs(x2 - x1) + 'px'
    });
    
    // Crear línea vertical hacia subordinado
    const lineaVertical2 = $('<div class="conexion-linea conexion-vertical"></div>');
    lineaVertical2.css({
        left: x2 + 'px',
        top: midY + 'px',
        height: Math.abs(y2 - y1) / 2 + 'px'
    });
    
    $('#contenedor-jerarquia').append(lineaVertical, lineaHorizontal, lineaVertical2);
}

function cargarRelaciones() {
    let html = '';
    
    relacionesJerarquicas.forEach(relacion => {
        const tipoSuperior = tiposUsuario.find(t => t.id == relacion.tipo_superior_id);
        const tipoSubordinado = tiposUsuario.find(t => t.id == relacion.tipo_subordinado_id);
        
        html += `
            <div class="relacion-item superior">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <span class="badge bg-success me-2">Superior</span>
                            ${tipoSuperior?.nombre || 'Tipo no encontrado'}
                        </h6>
                        <p class="mb-1">
                            <i class="fas fa-arrow-down me-2"></i>
                            <span class="badge bg-danger">Subordinado</span>
                            ${tipoSubordinado?.nombre || 'Tipo no encontrado'}
                        </p>
                        <small class="text-muted">
                            Nivel: ${relacion.nivel_autoridad} | 
                            Delega: ${relacion.puede_delegar ? 'Sí' : 'No'} | 
                            Hereda: ${relacion.hereda_permisos ? 'Sí' : 'No'}
                        </small>
                        ${relacion.observaciones ? `<p class="mt-2 mb-0"><small>${relacion.observaciones}</small></p>` : ''}
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editarRelacion(${relacion.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="eliminarRelacion(${relacion.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    if (html === '') {
        html = `
            <div class="text-center py-4">
                <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay relaciones jerárquicas configuradas</p>
                <button class="btn btn-primary" onclick="agregarRelacion()">
                    <i class="fas fa-plus"></i> Agregar Primera Relación
                </button>
            </div>
        `;
    }
    
    $('#lista-relaciones').html(html);
}

function generarMatrizRelaciones() {
    let html = '<tr><th>Tipo / Tipo</th>';
    
    // Generar headers
    tiposUsuario.forEach(tipo => {
        html += `<th class="tipo-header">${tipo.codigo}</th>`;
    });
    html += '</tr>';
    
    // Generar filas
    tiposUsuario.forEach(tipoFila => {
        html += `<tr><th class="tipo-header">${tipoFila.codigo}</th>`;
        
        tiposUsuario.forEach(tipoColumna => {
            let cellClass = 'sin-relacion';
            let cellContent = '-';
            
            if (tipoFila.id === tipoColumna.id) {
                cellClass = 'relacion-igual';
                cellContent = '=';
            } else {
                const relacion = relacionesJerarquicas.find(r => 
                    (r.tipo_superior_id == tipoFila.id && r.tipo_subordinado_id == tipoColumna.id) ||
                    (r.tipo_superior_id == tipoColumna.id && r.tipo_subordinado_id == tipoFila.id)
                );
                
                if (relacion) {
                    if (relacion.tipo_superior_id == tipoFila.id) {
                        cellClass = 'relacion-superior';
                        cellContent = '↓';
                    } else {
                        cellClass = 'relacion-subordinado';
                        cellContent = '↑';
                    }
                }
            }
            
            html += `<td class="relacion-cell ${cellClass}">${cellContent}</td>`;
        });
        
        html += '</tr>';
    });
    
    $('#tablaMatriz tbody').html(html);
}

function cambiarVista(vista) {
    // Actualizar botones
    $('.vista-toggle .btn').removeClass('active');
    $(`.vista-toggle .btn:contains('${vista === 'visual' ? 'Visual' : vista === 'tabla' ? 'Tabla' : 'Matriz'}')`).addClass('active');
    
    // Mostrar/ocultar vistas
    $('.vista-contenido').addClass('d-none');
    $(`#vista-${vista}`).removeClass('d-none');
    
    vistaActual = vista;
    
    if (vista === 'visual') {
        setTimeout(() => dibujarConexiones(), 100);
    }
}

function seleccionarTipo(tipoId) {
    // Resaltar tipo seleccionado
    $('.tipo-card').removeClass('border-primary');
    $(`.tipo-card[data-tipo-id="${tipoId}"]`).addClass('border-primary');
    
    // Mostrar información del tipo
    const tipo = tiposUsuario.find(t => t.id == tipoId);
    if (tipo) {
        mostrarInformacionTipo(tipo);
    }
}

function mostrarInformacionTipo(tipo) {
    // Implementar panel de información del tipo
    console.log('Información del tipo:', tipo);
}

function agregarRelacion() {
    $('#relacionId').val('');
    $('#formRelacion')[0].reset();
    $('#modalRelacion').modal('show');
}

function editarRelacion(relacionId) {
    const relacion = relacionesJerarquicas.find(r => r.id == relacionId);
    if (relacion) {
        $('#relacionId').val(relacion.id);
        $('#tipoSuperior').val(relacion.tipo_superior_id).trigger('change');
        $('#tipoSubordinado').val(relacion.tipo_subordinado_id).trigger('change');
        $('#nivelAutoridad').val(relacion.nivel_autoridad);
        $('#puedeDelegar').prop('checked', relacion.puede_delegar);
        $('#heredaPermisos').prop('checked', relacion.hereda_permisos);
        $('#observaciones').val(relacion.observaciones || '');
        $('#modalRelacion').modal('show');
    }
}

function guardarRelacion() {
    const formData = {
        id: $('#relacionId').val(),
        tipo_superior_id: $('#tipoSuperior').val(),
        tipo_subordinado_id: $('#tipoSubordinado').val(),
        nivel_autoridad: $('#nivelAutoridad').val(),
        puede_delegar: $('#puedeDelegar').is(':checked'),
        hereda_permisos: $('#heredaPermisos').is(':checked'),
        observaciones: $('#observaciones').val()
    };
    
    if (!formData.tipo_superior_id || !formData.tipo_subordinado_id) {
        Swal.fire('Error', 'Debe seleccionar ambos tipos de usuario', 'error');
        return;
    }
    
    if (formData.tipo_superior_id === formData.tipo_subordinado_id) {
        Swal.fire('Error', 'Un tipo no puede tener relación consigo mismo', 'error');
        return;
    }
    
    const url = formData.id ? 
        `<?= base_url('api/tipos-usuario/actualizar-relacion') ?>/${formData.id}` :
        '<?= base_url('api/tipos-usuario/crear-relacion') ?>';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function(data) {
            if (data.success) {
                Swal.fire('Éxito', data.message, 'success');
                $('#modalRelacion').modal('hide');
                location.reload();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });
}

function eliminarRelacion(relacionId) {
    Swal.fire({
        title: '¿Eliminar relación?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `<?= base_url('api/tipos-usuario/eliminar-relacion') ?>/${relacionId}`,
                method: 'DELETE',
                success: function(data) {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error de conexión', 'error');
                }
            });
        }
    });
}

function validarJerarquia() {
    $.get('<?= base_url('api/tipos-usuario/validar-jerarquia') ?>', function(data) {
        if (data.success) {
            mostrarResultadoValidacion(data.validacion);
            $('#modalValidacion').modal('show');
        }
    });
}

function mostrarResultadoValidacion(validacion) {
    let html = '';
    
    if (validacion.errores.length === 0) {
        html = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <strong>¡Jerarquía válida!</strong> No se encontraron problemas.
            </div>
        `;
    } else {
        html = '<div class="alert alert-danger"><h6>Problemas encontrados:</h6><ul>';
        validacion.errores.forEach(error => {
            html += `<li>${error}</li>`;
        });
        html += '</ul></div>';
    }
    
    if (validacion.advertencias.length > 0) {
        html += '<div class="alert alert-warning"><h6>Advertencias:</h6><ul>';
        validacion.advertencias.forEach(advertencia => {
            html += `<li>${advertencia}</li>`;
        });
        html += '</ul></div>';
    }
    
    $('#resultado-validacion').html(html);
}

function exportarJerarquia() {
    window.open('<?= base_url('api/tipos-usuario/exportar-jerarquia') ?>', '_blank');
}

function filtrarPorCategoria() {
    const categoriaId = $('#filtroCategoria').val();
    
    if (categoriaId) {
        $('.tipo-card').hide();
        $(`.tipo-card[data-categoria-id="${categoriaId}"]`).show();
    } else {
        $('.tipo-card').show();
    }
    
    if (vistaActual === 'visual') {
        setTimeout(() => dibujarConexiones(), 100);
    }
}

function filtrarPorEstado() {
    const estado = $('#filtroEstado').val();
    
    $('.tipo-card').show();
    
    if (estado) {
        $(`.tipo-card:not(.${estado})`).hide();
    }
    
    if (vistaActual === 'visual') {
        setTimeout(() => dibujarConexiones(), 100);
    }
}
</script>
<?= $this->endSection() ?>