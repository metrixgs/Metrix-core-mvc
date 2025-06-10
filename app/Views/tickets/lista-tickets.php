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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Reportes</h4>
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
                            <table class="datatable display table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Área</th>
                                        <th>Cliente</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Creación</th>
                                        <th>Vencimiento</th>
                                        <th>(Horas)</th> <!-- Nuevo campo para mostrar el tiempo en horas -->
                                        <th>TDR</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tickets)) { ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><strong><?= $ticket['identificador']; ?></strong></td>
                                                <td><?= $ticket['titulo'] ?></td>
                                                <td><?= $ticket['nombre_area'] ?></td>
                                                <td><?= $ticket['nombre_cliente'] ?></td>
                                                <td style="background-color: <?= htmlspecialchars($ticket['color_sla']) ?>;"><?= $ticket['prioridad'] ?></td>
                                                <td>
                                                    <?php
                                                    // Determinamos el color del estado
                                                    $estado_clase = ($ticket['estado'] === 'Cerrado') ? 'text-danger' : 'text-success';
                                                    ?>
                                                    <span class="<?= $estado_clase; ?>"><?= $ticket['estado'] ?></span>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($ticket['fecha_creacion'])) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($ticket['fecha_vencimiento'])) ?></td>
                                                <td>
                                                    <?php
                                                    // Calculamos la diferencia entre la fecha de creación y la fecha de vencimiento en horas
                                                    $fecha_creacion = strtotime($ticket['fecha_creacion']);
                                                    $fecha_vencimiento = strtotime($ticket['fecha_vencimiento']);
                                                    $diferencia_segundos = $fecha_vencimiento - $fecha_creacion;
                                                    $diferencia_horas = round($diferencia_segundos / 3600); // Convertimos los segundos a horas
                                                    ?>
                                                    <?= $diferencia_horas ?> horas
                                                </td>
                                                <td>
                                                    <?php
                                                    $estado_sla = strtotime($ticket['fecha_vencimiento']) < time() ? 'Vencido' : 'Activo';
                                                    $clase_sla = $estado_sla === 'Vencido' ? 'text-danger' : 'text-success';
                                                    ?>
                                                    <span class="<?= $clase_sla; ?>"><?= $estado_sla; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url() . 'tickets/detalle/' . $ticket['id'] ?>" class="btn btn-info btn-sm">
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

