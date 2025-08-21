<div class="page-content">
    <div class="container-fluid">
        <!-- Título de página y Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Rondas Vinculadas a la Campaña</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'panel'); ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'campanas'); ?>">Campañas</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url(obtener_rol() . 'campanas/detalle/' . ($campana['id'] ?? 0)); ?>">Detalle Campaña</a></li>
                            <li class="breadcrumb-item active">Rondas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información principal de la Campaña -->
        <?php if (isset($campana) && is_array($campana)): ?>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <h5 class="mb-0">Campaña: <span class="text-primary">#CAM-<?= str_pad($campana['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></span></h5>
                                <span class="badge bg-info-subtle text-info fs-14"><?= esc($campana['nombre'] ?? 'No especificado'); ?></span>
                                <?php
                                $badgeClass = 'bg-secondary';
                                $estado = $campana['estado'] ?? 'Desconocido';
                                switch ($estado) {
                                    case 'Programada': $badgeClass = 'bg-warning'; break;
                                    case 'Activa': $badgeClass = 'bg-success'; break;
                                    case 'Finalizada': $badgeClass = 'bg-info'; break;
                                    case 'Propuesta': $badgeClass = 'bg-danger'; break;
                                }
                                ?>
                                <span class="badge rounded-pill <?= $badgeClass; ?> fs-12"><?= esc($estado); ?></span>
                            </div>
                            <div class="flex-shrink-0 mt-2 mt-md-0">
                                <a href="<?= base_url(obtener_rol() . 'campanas/detalle/' . ($campana['id'] ?? 0)); ?>" class="btn btn-sm btn-secondary"><i class="ri-arrow-left-line me-1"></i> Volver a Detalle de Campaña</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabla de Rondas -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-map-pin-line me-1"></i> Lista de Rondas</h4>
                        <div class="flex-shrink-0">
                            <a href="<?= base_url('rondas/crear?campana_id=' . ($campana['id'] ?? 0)); ?>" class="btn btn-sm btn-success"><i class="ri-add-line me-1"></i> Crear Nueva Ronda</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped align-middle table-nowrap mb-0" id="rondasTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID Ronda</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Horario</th>
                                        <th scope="col">Brigada</th>
                                        <th scope="col">Encargado</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($rondas) && !empty($rondas)): ?>
                                        <?php foreach ($rondas as $ronda): ?>
                                            <tr>
                                                <td>#RDA-<?= str_pad($ronda['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></td>
                                                <td><?= esc($ronda['nombre'] ?? 'N/A'); ?></td>
                                                <td><?= esc($ronda['fecha_actividad'] ?? 'N/A'); ?></td>
                                                <td><?= esc($ronda['hora_actividad'] ?? 'N/A'); ?></td>
                                                <td><?= esc($ronda['brigada_nombre'] ?? 'N/A'); ?></td>
                                                <td><?= esc($ronda['encargado_nombre'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <?php
                                                    $estado_clase = 'bg-secondary';
                                                    switch ($ronda['estado'] ?? 'Desconocido') {
                                                        case 'Programada': $estado_clase = 'bg-warning'; break;
                                                        case 'Activa': $estado_clase = 'bg-success'; break;
                                                        case 'Finalizada': $estado_clase = 'bg-info'; break;
                                                        case 'Cancelada': $estado_clase = 'bg-danger'; break;
                                                    }
                                                    ?>
                                                    <span class="badge rounded-pill <?= $estado_clase; ?>"><?= esc($ronda['estado'] ?? 'N/A'); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="<?= base_url(obtener_rol() . 'rondas/detalle/' . ($ronda['id'] ?? '')); ?>" class="btn btn-info btn-sm" title="Ver Detalle">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="<?= base_url(obtener_rol() . 'rondas/editar/' . ($ronda['id'] ?? '')); ?>" class="btn btn-warning btn-sm" title="Editar Ronda">
                                                            <i class="ri-pencil-fill"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-sm btn-eliminar-ronda" data-id="<?= esc($ronda['id'] ?? ''); ?>" title="Eliminar Ronda">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay rondas vinculadas a esta campaña.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar la eliminación (si es necesario, o se puede manejar con SweetAlert2) -->
<div class="modal fade" id="modalEliminarRonda" tabindex="-1" aria-labelledby="modalEliminarRondaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarRondaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEliminarRonda" method="post" action="">
                <input type="hidden" name="_method" value="DELETE">
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta ronda? Esta acción es irreversible.
                    <input type="hidden" name="ronda_id_to_delete" id="ronda_id_to_delete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el clic en el botón de eliminar ronda
    document.querySelectorAll('.btn-eliminar-ronda').forEach(button => {
        button.addEventListener('click', function() {
            const rondaId = this.dataset.id;
            document.getElementById('ronda_id_to_delete').value = rondaId;
            document.getElementById('formEliminarRonda').action = '<?= base_url('rondas/eliminar/'); ?>' + rondaId;
            const deleteModal = new bootstrap.Modal(document.getElementById('modalEliminarRonda'));
            deleteModal.show();
        });
    });

    // Inicializar DataTables si es necesario (asegúrate de que la librería esté cargada)
    // if ($.fn.DataTable) {
    //     $('#rondasTable').DataTable({
    //         "language": {
    //             "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-es.json"
    //         }
    //     });
    // }
});
</script>