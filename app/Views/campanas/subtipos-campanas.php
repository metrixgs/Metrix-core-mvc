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
                            <li class="breadcrumb-item"><a href="<?= base_url() . "campanas/tipos" ?>">Tipos de Campañas</a></li>
                            <li class="breadcrumb-item active">Subtipos de Campañas</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Subtipos de Campañas</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoSubtipoCampana">
                                            Nuevo
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
                                        <th class="text-center">#</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Campaña</th>
                                        <th>Fecha de Creación</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($subtipos_campanas) && !empty($subtipos_campanas)) { ?>
                                        <?php foreach ($subtipos_campanas as $subtipo): ?>
                                            <tr>
                                                <!-- Número de fila -->
                                                <td class="text-center"><?= $contador; ?></td>

                                                <!-- Nombre del subtipo de campaña -->
                                                <td><?= htmlspecialchars($subtipo['nombre']); ?></td>

                                                <!-- Tipo de campaña al que pertenece -->
                                                <td>
                                                    <a href="<?= base_url() . "campanas/tipos/detalle/{$subtipo['tipo_campana_id']}"; ?>" class="badge bg-primary">
                                                        <?= htmlspecialchars($subtipo['nombre_tipo_campana']); ?>
                                                    </a>
                                                </td>

                                                <!-- Fecha de creación -->
                                                <td><?= date('d/m/Y H:i:s', strtotime($subtipo['fecha_creacion'])); ?></td>

                                                <!-- Opciones responsivas -->
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        <!-- Para pantallas medianas y grandes -->
                                                        <button type="button" class="btn btn-primary d-none d-md-inline-block btn-editar-subtipo" 
                                                                data-id="<?= $subtipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>" 
                                                                data-descripcion="<?= htmlspecialchars($subtipo['descripcion']); ?>"
                                                                data-tipo-id="<?= $subtipo['tipo_campana_id']; ?>"
                                                                data-tipo-nombre="<?= htmlspecialchars($subtipo['nombre_tipo_campana']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEditarSubtipoCampana">
                                                            <i class="ri ri-pencil-fill"></i><span class="d-none d-lg-inline-block">&nbsp;Editar</span>
                                                        </button>

                                                        <button type="button" class="btn btn-danger d-none d-md-inline-block btn-eliminar-subtipo" 
                                                                data-id="<?= $subtipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>"
                                                                data-tipo-nombre="<?= htmlspecialchars($subtipo['nombre_tipo_campana']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEliminarSubtipoCampana">
                                                            <i class="ri ri-delete-bin-line"></i><span class="d-none d-lg-inline-block">&nbsp;Eliminar</span>
                                                        </button>

                                                        <!-- En pantallas pequeñas mostramos un menú desplegable -->
                                                        <div class="dropdown d-md-none">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownOptions<?= $subtipo['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownOptions<?= $subtipo['id']; ?>">
                                                                <li>
                                                                    <!-- Para dispositivos móviles -->
                                                                    <a class="dropdown-item btn-editar-subtipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $subtipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>" 
                                                                       data-descripcion="<?= htmlspecialchars($subtipo['descripcion']); ?>"
                                                                       data-tipo-id="<?= $subtipo['tipo_campana_id']; ?>"
                                                                       data-tipo-nombre="<?= htmlspecialchars($subtipo['nombre_tipo_campana']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEditarSubtipoCampana">
                                                                        <i class="ri ri-pencil-fill"></i>&nbsp;Editar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger btn-eliminar-subtipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $subtipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>"
                                                                       data-tipo-nombre="<?= htmlspecialchars($subtipo['nombre_tipo_campana']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEliminarSubtipoCampana">
                                                                        <i class="ri ri-delete-bin-line"></i>&nbsp;Eliminar
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay subtipos de campañas disponibles.</td>
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

<!-- Modal para crear un nuevo subtipo de campaña -->
<div class="modal fade" id="modalNuevoSubtipoCampana" tabindex="-1" aria-labelledby="modalNuevoSubtipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoSubtipoCampanaLabel">Nuevo Subtipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear un nuevo subtipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/subtipos/crear"; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="10" cols="10"></textarea>
                    </div>

                    <!-- En el modal de edición de subtipo -->
                    <div class="mb-3">
                        <label for="editar_tipo_campana_id" class="form-label">Tipo de Campaña</label>
                        <select class="form-select" id="editar_tipo_campana_id" name="tipo_id" required>
                            <option value="">Seleccione un tipo</option>
                            <?php if (isset($tipos_campanas)) { ?>
                                <?php foreach ($tipos_campanas as $tipo): ?>
                                    <!-- La parte más importante: Este código establece selected cuando corresponde -->
                                    <option value="<?= $tipo['id']; ?>" data-id="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                                <?php endforeach; ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar un subtipo de campaña -->
<div class="modal fade" id="modalEditarSubtipoCampana" tabindex="-1" aria-labelledby="modalEditarSubtipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarSubtipoCampanaLabel">Editar Subtipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar un subtipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/subtipos/actualizar"; ?>">
                    <input type="hidden" id="editar_id" name="subtipo_id">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editar_tipo_campana_id" class="form-label">Tipo de Campaña</label>
                        <select class="form-select" id="editar_tipo_campana_id" name="tipo_id" required>
                            <option value="">Seleccione un tipo</option>
                            <?php if (isset($tipos_campanas)) { ?>
                                <?php foreach ($tipos_campanas as $tipo): ?>
                                    <option value="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                                <?php endforeach; ?>
                            <?php } ?>
                        </select>
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
<div class="modal fade" id="modalEliminarSubtipoCampana" tabindex="-1" aria-labelledby="modalEliminarSubtipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarSubtipoCampanaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar el subtipo <strong id="nombre_subtipo_eliminar"></strong> del tipo de campaña <strong id="nombre_tipo_eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="post" action="<?= base_url() . "campanas/subtipos/eliminar"; ?>">
                    <input type="hidden" id="eliminar_id" name="id">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Código para cargar datos en el modal de edición
        var btnsEditar = document.querySelectorAll('.btn-editar-subtipo');
        btnsEditar.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                var descripcion = this.getAttribute('data-descripcion');
                var tipoId = this.getAttribute('data-tipo-id');

                console.log("ID del subtipo:", id); // Debug
                console.log("Nombre del subtipo:", nombre); // Debug
                console.log("Descripción del subtipo:", descripcion); // Debug
                console.log("ID del tipo de campaña:", tipoId); // Debug

                // Establecer los valores en el formulario
                document.getElementById('editar_id').value = id;
                document.getElementById('editar_nombre').value = nombre;
                document.getElementById('editar_descripcion').value = descripcion || '';

                // Seleccionar el tipo de campaña correcto (con retraso para asegurar carga)
                setTimeout(function () {
                    var tipoSelect = document.getElementById('editar_tipo_campana_id');

                    // Método 1: Establecer directamente el valor
                    tipoSelect.value = tipoId;

                    // Método 2 (alternativo): Recorrer las opciones
                    if (tipoSelect.value !== tipoId) {
                        console.log("El método directo falló, intentando recorrer opciones");
                        var encontrado = false;
                        for (var i = 0; i < tipoSelect.options.length; i++) {
                            if (tipoSelect.options[i].value == tipoId) {
                                tipoSelect.selectedIndex = i;
                                encontrado = true;
                                console.log("Opción encontrada y seleccionada:", i);
                                break;
                            }
                        }

                        if (!encontrado) {
                            console.log("No se encontró la opción con valor:", tipoId);
                            console.log("Opciones disponibles:", Array.from(tipoSelect.options).map(o => o.value));
                        }
                    }

                    // Verificar si se seleccionó correctamente
                    console.log("Valor seleccionado después:", tipoSelect.value);
                }, 200);
            });
        });

        // Código para cargar datos en el modal de eliminación
        var btnsEliminar = document.querySelectorAll('.btn-eliminar-subtipo');
        btnsEliminar.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                var tipoNombre = this.getAttribute('data-tipo-nombre');

                document.getElementById('eliminar_id').value = id;
                document.getElementById('nombre_subtipo_eliminar').textContent = nombre;
                document.getElementById('nombre_tipo_eliminar').textContent = tipoNombre;
            });
        });

        // Evento para mostrar el modal - esto garantiza que se active el código
        $('#modalEditarSubtipoCampana').on('shown.bs.modal', function (e) {
            console.log("Modal abierto, verificando valores:");
            console.log("ID en formulario:", document.getElementById('editar_id').value);
            console.log("Nombre en formulario:", document.getElementById('editar_nombre').value);
            console.log("Descripción en formulario:", document.getElementById('editar_descripcion').value);
            console.log("Tipo seleccionado:", document.getElementById('editar_tipo_campana_id').value);
        });

        // Evento para cuando el modal se está abriendo
        $('#modalEditarSubtipoCampana').on('show.bs.modal', function (e) {
            console.log("Modal iniciando apertura");
        });

        // Evento para cargar el select después que el DOM está listo
        setTimeout(function () {
            console.log("Verificando IDs de elementos disponibles:");
            console.log("¿Existe editar_id?", document.getElementById('editar_id') !== null);
            console.log("¿Existe editar_nombre?", document.getElementById('editar_nombre') !== null);
            console.log("¿Existe editar_descripcion?", document.getElementById('editar_descripcion') !== null);
            console.log("¿Existe editar_tipo_campana_id?", document.getElementById('editar_tipo_campana_id') !== null);

            // Verificar opciones en el select
            var tipoSelect = document.getElementById('editar_tipo_campana_id');
            if (tipoSelect) {
                console.log("Opciones en el select de tipos:", tipoSelect.options.length);
                console.log("Valores disponibles:", Array.from(tipoSelect.options).map(o => o.value));
            }
        }, 1000);
    });
</script>

<!-- Script específico para el select -->
<script>
    // Esta función se ejecutará cuando se abra el modal
    function setSelectValue(tipoId) {
        document.getElementById('editar_tipo_campana_id').value = tipoId;
    }
</script>