<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Panel Administrador - Gestión de Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Panel Administrador</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Estadísticas de la Cuenta -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total-usuarios-cuenta"><?= $estadisticas['total'] ?></h3>
                            <p>Total Usuarios</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="usuarios-activos-cuenta"><?= $estadisticas['activos'] ?></h3>
                            <p>Usuarios Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="usuarios-inactivos-cuenta"><?= $estadisticas['inactivos'] ?></h3>
                            <p>Usuarios Inactivos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="porcentaje-activos"><?= $estadisticas['porcentaje_activos'] ?>%</h3>
                            <p>% Usuarios Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controles y Filtros -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Controles de Gestión</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" onclick="actualizarEstadisticas()">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="exportarReporte()">
                                    <i class="fas fa-download"></i> Exportar Reporte
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Estado:</label>
                                        <select class="form-control" id="filtro-estado" onchange="filtrarUsuarios()">
                                            <option value="todos">Todos</option>
                                            <option value="activo">Activos</option>
                                            <option value="inactivo">Inactivos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Rol:</label>
                                        <select class="form-control" id="filtro-rol" onchange="filtrarUsuarios()">
                                            <option value="">Todos los roles</option>
                                            <?php foreach ($roles as $rol): ?>
                                                <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Buscar:</label>
                                        <input type="text" class="form-control" id="busqueda-usuario" placeholder="Nombre o email" onkeyup="filtrarUsuarios()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Usuarios -->
            <div class="row">
                <!-- Usuarios Activos -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-check"></i> Usuarios Activos
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-light"><?= count($usuarios_activos) ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-striped">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Último Acceso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-usuarios-activos">
                                        <?php foreach ($usuarios_activos as $usuario): ?>
                                            <tr data-usuario-id="<?= $usuario['id'] ?>" data-rol="<?= $usuario['rol_id'] ?>">
                                                <td>
                                                    <div class="user-panel d-flex">
                                                        <div class="image">
                                                            <div class="img-circle elevation-1 bg-success d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="info ml-2">
                                                            <strong><?= $usuario['nombre'] ?></strong><br>
                                                            <small class="text-muted"><?= $usuario['correo'] ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?= $usuario['rol_nombre'] ?></span>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-info" onclick="verUsuario(<?= $usuario['id'] ?>)" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($usuario['id'] != session('session_data.id')): ?>
                                                            <button class="btn btn-warning" onclick="desactivarUsuario(<?= $usuario['id'] ?>)" title="Desactivar">
                                                                <i class="fas fa-user-times"></i>
                                                            </button>
                                                        <?php endif; ?>
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

                <!-- Usuarios Inactivos -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-times"></i> Usuarios Inactivos
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-light"><?= count($usuarios_inactivos) ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-striped">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Inactivo desde</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-usuarios-inactivos">
                                        <?php foreach ($usuarios_inactivos as $usuario): ?>
                                            <tr data-usuario-id="<?= $usuario['id'] ?>" data-rol="<?= $usuario['rol_id'] ?>">
                                                <td>
                                                    <div class="user-panel d-flex">
                                                        <div class="image">
                                                            <div class="img-circle elevation-1 bg-secondary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="info ml-2">
                                                            <strong><?= $usuario['nombre'] ?></strong><br>
                                                            <small class="text-muted"><?= $usuario['correo'] ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary"><?= $usuario['rol_nombre'] ?></span>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?= $usuario['fecha_inactivacion'] ? date('d/m/Y', strtotime($usuario['fecha_inactivacion'])) : 'N/A' ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-info" onclick="verUsuario(<?= $usuario['id'] ?>)" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-success" onclick="activarUsuario(<?= $usuario['id'] ?>)" title="Activar">
                                                            <i class="fas fa-user-check"></i>
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

            <!-- Historial de Activaciones -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Historial de Activaciones
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha/Hora</th>
                                            <th>Usuario Afectado</th>
                                            <th>Ejecutor</th>
                                            <th>Acción</th>
                                            <th>Estado</th>
                                            <th>Motivo</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($historial_cuenta as $item): ?>
                                            <tr>
                                                <td>
                                                    <small><?= date('d/m/Y H:i:s', strtotime($item['fecha_hora'])) ?></small>
                                                </td>
                                                <td>
                                                    <strong><?= $item['usuario_afectado_nombre'] ?></strong>
                                                </td>
                                                <td>
                                                    <?= $item['usuario_ejecutor_nombre'] ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $item['accion'] == 'activar' ? 'badge-success' : 'badge-warning' ?>">
                                                        <i class="fas <?= $item['accion'] == 'activar' ? 'fa-user-check' : 'fa-user-times' ?>"></i>
                                                        <?= ucfirst($item['accion']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $item['estado_nuevo'] ? 'badge-success' : 'badge-secondary' ?>">
                                                        <?= $item['estado_nuevo'] ? 'Activo' : 'Inactivo' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?= $item['motivo'] ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= $item['ip_address'] ?></small>
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
    </section>
</div>

<!-- Modal para ver detalles de usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Usuario</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-modal-usuario">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para activar/desactivar usuario -->
<div class="modal fade" id="modalAccion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-modal-accion">Confirmar Acción</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-accion-usuario">
                    <input type="hidden" id="usuario-id-accion" name="usuario_id">
                    <input type="hidden" id="tipo-accion" name="accion">
                    
                    <div class="form-group">
                        <label>Motivo de la acción:</label>
                        <textarea class="form-control" id="motivo-accion" name="motivo" rows="3" required placeholder="Ingrese el motivo de la acción..."></textarea>
                        <small class="form-text text-muted">Este motivo quedará registrado en el historial de activaciones.</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="mensaje-confirmacion"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="ejecutarAccion()">
                    <i class="fas fa-check"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let usuariosFiltrados = [];

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    $.ajax({
        url: '<?= base_url('activacion-usuarios/obtener-estadisticas') ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#total-usuarios-cuenta').text(response.estadisticas.total);
                $('#usuarios-activos-cuenta').text(response.estadisticas.activos);
                $('#usuarios-inactivos-cuenta').text(response.estadisticas.inactivos);
                
                // Usar porcentaje calculado del modelo
                const porcentaje = response.estadisticas.porcentaje_activos;
                $('#porcentaje-activos').text(porcentaje + '%');
                
                toastr.success('Estadísticas actualizadas');
            }
        },
        error: function() {
            toastr.error('Error al actualizar estadísticas');
        }
    });
}

