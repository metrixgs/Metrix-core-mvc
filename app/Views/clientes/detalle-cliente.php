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
                            <li class="breadcrumb-item active">Tickets</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Información del Cliente</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para eliminar cliente -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
                                            <i class="ri ri-delete-bin-6-line"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= base_url() . "clientes/actualizar"; ?>">
                            <input type="hidden" name="cliente_id" required="" value="<?= $cliente['id']; ?>">

                            <div class="row">
                                <!-- Campo Nombre -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" name="nombre" id="nombre" value="<?= old('nombre', $cliente['nombre']); ?>">
                                        <?php if (session('validation.nombre')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.nombre') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Campo Correo -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input type="email" class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" name="correo" id="correo" value="<?= old('correo', $cliente['correo']); ?>">
                                        <?php if (session('validation.correo')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.correo') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Campo Contraseña -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contrasena" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control <?= session('validation.contrasena') ? 'is-invalid' : '' ?>" name="contrasena" id="contrasena" value="<?= old('contrasena', $cliente['contrasena']); ?>">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="ri ri-eye-close-line" id="eyeIcon"></i> <!-- Icono de Remix Icon -->
                                            </button>
                                        </div>
                                        <?php if (session('validation.contrasena')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.contrasena') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Campo Fecha de Registro -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                                        <input type="text" class="form-control <?= session('validation.fecha_registro') ? 'is-invalid' : '' ?>" id="fecha_registro" value="<?= old('fecha_registro', $cliente['fecha_registro']); ?>" required disabled readonly>
                                        <?php if (session('validation.fecha_registro')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.fecha_registro') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit"><i class="ri-refresh-line"></i>&nbsp;Actualizar</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>


<!-- Modal de Confirmación -->
<div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-labelledby="nuevoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoClienteModalLabel">Confirmación de Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este cliente de forma permanente? Toda la información asociada al cliente también será eliminada sin posibilidad de recuperación.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= base_url() . 'clientes/eliminar/' . $cliente['id']; ?>" class="btn btn-danger">
                    Confirmar Eliminación
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script para alternar la visibilidad de la contraseña -->
<script>
    // Seleccionamos el botón de alternancia y el campo de la contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('contrasena');
    const eyeIcon = document.getElementById('eyeIcon');

    // Añadimos un event listener al botón
    togglePassword.addEventListener('click', function () {
        // Si la contraseña es de tipo 'password', la cambiamos a 'text' para mostrarla
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('ri-eye-close-line'); // Cambiar a icono de ojo abierto
            eyeIcon.classList.add('ri-eye-2-line');
        } else {
            // Si la contraseña es de tipo 'text', la cambiamos a 'password' para ocultarla
            passwordField.type = 'password';
            eyeIcon.classList.remove('ri-eye-2-line'); // Cambiar a icono de ojo cerrado
            eyeIcon.classList.add('ri-eye-close-line');
        }
    });
</script>