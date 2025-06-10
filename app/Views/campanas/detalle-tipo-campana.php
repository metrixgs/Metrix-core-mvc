<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . obtener_rol() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url() . obtener_rol() . "campanas/tipos" ?>">Tipos de Campañas</a></li>
                            <li class="breadcrumb-item active">Detalle Tipo de Campaña</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-lg-12">

                <?= mostrar_alerta(); ?>

                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Información del Tipo de Campaña</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal de eliminación -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarTipoCampana">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Formulario para editar el tipo de campaña -->
                        <form id="formEditarTipoCampana" method="post" action="<?= base_url() . "campanas/tipos/actualizar"; ?>">
                            <input type="hidden" name="id" value="<?= $tipo_campana['id']; ?>">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($tipo_campana['nombre']); ?>" required>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-3">
                                    <label for="fecha_creacion" class="form-label">Fecha de Creación</label>
                                    <input type="text" class="form-control" id="fecha_creacion" value="<?= date('d/m/Y H:i:s', strtotime($tipo_campana['fecha_creacion'])); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="6" required><?= htmlspecialchars($tipo_campana['descripcion']); ?></textarea>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Subtipos de Campaña</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear subtipo -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoSubtipo">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Fecha Creación</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($subtipos_campana) && !empty($subtipos_campana)) { ?>
                                        <?php foreach ($subtipos_campana as $subtipo): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= htmlspecialchars($subtipo['nombre']); ?></td>
                                                <td><?= htmlspecialchars($subtipo['descripcion']); ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($subtipo['fecha_creacion'])); ?></td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        <!-- Botones para pantallas medianas y grandes -->
                                                        <button type="button" class="btn btn-primary d-none d-md-inline-block btn-editar-subtipo" 
                                                                data-id="<?= $subtipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>" 
                                                                data-descripcion="<?= htmlspecialchars($subtipo['descripcion']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEditarSubtipo">
                                                            <i class="ri ri-pencil-fill"></i><span class="d-none d-lg-inline-block">&nbsp;Editar</span>
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-danger d-none d-md-inline-block btn-eliminar-subtipo" 
                                                                data-id="<?= $subtipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEliminarSubtipo">
                                                            <i class="ri ri-delete-bin-line"></i><span class="d-none d-lg-inline-block">&nbsp;Eliminar</span>
                                                        </button>
                                                        
                                                        <!-- Menú desplegable para móviles -->
                                                        <div class="dropdown d-md-none">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownOptions<?= $subtipo['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownOptions<?= $subtipo['id']; ?>">
                                                                <li>
                                                                    <a class="dropdown-item btn-editar-subtipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $subtipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>" 
                                                                       data-descripcion="<?= htmlspecialchars($subtipo['descripcion']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEditarSubtipo">
                                                                        <i class="ri ri-pencil-fill"></i>&nbsp;Editar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger btn-eliminar-subtipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $subtipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($subtipo['nombre']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEliminarSubtipo">
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
                                            <td colspan="5" class="text-center">No hay subtipos de campaña disponibles para este tipo.</td>
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

<!-- Modal para confirmar la eliminación del tipo de campaña -->
<div class="modal fade" id="modalEliminarTipoCampana" tabindex="-1" aria-labelledby="modalEliminarTipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarTipoCampanaLabel">Eliminar Tipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url() . "campanas/tipos/eliminar"; ?>">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este tipo de campaña?</p>
                    <p class="text-danger"><strong>Esta acción también eliminará todos los subtipos asociados a este tipo de campaña.</strong></p>
                    <!-- Campo oculto para enviar el ID del tipo de campaña -->
                    <input type="hidden" name="id" value="<?= $tipo_campana['id']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear un nuevo subtipo de campaña -->
<div class="modal fade" id="modalNuevoSubtipo" tabindex="-1" aria-labelledby="modalNuevoSubtipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoSubtipoLabel">Nuevo Subtipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear un nuevo subtipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/subtipos/crear"; ?>">
                    <input type="hidden" name="tipo_id" value="<?= $tipo_campana['id']; ?>">
                    <div class="mb-3">
                        <label for="nombre_subtipo" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_subtipo" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_subtipo" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_subtipo" name="descripcion" rows="4" required></textarea>
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
<div class="modal fade" id="modalEditarSubtipo" tabindex="-1" aria-labelledby="modalEditarSubtipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarSubtipoLabel">Editar Subtipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar un subtipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/subtipos/actualizar"; ?>">
                    <input type="hidden" id="editar_subtipo_id" name="subtipo_id">
                    <input type="hidden" name="tipo_id" value="<?= $tipo_campana['id']; ?>">
                    <div class="mb-3">
                        <label for="editar_nombre_subtipo" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre_subtipo" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_descripcion_subtipo" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion_subtipo" name="descripcion" rows="4" required></textarea>
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

<!-- Modal para confirmar eliminación de subtipo -->
<div class="modal fade" id="modalEliminarSubtipo" tabindex="-1" aria-labelledby="modalEliminarSubtipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarSubtipoLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar el subtipo <strong id="nombre_subtipo_eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="post" action="<?= base_url() . "campanas/subtipos/eliminar"; ?>">
                    <input type="hidden" id="eliminar_subtipo_id" name="subtipo_id">
                    <input type="hidden" name="tipo_id" value="<?= $tipo_campana['id']; ?>">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para manejar la edición y eliminación de subtipos -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Código para cargar datos en el modal de edición de subtipos
        var btnsEditarSubtipo = document.querySelectorAll('.btn-editar-subtipo');
        btnsEditarSubtipo.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                var descripcion = this.getAttribute('data-descripcion');
                
                document.getElementById('editar_subtipo_id').value = id;
                document.getElementById('editar_nombre_subtipo').value = nombre;
                document.getElementById('editar_descripcion_subtipo').value = descripcion;
            });
        });
        
        // Código para cargar datos en el modal de eliminación de subtipos
        var btnsEliminarSubtipo = document.querySelectorAll('.btn-eliminar-subtipo');
        btnsEliminarSubtipo.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                
                document.getElementById('eliminar_subtipo_id').value = id;
                document.getElementById('nombre_subtipo_eliminar').textContent = nombre;
            });
        });
    });
</script>