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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Requerimientos</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear empresa-->
                                    <div class="search-box ms-2">
                                        <a href="<?= base_url() . "tickets/nuevo"; ?>" class="btn btn-primary">Nuevo</a>
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
                                        <th>Usuario</th>
                                        <th>Fecha de creacion</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($conversaciones)) { ?>
                                        <?php foreach ($conversaciones as $conversacion): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td class="text-center"><?= $conversacion['nombre_usuario']; ?></td>
                                                <td class="text-center"><?= $conversacion['fecha_creacion']; ?></td>
                                                <td class="text-center">
                                                    <a class="btn btn-primary" href="<?= base_url() . "soporte/conversaciones/detalle/{$conversacion['id']}"; ?>">
                                                        <i class="ri-chat-voice-line"></i>&nbsp;
                                                        Detalle
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




