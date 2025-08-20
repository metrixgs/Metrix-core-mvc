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
                            <li class="breadcrumb-item"><a href="<?= base_url() . "dependencias/detalle/" . $dependencia['id']; ?>">Detalle Dependencia</a></li>
                            <li class="breadcrumb-item active">Asignar Usuario</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Asignar Usuario a Dependencia: <?= $dependencia['nombre']; ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url() . 'dependencias/asignarUsuarioADependencia'; ?>" method="POST">
                            <input type="hidden" name="dependencia_id" value="<?= $dependencia['id']; ?>">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Seleccionar Usuario Operador</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">-- Seleccione un usuario --</option>
                                    <?php if (!empty($usuarios_operadores_disponibles)) : ?>
                                        <?php foreach ($usuarios_operadores_disponibles as $usuario_disponible) : ?>
                                            <option value="<?= $usuario_disponible['id']; ?>">
                                                <?= $usuario_disponible['nombre']; ?> (<?= $usuario_disponible['correo']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="" disabled>No hay usuarios operadores disponibles para asignar.</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="text-end">
                                <a href="<?= base_url() . 'dependencias/detalle/' . $dependencia['id']; ?>" class="btn btn-light">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-user-add-line align-middle me-1"></i>Asignar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>