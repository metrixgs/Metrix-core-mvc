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
                            <li class="breadcrumb-item active">Tareas</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <?= mostrar_alerta(); ?>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Mis Tareas</h4>
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
                                        <th>Descripción</th>
                                        <th>Área</th>
                                        <th>Prioridad</th>
                                        <th>Vencimiento</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Estado TDR</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tareas)) { ?>
                                        <?php foreach ($tareas as $tarea): ?>

                                            <?php
                                            // Validar fechas antes de procesar
                                            if (!empty($tarea['fecha_creacion']) && !empty($tarea['fecha_vencimiento'])) {
                                                // Convertir fechas a timestamp
                                                $fecha_creacion = strtotime($tarea['fecha_creacion']);
                                                $fecha_vencimiento = strtotime($tarea['fecha_vencimiento']);
                                                $fecha_actual = time(); // Fecha y hora actuales
                                                // Validar que las conversiones fueron exitosas
                                                if ($fecha_creacion && $fecha_vencimiento) {
                                                    // Estado inicial de la tarea
                                                    $estadoSLA = '';

                                                    // Calcula la diferencia en horas entre la fecha actual y la fecha de vencimiento
                                                    $diff = $fecha_vencimiento - $fecha_actual;
                                                    $horas_restantes = $diff / 3600; // Diferencia en horas
                                                    // Lógica para establecer el estado basado en las fechas
                                                    if ($fecha_actual > $fecha_vencimiento) {
                                                        // Si la fecha actual es mayor que la fecha de vencimiento, la tarea está retrasada
                                                        $estadoSLA = 'Retrasada';
                                                    } elseif ($horas_restantes <= 24 && $horas_restantes > 0) {
                                                        // Si faltan 24 horas o menos para la fecha de vencimiento, está casi por vencer
                                                        $estadoSLA = 'Por vencer';
                                                    } elseif ($fecha_actual >= $fecha_creacion && $fecha_actual < $fecha_vencimiento) {
                                                        // Si la tarea aún está dentro del plazo
                                                        $estadoSLA = 'En progreso';
                                                    } else {
                                                        // Si no encaja en ninguna de las anteriores, la tarea está pendiente
                                                        $estadoSLA = 'Pendiente';
                                                    }
                                                } else {
                                                    // Si las fechas no son válidas
                                                    $estadoSLA = 'Fechas inválidas';
                                                }
                                            } else {
                                                // Si las fechas no están definidas
                                                $estadoSLA = 'Fechas no definidas';
                                            }
                                            ?>

                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= strlen($tarea['descripcion']) > 50 ? substr($tarea['descripcion'], 0, 50) . '...' : $tarea['descripcion']; ?></td>
                                                <td><?= $tarea['nombre_area']; ?></td>
                                                <td><?= $tarea['prioridad']; ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_vencimiento'])); ?></td>
                                                <td class="text-center <?= $tarea['estado'] == 'Pendiente' ? 'text-danger' : ($tarea['estado'] == 'Resuelto' ? 'text-success' : '') ?>">
                                                    <?= $tarea['estado']; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    // Lógica para determinar la clase según el estado
                                                    $estadoClase = '';

                                                    // Si el estado de la tarea es 'Resuelto', establecemos el estado SLA como 'Resuelto' y la clase correspondiente
                                                    if ($tarea['estado'] == 'Resuelto') {
                                                        $estadoSLA = 'Resuelto';
                                                        $estadoClase = 'text-success'; // Color verde
                                                    } elseif ($estadoSLA == 'Retrasada') {
                                                        $estadoClase = 'text-danger'; // Color rojo
                                                    } elseif ($estadoSLA == 'Por vencer') {
                                                        $estadoClase = 'text-warning'; // Color amarillo
                                                    } elseif ($estadoSLA == 'En progreso') {
                                                        $estadoClase = 'text-primary'; // Color azul
                                                    } elseif ($estadoSLA == 'Pendiente') {
                                                        $estadoClase = 'text-muted'; // Color gris
                                                    }
                                                    ?>

                                                    <span class="<?= $estadoClase ?>"><?= $estadoSLA ?></span>
                                                </td>

                                                <td class="text-center">
                                                    <!-- Botón Gestionar Tarea -->
                                                    <a href="<?= base_url() . "tareas/detalle/{$tarea['id']}"; ?>" class="btn btn-primary">
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




