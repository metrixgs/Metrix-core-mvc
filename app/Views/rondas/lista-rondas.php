<!-- Vista de lista de rondas (rondas/lista-rondas.php) -->
<div class="page-content">
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Lista de Rondas</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Rondas</li>
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
                            <h5 class="card-title mb-0">Gestión de Rondas</h5>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('rondas/crear') ?>" class="btn btn-success">
                                    <i class="ri-add-line align-bottom"></i> 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-rondas" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nombre de Ronda</th>
                                    <th>Segmentación</th>
                                    <th class="text-center">Estatus</th>
                                    <th>Coordinador(a)</th>
                                    <th>Encargado(a)</th>
                                    <th>Fecha de Actividad</th>
                                    <th>Horario de Actividad</th>
                                    <th class="text-center">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rondas)): ?>
                                    <?php foreach ($rondas as $key => $ronda): ?>
                                        <tr>
                                            <td class="text-center"><?= $key + 1 ?></td>
                                            <td><?= esc($ronda['nombre']) ?></td>
                                            <td>
                                                <?php if (!empty($ronda['segmentaciones'])): ?>
                                                    <?php foreach ($ronda['segmentaciones'] as $seg): ?>
                                                        <span class="badge bg-primary me-1"><?= esc($seg['codigo']) ?></span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No definido</span>
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
                                            <td><?= esc($ronda['nombre_coordinador']) ?></td>
                                            <td><?= esc($ronda['nombre_encargado']) ?></td>

                                            <td><?= date('d/m/Y', strtotime($ronda['fecha_actividad'])) ?></td>
                                            <td><?= date('H:i', strtotime($ronda['hora_actividad'])) ?></td>
                                            <td class="text-center">
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="<?= base_url('rondas/detalle/' . $ronda['id']) ?>" class="dropdown-item">
                                                                <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Ver Detalle
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= base_url('rondas/editar/' . $ronda['id']) ?>" class="dropdown-item">
                                                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Editar
                                                            </a>
                                                        </li>
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" class="dropdown-item eliminar-ronda"
                                                               data-id="<?= $ronda['id'] ?>" data-nombre="<?= esc($ronda['nombre']) ?>">
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <a href="<?= base_url('rondas/detalle/' . $ronda['id']) ?>" class="btn btn-success btn-sm">
                                                    <i class="ri-information-line align-bottom"></i> Más Información
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No hay rondas registradas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th>
                                        <select class="form-select form-select-sm">
                                            <option value="">Filtrar...</option>
                                            <option value="Programada">Programada</option>
                                            <option value="Activa">Activa</option>
                                            <option value="Finalizada">Finalizada</option>
                                            <option value="Pospuesta">Pospuesta</option>
                                        </select>
                                    </th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th>
                                        <input type="text" class="form-control form-control-sm" placeholder="Filtrar...">
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modal-eliminar-ronda" tabindex="-1" aria-labelledby="modal-eliminar-ronda-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-eliminar-ronda-label">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la ronda <strong id="nombre-ronda-eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btn-confirmar-eliminar" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        var tabla = $('#tabla-rondas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            order: [[0, 'asc']],
            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this;
                    
                    // Solo procesamos columnas que tienen filtros (no la primera ni la última)
                    if (index > 0 && index < 8) {
                        $('input, select', this.footer()).on('keyup change', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    }
                });
            }
        });
        
        // Configurar modal de eliminación
        document.querySelectorAll('.eliminar-ronda').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                
                document.getElementById('nombre-ronda-eliminar').textContent = nombre;
                document.getElementById('btn-confirmar-eliminar').setAttribute('href', '<?= base_url('rondas/eliminar/') ?>' + id);
                
                var modal = new bootstrap.Modal(document.getElementById('modal-eliminar-ronda'));
                modal.show();
            });
        });
    });
</script>