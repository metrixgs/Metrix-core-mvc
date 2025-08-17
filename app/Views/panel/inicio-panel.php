<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inicio</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->



        <!-- KPIs Principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Total Incidencias</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                    <span class="counter-value" data-target="<?= count($tickets); ?>">0</span>
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="ri-file-list-3-line fs-16"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Abiertas</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-warning">
                                    <span class="counter-value" data-target="<?= $pendientes ?>" id="kpi-abiertas">0</span>
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="ri-time-line fs-16"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Cerradas</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-success">
                                    <span class="counter-value" data-target="<?= $cumplidos ?>" id="kpi-cerradas">0</span>
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="ri-check-line fs-16"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Tiempo Promedio</p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-info">
                                    <span class="counter-value" data-target="24" id="kpi-tiempo-promedio">0</span>h
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title rounded-circle bg-info">
                                        <i class="ri-timer-line fs-16"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gráficos Principales -->
        <div class="row mb-3">
            <!-- Incidencias por Prioridad -->
            <div class="col-xl-4 col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Incidencias por Prioridad</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 200px; position: relative;">
                            <canvas id="graficaPrioridad"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Incidencias Abiertas vs Cerradas -->
            <div class="col-xl-4 col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Estado de Incidencias</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 200px; position: relative;">
                            <canvas id="graficaEstado"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiempo Promedio de Resolución -->
            <div class="col-xl-4 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Tiempo Promedio por Área</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 200px; position: relative;">
                            <canvas id="graficaTiempoArea"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila de gráficos -->
        <div class="row mb-3">
            <!-- Incidencias por Categoría -->
            <div class="col-xl-6 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Incidencias por Categoría</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 180px; position: relative;">
                            <canvas id="graficaCategoria"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tendencia en el Tiempo -->
            <div class="col-xl-6 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Tendencia de Incidencias</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 180px; position: relative;">
                            <canvas id="graficaTendencia"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila de gráficos -->
        <div class="row mb-3">
            <!-- Top 10 Colonias -->
            <div class="col-xl-6 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Top 10 Colonias con Más Incidencias</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 220px; position: relative;">
                            <canvas id="graficaColonias"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Incidencias por Área Responsable -->
            <div class="col-xl-6 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Incidencias por Área Responsable</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 180px; position: relative;">
                            <canvas id="graficaAreaResponsable"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa de Calor y Comparativa Temporal -->
        <div class="row mb-3">
            <!-- Mapa de Calor -->
            <div class="col-xl-8 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Mapa de Calor Geográfico</h6>
                    </div>
                    <div class="card-body py-2">
                        <div id="mapaCalor" style="height: 250px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <i class="ri-map-pin-line fs-36 text-muted mb-2"></i>
                                <p class="text-muted mb-0 small">Mapa de calor geográfico<br>Requiere integración con servicio de mapas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparativa Temporal por Categoría -->
            <div class="col-xl-4 col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">Comparativa Temporal</h6>
                    </div>
                    <div class="card-body py-2">
                        <div style="height: 250px; position: relative;">
                            <canvas id="graficaComparativa"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Incidencias Recientes -->
        <div class="row">
            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Incidencias Recientes</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="exportar-excel">
                                        <i class="ri-file-excel-line"></i> Exportar
                                    </button>
                                    <a href="<?= base_url() . "tickets/nuevo"; ?>" class="btn btn-primary">
                                        <i class="ri-add-line"></i> Nueva Incidencia
                                    </a>
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
                                        <th>Título</th>
                                        <th>Categoría</th>
                                        <th>Cliente</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Creación</th>
                                        <th>SLA</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $contador = 1; ?>
                                    <?php if (isset($tickets)) { ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <td class="text-center"><?= $contador; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?= $ticket['titulo'] ?></h6>
                                                            <p class="text-muted mb-0 fs-12">ID: #<?= $ticket['id'] ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark"><?= isset($ticket['nombre_categoria']) && !empty($ticket['nombre_categoria']) ? $ticket['nombre_categoria'] : 'Sin categoría' ?></span>
                                                </td>
                                                <td><?= isset($ticket['nombre_cliente']) && !empty($ticket['nombre_cliente']) ? $ticket['nombre_cliente'] : 'Sin cliente' ?></td>
                                                <td>
                                                    <?php
                                                    $prioridad_clase = '';
                                                    $nombre_prioridad = isset($ticket['nombre_prioridad']) && !empty($ticket['nombre_prioridad']) ? $ticket['nombre_prioridad'] : 'Sin prioridad';
                                                    switch(strtolower($nombre_prioridad)) {
                                                        case 'alta': $prioridad_clase = 'bg-danger'; break;
                                                        case 'media': $prioridad_clase = 'bg-warning'; break;
                                                        case 'baja': $prioridad_clase = 'bg-success'; break;
                                                        default: $prioridad_clase = 'bg-secondary';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $prioridad_clase ?>"><?= $nombre_prioridad ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $estado_clase = ($ticket['estado'] === 'Cerrado') ? 'bg-success' : 'bg-primary';
                                                    ?>
                                                    <span class="badge <?= $estado_clase ?>"><?= $ticket['estado'] ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($ticket['fecha_creacion'])) ?></small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $estado_sla = strtotime($ticket['fecha_vencimiento']) < time() ? 'Vencido' : 'Activo';
                                                    $clase_sla = $estado_sla === 'Vencido' ? 'bg-danger' : 'bg-success';
                                                    ?>
                                                    <span class="badge <?= $clase_sla ?>"><?= $estado_sla; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="<?= base_url() . 'tickets/detalle/' . $ticket['id'] ?>"><i class="ri-eye-line me-2"></i>Ver Detalles</a></li>
                                                            <li><a class="dropdown-item" href="<?= base_url() . 'tickets/editar/' . $ticket['id'] ?>"><i class="ri-edit-line me-2"></i>Editar</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div><!--end row-->
    </div>
</div>

<!-- Modal para crear la empresa -->
<div class="modal fade" id="modalCrearEmpresa" tabindex="-1" aria-labelledby="modalCrearEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearEmpresaLabel">Crear Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear la empresa -->
                <form action="<?= base_url('empresas/crear'); ?>" method="POST">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la empresa</label>
                        <input type="text" name="nombre" value="<?= old('nombre'); ?>" autocomplete="off" class="form-control <?= session('validation.nombre') ? 'is-invalid' : '' ?>" id="nombre" placeholder="Nombre de la empresa" required>
                        <?php if (session('validation.nombre')): ?>
                            <div class="text-danger">
                                <?= session('validation.nombre') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="identificacion" class="form-label">Identificación</label>
                        <input type="text" name="identificacion" value="<?= old('identificacion'); ?>" autocomplete="off" class="form-control <?= session('validation.identificacion') ? 'is-invalid' : '' ?>" id="identificacion" placeholder="Identificación" required>
                        <?php if (session('validation.identificacion')): ?>
                            <div class="text-danger">
                                <?= session('validation.identificacion') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" name="correo" value="<?= old('correo'); ?>" autocomplete="off" class="form-control <?= session('validation.correo') ? 'is-invalid' : '' ?>" id="correo" placeholder="Correo electrónico" required>
                        <?php if (session('validation.correo')): ?>
                            <div class="text-danger">
                                <?= session('validation.correo') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="<?= old('telefono'); ?>" autocomplete="off" class="form-control <?= session('validation.telefono') ? 'is-invalid' : '' ?>" id="telefono" placeholder="Número de teléfono" required>
                        <?php if (session('validation.telefono')): ?>
                            <div class="text-danger">
                                <?= session('validation.telefono') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="responsable" class="form-label">Responsable</label>
                        <input type="text" name="responsable" value="<?= old('responsable'); ?>" autocomplete="off" class="form-control <?= session('validation.responsable') ? 'is-invalid' : '' ?>" id="responsable" placeholder="Responsable de la empresa" required>
                        <?php if (session('validation.responsable')): ?>
                            <div class="text-danger">
                                <?= session('validation.responsable') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="<?= old('direccion'); ?>" autocomplete="off" class="form-control <?= session('validation.direccion') ? 'is-invalid' : '' ?>" id="direccion" placeholder="Dirección" required>
                        <?php if (session('validation.direccion')): ?>
                            <div class="text-danger">
                                <?= session('validation.direccion') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" class="form-select <?= session('validation.estado') ? 'is-invalid' : '' ?>" id="estado" required>
                            <option value="1" <?= old('estado') == 1 ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= old('estado') == 0 ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                        <?php if (session('validation.estado')): ?>
                            <div class="text-danger">
                                <?= session('validation.estado') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts del Dashboard -->
<script>
// Datos del controlador
const dashboardData = {
    cumplidos: <?= $cumplidos ?>,
    incumplidos: <?= $incumplidos ?>,
    pendientes: <?= $pendientes ?>,
    totalIncidencias: <?= count($tickets) ?>
};

// Variables globales para los gráficos
let charts = {};

// Configuración de colores - Paleta compatible con verde
const colors = {
    primary: '#28a745',    // Verde principal
    success: '#20c997',    // Verde azulado
    warning: '#ffc107',    // Amarillo dorado
    danger: '#dc3545',     // Rojo suave
    info: '#17a2b8',      // Azul verdoso
    light: '#f8f9fa',     // Gris muy claro
    dark: '#343a40',      // Gris oscuro
    secondary: '#6c757d', // Gris medio
    accent: '#155724',    // Verde oscuro
    mint: '#d4edda',      // Verde menta claro
    forest: '#2d5a3d'     // Verde bosque
};

// Inicialización del dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    // Inicializar animación de contadores
    initializeCounters();
    
    // Configurar DataTables con paginación de 10 registros
    initializeDataTable();
    
    // Cargar datos iniciales del dashboard
    cargarDatosIniciales();
});

