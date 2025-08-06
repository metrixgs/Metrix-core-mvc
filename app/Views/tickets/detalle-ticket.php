<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Tickets</li>
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

            <div class="col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Información de la Incidencia</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($ticket['estado'] !== 'Cerrado'): ?>
                                            <!-- Botón Cerrar Ticket con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#cerrarTicketModal" 
                                               onclick="setActionUrl('cerrar', '<?= base_url() . 'tickets/cerrar/' . $ticket['id']; ?>')" class="btn btn-primary">
                                                <i class="ri ri-close-line"></i>&nbsp;Cerrar
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($ticket['estado'] !== 'Cerrado'): ?>
                                            <!-- Botón Enviar Recordatorio con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#enviarRecordatorioModal" 
                                               onclick="setActionUrl('recordatorio', '<?= base_url() . 'tickets/enviar-recordatorio/' . $ticket['id']; ?>')" class="btn btn-dark">
                                                <i class="ri ri-add-line"></i>&nbsp;Recordatorio
                                            </a>
                                        <?php endif; ?>
                                    </div>
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

                            <div class="card">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-white">Requerimiento ID</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="text-white me-3"><?= $ticket['identificador']; ?></span>
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
                                                <label for="cliente_id" class="form-label">¿Quién reporta?</label>
                                                <input type="text" value="<?= $ticket['nombre_cliente']; ?>" class="form-control" id="cliente_id" readonly disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="area_id" class="form-label">Oficina</label>
                                                <input type="text" value="<?= $ticket['nombre_area']; ?>" class="form-control" id="area_id" readonly disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="usuario_id" class="form-label">Creado por</label>
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
            </div>

            <div class="col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Archivos Adjuntos</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($ticket['estado'] !== 'Cerrado'): ?>
                                            <!-- Botón Subir Archivo con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#subirArchivoModal" 
                                               onclick="setActionUrl('subir', '<?= base_url() . 'tickets/subir-archivo/' . $ticket['id']; ?>')" class="btn btn-primary">
                                                <i class="ri-upload-2-line me-2"></i> Archivo
                                            </a>
                                        <?php endif; ?>
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
                                        <th>Descripción</th>
                                        <th>Tipo</th>
                                        <th>Usuario</th>
                                        <th>Fecha de Subida</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($archivos)) { ?>
                                        <?php foreach ($archivos as $archivo): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= $archivo['descripcion'] ?></td>
                                                <td><?= $archivo['tipo_mime']; ?></td>
                                                <td><?= $archivo['nombre_usuario']; ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($archivo['fecha_subida'])) ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalArchivo<?= $archivo['id'] ?>">
                                                        <i class="ri ri-eye-line"></i> 
                                                    </button>
                                                </td>
                                            </tr>

                                        <div class="modal fade" id="modalArchivo<?= $archivo['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalArchivoLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalArchivoLabel">Archivo Adjunto</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                  <?php
// Mejora para obtener la extensión de una URL de Wasabi S3
function getFileExtensionFromUrl($url) {
    // Eliminar los parámetros de consulta de la URL
    $urlPath = strtok($url, '?');
    // Obtener la extensión del archivo
    return strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
}

// Usar en tu código:
$extension = getFileExtensionFromUrl($archivo['ruta']);
$rutaArchivo = $archivo['ruta']; // La URL completa de Wasabi S3

