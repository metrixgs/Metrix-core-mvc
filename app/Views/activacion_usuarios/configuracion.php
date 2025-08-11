<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Configuración del Sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('activacion-usuarios/panel-master') ?>">Panel Master</a></li>
                        <li class="breadcrumb-item active">Configuración</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?= form_open('activacion-usuarios/configurar-sistema', ['id' => 'form-configuracion']) ?>
            
            <!-- Configuraciones de Usuarios -->
            <?php if (isset($configuraciones_agrupadas['usuarios'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i> Configuraciones de Usuarios
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($configuraciones_agrupadas['usuarios'] as $config): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="config_<?= $config['clave'] ?>">
                                                <?= $config['nombre'] ?>
                                                <?php if ($config['descripcion']): ?>
                                                    <i class="fas fa-info-circle text-info" title="<?= $config['descripcion'] ?>" data-toggle="tooltip"></i>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php if ($config['tipo'] == 'boolean'): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="config_<?= $config['clave'] ?>" 
                                                           name="config[<?= $config['clave'] ?>]" 
                                                           value="1" 
                                                           <?= $config['valor'] ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="config_<?= $config['clave'] ?>">
                                                        <?= $config['valor'] ? 'Habilitado' : 'Deshabilitado' ?>
                                                    </label>
                                                </div>
                                            <?php elseif ($config['tipo'] == 'integer'): ?>
                                                <input type="number" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>" 
                                                       min="0">
                                            <?php elseif ($config['tipo'] == 'select'): ?>
                                                <select class="form-control" 
                                                        id="config_<?= $config['clave'] ?>" 
                                                        name="config[<?= $config['clave'] ?>]">
                                                    <?php 
                                                    $opciones = json_decode($config['opciones'], true);
                                                    foreach ($opciones as $valor => $etiqueta): 
                                                    ?>
                                                        <option value="<?= $valor ?>" <?= $config['valor'] == $valor ? 'selected' : '' ?>>
                                                            <?= $etiqueta ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="text" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>">
                                            <?php endif; ?>
                                            
                                            <?php if ($config['descripcion']): ?>
                                                <small class="form-text text-muted"><?= $config['descripcion'] ?></small>
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

            <!-- Configuraciones de Seguridad -->
            <?php if (isset($configuraciones_agrupadas['seguridad'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt"></i> Configuraciones de Seguridad
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($configuraciones_agrupadas['seguridad'] as $config): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="config_<?= $config['clave'] ?>">
                                                <?= $config['nombre'] ?>
                                                <?php if ($config['descripcion']): ?>
                                                    <i class="fas fa-info-circle text-info" title="<?= $config['descripcion'] ?>" data-toggle="tooltip"></i>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php if ($config['tipo'] == 'boolean'): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="config_<?= $config['clave'] ?>" 
                                                           name="config[<?= $config['clave'] ?>]" 
                                                           value="1" 
                                                           <?= $config['valor'] ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="config_<?= $config['clave'] ?>">
                                                        <?= $config['valor'] ? 'Habilitado' : 'Deshabilitado' ?>
                                                    </label>
                                                </div>
                                            <?php elseif ($config['tipo'] == 'integer'): ?>
                                                <input type="number" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>" 
                                                       min="0">
                                            <?php else: ?>
                                                <input type="text" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>">
                                            <?php endif; ?>
                                            
                                            <?php if ($config['descripcion']): ?>
                                                <small class="form-text text-muted"><?= $config['descripcion'] ?></small>
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

            <!-- Configuraciones del Sistema -->
            <?php if (isset($configuraciones_agrupadas['sistema'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs"></i> Configuraciones del Sistema
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($configuraciones_agrupadas['sistema'] as $config): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="config_<?= $config['clave'] ?>">
                                                <?= $config['nombre'] ?>
                                                <?php if ($config['descripcion']): ?>
                                                    <i class="fas fa-info-circle text-info" title="<?= $config['descripcion'] ?>" data-toggle="tooltip"></i>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php if ($config['tipo'] == 'boolean'): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="config_<?= $config['clave'] ?>" 
                                                           name="config[<?= $config['clave'] ?>]" 
                                                           value="1" 
                                                           <?= $config['valor'] ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="config_<?= $config['clave'] ?>">
                                                        <?= $config['valor'] ? 'Habilitado' : 'Deshabilitado' ?>
                                                    </label>
                                                </div>
                                            <?php elseif ($config['tipo'] == 'integer'): ?>
                                                <input type="number" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>" 
                                                       min="0">
                                            <?php elseif ($config['tipo'] == 'textarea'): ?>
                                                <textarea class="form-control" 
                                                          id="config_<?= $config['clave'] ?>" 
                                                          name="config[<?= $config['clave'] ?>]" 
                                                          rows="3"><?= $config['valor'] ?></textarea>
                                            <?php else: ?>
                                                <input type="text" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>">
                                            <?php endif; ?>
                                            
                                            <?php if ($config['descripcion']): ?>
                                                <small class="form-text text-muted"><?= $config['descripcion'] ?></small>
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

            <!-- Configuraciones de Notificaciones -->
            <?php if (isset($configuraciones_agrupadas['notificaciones'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bell"></i> Configuraciones de Notificaciones
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($configuraciones_agrupadas['notificaciones'] as $config): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="config_<?= $config['clave'] ?>">
                                                <?= $config['nombre'] ?>
                                                <?php if ($config['descripcion']): ?>
                                                    <i class="fas fa-info-circle text-info" title="<?= $config['descripcion'] ?>" data-toggle="tooltip"></i>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php if ($config['tipo'] == 'boolean'): ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="config_<?= $config['clave'] ?>" 
                                                           name="config[<?= $config['clave'] ?>]" 
                                                           value="1" 
                                                           <?= $config['valor'] ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="config_<?= $config['clave'] ?>">
                                                        <?= $config['valor'] ? 'Habilitado' : 'Deshabilitado' ?>
                                                    </label>
                                                </div>
                                            <?php elseif ($config['tipo'] == 'email'): ?>
                                                <input type="email" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>">
                                            <?php else: ?>
                                                <input type="text" class="form-control" 
                                                       id="config_<?= $config['clave'] ?>" 
                                                       name="config[<?= $config['clave'] ?>]" 
                                                       value="<?= $config['valor'] ?>">
                                            <?php endif; ?>
                                            
                                            <?php if ($config['descripcion']): ?>
                                                <small class="form-text text-muted"><?= $config['descripcion'] ?></small>
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

            <!-- Botones de Acción -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Guardar Configuraciones
                            </button>
                            <a href="<?= base_url('activacion-usuarios/panel-master') ?>" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-arrow-left"></i> Volver al Panel
                            </a>
                            <button type="button" class="btn btn-warning btn-lg ml-2" onclick="resetearConfiguraciones()">
                                <i class="fas fa-undo"></i> Restablecer Valores por Defecto
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?= form_close() ?>

            <!-- Información Adicional -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Información sobre las Configuraciones
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Configuraciones de Usuarios</h5>
                                    <ul>
                                        <li><strong>Permitir Login Inactivos:</strong> Permite que usuarios inactivos puedan iniciar sesión</li>
                                        <li><strong>Días Límite Inactividad:</strong> Número de días sin actividad antes de considerar un usuario como inactivo</li>
                                        <li><strong>Activación Automática:</strong> Activa automáticamente nuevos usuarios al registrarse</li>
                                        <li><strong>Notificar Cambios Estado:</strong> Envía notificaciones cuando cambia el estado de un usuario</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Configuraciones de Seguridad</h5>
                                    <ul>
                                        <li><strong>Jerarquía de Roles:</strong> Define qué roles pueden gestionar otros roles</li>
                                        <li><strong>Auditoría de Acciones:</strong> Registra todas las acciones de activación/desactivación</li>
                                        <li><strong>Validación IP:</strong> Valida las direcciones IP en las acciones de gestión</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Importante:</strong> Los cambios en estas configuraciones afectan el comportamiento de todo el sistema. 
                                Asegúrese de entender las implicaciones antes de realizar modificaciones.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Confirmación para Restablecer -->
<div class="modal fade" id="modalRestablecer" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Restablecimiento
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>¡Atención!</strong> Esta acción restablecerá todas las configuraciones a sus valores por defecto.
                </div>
                <p>¿Está seguro que desea continuar? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" onclick="confirmarRestablecimiento()">
                    <i class="fas fa-undo"></i> Restablecer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para manejar el envío del formulario
$('#form-configuracion').on('submit', function(e) {
    e.preventDefault();
    
    // Mostrar indicador de carga
    const $submitBtn = $(this).find('button[type="submit"]');
    const originalText = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    
    // Procesar checkboxes no marcados
    $(this).find('input[type="checkbox"]:not(:checked)').each(function() {
        $(this).after('<input type="hidden" name="' + this.name + '" value="0">');
    });
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            toastr.success('Configuraciones guardadas correctamente');
            
            // Actualizar etiquetas de switches
            $('input[type="checkbox"]').each(function() {
                const $label = $(this).next('.custom-control-label');
                if ($(this).is(':checked')) {
                    $label.text('Habilitado');
                } else {
                    $label.text('Deshabilitado');
                }
            });
        },
        error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error('Error al guardar las configuraciones');
            }
        },
        complete: function() {
            // Restaurar botón
            $submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

// Función para restablecer configuraciones
function resetearConfiguraciones() {
    $('#modalRestablecer').modal('show');
}

// Función para confirmar restablecimiento
function confirmarRestablecimiento() {
    $.ajax({
        url: '<?= base_url('activacion-usuarios/restablecer-configuraciones') ?>',
        type: 'POST',
        success: function(response) {
            toastr.success('Configuraciones restablecidas correctamente');
            $('#modalRestablecer').modal('hide');
            
            // Recargar la página para mostrar los valores por defecto
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function() {
            toastr.error('Error al restablecer las configuraciones');
        }
    });
}

// Función para actualizar etiquetas de switches
function actualizarEtiquetasSwitch() {
    $('input[type="checkbox"].custom-control-input').on('change', function() {
        const $label = $(this).next('.custom-control-label');
        if ($(this).is(':checked')) {
            $label.text('Habilitado');
        } else {
            $label.text('Deshabilitado');
        }
    });
}

// Función para validar formulario
function validarFormulario() {
    let valido = true;
    
    // Validar campos numéricos
    $('input[type="number"]').each(function() {
        const valor = parseInt($(this).val());
        if (isNaN(valor) || valor < 0) {
            $(this).addClass('is-invalid');
            valido = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validar campos de email
    $('input[type="email"]').each(function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            valido = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    return valido;
}

// Inicializar al cargar la página
$(document).ready(function() {
    // Configurar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Configurar switches
    actualizarEtiquetasSwitch();
    
    // Validación en tiempo real
    $('input').on('blur', function() {
        validarFormulario();
    });
    
    // Confirmar antes de salir si hay cambios sin guardar
    let formChanged = false;
    $('#form-configuracion input, #form-configuracion select, #form-configuracion textarea').on('change', function() {
        formChanged = true;
    });
    
    $(window).on('beforeunload', function() {
        if (formChanged) {
            return '¿Está seguro que desea salir? Los cambios no guardados se perderán.';
        }
    });
    
    // Resetear flag cuando se guarda
    $('#form-configuracion').on('submit', function() {
        formChanged = false;
    });
});
</script>