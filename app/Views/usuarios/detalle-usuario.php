<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Informacion del Usuario</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para eliminar usuario -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarUsuarioModal" data-usuario-id="<?= $usuario['id']; ?>">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Formulario para editar la información del usuario -->
                        <form action="<?= base_url() . "usuarios/actualizar"; ?>" method="POST">
                            <!-- Campo oculto para el ID del usuario -->
                            <input type="hidden" name="id" value="<?= $usuario['id']; ?>">

                            <!-- Rol -->
                            <div class="form-group mb-3">
                                <label for="rol_id">Rol</label>
                                <select name="rol_id" id="rol_id" class="form-control js-example-basic-single <?= session('validation.rol_id') ? 'is-invalid' : '' ?>" required onchange="controlarArea()">
    <option value="">Seleccione un rol</option>
<?php foreach ($roles as $rol): ?>
    <?php if (!in_array($rol['id'], [1, 2, 3, 4])): // excluir roles globales ?>
        <option value="<?= $rol['id']; ?>" <?= $rol['id'] == $usuario['rol_id'] ? 'selected' : ''; ?>>
            <?= $rol['nombre']; ?>
        </option>
    <?php endif; ?>
<?php endforeach; ?>

</select>

                                <?php if (session('validation.rol')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.rol') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <!-- Área -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3" id="area-section">
                                    <label for="area_id">Área</label>
                                    <select name="area_id" id="area_id" class="form-control js-example-basic-single <?= session('validation.area_id') ? 'is-invalid' : '' ?>">
                                        <option value="">Seleccione un área</option>
                                        <?php foreach ($areas as $area): ?>
                                            <option value="<?= $area['id']; ?>" <?= $area['id'] == $usuario['area_id'] ? 'selected' : ''; ?>>
                                                <?= $area['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('validation.area_id')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.area_id') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Cargo -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="nombre">Cargo</label>
                                    <input type="text" name="cargo" id="cargo" class="form-control <?= session('validation.cargo') ? 'is-invalid' : '' ?>" value="<?= old('cargo', $usuario['cargo']); ?>" required>
                                    <?php if (session('validation.cargo')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.cargo') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Nombre -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" value="<?= old('nombre', $usuario['nombre']); ?>" required>
                                    <?php if (session('validation.nombre')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.nombre') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="telefono">Teléfono</label>
                                    <div class="input-group">
                                        <input type="text" name="telefono" id="telefono" 
                                               class="form-control <?= session('validation.telefono') ? 'is-invalid' : '' ?>" 
                                               value="<?= old('telefono', $usuario['telefono']); ?>" required>
                                        <div class="input-group-append">
                                            <a href="https://wa.me/52<?= $usuario['telefono']; ?>" id="whatsappLink" target="_blank" 
                                               class="btn btn-primary" 
                                               style="display: flex; align-items: center; justify-content: center;" 
                                               title="Enviar mensaje por WhatsApp">
                                                <i class="ri-whatsapp-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php if (session('validation.telefono')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.telefono') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Correo -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="correo">Correo</label>
                                    <div class="input-group">
                                        <input type="email" name="correo" id="correo" 
                                               class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" 
                                               value="<?= old('correo', $usuario['correo']); ?>" required>
                                        <div class="input-group-append">
                                            <a href="mailto:<?= $usuario['correo']; ?>" id="correoLink" target="_blank" 
                                               class="btn btn-primary" 
                                               style="display: flex; align-items: center; justify-content: center;" 
                                               title="Enviar correo">
                                                <i class="ri-mail-line"></i> <!-- Ícono de correo -->
                                            </a>
                                        </div>
                                    </div>
                                    <?php if (session('validation.correo')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.correo') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Contraseña -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="contrasena">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="contrasena" id="contrasena" class="form-control <?= session('validation.contrasena') ? 'is-invalid' : '' ?>" value="<?= $usuario['contrasena']; ?>">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="toggle-password" onclick="togglePasswordVisibility()">
                                                <i class="ri-eye-2-line" id="eye-icon"></i> <!-- Ícono de ojo para mostrar/ocultar -->
                                            </button>
                                        </div>
                                    </div>
                                    <span class="class">La contraseña debe contener al menos 8 caracteres</span>
                                    <?php if (session('validation.contrasena')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.contrasena') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div><!--end col-->

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Permisos</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear permiso -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearPermisoModal">
                                            Nuevo Permiso
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="datatable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Modulo</th>
                                    <th>Lectura</th>
                                    <th>Escritura</th>
                                    <th>Actualización</th>
                                    <th>Eliminación</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($permisos)) : ?>
                                    <?php foreach ($permisos as $index => $permiso) : ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= $permiso['nombre_modulo']; ?></td>
                                            <td><?= $permiso['lectura'] ? 'Sí' : 'No'; ?></td>
                                            <td><?= $permiso['escritura'] ? 'Sí' : 'No'; ?></td>
                                            <td><?= $permiso['actualizacion'] ? 'Sí' : 'No'; ?></td>
                                            <td><?= $permiso['eliminacion'] ? 'Sí' : 'No'; ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url() . "usuarios/eliminar-permiso/{$permiso['id']}"; ?>" class="btn btn-danger"><i class="ri-delete-bin-line"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Este usuario no tiene permisos asignados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!--end row-->
    </div>
</div>

<!-- Modal para crear permiso -->
<div class="modal fade" id="crearPermisoModal" tabindex="-1" aria-labelledby="crearPermisoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPermisoModalLabel">Crear Nuevo Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear permiso -->
                <form action="<?= base_url() . 'usuarios/crear-permiso'; ?>" method="POST">
                    <!-- Pasamos el id del usuario... -->
                    <input type="hidden" value="<?= $usuario['id']; ?>" name="usuario_id" id="usuario_id" required="">

                    <div class="modal-body">
                        <!-- Módulo -->
                        <div class="mb-4">
                            <label for="modulo" class="form-label">Seleccionar Módulo</label>
                            <select class="form-select js-example-basic-single" id="modulo" name="modulo_id" required>
                                <option value="" disabled selected>Seleccione un módulo</option>
                                <?php foreach ($modulos as $modulo) : ?>
                                    <option value="<?= $modulo['id']; ?>"><?= $modulo['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Permisos -->
                        <div class="mb-4">
                            <label class="form-label">Permisos</label>

                            <!-- Lectura -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="lectura" name="lectura" value="1">
                                <label class="form-check-label" for="lectura">Lectura</label>
                            </div>

                            <!-- Escritura -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="escritura" name="escritura" value="1">
                                <label class="form-check-label" for="escritura">Escritura</label>
                            </div>

                            <!-- Actualización -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="actualizacion" name="actualizacion" value="1">
                                <label class="form-check-label" for="actualizacion">Actualización</label>
                            </div>

                            <!-- Eliminación -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="eliminacion" name="eliminacion" value="1">
                                <label class="form-check-label" for="eliminacion">Eliminación</label>
                            </div>
                        </div>

                        <!-- Botón para guardar -->
                        <button type="submit" class="btn btn-success w-100">Guardar Permiso</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="eliminarUsuarioModal" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarUsuarioModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="<?= base_url() . 'usuarios/eliminar'; ?>">
                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $usuario['id']; ?>">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scrip para ocultar o mostrar campo area -->
<script>
    // Llamar a esta función para mostrar u ocultar la sección de área
    function controlarArea() {
        var rolId = document.getElementById('rol_id').value;

        // Ocultar área si es Máster o Cliente, habilitar si es Usuario
        if (rolId == '1' || rolId == '2' || rolId == '3' || rolId == '4') {
            // Deshabilitar el campo de área y ocultarlo
            document.getElementById('area-section').style.display = 'none';
            document.getElementById('area_id').disabled = true;
        } else if (rolId == '2') {
            // Mostrar y habilitar el campo de área
            document.getElementById('area-section').style.display = 'block';
            document.getElementById('area_id').disabled = false;
        }
    }

    // Llamar a la función al cargar la página para establecer el estado inicial
    window.onload = function () {
        controlarArea();
    };
</script>

<!-- Para ver u ocultar la contraseña -->
<script>
    // Función para alternar entre mostrar/ocultar la contraseña
    function togglePasswordVisibility() {
        var passwordField = document.getElementById('contrasena');
        var eyeIcon = document.getElementById('eye-icon');

        // Cambiar tipo de input de "password" a "text" y alternar el ícono
        if (passwordField.type === "password") {
            passwordField.type = "text"; // Mostrar la contraseña
            eyeIcon.classList.remove('ri-eye-2-line'); // Quitar ícono de "ojo cerrado"
            eyeIcon.classList.add('ri-eye-close-line'); // Añadir ícono de "ojo abierto"
        } else {
            passwordField.type = "password"; // Ocultar la contraseña
            eyeIcon.classList.remove('ri-eye-close-line'); // Quitar ícono de "ojo abierto"
            eyeIcon.classList.add('ri-eye-2-line'); // Añadir ícono de "ojo cerrado"
        }
    }
</script>