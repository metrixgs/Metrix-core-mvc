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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Clientes</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear cliente -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Fecha de Registro</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($clientes)) { ?>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= $cliente['nombre'] ?></td>
                                                <td><?= $cliente['correo'] ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($cliente['fecha_registro'])) ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . 'clientes/detalle/' . $cliente['id'] ?>" class="btn btn-info">
                                                        Detalles
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<!-- Modal para crear nuevo cliente -->
<div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-labelledby="nuevoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoClienteModalLabel">Crear Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="<?= base_url() . 'clientes/crear'; ?>" method="POST">
                <div class="modal-body">
                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
                        <?php if (session('validation.nombre')): ?>
                            <div class="text-danger">
                                <?= session('validation.nombre') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" id="correo" name="correo" value="<?= old('correo') ?>" required>
                        <?php if (session('validation.correo')): ?>
                            <div class="text-danger">
                                <?= session('validation.correo') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Telefono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="number" class="form-control <?= session('validation.telefono') ? 'is-invalid' : '' ?>" id="telefono" name="telefono" value="<?= old('telefono') ?>" required>
                        <?php if (session('validation.telefono')): ?>
                            <div class="text-danger">
                                <?= session('validation.telefono') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contrasena -->
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control <?= session('validation.contrasena') ? 'is-invalid' : '' ?>" id="contrasena" name="contrasena" value="<?= old('contrasena') ?>" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        <?php if (session('validation.contrasena')): ?>
                            <div class="text-danger">
                                <?= session('validation.contrasena') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('contrasena');
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Cambiar el icono según el estado
            var icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        });
    });
</script>