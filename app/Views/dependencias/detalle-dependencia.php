<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Dependencias</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Información de la Dependencia</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal de eliminación -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarDependencia">
                                            <i class="ri-delete-bin-line align-middle me-1"></i>Eliminar
                                        </button>
                                    </div>
                                    <!-- Actualizar Dependencia -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalActualizarDependencia">
                                            <i class="ri-refresh-line align-middle me-1"></i>Actualizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Información Principal -->
                            <div class="col-lg-12 mb-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-lg bg-light rounded p-3">
                                            <i class="ri-building-line fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-1"><?= $dependencia['nombre'] ?></h3>
                                        <p class="text-muted mb-0">Identificador: #<?= $dependencia['id'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-soft-light">
                                        <h5 class="card-title mb-0"><i class="ri-file-text-line align-middle me-2"></i>Descripción</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted"><?= $dependencia['descripcion'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Temporal -->
                            <div class="col-lg-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-soft-light">
                                        <h5 class="card-title mb-0"><i class="ri-time-line align-middle me-2"></i>Información Temporal</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-calendar-plus-line text-primary fs-4 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Fecha de Creación</h6>
                                                        <p class="text-muted mb-0"><?= date('d/m/Y H:i', strtotime($dependencia['fecha_creacion'])) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-calendar-check-line text-warning fs-4 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Última Actualización</h6>
                                                        <p class="text-muted mb-0">
                                                            <?= !empty($dependencia['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($dependencia['fecha_actualizacion'])) : 'Sin actualizaciones' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-calendar-event-line <?= empty($dependencia['fecha_eliminacion']) ? 'text-success' : 'text-danger' ?> fs-4 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Estado</h6>
                                                        <p class="text-muted mb-0">
                                                            <?= empty($dependencia['fecha_eliminacion']) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Usuarios de la Dependencia</h4>
                                </div>
                            </div>
                            <!-- Botón para abrir el modal -->
                            <div class="col-sm">
                                <!-- Botón para abrir el modal -->
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                                            <i class="ri-user-add-line align-middle me-1"></i>Nuevo Usuario
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($usuarios)) : ?>
                                        <?php foreach ($usuarios as $index => $usuario) : ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= $usuario['nombre']; ?></td>
                                                <td><?= $usuario['correo']; ?></td>
                                                <td><?= $usuario['fecha_registro']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . 'usuarios/detalle/' . $usuario['id']; ?>" class="btn btn-primary">Detalle</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay usuarios registrados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminarDependencia" tabindex="-1" aria-labelledby="modalEliminarDependenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarDependenciaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url() . "dependencias/eliminar"; ?>">
                <input type="hidden" name="dependencia_id" value="<?= $dependencia['id']; ?>">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="ri-alert-line text-danger display-5"></i>
                        <h4 class="mt-3">¿Estás seguro de eliminar esta dependencia?</h4>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="ri-information-line me-2"></i>
                        <strong>Advertencia:</strong> Esta acción eliminará la dependencia <strong id="nombreDependencia"></strong> y todos los usuarios asignados a la misma. Esta acción no se puede deshacer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line align-middle me-1"></i>Sí, Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para Actualizar Dependencia -->
<div class="modal fade" id="modalActualizarDependencia" tabindex="-1" aria-labelledby="modalActualizarDependenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarDependenciaLabel">Actualizar Dependencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url() . "dependencias/actualizar/{$dependencia['id']}"; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $dependencia['nombre']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= $dependencia['descripcion']; ?></textarea>
                    </div>
                    <input type="hidden" id="idDependencia" name="id">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoUsuarioLabel">Crear Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() . 'usuarios/crear'; ?>" method="POST">
                <div class="modal-body">
                    <!-- Campos ocultos requeridos -->
                    <input type="hidden" name="rol_id" id="rol_id" value="2" required="">
                    <input type="hidden" name="area_id" id="area_id" value="<?= $dependencia['id']; ?>" required="">

                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required 
                                   maxlength="100" placeholder="Ingrese el nombre completo">
                        </div>

                        <!-- Correo -->
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required 
                                   maxlength="150" placeholder="correo@ejemplo.com">
                        </div>

                        <!-- Contraseña -->
                        <div class="col-md-6">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" 
                                       required minlength="8" placeholder="Ingrese la contraseña">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                            <div class="form-text">La contraseña debe tener al menos 8 caracteres</div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="col-md-6">
                            <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmar_contrasena" 
                                       name="confirmar_contrasena" required minlength="8" 
                                       placeholder="Confirme la contraseña">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line align-middle me-1"></i>Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para alternar visibilidad de contraseña
        function togglePasswordVisibility(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            button.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                button.querySelector('i').className = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
            });
        }

        // Función para validar contraseñas y manejar estado del botón
        function validarContrasenas() {
            const password = document.getElementById('contrasena');
            const confirmPassword = document.getElementById('confirmar_contrasena');

            // Crear o obtener el div de mensaje de error
            let mensajeError = document.getElementById('mensaje-error-password');
            if (!mensajeError) {
                mensajeError = document.createElement('div');
                mensajeError.id = 'mensaje-error-password';
                mensajeError.className = 'text-danger mt-2 small';
                confirmPassword.parentNode.parentNode.appendChild(mensajeError);
            }

            // Función de validación
            function validar() {
                // Inhabilitar el botón por defecto
                submitButton.disabled = true;

                // Si ambos campos están vacíos, mantener el botón deshabilitado
                if (password.value === '' && confirmPassword.value === '') {
                    mensajeError.textContent = 'Por favor, ingrese una contraseña';
                    mensajeError.className = 'text-muted mt-2 small';
                    return;
                }

                // Si solo uno de los campos está lleno
                if ((password.value === '' && confirmPassword.value !== '') ||
                        (password.value !== '' && confirmPassword.value === '')) {
                    mensajeError.textContent = 'Por favor, complete ambos campos de contraseña';
                    mensajeError.className = 'text-warning mt-2 small';
                    return;
                }

                // Si ambos campos están llenos, verificar si coinciden
                if (password.value !== '' && confirmPassword.value !== '') {
                    if (password.value === confirmPassword.value) {
                        mensajeError.textContent = '✓ Las contraseñas coinciden';
                        mensajeError.className = 'text-success mt-2 small';
                        submitButton.disabled = false; // Habilitar solo si coinciden
                    } else {
                        mensajeError.textContent = '✗ Las contraseñas no coinciden';
                        mensajeError.className = 'text-danger mt-2 small';
                    }
                }
            }

            // Eventos para validación en tiempo real
            password.addEventListener('input', validar);
            confirmPassword.addEventListener('input', validar);

            // Validación inicial
            validar();
        }

        // Aplicar visibilidad de contraseñas
        togglePasswordVisibility('contrasena', 'togglePassword');
        togglePasswordVisibility('confirmar_contrasena', 'toggleConfirmPassword');

        // Iniciar validación de contraseñas
        validarContrasenas();

        // Ya no necesitamos el event listener del submit porque el botón estará
        // deshabilitado si las contraseñas no coinciden
    });
</script>




