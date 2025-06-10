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
                            <li class="breadcrumb-item active">Tareas</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
            </div>

            <div class="col-lg-6">



                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Información del Ticket</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <?php
                            // Calcular el estado del ticket basado en las fechas
                            $fecha_actual = new DateTime();
                            $fecha_creacion = new DateTime($ticket['fecha_creacion']);
                            $fecha_vencimiento = new DateTime($ticket['fecha_vencimiento']);

                            if ($fecha_actual < $fecha_creacion) {
                                $estado_calculado = 'Pendiente';
                            } elseif ($fecha_actual >= $fecha_creacion && $fecha_actual <= $fecha_vencimiento) {
                                $estado_calculado = 'En Proceso';
                            } else {
                                $estado_calculado = 'Vencido';
                            }
                            ?>

                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Requerimiento ID</h5>
                                <div class="d-flex align-items-center">
                                    <span class="text-white me-3">
                                        <a class="text-white" href="<?= base_url() . "tickets/detalle/{$ticket['id']}"; ?>"><?= $ticket['identificador']; ?></a>
                                    </span>
                                    <span class="badge <?= ($ticket['estado'] === 'Cerrado') ? 'bg-danger' : 'bg-success'; ?>">
                                        <?= $ticket['estado']; ?>
                                    </span> <!-- Aquí agregamos el estado real -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6 class="text-primary">Información General</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="cliente_id" class="form-label">Cliente</label>
                                            <input type="text" value="<?= $ticket['nombre_cliente']; ?>" class="form-control" id="cliente_id" readonly disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="area_id" class="form-label">Área</label>
                                            <input type="text" value="<?= $ticket['nombre_area']; ?>" class="form-control" id="area_id" readonly disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="usuario_id" class="form-label">Usuario</label>
                                            <input type="text" value="<?= $ticket['nombre_usuario']; ?>" class="form-control" id="usuario_id" readonly disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">  
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="titulo" class="form-label">Título</label>
                                            <input type="text" value="<?= $ticket['titulo']; ?>" class="form-control" id="titulo" readonly disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="prioridad" class="form-label">Prioridad</label>
                                            <input type="text" value="<?= $ticket['prioridad']; ?>" class="form-control" id="prioridad" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" rows="4" readonly disabled><?= $ticket['descripcion']; ?></textarea>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-primary">Estado y Fechas</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="estado" class="form-label">Estado</label>
                                            <input type="text" value="<?= $estado_calculado; ?>" class="form-control" id="estado" readonly disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecha_creacion" class="form-label">Fecha de Creación</label>
                                            <input type="text" value="<?= $ticket['fecha_creacion']; ?>" class="form-control" id="fecha_creacion" readonly disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                            <input type="text" value="<?= $ticket['fecha_vencimiento']; ?>" class="form-control" id="fecha_vencimiento" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Información de la Tarea</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($tarea['estado'] !== 'Resuelto'): ?>
                                            <!-- Botón Enviar Recordatorio con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#enviarRecordatorioModal" class="btn btn-secondary">
                                                <i class="ri-chat-1-line"></i>&nbsp;Solucionar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="ticket-id" class="form-label">Ticket</label>
                                    <input type="text" class="form-control" id="ticket-id" value="<?= htmlspecialchars($tarea['identificador_ticket'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="emisor" class="form-label">Emisor</label>
                                    <input type="text" class="form-control" id="emisor" value="<?= htmlspecialchars($tarea['nombre_emisor'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="receptor" class="form-label">Receptor</label>
                                    <input type="text" class="form-control" id="receptor" value="<?= $tarea['nombre_receptor'] ? htmlspecialchars($tarea['nombre_receptor'], ENT_QUOTES, 'UTF-8') : 'No asignado'; ?>" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="area" class="form-label">Área</label>
                                    <input type="text" class="form-control" id="area" value="<?= htmlspecialchars($tarea['nombre_area'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="prioridad" class="form-label">Prioridad</label>
                                    <input type="text" class="form-control" id="prioridad" 
                                           style="<?=
                                           $tarea['prioridad'] === 'Alta' ? 'color: #e74c3c; font-weight: bold;' :
                                                   ($tarea['prioridad'] === 'Media' ? 'color: #f1c40f; font-weight: bold;' :
                                                           ($tarea['prioridad'] === 'Baja' ? 'color: #2ecc71; font-weight: bold;' :
                                                                   'color: #d63031; font-weight: bold;'));
                                           ?>" 
                                           value="<?= htmlspecialchars($tarea['prioridad'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" rows="3" disabled><?= htmlspecialchars($tarea['descripcion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                            <?php if (!is_null($tarea['solucion'])): ?>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="solucion" class="form-label">Solución</label>
                                        <textarea class="form-control" id="solucion" rows="3" disabled><?= htmlspecialchars($tarea['solucion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <h6 class="text-primary">Estado y Fechas</h6>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" value="<?= htmlspecialchars($tarea['estado'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha-creacion" class="form-label">Fecha de Creación</label>
                                    <input type="text" class="form-control" id="fecha-creacion" value="<?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha-vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="text" class="form-control" id="fecha-vencimiento" value="<?= date('d/m/Y H:i', strtotime($tarea['fecha_vencimiento'])); ?>" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Documentos Anexos</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($tarea['estado'] !== 'Resuelto'): ?>
                                            <!-- Botón Cerrar Ticket con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#cerrarTicketModal" class="btn btn-primary">
                                                <i class="ri ri-add-line"></i>&nbsp;Documento
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="datatable display table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size: 14px;">#</th>
                                    <th style="font-size: 14px;">Documento</th>
                                    <th class="text-center" style="font-size: 14px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador = 1; ?>
                                <?php if (isset($documentos) && !empty($documentos)) { ?>
                                    <?php foreach ($documentos as $documento): ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 13px;"><?= $contador; ?></td>
                                            <td>
                                                <div>
                                                    <h6 style="margin: 0; font-size: 14px;"><?= basename($documento['archivo']); ?></h6>
                                                    <small style="font-size: 12px; color: #6c757d;">
                                                        <strong>Comentario:</strong> <?= $documento['comentario']; ?><br>
                                                        <strong>Usuario:</strong> <?= $documento['nombre_usuario']; ?><br>
                                                        <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($documento['fecha_creacion'])); ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <!-- Botón para descargar el documento -->
                                                <a href="<?= base_url($documento['archivo']); ?>" 
                                                   class="btn btn-success btn-sm" 
                                                   download>
                                                    <i class="fas fa-download"></i> Descargar
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted" style="font-size: 13px;">No hay documentos registrados.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
</div>

<!-- Modal para dar solucion a la tarea-->
<div class="modal fade" id="enviarRecordatorioModal" tabindex="-1" aria-labelledby="enviarRecordatorioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enviarRecordatorioModalLabel">Solucionar Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url() . 'tareas/solucionar'; ?>" method="POST">
                    <input type="hidden" name="tarea_id" id="tarea_id" required="" value="<?= $tarea['id']; ?>">
                    <div class="mb-3">
                        <label for="solucion" class="form-label">Solución</label>
                        <textarea placeholder="Escribe una solucion..." class="form-control" id="solucion" name="solucion" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="tarea_id" value="<?= $tarea['id']; ?>" />
                    </div>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-primary">Enviar Solución</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cerrarTicketModal" tabindex="-1" aria-labelledby="cerrarTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cerrarTicketModalLabel">Agregar Documento y Comentario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() . 'tareas/documento'; ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Campo para seleccionar archivo -->
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Seleccionar Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" 
                               accept=".jpg,.jpeg,.png,.pdf,.txt,.xlsx,.xls" required>
                    </div>
                    <!-- Campo de texto para el comentario -->
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="5" required></textarea>
                    </div>
                    <!-- Campo oculto para asociar la tarea y usuario -->
                    <input type="hidden" name="tarea_id" value="<?= $tarea['id']; ?>">
                    <input type="hidden" name="usuario_id" value="<?= session('session_data.id'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>





