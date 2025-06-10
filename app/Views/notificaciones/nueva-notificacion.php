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
                            <li class="breadcrumb-item active">Notificaciones</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Nueva Notificación</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Formulario para crear una nueva notificación -->
                        <form action="<?= base_url() . obtener_rol(). 'notificaciones/crear'; ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuario_id" class="form-label">Seleccionar Usuario</label>
                                        <select id="usuario_id" name="usuario_id" class="form-select js-example-basic-single <?= session('validation.usuario_id') ? 'is-invalid' : '' ?>" required>
                                            <option value="" disabled selected>Seleccione un usuario</option>
                                            <?php foreach ($usuarios as $usuario): ?>
                                                <option value="<?= $usuario['id']; ?>" <?= old('usuario_id') == $usuario['id'] ? 'selected' : ''; ?>>
                                                    <?= $usuario['nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (session('validation.usuario_id')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.usuario_id') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label">Título</label>
                                        <input type="text" id="titulo" name="titulo" class="form-control <?= session('validation.titulo') ? 'is-invalid' : '' ?>" value="<?= old('titulo'); ?>" required maxlength="100">
                                        <?php if (session('validation.titulo')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.titulo') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea id="descripcion" name="descripcion" class="form-control <?= session('validation.descripcion') ? 'is-invalid' : '' ?>" rows="10" required><?= old('descripcion'); ?></textarea>
                                        <?php if (session('validation.descripcion')): ?>
                                            <div class="text-danger">
                                                <?= session('validation.descripcion') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">Enviar Notificación</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>