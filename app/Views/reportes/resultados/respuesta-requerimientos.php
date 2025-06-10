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
                            <li class="breadcrumb-item active">Reportes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>

                <div class="col-lg-12 col-md-12">
                    <!-- Alerta de cumplimiento/incumplimiento -->
                    <div id="alertCumplimiento" class="alert alert-warning alert-border-left alert-dismissible fade show material-shadow" role="alert">
                        <i class="ri-alert-line me-3 align-middle fs-16"></i><strong id="alertCumplimientoStrong"></strong>
                        <span id="alertCumplimientoText"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> 
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Cumplimiento de Requerimientos (<?= $area['nombre']; ?>)</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tabla de requerimientos -->
                        <div class="table-responsive">
                            <table class="table datatable table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Identificador</th>
                                        <th>Título</th>
                                        <th>Cliente</th>
                                        <th>Creación</th>
                                        <th>Vencimiento</th>
                                        <th class="text-center">Cumplimiento</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($requerimientos)): ?>
                                        <?php foreach ($requerimientos as $index => $req): ?>
                                            <?php
                                            $fecha_creacion = new DateTime($req['fecha_creacion']);
                                            $fecha_vencimiento = new DateTime($req['fecha_vencimiento']);
                                            $fecha_cierre = !empty($req['fecha_cierre']) ? new DateTime($req['fecha_cierre']) : null;

                                            if ($fecha_cierre) {
                                                if ($fecha_cierre <= $fecha_vencimiento) {
                                                    $cumplimiento = 'Cumplido';
                                                    $cumplimiento_class = 'success';
                                                } else {
                                                    $cumplimiento = 'Incumplido';
                                                    $cumplimiento_class = 'danger';
                                                }
                                            } else {
                                                if (new DateTime() > $fecha_vencimiento) {
                                                    $cumplimiento = 'Incumplido';
                                                    $cumplimiento_class = 'danger';
                                                } else {
                                                    $cumplimiento = 'Pendiente';
                                                    $cumplimiento_class = 'warning';
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($req['identificador']); ?></td>
                                                <td><?= htmlspecialchars($req['titulo']); ?></td>
                                                <td><?= htmlspecialchars($req['nombre_cliente']); ?></td>
                                                <td><?= htmlspecialchars($req['fecha_creacion']); ?></td>
                                                <td><?= htmlspecialchars($req['fecha_vencimiento']); ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill bg-<?= $cumplimiento_class; ?>">
                                                        <?= $cumplimiento; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-primary" href="<?= base_url() . "tickets/detalle/{$req['id']}"; ?>">
                                                        <i class="ri-eye-2-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No hay requerimientos disponibles en este rango de fechas.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Gráfica de Cumplimiento</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Gráfica de cumplimiento circular -->
                        <canvas id="graficaCumplimiento" width="400" height="400"></canvas><br>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Informe simplificado</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Grafica de cumplimiento en barras -->
                        <canvas id="graficaBarrasCumplimiento" width="400" height="400"></canvas>
                    </div>
                </div>
            </div><!--end col-->

        </div><!--end row-->
    </div>
</div>

<script>
    // Datos obtenidos desde el controlador
    const cumplidos = <?= $cumplidos; ?>;
    const incumplidos = <?= $incumplidos; ?>;
    const en_proceso = <?= $pendientes; ?>;

    // Datos para la gráfica de dona
    const dataCumplimiento = {
        labels: ['Cumplimiento', 'Incumplimiento', 'En Proceso'],
        datasets: [{
                data: [cumplidos, incumplidos, en_proceso],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'], // Colores para las categorías
                hoverBackgroundColor: ['#218838', '#c82333', '#e0a800'] // Colores al hacer hover
            }]
    };

    // Configuración de la gráfica de dona
    const configDona = {
        type: 'doughnut',
        data: dataCumplimiento,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const index = tooltipItem.dataIndex;
                            const total = dataset.data.reduce((a, b) => a + b, 0);
                            const value = dataset.data[index];
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${dataset.labels[index]}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${percentage}%`;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    // Renderizar la gráfica de dona
    const ctxDona = document.getElementById('graficaCumplimiento').getContext('2d');
    new Chart(ctxDona, configDona);

    // Datos para la gráfica de barras
    const dataBarrasCumplimiento = {
        labels: ['Cumplimiento', 'Incumplimiento', 'En Proceso'],
        datasets: [{
                label: 'Cumplimiento vs Incumplimiento vs En Proceso',
                data: [cumplidos, incumplidos, en_proceso],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'], // Colores para las categorías
                hoverBackgroundColor: ['#218838', '#c82333', '#e0a800'],
                borderColor: ['#28a745', '#dc3545', '#ffc107'],
                borderWidth: 1
            }]
    };

    // Configuración de la gráfica de barras
    const configBarras = {
        type: 'bar',
        data: dataBarrasCumplimiento,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const index = tooltipItem.dataIndex;
                            const value = dataset.data[index];
                            return `${dataset.labels[index]}: ${value}`;
                        }
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${percentage}%`;
                    },
                    color: '#000',
                    anchor: 'end',
                    align: 'top',
                    font: {
                        weight: 'bold',
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    // Renderizar la gráfica de barras
    const ctxBarras = document.getElementById('graficaBarrasCumplimiento').getContext('2d');
    new Chart(ctxBarras, configBarras);

    // Mostrar mensaje basado en el cumplimiento
    const alertCumplimiento = document.getElementById('alertCumplimiento');
    const alertCumplimientoStrong = document.getElementById('alertCumplimientoStrong');
    const alertCumplimientoText = document.getElementById('alertCumplimientoText');

    if (cumplidos >= incumplidos && cumplidos >= en_proceso) {
        alertCumplimiento.classList.remove('alert-warning', 'alert-danger');
        alertCumplimiento.classList.add('alert-success');
        alertCumplimientoStrong.innerHTML = '¡Excelente!';
        alertCumplimientoText.innerHTML = ' La mayoría de los requerimientos han sido cumplidos a tiempo.';
    } else if (incumplidos > cumplidos && incumplidos > en_proceso) {
        alertCumplimiento.classList.remove('alert-success', 'alert-warning');
        alertCumplimiento.classList.add('alert-danger');
        alertCumplimientoStrong.innerHTML = 'Alerta:';
        alertCumplimientoText.innerHTML = ' La mayoría de los requerimientos no se han cumplido dentro del tiempo estipulado.';
    } else {
        alertCumplimiento.classList.remove('alert-success', 'alert-danger');
        alertCumplimiento.classList.add('alert-warning');
        alertCumplimientoStrong.innerHTML = 'Atención:';
        alertCumplimientoText.innerHTML = ' Hay varios requerimientos que aún están en proceso.';
    }
</script>



