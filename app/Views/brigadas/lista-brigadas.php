<?php
$session = session();
$flashdata = $session->getFlashdata();
if ($flashdata && isset($flashdata['titulo'], $flashdata['mensaje'], $flashdata['tipo'])): ?>
    <div class="alert alert-<?= esc($flashdata['tipo']); ?>">
        <strong><?= esc($flashdata['titulo']); ?></strong> <?= esc($flashdata['mensaje']); ?>
    </div>
<?php endif; ?>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= esc($titulo_pagina); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('panel'); ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Brigadas</li>
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
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <h4 class="card-title mb-0 flex-grow-1">Lista de Brigadas</h4>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaBrigada">
                                        Nueva Brigada
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="brigadasTable" class="table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Área</th>
                                        <th>Coordinador</th>
                                        <th>Enlace</th>
                                        <th>Integrantes</th>
                                        <th>Fecha de Creación</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($brigadas)): ?>
                                        <?php foreach ($brigadas as $brigada): ?>
                                            <tr>
                                                <td><?= esc($brigada['nombre']); ?></td>
                                                <td><?= esc($brigada['area_nombre'] ?: 'Sin área'); ?></td>
                                                <td><?= esc($brigada['coordinador_nombre'] ?: 'Sin coordinador'); ?></td>
                                                <td><?= esc($brigada['enlace_nombre']); ?></td>
                                                <td><?= esc($brigada['integrantes'] ?: 'Sin integrantes'); ?></td>
                                                <td><?= date('d/m/Y H:i:s', strtotime($brigada['created_at'])); ?></td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        <a class="btn btn-primary d-none d-md-inline-block" href="<?= base_url("brigadas/detalle/{$brigada['id']}"); ?>">
                                                            <i class="ri ri-eye-fill"></i><span class="d-none d-lg-inline-block"> Detalle</span>
                                                        </a>
                                                        <button type="button" class="btn btn-primary d-none d-md-inline-block btn-editar-brigada"
                                                                data-id="<?= $brigada['id']; ?>"
                                                                data-area-id="<?= $brigada['area_id']; ?>"
                                                                data-coordinador-id="<?= $brigada['coordinador_id']; ?>"
                                                                data-enlace-id="<?= $brigada['enlace_id']; ?>"
                                                                data-integrantes='<?= esc(json_encode($brigada['integrante_ids'] ?? [])); ?>'
                                                                data-integrantes-count="<?= $brigada['integrantes_count']; ?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditarBrigada">
                                                            <i class="ri ri-pencil-fill"></i><span class="d-none d-lg-inline-block"> Editar</span>
                                                        </button>
                                                        <button type="button" class="btn btn-danger d-none d-md-inline-block btn-eliminar-brigada"
                                                                data-id="<?= $brigada['id']; ?>"
                                                                data-nombre="<?= esc($brigada['nombre']); ?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEliminarBrigada">
                                                            <i class="ri ri-delete-bin-line"></i><span class="d-none d-lg-inline-block"> Eliminar</span>
                                                        </button>
                                                        <div class="dropdown d-md-none">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownOptions<?= $brigada['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownOptions<?= $brigada['id']; ?>">
                                                                <li><a class="dropdown-item" href="<?= base_url("brigadas/detalle/{$brigada['id']}"); ?>">
                                                                    <i class="ri ri-eye-fill"></i> Detalle</a></li>
                                                                <li><a class="dropdown-item btn-editar-brigada"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $brigada['id']; ?>"
                                                                       data-area-id="<?= $brigada['area_id']; ?>"
                                                                       data-coordinador-id="<?= $brigada['coordinador_id']; ?>"
                                                                       data-enlace-id="<?= $brigada['enlace_id']; ?>"
                                                                       data-integrantes='<?= esc(json_encode($brigada['integrante_ids'] ?? [])); ?>'
                                                                       data-integrantes-count="<?= $brigada['integrantes_count']; ?>"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#modalEditarBrigada">
                                                                    <i class="ri ri-pencil-fill"></i> Editar</a></li>
                                                                <li><a class="dropdown-item text-danger btn-eliminar-brigada"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $brigada['id']; ?>"
                                                                       data-nombre="<?= esc($brigada['nombre']); ?>"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#modalEliminarBrigada">
                                                                    <i class="ri ri-delete-bin-line"></i> Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No hay brigadas disponibles.</td>
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

<!-- Modal para crear una nueva brigada -->
<div class="modal fade" id="modalNuevaBrigada" tabindex="-1" aria-labelledby="modalNuevaBrigadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaBrigadaLabel">Nueva Brigada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url('brigadas/crear'); ?>" id="formNuevaBrigada">
                    <div class="mb-3 position-relative">
                        <label for="area_id" class="form-label">Área</label>
                        <select class="form-control" id="area_id" name="area_id" required>
                            
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= esc($area['id']); ?>"><?= esc($area['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-check-circle-fill text-success position-absolute end-0 top-50 translate-middle-y me-3 d-none" id="area_check"></i>
                        <div class="invalid-feedback">Por favor, seleccione un área.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="coordinador" class="form-label">Coordinador</label>
                        <select class="form-control" id="coordinador" name="coordinador_id" required>
                            
                            <?php foreach ($coordinadores as $coordinador): ?>
                                <option value="<?= esc($coordinador['id']); ?>"><?= esc($coordinador['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-check-circle-fill text-success position-absolute end-0 top-50 translate-middle-y me-3 d-none" id="coordinador_check"></i>
                        <div class="invalid-feedback">Por favor, seleccione un coordinador.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="enlace" class="form-label">Enlace (Líder)</label>
                        <select class="form-control" id="enlace" name="enlace_id" required>
                            
                            <?php foreach ($coordinadores as $coordinador): ?>
                                <option value="<?= esc($coordinador['id']); ?>"><?= esc($coordinador['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-check-circle-fill text-success position-absolute end-0 top-50 translate-middle-y me-3 d-none" id="enlace_check"></i>
                        <div class="invalid-feedback">Por favor, seleccione un enlace.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="integrantes" class="form-label">Integrantes</label>
                        <select class="form-control select2" id="integrantes" name="integrantes[]" multiple>
                            <?php foreach ($operativos as $operativo): ?>
                                <option value="<?= esc($operativo['id']); ?>"><?= esc($operativo['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-check-circle-fill text-success position-absolute end-0 top-50 translate-middle-y me-3 d-none" id="integrantes_check"></i>
                        <small class="form-text text-muted">Seleccione múltiples integrantes.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar una brigada -->
<div class="modal fade" id="modalEditarBrigada" tabindex="-1" aria-labelledby="modalEditarBrigadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarBrigadaLabel">Editar Brigada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url('brigadas/actualizar'); ?>">
                    <input type="hidden" id="editar_id" name="id">
                    <div class="mb-3">
                        <label for="editar_area_id" class="form-label">Área</label>
                        <select class="form-control" id="editar_area_id" name="area_id" required>
                            
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= esc($area['id']); ?>"><?= esc($area['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editar_coordinador" class="form-label">Coordinador</label>
                        <select class="form-control" id="editar_coordinador" name="coordinador_id" required>
                            
                            <?php foreach ($coordinadores as $coordinador): ?>
                                <option value="<?= esc($coordinador['id']); ?>"><?= esc($coordinador['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editar_enlace" class="form-label">Enlace (Líder)</label>
                        <select class="form-control" id="editar_enlace" name="enlace_id" required>
                            
                            <?php foreach ($coordinadores as $coordinador): ?>
                                <option value="<?= esc($coordinador['id']); ?>"><?= esc($coordinador['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editar_integrantes" class="form-label">Integrantes <span id="integrantes_count" class="badge bg-primary ms-2"></span> <button type="button" id="agregar_integrantes" class="btn btn-primary d-none d-md-inline-block ms-2">+</button></label>
                        <select class="form-control select2" id="editar_integrantes" name="integrantes[]" multiple style="width: 100%;">
                            <?php foreach ($operativos as $operativo): ?>
                                <option value="<?= esc($operativo['id']); ?>"><?= esc($operativo['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="integrantes_list" class="mt-2"></div>
                        <small class="form-text text-muted">Seleccione múltiples integrantes.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="modalEliminarBrigada" tabindex="-1" aria-labelledby="modalEliminarBrigadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarBrigadaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar la brigada <strong id="nombre_brigada_eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="post" action="<?= base_url('brigadas/eliminar'); ?>">
                    <input type="hidden" id="eliminar_id" name="id">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluye Select2 CSS y JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Incluye Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Incluye DataTables CSS y JS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Estilos personalizados -->
<style>
    .select2-container {
        display: block !important;
        width: 100% !important;
    }
    .select2-container .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container .select2-selection--multiple .select2-selection__rendered {
        padding: 0.375rem 0.75rem;
    }
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        margin: 0.2rem;
    }
    #integrantes_list .badge {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }
    #integrantes_list .btn-danger {
        padding: 0 0.5em;
        line-height: 1.2;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
</style>

<!-- JavaScript para manejar la tabla, edición, eliminación y validación de brigadas -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar DataTables con paginación y búsqueda
    $('#brigadasTable').DataTable({
        paging: true,
        pageLength: 10,
        searching: true,
        ordering: true,
        info: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        columnDefs: [
            { orderable: false, targets: 6 } // Deshabilitar ordenación en la columna de opciones
        ]
    });

    // Inicializar Select2 en los selects
    $('#integrantes').select2({
        placeholder: 'Busque y seleccione integrantes',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modalNuevaBrigada')
    });

    $('#editar_integrantes').select2({
        placeholder: 'Busque y seleccione integrantes',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modalEditarBrigada')
    });

    // Validación y checkmarks para el formulario de nueva brigada
    const formNuevaBrigada = document.getElementById('formNuevaBrigada');
    const areaSelect = document.getElementById('area_id');
    const coordinadorSelect = document.getElementById('coordinador');
    const enlaceSelect = document.getElementById('enlace');
    const integrantesSelect = document.getElementById('integrantes');

    function updateCheckmark(select, checkId) {
        const checkIcon = document.getElementById(checkId);
        if (select.value || (select.multiple && select.selectedOptions.length > 0)) {
            checkIcon.classList.remove('d-none');
            select.classList.remove('is-invalid');
        } else {
            checkIcon.classList.add('d-none');
        }
    }

    areaSelect.addEventListener('change', () => updateCheckmark(areaSelect, 'area_check'));
    coordinadorSelect.addEventListener('change', () => updateCheckmark(coordinadorSelect, 'coordinador_check'));
    enlaceSelect.addEventListener('change', () => updateCheckmark(enlaceSelect, 'enlace_check'));
    integrantesSelect.addEventListener('change', () => updateCheckmark(integrantesSelect, 'integrantes_check'));

    formNuevaBrigada.addEventListener('submit', function (e) {
        let isValid = true;
        [areaSelect, coordinadorSelect, enlaceSelect].forEach(select => {
            if (!select.value) {
                select.classList.add('is-invalid');
                isValid = false;
            } else {
                select.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Manejar edición de brigadas
    var btnsEditar = document.querySelectorAll('.btn-editar-brigada');
    btnsEditar.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var areaId = this.getAttribute('data-area-id');
            var coordinadorId = this.getAttribute('data-coordinador-id');
            var enlaceId = this.getAttribute('data-enlace-id');
            var integrantes = [];
            try {
                integrantes = JSON.parse(this.getAttribute('data-integrantes') || '[]');
                console.log('Integrantes para brigada ID ' + id + ':', integrantes);
            } catch (e) {
                console.error('Error parsing data-integrantes:', e);
            }
            var integrantesCount = parseInt(this.getAttribute('data-integrantes-count') || '0');

            // Setear valores en el modal
            document.getElementById('editar_id').value = id;
            document.getElementById('editar_area_id').value = areaId;
            document.getElementById('editar_coordinador').value = coordinadorId;
            document.getElementById('editar_enlace').value = enlaceId;
            document.getElementById('integrantes_count').textContent = integrantesCount;

            // Actualizar select de integrantes
            var selectIntegrantes = document.getElementById('editar_integrantes');
            Array.from(selectIntegrantes.options).forEach(function (option) {
                option.selected = integrantes.includes(Number(option.value));
            });
            $(selectIntegrantes).val(integrantes).trigger('change');

            // Mostrar lista de nombres de integrantes
            var integrantesList = document.getElementById('integrantes_list');
            integrantesList.innerHTML = '';
            Array.from(selectIntegrantes.options).forEach(function (option) {
                if (integrantes.includes(Number(option.value))) {
                    var div = document.createElement('div');
                    div.className = 'badge bg-secondary me-1 mb-1';
                    div.textContent = option.text;
                    var removeBtn = document.createElement('button');
                    removeBtn.className = 'btn btn-sm btn-danger ms-1';
                    removeBtn.textContent = 'x';
                    removeBtn.onclick = function () {
                        option.selected = false;
                        $(selectIntegrantes).trigger('change');
                        div.remove();
                        var newCount = Array.from(selectIntegrantes.selectedOptions).length;
                        document.getElementById('integrantes_count').textContent = newCount;
                    };
                    div.appendChild(removeBtn);
                    integrantesList.appendChild(div);
                }
            });
        });
    });

    // Manejar el botón de agregar integrantes
    document.getElementById('agregar_integrantes').addEventListener('click', function () {
        $('#editar_integrantes').select2('open');
    });

    // Actualizar lista de nombres cuando cambie el select
    $('#editar_integrantes').on('change', function () {
        var selectIntegrantes = document.getElementById('editar_integrantes');
        var integrantesList = document.getElementById('integrantes_list');
        integrantesList.innerHTML = '';
        var selectedOptions = Array.from(selectIntegrantes.selectedOptions);
        selectedOptions.forEach(function (option) {
            var div = document.createElement('div');
            div.className = 'badge bg-secondary me-1 mb-1';
            div.textContent = option.text;
            var removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-danger ms-1';
            removeBtn.textContent = 'x';
            removeBtn.onclick = function () {
                option.selected = false;
                $(selectIntegrantes).trigger('change');
                div.remove();
                var count = Array.from(selectIntegrantes.selectedOptions).length;
                document.getElementById('integrantes_count').textContent = count;
            };
            div.appendChild(removeBtn);
            integrantesList.appendChild(div);
        });
        document.getElementById('integrantes_count').textContent = selectedOptions.length;
    });

    // Manejar eliminación de brigadas
    var btnsEliminar = document.querySelectorAll('.btn-eliminar-brigada');
    btnsEliminar.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var nombre = this.getAttribute('data-nombre');

            document.getElementById('eliminar_id').value = id;
            document.getElementById('nombre_brigada_eliminar').textContent = nombre;
        });
    });

    // Forzar la visibilidad del select2 al abrir los modales
    $('#modalNuevaBrigada').on('shown.bs.modal', function () {
        $('#integrantes').select2('destroy').select2({
            placeholder: 'Busque y seleccione integrantes',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalNuevaBrigada')
        });
    });

    $('#modalEditarBrigada').on('shown.bs.modal', function () {
        $('#editar_integrantes').select2('destroy').select2({
            placeholder: 'Busque y seleccione integrantes',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalEditarBrigada')
        });
    });
});
</script>