// Vista previa según el tipo de archivo
if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
    echo "<img src='$rutaArchivo' width='100%' height='400px' />";
} elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
    echo "<video src='$rutaArchivo' width='100%' height='400px' controls></video>";
} elseif ($extension === 'pdf') {
    echo "<embed src='$rutaArchivo' width='100%' height='400px' type='application/pdf'>";
} elseif (in_array($extension, ['xls', 'xlsx', 'doc', 'docx', 'txt'])) {
    $googleViewer = 'https://docs.google.com/viewer?embedded=true&url=' . urlencode($rutaArchivo);
    echo "<iframe src='$googleViewer' width='100%' height='500px'></iframe>";
} else {
    echo "Este formato no es compatible para vista previa.";
}
                                                        ?>
                                                        <br><br>
                                                        <!-- Opción de descarga para todos los archivos -->
                                                        <a href="<?= $rutaArchivo ?>" class="btn btn-primary" download>Descargar archivo</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Ubicacion del Reporte</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($ticket['estado'] !== 'Cerrado' && $ticket['area_id'] == session('session_data.area_id')): ?>
                                            <!-- Botón Enviar Comentario -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#comentarioModal" 
                                               onclick="setActionUrl('comentario', '<?= base_url() . 'tickets/crear-comentario/' . $ticket['id']; ?>')" 
                                               class="btn btn-secondary">
                                                <i class="ri ri-add-line"></i>&nbsp;Comentario
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mapa -->
                        <div class="col-md-12">
                            <div id="map" style="height: 400px; width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Usuarios</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>WhatsApp</th>
                                        <th>Fecha de Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($usuarios)) : ?>
                                        <?php foreach ($usuarios as $index => $usuario) : ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= $usuario['nombre']; ?></td>
                                                <td><?= $usuario['correo'] ?? '---------'; ?></td>
                                                <td><?= $usuario['telefono'] ?? '---------'; ?></td>
                                                <td><?= $usuario['fecha_registro']; ?></td>
                                                <td class="text-center">
                                                    <!-- Botones agrupados -->
                                                    <div class="btn-group" role="group">
                                                        <!-- Detalle -->
                                                        <a href="<?= base_url() . 'usuarios/detalle/' . $usuario['id']; ?>" 
                                                           class="btn btn-primary me-2" title="Ver detalles">
                                                            <i class="ri-eye-line"></i>
                                                        </a>

                                                        <!-- Correo -->
                                                        <?php if (!empty($usuario['correo'])) : ?>
                                                            <a href="mailto:<?= $usuario['correo']; ?>" 
                                                               class="btn btn-secondary me-2" title="Enviar correo">
                                                                <i class="ri-mail-line"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <!-- WhatsApp -->
                                                        <?php if (!empty($usuario['telefono'])) : ?>
                                                            <a href="https://wa.me/52<?= preg_replace('/\D/', '', $usuario['telefono']); ?>" 
                                                               class="btn btn-success" target="_blank" title="Enviar mensaje por WhatsApp">
                                                                <i class="ri-whatsapp-line"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay usuarios registrados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-12">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Tareas</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($ticket['estado'] !== 'Cerrado'): ?>
                                            <!-- Botón Subir Archivo con icono -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#crearTareaModal" 
                                               onclick="setActionUrl('subir', '<?= base_url() . 'tickets/subir-archivo/' . $ticket['id']; ?>')" class="btn btn-primary">
                                                <i class="ri ri-add-line me-2"></i> Nueva
                                            </a>
                                        <?php endif; ?>
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
                                        <th>Descripción</th>
                                        <th>Área</th>
                                        <th>Prioridad</th>
                                        <th>Vencimiento</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Estado TDR</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tareas)) { ?>
                                        <?php foreach ($tareas as $tarea): ?>

                                            <?php
                                            // Validar fechas antes de procesar
                                            if (!empty($tarea['fecha_creacion']) && !empty($tarea['fecha_vencimiento'])) {
                                                // Convertir fechas a timestamp
                                                $fecha_creacion = strtotime($tarea['fecha_creacion']);
                                                $fecha_vencimiento = strtotime($tarea['fecha_vencimiento']);
                                                $fecha_actual = time(); // Fecha y hora actuales
                                                // Validar que las conversiones fueron exitosas
                                                if ($fecha_creacion && $fecha_vencimiento) {
                                                    // Estado inicial de la tarea
                                                    $estadoSLA = '';

                                                    // Calcula la diferencia en horas entre la fecha actual y la fecha de vencimiento
                                                    $diff = $fecha_vencimiento - $fecha_actual;
                                                    $horas_restantes = $diff / 3600; // Diferencia en horas
                                                    // Lógica para establecer el estado basado en las fechas
                                                    if ($fecha_actual > $fecha_vencimiento) {
                                                        // Si la fecha actual es mayor que la fecha de vencimiento, la tarea está retrasada
                                                        $estadoSLA = 'Retrasada';
                                                    } elseif ($horas_restantes <= 24 && $horas_restantes > 0) {
                                                        // Si faltan 24 horas o menos para la fecha de vencimiento, está casi por vencer
                                                        $estadoSLA = 'Por vencer';
                                                    } elseif ($fecha_actual >= $fecha_creacion && $fecha_actual < $fecha_vencimiento) {
                                                        // Si la tarea aún está dentro del plazo
                                                        $estadoSLA = 'En progreso';
                                                    } else {
                                                        // Si no encaja en ninguna de las anteriores, la tarea está pendiente
                                                        $estadoSLA = 'Pendiente';
                                                    }
                                                } else {
                                                    // Si las fechas no son válidas
                                                    $estadoSLA = 'Fechas inválidas';
                                                }
                                            } else {
                                                // Si las fechas no están definidas
                                                $estadoSLA = 'Fechas no definidas';
                                            }
                                            ?>


                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td><?= strlen($tarea['descripcion']) > 50 ? substr($tarea['descripcion'], 0, 50) . '...' : $tarea['descripcion']; ?></td>
                                                <td><?= $tarea['nombre_area']; ?></td>
                                                <td><?= $tarea['prioridad']; ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_vencimiento'])); ?></td>
                                                <td class="text-center <?= $tarea['estado'] == 'Pendiente' ? 'text-danger' : ($tarea['estado'] == 'Resuelto' ? 'text-success' : '') ?>">
                                                    <?= $tarea['estado']; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    // Lógica para determinar la clase según el estado
                                                    $estadoClase = '';

                                                    // Si el estado de la tarea es 'Resuelto', establecemos el estado SLA como 'Resuelto' y la clase correspondiente
                                                    if ($tarea['estado'] == 'Resuelto') {
                                                        $estadoSLA = 'Resuelto';
                                                        $estadoClase = 'text-success'; // Color verde
                                                    } elseif ($estadoSLA == 'Retrasada') {
                                                        $estadoClase = 'text-danger'; // Color rojo
                                                    } elseif ($estadoSLA == 'Por vencer') {
                                                        $estadoClase = 'text-warning'; // Color amarillo
                                                    } elseif ($estadoSLA == 'En progreso') {
                                                        $estadoClase = 'text-primary'; // Color azul
                                                    } elseif ($estadoSLA == 'Pendiente') {
                                                        $estadoClase = 'text-muted'; // Color gris
                                                    }
                                                    ?>

                                                    <span class="<?= $estadoClase ?>"><?= $estadoSLA ?></span>
                                                </td>

                                                <td class="text-center">
                                                    <!-- Botón Ver Detalle -->
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTarea<?= $tarea['id'] ?>">
                                                        <i class="ri ri-eye-line"></i>
                                                    </button>
                                                    <!-- Botón Gestionar Tarea -->
                                                    <a href="<?= base_url() . "tareas/detalle/{$tarea['id']}"; ?>" class="btn btn-primary">
                                                        <i class="ri ri-pencil-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Modal Detalle de Tarea -->
                                        <div class="modal fade" id="modalTarea<?= $tarea['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalTareaLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content shadow-lg rounded-3">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTareaLabel">Detalle de Tarea</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Área -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Área:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= $tarea['nombre_area']; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Usuario Emisor -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Usuario Emisor:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= $tarea['nombre_emisor']; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Usuario Receptor -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Usuario Receptor:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= $tarea['nombre_receptor'] ?? 'Sin asignar'; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Prioridad -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Prioridad:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= $tarea['prioridad']; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Estado -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Estado:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= $tarea['estado']; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Fecha de Creación -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Fecha de Creación:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Fecha de Vencimiento -->
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"><strong>Fecha de Vencimiento:</strong></label>
                                                            <div class="col-sm-9">
                                                                <p class="form-control-plaintext"><?= date('d/m/Y H:i', strtotime($tarea['fecha_vencimiento'])); ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Tarea -->
                                                        <div class="row mb-3">
                                                            <label for="tareaDescripcion<?= $tarea['id'] ?>" class="col-sm-3 col-form-label"><strong>Tarea:</strong></label>
                                                            <div class="col-sm-9">
                                                                <textarea id="tareaDescripcion<?= $tarea['id'] ?>" class="form-control" rows="5" readonly><?= $tarea['descripcion']; ?></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Solución -->
                                                        <div class="row mb-3">
                                                            <label for="tareaSolucion<?= $tarea['id'] ?>" class="col-sm-3 col-form-label"><strong>Solución:</strong></label>
                                                            <div class="col-sm-9">
                                                                <textarea id="tareaSolucion<?= $tarea['id'] ?>" class="form-control" rows="5" readonly><?= $tarea['solucion'] ?? 'Sin Solución'; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Registro de Actividad</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <?php if ($ticket['estado'] !== 'Cerrado' && $ticket['area_id'] == session('session_data.area_id')): ?>
                                            <!-- Botón Enviar Comentario -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#comentarioModal" 
                                               onclick="setActionUrl('comentario', '<?= base_url() . 'tickets/crear-comentario/' . $ticket['id']; ?>')" 
                                               class="btn btn-secondary">
                                                <i class="ri ri-add-line"></i>&nbsp;Comentario
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="datatable display table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size: 14px;">#</th>
                                    <th style="font-size: 14px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador = 1; ?>
                                <?php if (isset($acciones)) { ?>
                                    <?php foreach ($acciones as $accion): ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 13px; width: 50px;"><?= $contador; ?></td>
                                            <td>
                                                <div class="d-flex align-items-start" style="padding-left: 15px;">
                                                    <div class="text-primary" style="font-size: 18px; margin-right: 10px;">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div class="bubble" style="border-left: 3px solid #0d6efd; background: #f8f9fa; padding: 15px; border-radius: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); width: 100%;">
                                                        <h6 class="text-secondary" style="margin: 0; font-size: 14px;"><?= $accion['titulo']; ?></h6>
                                                        <p style="margin: 5px 0; font-size: 12px; color: #6c757d;">
                                                            <?= $accion['descripcion']; ?>
                                                        </p>
                                                        <small style="font-size: 12px; color: #6c757d;">
                                                            <strong>Usuario:</strong> <?= $accion['nombre_usuario']; ?> |
                                                            <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($accion['fecha_creacion'])); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted" style="font-size: 13px;">No hay acciones registradas.</td>
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

<!-- Modal Subir Archivo -->
<div class="modal fade" id="subirArchivoModal" tabindex="-1" aria-labelledby="subirArchivoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subirArchivoModalLabel">Confirmar Subida de Archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Formulario para subir el archivo -->
            <form action="<?= base_url() . 'tickets/subir-archivo' ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="10" required=""></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" required accept=".txt, .pdf, .jpeg, .jpg, .png, .xls, .xlsx, .doc, .docx">
                    </div>

                    <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- Botón de Confirmación que envía el formulario -->
                    <button type="submit" class="btn btn-primary">Subir Archivo</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Cerrar Ticket -->
<div class="modal fade" id="cerrarTicketModal" tabindex="-1" aria-labelledby="cerrarTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cerrarTicketModalLabel">Confirmar Cierre de Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url() . "tickets/cerrar-ticket"; ?>">
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>" required="">
                    <textarea class="form-control" id="comentario" name="comentario" rows="10" required="" placeholder="Anotaciones"></textarea>
                    <span class="text-primary"><strong>Nota: </strong>Al cerrar un ticket, no podrás realizar modificaciones, añadir comentarios ni asignar nuevas tareas.</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Cerrar Ticket</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Enviar Recordatorio -->
<div class="modal fade" id="enviarRecordatorioModal" tabindex="-1" aria-labelledby="enviarRecordatorioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enviarRecordatorioModalLabel">Confirmar Envío de Recordatorio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea enviar un recordatorio para este ticket?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= base_url() . "tickets/enviar-recordatorio/{$ticket['id']}"; ?>" class="btn btn-primary">Enviar</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir un nuevo comentario-->
<div class="modal fade" id="comentarioModal" tabindex="-1" aria-labelledby="comentarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comentarioModalLabel">Añadir Comentario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= base_url() . 'tickets/crear-comentario'; ?>">
                    <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>" required="">
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario</label>
                        <textarea id="comentario" name="comentario" class="form-control" rows="4" placeholder="Escribe tu comentario aquí..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Tarea -->
<div class="modal fade" id="crearTareaModal" tabindex="-1" aria-labelledby="crearTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="crearTareaModalLabel">Crear Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="<?= base_url() . "tareas/crear"; ?>" method="POST">
                <div class="modal-body">
                    <!-- ID del Ticket -->
                    <input type="hidden" name="ticket_id" id="ticket_id" required="" value="<?= $ticket['id']; ?>">

                    <!-- Área -->
                    <div class="mb-3">
                        <label for="area_id" class="form-label"><strong>Área:</strong></label>
                        <select id="tuSelect" class="form-control js-example-basic-single" id="area_id" name="area_id" style="z-index: 9999 !important">
                            <!-- Suponiendo que ya tienes una lista de áreas en $areas -->
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= $area['id'] ?>"><?= $area['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div class="mb-3">
                        <label for="prioridad" class="form-label"><strong>Prioridad:</strong></label>
                        <select class="form-select" id="prioridad" name="prioridad">
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Crítica">Crítica</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Inicialización de Select2 en modales
    $(document).ready(function () {
        // Función para inicializar Select2
        function initSelect2InModal() {
            $('.js-example-basic-single').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }

                $(this).select2({
                    dropdownParent: $(this).closest('.modal-content'),
                    width: '100%',
                    language: 'es',
                    placeholder: 'Seleccione una opción...',
                    allowClear: true
                });
            });
        }

        // Inicializar Select2 cuando se abre cualquier modal
        $(document).on('shown.bs.modal', '.modal', function () {
            initSelect2InModal();
        });

        // Destruir Select2 cuando se cierra el modal
        $(document).on('hide.bs.modal', '.modal', function () {
            $('.js-example-basic-single').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
        });
    });
