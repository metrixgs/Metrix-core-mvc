<!-- Vista de lista de segmentaciones (segmentaciones/lista-segmentaciones.php) -->
<div class="page-content">
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-dark">Segmentaciones</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary">Inicio</a></li>
                        <li class="breadcrumb-item active text-dark">Segmentaciones</li>
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
                    <div class="row align-items-center g-3">
                        <div class="col-md-3">
                            <h5 class="card-title mb-0 text-dark">Lista de Segmentaciones</h5>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('rondas/crear_segmentacion') ?>" class="btn btn-success">
                                    <i class="ri-add-line align-bottom"></i> Nueva Segmentación
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-segmentaciones" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($segmentaciones)): ?>
                                    <?php foreach ($segmentaciones as $seg): ?>
                                        <tr>
                                            <td class="text-center"><?= $seg['id'] ?></td>
                                            <td><?= esc($seg['codigo']) ?></td>
                                            <td><?= esc($seg['descripcion']) ?></td>
                                            <td class="text-center">
                                                <span class="badge <?= $seg['estado'] == 'Activo' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= esc($seg['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="<?= base_url('rondas/por_segmentacion?segmentacion_id=' . $seg['id']) ?>" class="dropdown-item">
                                                                <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver Rondas
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= base_url('rondas/editar_segmentacion/' . $seg['id']) ?>" class="dropdown-item">
                                                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar
                                                            </a>
                                                        </li>
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" class="dropdown-item eliminar-segmentacion"
                                                               data-id="<?= $seg['id'] ?>" data-codigo="<?= esc($seg['codigo']) ?>">
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay segmentaciones registradas</td>
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modal-eliminar-segmentacion" tabindex="-1" aria-labelledby="modal-eliminar-segmentacion-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="modal-eliminar-segmentacion-label">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark">¿Estás seguro de que deseas eliminar la segmentación <strong id="codigo-segmentacion-eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btn-confirmar-eliminar" class="btn btn-danger text-white">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        $('#tabla-segmentaciones').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            order: [[0, 'asc']]
        });
        
        // Configurar modal de eliminación
        document.querySelectorAll('.eliminar-segmentacion').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var codigo = this.getAttribute('data-codigo');
                
                document.getElementById('codigo-segmentacion-eliminar').textContent = codigo;
                document.getElementById('btn-confirmar-eliminar').setAttribute('href', '<?= base_url('rondas/eliminar_segmentacion/') ?>' + id);
                
                var modal = new bootstrap.Modal(document.getElementById('modal-eliminar-segmentacion'));
                modal.show();
            });
        });
    });
</script>