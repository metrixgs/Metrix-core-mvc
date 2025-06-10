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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Usuarios</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear cliente -->
                                    <div class="search-box ms-2">
                                        <a href="<?= base_url() . "usuarios/nuevo"; ?>" class="btn btn-primary">
                                            Nuevo
                                        </a>
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
                                        <th>Rol</th>
                                        <th>Área</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Número Telefónico</th>
                                        <th>Fecha de Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($usuarios)) : ?>
                                        <?php foreach ($usuarios as $index => $usuario) : ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                 <td><?= $usuario['nombre_rol']; ?></td>

                                                <td><?= !empty($usuario['nombre_area']) ? $usuario['nombre_area'] : '---------'; ?></td>
                                                <td><?= $usuario['nombre']; ?></td>
                                                <td><?= $usuario['correo'] ?? '---------'; ?></td>
                                                <td><?= $usuario['telefono'] ?? '---------'; ?></td>
                                                <td><?= $usuario['fecha_registro']; ?></td>
                                                <td class="text-center">
                                                    <!-- Botones agrupados -->
                                                    <div class="btn-group" role="group">
                                                        <!-- Detalle -->
                                                        <a href="<?= base_url() . 'usuarios/detalle/' . $usuario['id']; ?>" 
                                                           class="btn btn-primary me-2" title="Ver detalles">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        
                                                        <!-- Actualizar -->
                                                        <a href="<?= base_url() . 'usuarios/actualizar/' . $usuario['id']; ?>" 
                                                           class="btn btn-warning me-2" title="Actualizar usuario">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        
                                                        <!-- Desactivar -->
                                                        <a href="<?= base_url() . 'usuarios/desactivar/' . $usuario['id']; ?>" 
                                                           class="btn btn-danger" title="Desactivar usuario" 
                                                           onclick="return confirm('¿Está seguro de desactivar este usuario?');">
                                                            <i class="ri-user-unfollow-line"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay usuarios registrados</td>
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