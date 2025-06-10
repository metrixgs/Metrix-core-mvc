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
                            <li class="breadcrumb-item active">Configuración</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Configuración TDR</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para crear nuevo TDR -->
                                    <div class="search-box ms-2">
                                        <!-- Abre el modal -->
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoSlaModal">
                                            Nuevo
                                        </button>
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
                                        <th class="text-center">#</th>
                                        <th>Título</th>
                                        <th>Tiempo (Horas)</th>
                                        <th class="text-center">Color (HEX)</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($sla_list)): ?>
                                        <?php $contador = 1; ?>
                                        <?php foreach ($sla_list as $sla): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= esc($sla['titulo']); ?></td>
                                                <td><?= esc($sla['tiempo']) . " Horas"; ?></td>
                                                <td class="text-center text-white" style="background-color: <?= htmlspecialchars($sla['color']) ?>;"><?= htmlspecialchars($sla['color']) ?></td>
                                                <td><?= esc($sla['descripcion']); ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . 'configuracion/sla/eliminar/' . $sla['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este TDR?');">Eliminar</a>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No se encontraron registros</td>
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

<!-- Modal para crear nuevo SLA -->
<div class="modal fade" id="nuevoSlaModal" tabindex="-1" aria-labelledby="nuevoSlaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoSlaModalLabel">Crear Nuevo TDR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url() . 'configuracion/sla/crear'; ?>" method="POST">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="tiempo" class="form-label">Tiempo (Horas)</label>
                        <input type="number" class="form-control" id="tiempo" name="tiempo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="color" name="color" value="#FF0000" style="height: 50px"> <!-- Color rojo por defecto -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