// Función para filtrar usuarios
function filtrarUsuarios() {
    const estado = $('#filtro-estado').val();
    const rol = $('#filtro-rol').val();
    const busqueda = $('#busqueda-usuario').val().toLowerCase();
    
    // Filtrar usuarios activos
    $('#tabla-usuarios-activos tr').each(function() {
        const $row = $(this);
        const usuarioId = $row.data('usuario-id');
        const rolId = $row.data('rol');
        const nombre = $row.find('strong').text().toLowerCase();
        const email = $row.find('.text-muted').text().toLowerCase();
        
        let mostrar = true;
        
        // Filtro por estado
        if (estado === 'inactivo') {
            mostrar = false;
        }
        
        // Filtro por rol
        if (rol && rolId != rol) {
            mostrar = false;
        }
        
        // Filtro por búsqueda
        if (busqueda && !nombre.includes(busqueda) && !email.includes(busqueda)) {
            mostrar = false;
        }
        
        $row.toggle(mostrar);
    });
    
    // Filtrar usuarios inactivos
    $('#tabla-usuarios-inactivos tr').each(function() {
        const $row = $(this);
        const usuarioId = $row.data('usuario-id');
        const rolId = $row.data('rol');
        const nombre = $row.find('strong').text().toLowerCase();
        const email = $row.find('.text-muted').text().toLowerCase();
        
        let mostrar = true;
        
        // Filtro por estado
        if (estado === 'activo') {
            mostrar = false;
        }
        
        // Filtro por rol
        if (rol && rolId != rol) {
            mostrar = false;
        }
        
        // Filtro por búsqueda
        if (busqueda && !nombre.includes(busqueda) && !email.includes(busqueda)) {
            mostrar = false;
        }
        
        $row.toggle(mostrar);
    });
}

// Función para ver detalles de usuario
function verUsuario(usuarioId) {
    $.ajax({
        url: '<?= base_url('activacion-usuarios/obtener-usuario') ?>/' + usuarioId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarDetallesUsuario(response.usuario, response.historial);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Error al obtener información del usuario');
        }
    });
}

