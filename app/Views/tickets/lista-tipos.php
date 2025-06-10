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
                            <li class="breadcrumb-item active">Tipos de Incidencias</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Tipos de Incidencias</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Modificación del botón para que abra el modal en lugar de redirigir -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoTipo">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Título</th>
                                        <th>fecha</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tipos_incidencias)) { ?>
                                        <?php foreach ($tipos_incidencias as $tipo): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= $tipo['titulo'] ?></td>
                                                <td><?= $tipo['fecha_creacion'] ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . 'tickets/detalle/' . $tipo['id'] ?>" class="btn btn-info btn-sm">
                                                        Eliminar
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

        <!-- Modal para crear nuevo tipo de incidencia -->
        <div class="modal fade" id="modalNuevoTipo" tabindex="-1" aria-labelledby="modalNuevoTipoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoTipoLabel">Crear Nuevo Tipo de Incidencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formNuevoTipo" action="<?= base_url() . 'tickets/crear-tipo'; ?>" method="POST">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