</script>

<!-- Estilos para el circulo radar -->
<style>
    /* Estilo para el círculo animado */
    .radar-circle {
        border-radius: 50%;
        position: absolute;
        pointer-events: none; /* Para evitar interacciones con el círculo */
        animation: pulse 1.5s infinite; /* Animación continua */
    }

    @keyframes pulse {
        0% {
            transform: scale(0); /* Comienza desde un tamaño pequeño */
            opacity: 1; /* Comienza completamente visible */
        }
        50% {
            transform: scale(1.5); /* Aumenta al 150% */
            opacity: 0; /* Se desvanece completamente */
        }
        100% {
            transform: scale(2); /* Se expande aún más */
            opacity: 0; /* Mantiene el desvanecimiento */
        }
    }
</style>

<!-- Script para mapa y posición de radar en movimiento -->
<script>
    // Verificar que las coordenadas existan y sean válidas
    const lat = <?= $ticket['latitud']; ?>;
    const lng = <?= $ticket['longitud']; ?>;
    const nombreUsuario = "<?= !empty($ticket['nombre_usuario']) ? htmlspecialchars($ticket['nombre_usuario']) : 'Usuario' ?>";

    // Esperar a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function () {
        // Verificar que el contenedor del mapa exista
        if (!document.getElementById('map')) {
            console.error('El contenedor del mapa no existe');
            return;
        }

        // Inicialización del mapa sin zoom ni movimiento
        const map = L.map('map', {
            center: [lat, lng],
            zoom: 18,
            zoomControl: false, // Deshabilitar controles de zoom
            dragging: false, // Deshabilitar arrastre del mapa
            scrollWheelZoom: false, // Deshabilitar zoom con rueda del mouse
            doubleClickZoom: false, // Deshabilitar zoom por doble clic
            boxZoom: false, // Deshabilitar zoom por selección
            touchZoom: false, // Deshabilitar zoom táctil
            keyboard: false, // Deshabilitar navegación con teclado
            trackResize: false // Evitar que el mapa se redimensione automáticamente
        });

        // Capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Crear botón para navegación
        const navigateButton = L.control({position: 'bottomright'});
        navigateButton.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            div.innerHTML = `
            <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
               target="_blank" 
               style="display: block; background: #fff; padding: 5px 10px; text-decoration: none; border: 2px solid rgba(0,0,0,0.2); border-radius: 4px;">
                <img src="https://maps.google.com/mapfiles/ms/micons/driving.png" style="width: 20px; height: 20px;"/>
                Navegar
            </a>`;
            return div;
        };
        navigateButton.addTo(map);

        // Marcador en la ubicación de la alerta con popup que incluye botón de navegación
        const marker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`
            <div>
                <strong>Lat: <?= $ticket['latitud']; ?> - Lng: <?= $ticket['longitud']; ?></strong><br>
                User: ${nombreUsuario}<br>
                <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                   target="_blank" 
                   style="display: block; background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; margin-top: 5px; text-align: center;">
                    Navegar a este punto
                </a>
            </div>
        `)
                .openPopup();

        // Crear un círculo pulsante
        const radarCircle = L.circle([lat, lng], {
            radius: 60,
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            weight: 1,
            interactive: false
        }).addTo(map);

        // Función para animar el círculo
        function pulseCircle() {
            let radius = 0;
            const animate = () => {
                radius = (radius + 15) % 101; // Ciclo entre 0 y 100
                radarCircle.setRadius(radius);
                radarCircle.setStyle({
                    fillOpacity: 0.3 * (1 - radius / 100) // Reducir opacidad mientras se expande
                });
            };
            // Ejecutar la animación cada 200ms
            setInterval(animate, 200);
        }

        // Iniciar la animación
        pulseCircle();

        // Añadir evento de clic al mapa
        map.on('click', function (e) {
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
        });
    });
</script>