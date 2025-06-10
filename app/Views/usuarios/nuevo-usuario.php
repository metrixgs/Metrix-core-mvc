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
                                    <h4 class="card-title mb-0 flex-grow-1">Nuevo Usuario</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Información del usuario que está creando - ACTUALIZADO -->
                        <div class="alert alert-info">
                            <strong>Usuario actual:</strong> <?= session('session_data.nombre') ?? 'No identificado'; ?><br>
                            <strong>Rol:</strong> <?= isset($nombre_rol) ? $nombre_rol : (session('session_data.rol_id') ? 'Rol ID: ' . session('session_data.rol_id') : 'Sin rol'); ?><br>
                            <?php if (session('session_data.cuenta_id')): ?>
                                <strong>Cuenta:</strong> <?= isset($nombre_cuenta) ? $nombre_cuenta : 'Cuenta ID: ' . session('session_data.cuenta_id'); ?>
                            <?php endif; ?>
                        </div>

                        <!-- Formulario para crear un nuevo usuario -->
                        <form action="<?= base_url() . "usuarios/crear"; ?>" method="POST">
                            <!-- No es necesario el campo oculto para ID, ya que es un nuevo usuario -->

                            <!-- Rol -->
                            <div class="form-group mb-3">
                                <label for="rol_id">Rol</label>
                                <select name="rol_id" id="rol_id" class="form-control js-example-basic-single <?= session('validation.rol_id') ? 'is-invalid' : '' ?>" required onchange="controlarArea()">
                                    <option value="">Seleccione un rol</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol['id']; ?>" <?= old('rol_id') == $rol['id'] ? 'selected' : ''; ?>>
                                            <?= $rol['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('validation.rol_id')): ?>
                                    <div class="text-danger">
                                        <?= session('validation.rol_id') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <!-- Área -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3" id="area-section">
                                    <label for="area_id">Área</label>
                                    <select name="area_id" id="area_id" class="form-control js-example-basic-single <?= session('validation.area_id') ? 'is-invalid' : '' ?>" required>
                                        <option value="">Seleccione un área</option>
                                        <?php foreach ($areas as $area): ?>
                                            <option value="<?= $area['id']; ?>" <?= old('area_id') == $area['id'] ? 'selected' : ''; ?>>
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
                                    <label for="cargo">Cargo</label>
                                    <input type="text" name="cargo" id="cargo" class="form-control <?= session('validation.cargo') ? 'is-invalid' : '' ?>" value="<?= old('cargo'); ?>" required>
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
                                    <input type="text" name="nombre" id="nombre" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" value="<?= old('nombre'); ?>" required>
                                    <?php if (session('validation.nombre')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.nombre') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control <?= session('validation.telefono') ? 'is-invalid' : '' ?>" value="<?= old('telefono'); ?>" required>
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
                                    <input type="email" name="correo" id="correo" class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" value="<?= old('correo'); ?>" required>
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
                                        <input type="password" name="contrasena" id="contrasena" class="form-control <?= session('validation.contrasena') ? 'is-invalid' : '' ?>" placeholder="Ingrese una contraseña" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-password" onclick="togglePasswordVisibility()">
                                                <i class="ri-eye-2-line" id="eye-icon"></i>
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

                            <!-- Botón -->
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<!-- Scripts -->
<script>
    function controlarArea() {
        var rolId = document.getElementById('rol_id').value;
        if (rolId == '1' || rolId == '3') {
            document.getElementById('area-section').style.display = 'none';
            document.getElementById('area_id').disabled = true;
        } else {
            document.getElementById('area-section').style.display = 'block';
            document.getElementById('area_id').disabled = false;
        }
    }

    window.onload = function () {
        controlarArea();
    };

    function togglePasswordVisibility() {
        var passwordField = document.getElementById('contrasena');
        var eyeIcon = document.getElementById('eye-icon');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove('ri-eye-2-line');
            eyeIcon.classList.add('ri-eye-close-line');
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove('ri-eye-close-line');
            eyeIcon.classList.add('ri-eye-2-line');
        }
    }
</script>