// Función para mostrar detalles del usuario
function mostrarDetallesUsuario(usuario, historial) {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-user"></i> Información Personal</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td><strong>Nombre:</strong></td><td>${usuario.nombre}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${usuario.correo}</td></tr>
                            <tr><td><strong>Rol:</strong></td><td><span class="badge badge-info">${usuario.rol_nombre}</span></td></tr>
                            <tr><td><strong>Estado:</strong></td><td><span class="badge ${usuario.activo ? 'badge-success' : 'badge-warning'}">${usuario.activo ? 'Activo' : 'Inactivo'}</span></td></tr>
                            <tr><td><strong>Último Acceso:</strong></td><td>${usuario.ultimo_acceso ? new Date(usuario.ultimo_acceso).toLocaleString() : 'Nunca'}</td></tr>
                            <tr><td><strong>Fecha Registro:</strong></td><td>${usuario.fecha_registro ? new Date(usuario.fecha_registro).toLocaleDateString() : 'N/A'}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-history"></i> Historial Reciente</h6>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">`;
    
    if (historial.length > 0) {
        historial.forEach(function(item) {
            html += `
                <div class="timeline-item mb-3">
                    <div class="d-flex">
                        <div class="mr-3">
                            <i class="fas fa-${item.accion === 'activar' ? 'user-check text-success' : 'user-times text-warning'}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong>${item.accion === 'activar' ? 'Activado' : 'Desactivado'}</strong>
                                <small class="text-muted">${new Date(item.fecha_hora).toLocaleString()}</small>
                            </div>
                            <div class="text-muted">
                                <strong>Por:</strong> ${item.usuario_ejecutor_nombre}<br>
                                <strong>Motivo:</strong> ${item.motivo}<br>
                                <strong>IP:</strong> ${item.ip_address}
                            </div>
                        </div>
                    </div>
                </div>`;
        });
    } else {
        html += '<p class="text-muted text-center">No hay historial disponible</p>';
    }
    
    html += `
                    </div>
                </div>
            </div>
        </div>`;
    
    $('#contenido-modal-usuario').html(html);
    $('#modalUsuario').modal('show');
}

// Función para activar usuario
function activarUsuario(usuarioId) {
    $('#usuario-id-accion').val(usuarioId);
    $('#tipo-accion').val('activar');
    $('#titulo-modal-accion').text('Activar Usuario');
    $('#mensaje-confirmacion').text('¿Está seguro que desea activar este usuario? El usuario podrá iniciar sesión nuevamente.');
    $('#motivo-accion').val('');
    $('#modalAccion').modal('show');
}

// Función para desactivar usuario
function desactivarUsuario(usuarioId) {
    $('#usuario-id-accion').val(usuarioId);
    $('#tipo-accion').val('desactivar');
    $('#titulo-modal-accion').text('Desactivar Usuario');
    $('#mensaje-confirmacion').text('¿Está seguro que desea desactivar este usuario? El usuario no podrá iniciar sesión hasta ser reactivado.');
    $('#motivo-accion').val('');
    $('#modalAccion').modal('show');
}

// Función para ejecutar la acción
function ejecutarAccion() {
    const accion = $('#tipo-accion').val();
    const usuarioId = $('#usuario-id-accion').val();
    const motivo = $('#motivo-accion').val();
    
    if (!motivo.trim()) {
        toastr.error('Debe ingresar un motivo para la acción');
        $('#motivo-accion').focus();
        return;
    }
    
    const url = accion === 'activar' ? 
        '<?= base_url('activacion-usuarios/activar-usuario') ?>' : 
        '<?= base_url('activacion-usuarios/desactivar-usuario') ?>';
    
    // Deshabilitar botón para evitar doble clic
    const $btn = $('#modalAccion .btn-primary');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
    
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            usuario_id: usuarioId,
            motivo: motivo
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#modalAccion').modal('hide');
                
                // Actualizar la vista sin recargar completamente
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Error al ejecutar la acción');
        },
        complete: function() {
            // Rehabilitar botón
            $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirmar');
        }
    });
}

// Función para exportar reporte
function exportarReporte() {
    const fechaInicio = prompt('Fecha de inicio (YYYY-MM-DD):', '<?= date('Y-m-01') ?>');
    const fechaFin = prompt('Fecha de fin (YYYY-MM-DD):', '<?= date('Y-m-d') ?>');
    
    if (fechaInicio && fechaFin) {
        window.open(`<?= base_url('activacion-usuarios/exportar-reporte') ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
    }
}

// Actualizar estadísticas cada 60 segundos
setInterval(actualizarEstadisticas, 60000);

// Inicializar al cargar la página
$(document).ready(function() {
    // Configurar tooltips
    $('[title]').tooltip();
    
    // Configurar filtros
    $('#filtro-estado, #filtro-rol').on('change', filtrarUsuarios);
    $('#busqueda-usuario').on('keyup', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(filtrarUsuarios, 300);
    });
});
</script>