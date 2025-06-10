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
                            <li class="breadcrumb-item active">Tipos de Campañas</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Tipos de Campañas</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoTipoCampana">
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
                                        <th>Descripción</th>
                                        <th>Fecha de Creación</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tipos_campanas) && !empty($tipos_campanas)) { ?>
                                        <?php foreach ($tipos_campanas as $tipo): ?>
                                            <tr>
                                                <!-- Número de fila -->
                                                <td class="text-center"><?= $contador; ?></td>

                                                <!-- Nombre del tipo de campaña -->
                                                <td><?= htmlspecialchars($tipo['nombre']); ?></td>

                                                <!-- Descripción del tipo de campaña -->
                                                <td><?= htmlspecialchars($tipo['descripcion']); ?></td>

                                                <!-- Fecha de creación -->
                                                <td><?= date('d/m/Y H:i:s', strtotime($tipo['fecha_creacion'])); ?></td>

                                                <!-- Opciones responsivas -->
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        <!-- En pantallas grandes mostramos todos los botones -->
                                                        <a class="btn btn-primary d-none d-md-inline-block" href="<?= base_url() . "campanas/tipos/detalle/{$tipo['id']}"; ?>">
                                                            <i class="ri ri-eye-fill"></i><span class="d-none d-lg-inline-block">&nbsp;Detalle</span>
                                                        </a>

                                                        <button type="button" class="btn btn-primary d-none d-md-inline-block btn-editar-tipo" 
                                                                data-id="<?= $tipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($tipo['nombre']); ?>" 
                                                                data-descripcion="<?= htmlspecialchars($tipo['descripcion']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEditarTipoCampana">
                                                            <i class="ri ri-pencil-fill"></i><span class="d-none d-lg-inline-block">&nbsp;Editar</span>
                                                        </button>

                                                        <button type="button" class="btn btn-danger d-none d-md-inline-block btn-eliminar-tipo" 
                                                                data-id="<?= $tipo['id']; ?>" 
                                                                data-nombre="<?= htmlspecialchars($tipo['nombre']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEliminarTipoCampana">
                                                            <i class="ri ri-delete-bin-line"></i><span class="d-none d-lg-inline-block">&nbsp;Eliminar</span>
                                                        </button>

                                                        <!-- En pantallas pequeñas mostramos un menú desplegable -->
                                                        <div class="dropdown d-md-none">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownOptions<?= $tipo['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownOptions<?= $tipo['id']; ?>">
                                                                <li>
                                                                    <a class="dropdown-item" href="<?= base_url() . "campanas/tipos/detalle/{$tipo['id']}"; ?>">
                                                                        <i class="ri ri-eye-fill"></i>&nbsp;Detalle
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item btn-editar-tipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $tipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($tipo['nombre']); ?>" 
                                                                       data-descripcion="<?= htmlspecialchars($tipo['descripcion']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEditarTipoCampana">
                                                                        <i class="ri ri-pencil-fill"></i>&nbsp;Editar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger btn-eliminar-tipo"
                                                                       href="javascript:void(0);"
                                                                       data-id="<?= $tipo['id']; ?>" 
                                                                       data-nombre="<?= htmlspecialchars($tipo['nombre']); ?>"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#modalEliminarTipoCampana">
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
                                            <td colspan="6" class="text-center">No hay tipos de campañas disponibles.</td>
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

<!-- Modal para crear un nuevo tipo de campaña -->
<div class="modal fade" id="modalNuevoTipoCampana" tabindex="-1" aria-labelledby="modalNuevoTipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoTipoCampanaLabel">Nuevo Tipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear un nuevo tipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/tipos/crear"; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
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

<!-- Modal para editar un tipo de campaña -->
<div class="modal fade" id="modalEditarTipoCampana" tabindex="-1" aria-labelledby="modalEditarTipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarTipoCampanaLabel">Editar Tipo de Campaña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar un tipo de campaña -->
                <form method="post" action="<?= base_url() . "campanas/tipos/actualizar"; ?>">
                    <input type="hidden" id="editar_id" name="id">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="5" required></textarea>
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
<div class="modal fade" id="modalEliminarTipoCampana" tabindex="-1" aria-labelledby="modalEliminarTipoCampanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarTipoCampanaLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar el tipo de campaña <strong id="nombre_tipo_eliminar"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="post" action="<?= base_url() . "campanas/tipos/eliminar"; ?>">
                    <input type="hidden" id="eliminar_id" name="id">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para manejar la edición y eliminación de tipos de campañas -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Código para cargar datos en el modal de edición
        var btnsEditar = document.querySelectorAll('.btn-editar-tipo');
        btnsEditar.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');
                var descripcion = this.getAttribute('data-descripcion');

                document.getElementById('editar_id').value = id;
                document.getElementById('editar_nombre').value = nombre;
                document.getElementById('editar_descripcion').value = descripcion;
            });
        });

        // Código para cargar datos en el modal de eliminación
        var btnsEliminar = document.querySelectorAll('.btn-eliminar-tipo');
        btnsEliminar.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');

                document.getElementById('eliminar_id').value = id;
                document.getElementById('nombre_tipo_eliminar').textContent = nombre;
            });
        });
    });
</script>