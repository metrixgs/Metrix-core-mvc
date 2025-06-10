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
                            <li class="breadcrumb-item active">Perfil</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Mi Perfil</h4>
                                </div>
                            </div>
                            <!-- Botón para abrir el modal para eliminar cuenta -->
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal -->
                                    <div class="search-box ms-2">
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Formulario de información de perfil -->
                        <form method="POST" action="<?= base_url() . 'perfil/actualizar'; ?>" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Foto de perfil (no editable) -->
                                <div class="col-md-3 text-center">
                                    <img src="<?= base_url() . "public/files/images/foto-usuario.jpg"; ?>" class="img-fluid rounded-circle" alt="Foto de perfil" style="max-width: 150px;">
                                    <p class="mt-2">Foto de perfil (No editable)</p>
                                </div>

                                <!-- Datos del usuario -->
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" disabled="" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                            <label for="correo" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo']); ?>" required>
                                        </div>

                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                            <label for="contrasena" class="form-label">Contraseña</label>
                                            <!-- Campo de contraseña -->
                                            <div class="input-group">
                                                <input required="" type="password" class="form-control" id="contrasena" name="contrasena" value="<?= $usuario['contrasena']; ?>">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="ri ri-eye-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="area_id" class="form-label">Área</label>
                                        <select disabled="" class="form-select">
                                            <!-- Opción de área no asignada -->
                                            <option value="NULL" <?= is_null($usuario['area_id']) ? 'selected' : ''; ?>>Cliente</option>

                                            <!-- Generación dinámica de opciones basadas en el arreglo $areas -->
                                            <?php foreach ($areas as $area): ?>
                                                <option value="<?= $area['id']; ?>" <?= $usuario['area_id'] == $area['id'] ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($area['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                            <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($usuario['fecha_registro'])); ?>" disabled>
                                        </div>

                                        <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                            <label for="fecha_actualizacion" class="form-label">Última Actualización</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($usuario['fecha_actualizacion'])); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de actualización -->
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>


<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-labelledby="modalEliminarCuentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarCuentaLabel">Confirmación de eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar tu cuenta? Este proceso es irreversible y toda la información asociada a tu cuenta, incluyendo el acceso a la misma, será eliminada permanentemente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <!-- Enlace o botón para realizar la eliminación, por ejemplo a través de un formulario -->
                <a href="<?= base_url() . "perfil/eliminar-cuenta/"; ?>" class="btn btn-danger">Eliminar cuenta</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Seleccionar el botón y el campo de contraseña
    const togglePasswordButton = document.getElementById('togglePassword');
    const passwordField = document.getElementById('contrasena');

    togglePasswordButton.addEventListener('click', function () {
        // Alternar el tipo del campo entre 'password' y 'text'
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Alternar el icono de ojo
        const icon = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
        togglePasswordButton.innerHTML = `<i class="fa ${icon}"></i>`;
    });
</script>