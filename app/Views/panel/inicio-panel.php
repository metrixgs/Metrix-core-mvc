<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inicio</a></li>
                            <li class="breadcrumb-item active">Panel</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-xl-4 col-md-4">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3"> Reportes</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="<?= count($tickets); ?>"><?= count($tickets); ?></span></h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!--end col-->
            <div class="col-xl-4 col-md-4">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3"> Notificaciones</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="<?= count($notificaciones); ?>"><?= count($notificaciones); ?></span></h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!--end col-->
            <div class="col-xl-4 col-md-4">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3"> Tareas</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="<?= count($tareas); ?>"><?= count($tareas); ?></span></h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!--end col-->
            
            <div class="container-fluid">
                <div class="row">
                    <!-- Gráfica de Barras -->
                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0 text-white">Resumen de Reportes</h5>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="graficaRequerimientos" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Estados SLA -->
                    <div class="col-md-6 col-lg-6 mb-4">
                        <div class="row">
                            <!-- Cumplidos -->
                            <div class="col-12 col-md-12 mb-3">
                                <div class="card text-center bg-success text-white h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <h5 class="card-title">Cumplidos</h5>
                                        <p class="card-text display-4"><?= $cumplidos ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Incumplidos -->
                            <div class="col-12 col-md-12 mb-3">
                                <div class="card text-center bg-danger text-white h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <h5 class="card-title">Incumplidos</h5>
                                        <p class="card-text display-4"><?= $incumplidos ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pendientes -->
                            <div class="col-12 col-md-12 mb-3">
                                <div class="card text-center bg-warning text-dark h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <h5 class="card-title">Pendientes</h5>
                                        <p class="card-text display-4"><?= $pendientes ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                            <table class="datatable display table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Título</th>
                                        <th>Área</th>
                                        <th>Cliente</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Creación</th>
                                        <th>Vencimiento</th>
                                        <th>Tiempo (Horas)</th> <!-- Nuevo campo para mostrar el tiempo en horas -->
                                        <th>Estado SLA</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tickets)) { ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= $ticket['titulo'] ?></td>
                                                <td><?= $ticket['nombre_categoria'] ?></td>
                                                <td><?= $ticket['nombre_cliente'] ?></td>
                                                <td><?= $ticket['nombre_prioridad'] ?></td>
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

<!-- Modal para crear la empresa -->
<div class="modal fade" id="modalCrearEmpresa" tabindex="-1" aria-labelledby="modalCrearEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearEmpresaLabel">Crear Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear la empresa -->
                <form action="<?= base_url('empresas/crear'); ?>" method="POST">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la empresa</label>
                        <input type="text" name="nombre" value="<?= old('nombre'); ?>" autocomplete="off" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" id="nombre" placeholder="Nombre de la empresa" required>
                        <?php if (session('validation.nombre')): ?>
                            <div class="text-danger">
                                <?= session('validation.nombre') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="identificacion" class="form-label">Identificación</label>
                        <input type="text" name="identificacion" value="<?= old('identificacion'); ?>" autocomplete="off" class="form-control <?= session('validation.identificacion') ? 'is-invalid' : '' ?>" id="identificacion" placeholder="Identificación" required>
                        <?php if (session('validation.identificacion')): ?>
                            <div class="text-danger">
                                <?= session('validation.identificacion') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" name="correo" value="<?= old('correo'); ?>" autocomplete="off" class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" id="correo" placeholder="Correo electrónico" required>
                        <?php if (session('validation.correo')): ?>
                            <div class="text-danger">
                                <?= session('validation.correo') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="<?= old('telefono'); ?>" autocomplete="off" class="form-control <?= session('validation.telefono') ? 'is-invalid' : '' ?>" id="telefono" placeholder="Número de teléfono" required>
                        <?php if (session('validation.telefono')): ?>
                            <div class="text-danger">
                                <?= session('validation.telefono') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="responsable" class="form-label">Responsable</label>
                        <input type="text" name="responsable" value="<?= old('responsable'); ?>" autocomplete="off" class="form-control <?= session('validation.responsable') ? 'is-invalid' : '' ?>" id="responsable" placeholder="Responsable de la empresa" required>
                        <?php if (session('validation.responsable')): ?>
                            <div class="text-danger">
                                <?= session('validation.responsable') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="<?= old('direccion'); ?>" autocomplete="off" class="form-control <?= session('validation.direccion') ? 'is-invalid' : '' ?>" id="direccion" placeholder="Dirección" required>
                        <?php if (session('validation.direccion')): ?>
                            <div class="text-danger">
                                <?= session('validation.direccion') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" class="form-select <?= session('validation.estado') ? 'is-invalid' : '' ?>" id="estado" required>
                            <option value="1" <?= old('estado') == 1 ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= old('estado') == 0 ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                        <?php if (session('validation.estado')): ?>
                            <div class="text-danger">
                                <?= session('validation.estado') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para la gráfica -->
<script>
    // Datos pasados desde el controlador
    const cumplidos = <?= $cumplidos ?>;
    const incumplidos = <?= $incumplidos ?>;
    const pendientes = <?= $pendientes ?>;

    // Configuración de la gráfica
    const ctx = document.getElementById('graficaRequerimientos').getContext('2d');
    const graficaRequerimientos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Cumplidos', 'Incumplidos', 'Pendientes'],
            datasets: [{
                    label: 'Cantidad de Reportes',
                    data: [cumplidos, incumplidos, pendientes],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)', // Verde para cumplidos
                        'rgba(255, 99, 132, 0.6)', // Rojo para incumplidos
                        'rgba(255, 206, 86, 0.6)'  // Amarillo para pendientes
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>