// Función para inicializar la animación de contadores
function initializeCounters() {
    const counters = document.querySelectorAll('.counter-value');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 segundos
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Función para configurar DataTable específicamente para el dashboard
function initializeDataTable() {
    // Esperar a que se cargue completamente el DOM y las librerías
    setTimeout(function() {
        // Verificar si la tabla ya está inicializada y destruirla si es necesario
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }
        
        // Inicializar con configuración específica para el dashboard
        $('.datatable').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthChange": true,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es_es.json"
            },
            "dom": 'Bfrtip',
            "buttons": [
                {
                    "extend": 'excel',
                    "text": '<i class="ri-file-excel-line"></i> Excel',
                    "className": 'btn btn-success btn-sm'
                }
            ]
        });
    }, 100); // Pequeño delay para asegurar que el DOM esté listo
}

// Función para cargar datos iniciales
function cargarDatosIniciales() {
    // Usar datos PHP iniciales si están disponibles
    const datosIniciales = {
        prioridad: <?= json_encode($estadisticas_prioridad ?? []) ?>,
        categoria: <?= json_encode($estadisticas_categoria ?? []) ?>,
        tiempo_area: <?= json_encode($tiempo_promedio_area ?? []) ?>,
        tendencia: <?= json_encode($tendencia_mensual ?? []) ?>,
        colonias: <?= json_encode($top_colonias ?? []) ?>,
        area_responsable: <?= json_encode($area_responsable ?? []) ?>,
        generales: <?= json_encode($estadisticas_generales ?? []) ?>,
        comparativa: <?= json_encode($comparativa_temporal ?? []) ?>
    };
    
    // Actualizar dashboard con datos iniciales
    if (Object.values(datosIniciales).some(data => data && data.length > 0)) {
        updateChartsWithRealData(datosIniciales);
    }
}



