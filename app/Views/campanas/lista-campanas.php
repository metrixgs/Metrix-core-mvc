<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . obtener_rol() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Campañas</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Campañas</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCampana">
                                            Nueva
                                        </button>
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
                                        <th class="text-center" style="width: 40px;">#</th>
                                        <th>Nombre de Campaña</th>
                                        <th>Tipo de Campaña</th>
                                        <th>Estatus</th>
                                        <th>Área Responsable</th>
                                        <th>Coordinador(a)</th>
                                        <th>Fecha de Inicio</th>
                                        <th>Fecha de Término</th>
                                        <th class="text-center">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($campanas) && !empty($campanas)) { ?>
                                        <?php $contador = 1; ?>
                                        <?php foreach ($campanas as $index => $campana): ?>
                                            <tr>
                                                <!-- Número de fila -->
                                                <td class="text-center">
                                                    <?= $contador; ?>
                                                </td>

                                                <!-- Nombre de la campaña -->
                                                <td><?= htmlspecialchars($campana['nombre']); ?></td>

                                                <!-- Tipo de Campaña -->
                                                <td>
                                                    <?php if (!empty($campana['nombre_tipo_campana']) && !empty($campana['nombre_subtipo_campana'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_tipo_campana']); ?> / 
                                                        <?= htmlspecialchars($campana['nombre_subtipo_campana']); ?>
                                                    <?php elseif (!empty($campana['nombre_tipo_campana'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_tipo_campana']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No especificado</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Estatus -->
                                                <td>
                                                    <?php
                                                    $badgeClass = '';
                                                    switch ($campana['estado']) {
                                                        case 'Programada':
                                                            $badgeClass = 'bg-warning';
                                                            break;
                                                        case 'Activa':
                                                            $badgeClass = 'bg-success';
                                                            break;
                                                        case 'Finalizada':
                                                            $badgeClass = 'bg-info';
                                                            break;
                                                        case 'Propuesta':
                                                            $badgeClass = 'bg-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'bg-secondary';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass; ?> rounded-pill">
                                                        <?= htmlspecialchars($campana['estado']); ?>
                                                    </span>
                                                </td>

                                                <!-- Área Responsable -->
                                                <td>
                                                    <?php if (!empty($campana['nombre_area'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_area']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No especificada</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Coordinador -->
                                                <td><?= htmlspecialchars($campana['coordinador']); ?></td>

                                                <!-- Fecha de inicio -->
                                                <td><?= date('d/M/Y', strtotime($campana['fecha_inicio'])); ?></td>

                                                <!-- Fecha de fin -->
                                                <td><?= date('d/M/Y', strtotime($campana['fecha_fin'])); ?></td>

                                                <!-- Detalles -->
                                                <td class="text-center">
                                                    <a href="<?= base_url() . "campanas/detalle/{$campana['id']}"; ?>" class="btn btn-success btn-sm">
                                                        Más Información
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay campañas disponibles.</td>
                                        </tr>
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

<!-- Modal para crear una nueva campaña -->
<div class="modal fade" id="modalNuevaCampana" tabindex="-1" aria-labelledby="modalNuevaCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaCampanaLabel">Nueva Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url() . "campanas/crear"; ?>">
                    <div class="row">
                        <!-- Primera Columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ID de Campaña</label>
                                <p><?= $new_campana_id; ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de Campaña *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_id" class="form-label">Tipos de Campaña *</label>
                                <select class="form-select" id="tipo_id" name="tipo_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    <?php if (isset($tipos_campanas)) { ?>
                                        <?php foreach ($tipos_campanas as $tipo): ?>
                                            <option value="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="territorio" class="form-label">Territorio *</label>
                                <div class="custom-multiselect">
                                    <div class="selected-tags" id="territorio-tags"></div>
                                    <select class="form-select" id="territorio" name="territorio[]" multiple required style="display: none;">
                                        <option value="106">106</option>
                                        <option value="113">113</option>
                                        <option value="124">124</option>
                                    </select>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="territorioDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Seleccione
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="territorioDropdown" id="territorio-options">
                                            <li><a class="dropdown-item" href="#" data-value="106">106</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="113">113</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="124">124</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="segmentacion" class="form-label">Segmentación *</label>
                                <div class="custom-multiselect">
                                    <div class="selected-tags" id="segmentacion-tags"></div>
                                    <select class="form-select" id="segmentacion" name="segmentacion[]" multiple required style="display: none;">
                                        <?php if (isset($todas_segmentaciones)) { ?>
                                            <?php foreach ($todas_segmentaciones as $segmentacion): ?>
                                                <option value="<?= htmlspecialchars($segmentacion['id']); ?>"><?= htmlspecialchars($segmentacion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </select>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="segmentacionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Seleccione
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="segmentacionDropdown" id="segmentacion-options">
                                            <?php if (isset($todas_segmentaciones)) { ?>
                                                <?php foreach ($todas_segmentaciones as $segmentacion): ?>
                                                    <li><a class="dropdown-item" href="#" data-value="<?= htmlspecialchars($segmentacion['id']); ?>"><?= htmlspecialchars($segmentacion['descripcion']); ?></a></li>
                                                <?php endforeach; ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="universo" class="form-label">Universo Detectado</label>
                                <input type="text" class="form-control" id="universo" name="universo" value="Deportistas (144)" readonly list="universo-options">
                                <datalist id="universo-options">
                                    <option value="Deportistas (144)">
                                    <option value="Estudiantes (200)">
                                    <option value="Profesionales (300)">
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="encuesta" class="form-label">Encuesta *</label>
                                <input type="text" class="form-control" id="encuesta" name="encuesta" required>
                            </div>
                            <div class="mb-3">
                                <label for="entregables" class="form-label">Entregables *</label>
                                <input type="text" class="form-control" id="entregables" name="entregables" required>
                            </div>
                        </div>

                        <!-- Segunda Columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Área Responsable *</label>
                                <select class="form-select" id="area_id" name="area_id" required>
                                    <option value="">Seleccione un área</option>
                                    <?php if (isset($areas)) { ?>
                                        <?php foreach ($areas as $area): ?>
                                            <option value="<?= $area['id']; ?>"><?= htmlspecialchars($area['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Término *</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div id="map" style="height: 300px; border: 1px solid #ccc;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Campaña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.custom-multiselect .selected-tags {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    min-height: 38px;
    margin-bottom: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.custom-multiselect .tag {
    background-color: #f8d7da;
    color: #721c24;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.custom-multiselect .tag .remove-tag {
    cursor: pointer;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function setupMultiselect(selectId, tagsId, optionsId) {
        const select = document.getElementById(selectId);
        const tagsContainer = document.getElementById(tagsId);
        const options = document.querySelectorAll(`#${optionsId} .dropdown-item`);

        // Function to update tags
        function updateTags() {
            tagsContainer.innerHTML = '';
            Array.from(select.selectedOptions).forEach(option => {
                const tag = document.createElement('span');
                tag.className = 'tag';
                tag.innerHTML = `${option.text} <span class="remove-tag" data-value="${option.value}">x</span>`;
                tagsContainer.appendChild(tag);
            });

            // Add remove functionality
            tagsContainer.querySelectorAll('.remove-tag').forEach(button => {
                button.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const option = Array.from(select.options).find(opt => opt.value === value);
                    if (option) {
                        option.selected = false;
                        updateTags();
                    }
                });
            });
        }

        // Add click event to dropdown items
        options.forEach(option => {
            option.addEventListener('click', function (e) {
                e.preventDefault();
                const value = this.getAttribute('data-value');
                const selectOption = Array.from(select.options).find(opt => opt.value === value);
                if (selectOption) {
                    selectOption.selected = !selectOption.selected;
                    updateTags();
                }
            });
        });

        // Initialize tags
        updateTags();
    }

    // Setup for Territorio
    setupMultiselect('territorio', 'territorio-tags', 'territorio-options');

    // Setup for Segmentación
    setupMultiselect('segmentacion', 'segmentacion-tags', 'segmentacion-options');
});
</script>

<!-- Script para cargar los subtipos dependiendo del tipo seleccionado -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Obtener referencias a los elementos select
        const tipoSelect = document.getElementById('tipo_id');
        const subtipoSelect = document.getElementById('subtipo_id');

        // Función para cargar los subtipos
        function cargarSubtipos(tipoId) {
            // Limpiar las opciones actuales
            subtipoSelect.innerHTML = '<option value="">Seleccione un subtipo</option>';

            if (!tipoId)
                return;

            // Construir la URL correcta
            const url = '<?= base_url(); ?>campanas/obtener/subtipos/' + tipoId;
            console.log('Realizando petición a:', url);

            // Realizar petición AJAX para obtener los subtipos
            fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        if (data && data.length > 0) {
                            data.forEach(subtipo => {
                                const option = document.createElement('option');
                                option.value = subtipo.id;
                                option.textContent = subtipo.nombre;
                                subtipoSelect.appendChild(option);
                            });
                            console.log('Subtipos cargados correctamente');
                        } else {
                            console.log('No se encontraron subtipos para este tipo');
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar subtipos:', error);
                        // Mostrar mensaje de error en la interfaz
                        subtipoSelect.innerHTML = '<option value="">Error al cargar subtipos</option>';
                    });
        }

        // Asignar el evento change al select de tipos
        tipoSelect.addEventListener('change', function () {
            console.log('Tipo seleccionado:', this.value);
            cargarSubtipos(this.value);
        });

        // Log para verificar que el script se está ejecutando
        console.log('Script de carga de subtipos inicializado');
    });
</script>