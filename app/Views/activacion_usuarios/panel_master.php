<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Panel Master - Gestión de Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Panel Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Estadísticas Generales -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total-usuarios"><?= $estadisticas['total'] ?></h3>
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
                            <h3 id="usuarios-activos"><?= $estadisticas['activos'] ?></h3>
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
                            <h3 id="usuarios-inactivos"><?= $estadisticas['inactivos'] ?></h3>
                            <p>Usuarios Inactivos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="cuentas-total"><?= count($cuentas) ?></h3>
                            <p>Total Cuentas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y Controles -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filtros y Controles</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" onclick="actualizarEstadisticas()">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                                <a href="<?= base_url('activacion-usuarios/configurar-sistema') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                                <button type="button" class="btn btn-info btn-sm" onclick="exportarReporte()">
                                    <i class="fas fa-download"></i> Exportar Reporte
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Estado:</label>
                                        <select class="form-control" id="filtro-estado" onchange="filtrarUsuarios()">
                                            <option value="todos">Todos</option>
                                            <option value="activo">Activos</option>
                                            <option value="inactivo">Inactivos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cuenta:</label>
                                        <select class="form-control" id="filtro-cuenta" onchange="filtrarUsuarios()">
                                            <option value="">Todas las cuentas</option>
                                            <?php foreach ($cuentas as $cuenta): ?>
                                                <option value="<?= $cuenta['id'] ?>"><?= $cuenta['nombre'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Rol:</label>
                                        <select class="form-control" id="filtro-rol" onchange="filtrarUsuarios()">
                                            <option value="">Todos los roles</option>
                                            <option value="16">Máster</option>
                                            <option value="2">Administrador</option>
                                            <option value="3">Demo</option>
                                            <option value="4">Ejecutivo</option>
                                            <option value="5">Soporte</option>
                                            <option value="6">Desarrollador</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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

            <!-- Gestión por Cuentas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Gestión por Cuentas</h3>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionCuentas">
                                <?php foreach ($usuarios_por_cuenta as $cuentaId => $dataCuenta): ?>
                                    <div class="card">
                                        <div class="card-header" id="heading<?= $cuentaId ?>">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $cuentaId ?>" aria-expanded="false" aria-controls="collapse<?= $cuentaId ?>">
                                                    <strong><?= $dataCuenta['cuenta']['nombre'] ?></strong>
                                                    <span class="float-right">
                                                        <span class="badge badge-success"><?= $dataCuenta['estadisticas']['activos'] ?> Activos</span>
                                <span class="badge badge-warning"><?= $dataCuenta['estadisticas']['inactivos'] ?> Inactivos</span>
                                                    </span>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapse<?= $cuentaId ?>" class="collapse" aria-labelledby="heading<?= $cuentaId ?>" data-parent="#accordionCuentas">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- Usuarios Activos -->
                                                    <div class="col-md-6">
                                                        <h5 class="text-success">Usuarios Activos</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nombre</th>
                                                                        <th>Rol</th>
                                                                        <th>Último Acceso</th>
                                                                        <th>Acciones</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($dataCuenta['usuarios_activos'] as $usuario): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <strong><?= $usuario['nombre'] ?></strong><br>
                                                                                <small class="text-muted"><?= $usuario['correo'] ?></small>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge badge-info"><?= $usuario['rol_nombre'] ?></span>
                                                                            </td>
                                                                            <td>
                                                                                <small><?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?></small>
                                                                            </td>
                                                                            <td>
                                                                                <button class="btn btn-sm btn-info" onclick="verUsuario(<?= $usuario['id'] ?>)" title="Ver detalles">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </button>
                                                                                <?php if ($usuario['rol_id'] != 3): ?>
                                                                                    <button class="btn btn-sm btn-warning" onclick="desactivarUsuario(<?= $usuario['id'] ?>)" title="Desactivar">
                                                                                        <i class="fas fa-user-times"></i>
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Usuarios Inactivos -->
                                                    <div class="col-md-6">
                                                        <h5 class="text-warning">Usuarios Inactivos</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nombre</th>
                                                                        <th>Rol</th>
                                                                        <th>Inactivo desde</th>
                                                                        <th>Acciones</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($dataCuenta['usuarios_inactivos'] as $usuario): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <strong><?= $usuario['nombre'] ?></strong><br>
                                                                                <small class="text-muted"><?= $usuario['correo'] ?></small>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge badge-secondary"><?= $usuario['rol_nombre'] ?></span>
                                                                            </td>
                                                                            <td>
                                                                                <small><?= $usuario['fecha_inactivacion'] ? date('d/m/Y', strtotime($usuario['fecha_inactivacion'])) : 'N/A' ?></small>
                                                                            </td>
                                                                            <td>
                                                                                <button class="btn btn-sm btn-info" onclick="verUsuario(<?= $usuario['id'] ?>)" title="Ver detalles">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </button>
                                                                                <button class="btn btn-sm btn-success" onclick="activarUsuario(<?= $usuario['id'] ?>)" title="Activar">
                                                                                    <i class="fas fa-user-check"></i>
                                                                                </button>
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
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial Reciente -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Historial Reciente de Activaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha/Hora</th>
                                            <th>Usuario Afectado</th>
                                            <th>Ejecutor</th>
                                            <th>Acción</th>
                                            <th>Estado</th>
                                            <th>Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($historial_reciente as $item): ?>
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
                        <label>Motivo:</label>
                        <textarea class="form-control" id="motivo-accion" name="motivo" rows="3" required placeholder="Ingrese el motivo de la acción..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="mensaje-confirmacion"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="ejecutarAccion()">Confirmar</button>
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
                $('#total-usuarios').text(response.estadisticas.total);
                $('#usuarios-activos').text(response.estadisticas.activos);
                $('#usuarios-inactivos').text(response.estadisticas.inactivos);
                
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
    const filtros = {
        estado: $('#filtro-estado').val(),
        cuenta: $('#filtro-cuenta').val(),
        rol: $('#filtro-rol').val(),
        busqueda: $('#busqueda-usuario').val()
    };
    
    $.ajax({
        url: '<?= base_url('activacion-usuarios/obtener-usuarios-filtrados') ?>',
        type: 'POST',
        data: filtros,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                usuariosFiltrados = response.usuarios;
                // Aquí podrías actualizar una tabla de resultados si la implementas
                console.log('Usuarios filtrados:', usuariosFiltrados);
            }
        },
        error: function() {
            toastr.error('Error al filtrar usuarios');
        }
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
                <h6>Información Personal</h6>
                <table class="table table-sm">
                    <tr><td><strong>Nombre:</strong></td><td>${usuario.nombre}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${usuario.correo}</td></tr>
                    <tr><td><strong>Rol:</strong></td><td><span class="badge badge-info">${usuario.rol_nombre}</span></td></tr>
                    <tr><td><strong>Cuenta:</strong></td><td>${usuario.cuenta_nombre}</td></tr>
                    <tr><td><strong>Estado:</strong></td><td><span class="badge ${usuario.activo ? 'badge-success' : 'badge-warning'}">${usuario.activo ? 'Activo' : 'Inactivo'}</span></td></tr>
                    <tr><td><strong>Último Acceso:</strong></td><td>${usuario.ultimo_acceso ? new Date(usuario.ultimo_acceso).toLocaleString() : 'Nunca'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Historial Reciente</h6>
                <div class="timeline">`;
    
    historial.forEach(function(item) {
        html += `
            <div class="time-label">
                <span class="bg-${item.accion === 'activar' ? 'success' : 'warning'}">
                    ${new Date(item.fecha_hora).toLocaleDateString()}
                </span>
            </div>
            <div>
                <i class="fas fa-${item.accion === 'activar' ? 'user-check' : 'user-times'} bg-${item.accion === 'activar' ? 'success' : 'warning'}"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i> ${new Date(item.fecha_hora).toLocaleTimeString()}</span>
                    <h3 class="timeline-header">${item.usuario_ejecutor_nombre} ${item.accion === 'activar' ? 'activó' : 'desactivó'} al usuario</h3>
                    <div class="timeline-body">
                        <strong>Motivo:</strong> ${item.motivo}<br>
                        <strong>IP:</strong> ${item.ip_address}
                    </div>
                </div>
            </div>`;
    });
    
    html += `
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
    $('#mensaje-confirmacion').text('¿Está seguro que desea activar este usuario?');
    $('#motivo-accion').val('');
    $('#modalAccion').modal('show');
}

// Función para desactivar usuario
function desactivarUsuario(usuarioId) {
    $('#usuario-id-accion').val(usuarioId);
    $('#tipo-accion').val('desactivar');
    $('#titulo-modal-accion').text('Desactivar Usuario');
    $('#mensaje-confirmacion').text('¿Está seguro que desea desactivar este usuario?');
    $('#motivo-accion').val('');
    $('#modalAccion').modal('show');
}

// Función para ejecutar la acción
function ejecutarAccion() {
    const accion = $('#tipo-accion').val();
    const usuarioId = $('#usuario-id-accion').val();
    const motivo = $('#motivo-accion').val();
    
    if (!motivo.trim()) {
        toastr.error('Debe ingresar un motivo');
        return;
    }
    
    const url = accion === 'activar' ? 
        '<?= base_url('activacion-usuarios/activar-usuario') ?>' : 
        '<?= base_url('activacion-usuarios/desactivar-usuario') ?>';
    
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
                location.reload(); // Recargar para actualizar la vista
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Error al ejecutar la acción');
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

// Actualizar estadísticas cada 30 segundos
setInterval(actualizarEstadisticas, 30000);

// Inicializar filtros al cargar la página
$(document).ready(function() {
    filtrarUsuarios();
});
</script>