// Inicialización de gráficos
function initializeCharts() {
    // Gráfico de Prioridad (Donut)
    charts.prioridad = new Chart(document.getElementById('graficaPrioridad'), {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [colors.danger, colors.warning, colors.success],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Estado (Donut)
    charts.estado = new Chart(document.getElementById('graficaEstado'), {
        type: 'doughnut',
        data: {
            labels: ['Abiertas', 'Cerradas'],
            datasets: [{
                data: [dashboardData.pendientes, dashboardData.cumplidos],
                backgroundColor: [colors.warning, colors.success],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Tiempo por Área (Barras horizontales)
    charts.tiempoArea = new Chart(document.getElementById('graficaTiempoArea'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Horas Promedio',
                data: [],
                backgroundColor: colors.info,
                borderColor: colors.info,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Categorías (Barras)
    charts.categoria = new Chart(document.getElementById('graficaCategoria'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Incidencias',
                data: [],
                backgroundColor: [
                    colors.primary,
                    colors.success,
                    colors.warning,
                    colors.danger,
                    colors.info
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Tendencia (Líneas)
    charts.tendencia = new Chart(document.getElementById('graficaTendencia'), {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Incidencias',
                data: [12, 19, 15, 25, 22, 18],
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Colonias (Barras horizontales)
    charts.colonias = new Chart(document.getElementById('graficaColonias'), {
        type: 'bar',
        data: {
            labels: ['Centro', 'Norte', 'Sur', 'Este', 'Oeste', 'Zona Industrial', 'Residencial', 'Comercial', 'Universitaria', 'Periférica'],
            datasets: [{
                label: 'Incidencias',
                data: [25, 20, 18, 15, 12, 10, 8, 6, 4, 2],
                backgroundColor: colors.success
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Área Responsable (Barras horizontales)
    charts.areaResponsable = new Chart(document.getElementById('graficaAreaResponsable'), {
        type: 'bar',
        data: {
            labels: ['TI', 'Mantenimiento', 'Seguridad', 'Administración', 'Operaciones'],
            datasets: [{
                label: 'Incidencias Asignadas',
                data: [30, 25, 15, 10, 8],
                backgroundColor: colors.warning
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico Comparativo Temporal (Líneas múltiples)
    charts.comparativa = new Chart(document.getElementById('graficaComparativa'), {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Hardware',
                    data: [5, 8, 6, 10, 9, 7],
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20'
                },
                {
                    label: 'Software',
                    data: [8, 12, 10, 15, 13, 11],
                    borderColor: colors.success,
                    backgroundColor: colors.success + '20'
                },
                {
                    label: 'Red',
                    data: [3, 5, 4, 7, 6, 5],
                    borderColor: colors.warning,
                    backgroundColor: colors.warning + '20'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}



// Función para actualizar dashboard
function actualizarDashboard(data) {
    // Actualizar KPIs con datos reales
    if (data.generales && data.generales.length > 0) {
        const stats = data.generales[0];
        document.getElementById('kpi-total').textContent = stats.total || 0;
        document.getElementById('kpi-abiertas').textContent = stats.abiertas || 0;
        document.getElementById('kpi-cerradas').textContent = stats.cerradas || 0;
        document.getElementById('kpi-tiempo-promedio').textContent = (stats.tiempo_promedio || 0) + 'h';
    }
    
    // Actualizar gráficos con datos reales
    updateChartsWithRealData(data);
}

// Función para actualizar gráficos con datos reales
function updateChartsWithRealData(data) {
    console.log('Datos recibidos para actualizar gráficos:', data);
    
    // Actualizar gráfico de prioridad
    if (data.prioridad && data.prioridad.length > 0 && charts.prioridad) {
        const labels = data.prioridad.map(item => item.prioridad || item.nombre_prioridad || 'Sin prioridad');
        const valores = data.prioridad.map(item => parseInt(item.total) || 0);
        
        console.log('Datos de prioridad - Labels:', labels, 'Valores:', valores);
        
        charts.prioridad.data.labels = labels;
        charts.prioridad.data.datasets[0].data = valores;
        charts.prioridad.update();
    }
    
    // Actualizar gráfico de categorías
    if (data.categoria && data.categoria.length > 0 && charts.categoria) {
        const labels = data.categoria.map(item => item.categoria || item.nombre_categoria || 'Sin categoría');
        const valores = data.categoria.map(item => parseInt(item.total) || 0);
        
        console.log('Datos de categoría - Labels:', labels, 'Valores:', valores);
        
        charts.categoria.data.labels = labels;
        charts.categoria.data.datasets[0].data = valores;
        charts.categoria.update();
    }
    
    // Actualizar gráfico de tiempo por área
    if (data.tiempo_area && data.tiempo_area.length > 0 && charts.tiempoArea) {
        const labels = data.tiempo_area.map(item => item.area || item.nombre_area || 'Sin área');
        const valores = data.tiempo_area.map(item => parseFloat(item.tiempo_promedio) || 0);
        
        console.log('Datos de tiempo por área - Labels:', labels, 'Valores:', valores);
        
        charts.tiempoArea.data.labels = labels;
        charts.tiempoArea.data.datasets[0].data = valores;
        charts.tiempoArea.update();
    }
    
    // Actualizar gráfico de tendencia
    if (data.tendencia && data.tendencia.length > 0 && charts.tendencia) {
        const labels = data.tendencia.map(item => item.mes || 'Sin fecha');
        const valores = data.tendencia.map(item => parseInt(item.total) || 0);
        
        console.log('Datos de tendencia - Labels:', labels, 'Valores:', valores);
        
        charts.tendencia.data.labels = labels;
        charts.tendencia.data.datasets[0].data = valores;
        charts.tendencia.update();
    }
    
    // Actualizar gráfico de colonias
    if (data.colonias && data.colonias.length > 0 && charts.colonias) {
        const labels = data.colonias.map(item => item.colonia || 'Sin colonia');
        const valores = data.colonias.map(item => parseInt(item.total) || 0);
        
        console.log('Datos de colonias - Labels:', labels, 'Valores:', valores);
        
        charts.colonias.data.labels = labels;
        charts.colonias.data.datasets[0].data = valores;
        charts.colonias.update();
    }
    
    // Actualizar gráfico de área responsable
    if (data.area_responsable && data.area_responsable.length > 0 && charts.areaResponsable) {
        const labels = data.area_responsable.map(item => item.area || item.nombre_area || 'Sin área');
        const valores = data.area_responsable.map(item => parseInt(item.total) || 0);
        
        console.log('Datos de área responsable - Labels:', labels, 'Valores:', valores);
        
        charts.areaResponsable.data.labels = labels;
        charts.areaResponsable.data.datasets[0].data = valores;
        charts.areaResponsable.update();
    }
    
    // Actualizar gráfico comparativo
    if (data.comparativa && data.comparativa.length > 0 && charts.comparativa) {
        console.log('Datos de comparativa:', data.comparativa);
        
        // Agrupar datos por categoría
        const categorias = {};
        data.comparativa.forEach(item => {
            const nombreCategoria = item.categoria || item.nombre_categoria || 'Sin categoría';
            if (!categorias[nombreCategoria]) {
                categorias[nombreCategoria] = [];
            }
            categorias[nombreCategoria].push({
                mes: item.mes || 'Sin fecha',
                total: parseInt(item.total) || 0
            });
        });
        
        // Obtener todos los meses únicos
        const meses = [...new Set(data.comparativa.map(item => item.mes))].sort();
        
        // Actualizar labels
        charts.comparativa.data.labels = meses;
        
        // Actualizar datasets
        charts.comparativa.data.datasets = [];
        const colores = [colors.primary, colors.success, colors.accent, colors.forest, colors.info, colors.warning, colors.secondary];
        let colorIndex = 0;
        
        Object.keys(categorias).forEach(categoria => {
            const datos = meses.map(mes => {
                const item = categorias[categoria].find(c => c.mes === mes);
                return item ? item.total : 0;
            });
            
            charts.comparativa.data.datasets.push({
                label: categoria,
                data: datos,
                borderColor: colores[colorIndex % colores.length],
                backgroundColor: colores[colorIndex % colores.length] + '20',
                tension: 0.4,
                fill: false
            });
            colorIndex++;
        });
        
        charts.comparativa.update();
    }
}

// Función para exportar a Excel
function exportarExcel() {
    const periodo = document.getElementById('filtro-periodo').value;
    const fechaInicio = document.getElementById('fecha-inicio').value;
    const fechaFin = document.getElementById('fecha-fin').value;
    
    Swal.fire({
        icon: 'info',
        title: 'Exportando datos...',
        text: 'Se está generando el archivo Excel',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Llamada AJAX para exportar datos
    fetch('<?= base_url('dashboard/exportar') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            periodo: periodo,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            // Crear enlace de descarga
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = data.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            Swal.fire({
                icon: 'success',
                title: 'Exportación completada',
                text: `Se han exportado ${data.total_records} registros correctamente`,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error en la exportación',
                text: data.error || 'Error al exportar datos'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor para exportar'
        });
    });
}

// Función para redimensionar gráficos
window.addEventListener('resize', function() {
    Object.keys(charts).forEach(key => {
        if (charts[key]) {
            charts[key].resize();
        }
    });
});
</script>