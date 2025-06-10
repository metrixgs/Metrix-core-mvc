<!-- Vista de rondas por segmentación (rondas/por-segmentacion.php) -->
<div class="page-content">
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-dark">Rondas por Segmentación</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('rondas') ?>" class="text-primary">Rondas</a></li>
                        <li class="breadcrumb-item active text-dark">Por Segmentación</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-dark">Filtrar por Segmentación</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('rondas/por_segmentacion') ?>" method="get">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <select class="form-select" name="segmentacion_id">
                                    <option value="">Seleccionar Segmentación...</option>
                                    <?php foreach ($segmentaciones as $seg): ?>
                                        <option value="<?= $seg['id'] ?>" <?= (isset($_GET['segmentacion_id']) && $_GET['segmentacion_id'] == $seg['id']) ? 'selected' : '' ?>>
                                            <?= esc($seg['codigo']) ?> - <?= esc($seg['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="<?= base_url('rondas/por_segmentacion') ?>" class="btn btn-light">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($segmentacion_seleccionada)): ?>
        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-dark">
                        Rondas con segmentación: <?= esc($segmentacion_seleccionada['codigo']) ?> - <?= esc($segmentacion_seleccionada['descripcion']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-rondas" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nombre de Ronda</th>
                                    <th>Otras Segmentaciones</th>
                                    <th class="text-center">Estatus</th>
                                    <th>Coordinador(a)</th>
                                    <th>Encargado(a)</th>
                                    <th>Fecha de Actividad</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rondas)): ?>
                                    <?php foreach ($rondas as $key => $ronda): ?>
                                        <tr>
                                            <td class="text-center"><?= $key + 1 ?></td>
                                            <td><?= esc($ronda['nombre']) ?></td>
                                            <td>
                                                <?php 
                                                $otrasSegmentaciones = array_filter($ronda['segmentaciones'], function($seg) use ($segmentacion_seleccionada) {
                                                    return $seg['id'] != $segmentacion_seleccionada['id'];
                                                });
                                                if (!empty($otrasSegmentaciones)): 
                                                    foreach ($otrasSegmentaciones as $seg):
                                                ?>
                                                    <span class="badge bg-primary me-1"><?= esc($seg['codigo']) ?></span>
                                                <?php 
                                                    endforeach;
                                                else: 
                                                ?>
                                                    <span class="text-muted">Ninguna</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $estadoClase = '';
                                                switch ($ronda['estado']) {
                                                    case 'Programada':
                                                        $estadoClase = 'bg-warning';
                                                        break;
                                                    case 'Activa':
                                                        $estadoClase = 'bg-success';
                                                        break;
                                                    case 'Finalizada':
                                                        $estadoClase = 'bg-info';
                                                        break;
                                                    case 'Pospuesta':
                                                        $estadoClase = 'bg-danger';
                                                        break;
                                                    default:
                                                        $estadoClase = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?= $estadoClase ?>"><?= esc($ronda['estado']) ?></span>
                                            </td>
                                            <td><?= esc($ronda['coordinador']) ?></td>
                                            <td><?= esc($ronda['encargado']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($ronda['fecha_actividad'])) ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url('rondas/detalle/' . $ronda['id']) ?>" class="btn btn-success btn-sm">
                                                    <i class="ri-information-line align-bottom"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No se encontraron rondas con esta segmentación</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        $('#tabla-rondas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            order: [[0, 'asc']]
        });
    });
</script>