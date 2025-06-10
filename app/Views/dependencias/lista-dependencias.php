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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Dependencias</h4>
                                </div>
                            </div>
                            <!-- Botón para abrir el modal -->
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaDependencia">
                                            Nueva
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
                                        <th>Descripción</th>
                                        <th>Fecha Creación</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($dependencias)) { ?>
                                        <?php foreach ($dependencias as $dependencia): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= $dependencia['nombre']; ?></td>
                                                <td><?= strlen($dependencia['descripcion']) > 50 ? substr($dependencia['descripcion'], 0, 50) . '...' : $dependencia['descripcion']; ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($dependencia['fecha_creacion'])); ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . "dependencias/detalle/{$dependencia['id']}"; ?>" class="btn btn-primary">
                                                        <i class="ri ri-pencil-fill"></i>&nbsp;Detalle
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

<!-- Modal -->
<div class="modal fade" id="modalNuevaDependencia" tabindex="-1" aria-labelledby="modalNuevaDependenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaDependenciaLabel">Nueva Dependencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() . 'dependencias/crear'; ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
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




