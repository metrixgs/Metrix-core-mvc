<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Rondas Vinculadas a la Campaña #CAM-<?= str_pad($campana['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></h4>
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
        <!-- End page title -->

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h6>Lista de Rondas</h6>
                    <a href="<?= base_url('rondas/crear?campana_id=' . ($campana['id'] ?? 0)); ?>" class="btn btn-sm btn-success">Crear Nueva Ronda</a>
                </div>
                <div class="table-responsive">
                    <table class="datatable display table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>ID Ronda</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Brigada</th>
                                <th>Enlace</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="rondasTableBody">
                            <?php if (isset($rondas) && !empty($rondas)): ?>
                                <?php foreach ($rondas as $ronda): ?>
                                    <tr>
                                        <td>#RDA-<?= str_pad($ronda['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?></td>
                                        <td><?= esc($ronda['fecha_actividad'] ?? 'N/A'); ?></td>
                                        <td><?= esc($ronda['hora_actividad'] ?? 'N/A'); ?></td>
                                        <td><?= esc($ronda['brigada_nombre'] ?? 'N/A'); ?></td>
                                        <td><?= esc($ronda['encargado'] ?? 'N/A'); ?></td>
                                        <td><span class="<?= ($ronda['estado'] ?? '') === 'Cerrada' ? 'text-danger' : 'text-success'; ?>"><?= esc($ronda['estado'] ?? 'N/A'); ?></span></td>
                                        <td class="text-center">
                                            <a href="<?= base_url(obtener_rol() . 'rondas/detalle/' . ($ronda['id'] ?? '')); ?>" class="btn btn-info btn-sm" title="Ver">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="<?= base_url(obtener_rol() . 'rondas/editar/' . ($ronda['id'] ?? '')); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-eliminar-ronda" data-id="<?= esc($ronda['id'] ?? ''); ?>" title="Eliminar">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay rondas vinculadas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url(obtener_rol() . 'campanas/detalle/' . ($campana['id'] ?? 0)); ?>" class="btn btn-secondary">Volver a Detalle de Campaña</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar-ronda')) {
            const rondaId = e.target.closest('.btn-eliminar-ronda').dataset.id;
            if (confirm('¿Estás seguro de que deseas eliminar esta ronda?')) {
                fetch('<?= base_url('rondas/eliminar/'); ?>' + rondaId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al eliminar ronda: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Ronda eliminada correctamente.');
                        location.reload(); // Recargar la página para actualizar la tabla
                    } else {
                        alert('Error al eliminar la ronda: ' + (data.error || 'Desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar ronda:', error);
                    alert('Error al eliminar la ronda.');
                });
            }
        }
    });
});
</script>