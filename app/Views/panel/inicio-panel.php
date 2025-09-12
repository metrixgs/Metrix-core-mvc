<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm" id="dashboardSelector" onchange="navigateToDashboard()" style="max-width: 220px; font-size: 0.95rem; font-weight: 500; border: 1px solid #dee2e6; background-color: #fff; padding: 0.375rem 0.75rem;">
                            <option value="panel">Incidencias</option>
                            <option value="dashboard-metrix">Beneficiarios</option>
                            <option value="dashboard-eventos">Corregidora</option>
                        </select>
                    </div>

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
                                <div class="avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title rounded-circle bg-success">
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
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0" style="color: #20c997;">
                                    <span class="counter-value" data-target="<?= $pendientes ?>" id="kpi-abiertas">0</span>
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle" style="background-color: #20c997;">
                                    <span class="avatar-title rounded-circle" style="background-color: #20c997;">
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
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0" style="color: #17a2b8;">
                                    <span class="counter-value" data-target="24" id="kpi-tiempo-promedio">0</span>h
                                </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle" style="background-color: #17a2b8;">
                                    <span class="avatar-title rounded-circle" style="background-color: #17a2b8;">
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
                        <div style="height: 300px; position: relative;">
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
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Incidencias por Categoría</h6>
                        <div>
                            <button class="btn btn-primary btn-sm" onclick="showCategoriasComparator()">
                                <i class="fas fa-chart-bar me-1"></i>Comparar
                            </button>
                            <span id="selectedCategoriasCount" class="ms-2 text-muted">0 categorías seleccionadas</span>
                        </div>
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

// Configuración de colores - Paleta verde
const colors = {
    primary: '#28a745',    // Verde principal
    success: '#20c997',    // Verde azulado
    warning: '#6f9c3d',    // Verde oliva
    danger: '#5cb85c',     // Verde claro
    info: '#17a2b8',      // Azul verdoso
    light: '#f8f9fa',     // Gris muy claro
    dark: '#2d5a3d',      // Verde oscuro
    secondary: '#6c757d', // Gris medio
    accent: '#155724',    // Verde muy oscuro
    mint: '#d4edda',      // Verde menta claro
    forest: '#2d5a3d',    // Verde bosque
    lime: '#32cd32',      // Verde lima
    emerald: '#50c878',   // Verde esmeralda
    sage: '#9caf88'       // Verde salvia
};

// Función para formatear números a miles
function formatToThousands(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Función para mostrar total en gráficas
function showChartTotal(chartId, total) {
    const chartContainer = document.getElementById(chartId).parentElement;
    let totalElement = chartContainer.querySelector('.chart-total');
    
    if (!totalElement) {
        totalElement = document.createElement('div');
        totalElement.className = 'chart-total';
        totalElement.style.cssText = 'position: absolute; top: 10px; right: 10px; background: #fff; border: 2px solid #28a745; border-radius: 15px; padding: 5px 10px; font-weight: bold; font-size: 12px; z-index: 1000;';
        chartContainer.style.position = 'relative';
        chartContainer.appendChild(totalElement);
    }
    totalElement.textContent = `Total: ${formatToThousands(total)}`;
}

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
        generales: <?= json_encode($estadisticas_generales ?? []) ?>
    };
    
    // Actualizar dashboard con datos iniciales
    if (Object.values(datosIniciales).some(data => data && data.length > 0)) {
        updateChartsWithRealData(datosIniciales);
    }
}



// Inicialización de gráficos
function initializeCharts() {
    // Gráfico de Prioridad (Polar)
    charts.prioridad = new Chart(document.getElementById('graficaPrioridad'), {
        type: 'polarArea',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [colors.danger, colors.warning, colors.primary],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        boxWidth: 15,
                        boxHeight: 15,
                        generateLabels: function(chart) {
                            const chartData = chart.data;
                            if (chartData.datasets[0].data.length === 0) {
                                return [];
                            }
                            const total = chartData.datasets[0].data.reduce((sum, value) => sum + value, 0);
                            return chartData.labels.map((label, index) => {
                                const value = chartData.datasets[0].data[index];
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return {
                                    text: `${label}: ${value} (${percentage}%)`,
                                    fillStyle: chartData.datasets[0].backgroundColor[index],
                                    strokeStyle: chartData.datasets[0].backgroundColor[index],
                                    pointStyle: 'circle',
                                    index: index
                                };
                            });
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: function(context) {
                        // Usar color dinámico basado en el fondo para mejor contraste
                        const bgColor = context.dataset.backgroundColor[context.dataIndex];
                        return bgColor === colors.warning ? '#000' : '#fff';
                    },
                    font: {
                        weight: 'bold',
                        size: 16
                    },
                    formatter: function(value, context) {
                        // Mostrar valor con formato mejorado
                        return value > 0 ? value.toString() : '';
                    },
                    anchor: 'center',
                    align: 'center',
                    textStrokeColor: function(context) {
                        // Agregar borde al texto para mejor legibilidad
                        const bgColor = context.dataset.backgroundColor[context.dataIndex];
                        return bgColor === colors.warning ? '#fff' : '#000';
                    },
                    textStrokeWidth: 1
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
            backgroundColor: [colors.emerald, colors.primary],
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
                backgroundColor: colors.emerald,
                borderColor: colors.emerald,
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
                    colors.emerald,
                    colors.lime,
                    colors.sage
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
                backgroundColor: colors.sage
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
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.prioridad.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.prioridad || item.nombre_prioridad || 'Sin prioridad');
            const valores = datosFiltrados.map(item => parseInt(item.total));
            
            console.log('Datos de prioridad filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.prioridad.data.labels = labels;
            charts.prioridad.data.datasets[0].data = valores;
            charts.prioridad.update();
            
            // Mostrar total en el gráfico de prioridad
            const totalPrioridad = valores.reduce((sum, value) => sum + value, 0);
            showChartTotal('graficaPrioridad', totalPrioridad);
        } else {
            // Si no hay datos relevantes, ocultar el gráfico o mostrar mensaje
            charts.prioridad.data.labels = ['Sin datos'];
            charts.prioridad.data.datasets[0].data = [0];
            charts.prioridad.update();
        }
    }
    
    // Actualizar gráfico de categorías
    if (data.categoria && data.categoria.length > 0 && charts.categoria) {
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.categoria.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.categoria || item.nombre_categoria || 'Sin categoría');
            const valores = datosFiltrados.map(item => parseInt(item.total));
            
            console.log('Datos de categoría filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.categoria.data.labels = labels;
            charts.categoria.data.datasets[0].data = valores;
            charts.categoria.update();
        } else {
            charts.categoria.data.labels = ['Sin datos'];
            charts.categoria.data.datasets[0].data = [0];
            charts.categoria.update();
        }
    }
    
    // Actualizar gráfico de tiempo por área
    if (data.tiempo_area && data.tiempo_area.length > 0 && charts.tiempoArea) {
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.tiempo_area.filter(item => parseFloat(item.tiempo_promedio) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.area || item.nombre_area || 'Sin área');
            const valores = datosFiltrados.map(item => parseFloat(item.tiempo_promedio));
            
            console.log('Datos de tiempo por área filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.tiempoArea.data.labels = labels;
            charts.tiempoArea.data.datasets[0].data = valores;
            charts.tiempoArea.update();
        } else {
            charts.tiempoArea.data.labels = ['Sin datos'];
            charts.tiempoArea.data.datasets[0].data = [0];
            charts.tiempoArea.update();
        }
    }
    
    // Actualizar gráfico de tendencia
    if (data.tendencia && data.tendencia.length > 0 && charts.tendencia) {
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.tendencia.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.mes || 'Sin fecha');
            const valores = datosFiltrados.map(item => parseInt(item.total));
            
            console.log('Datos de tendencia filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.tendencia.data.labels = labels;
            charts.tendencia.data.datasets[0].data = valores;
            charts.tendencia.update();
        } else {
            charts.tendencia.data.labels = ['Sin datos'];
            charts.tendencia.data.datasets[0].data = [0];
            charts.tendencia.update();
        }
    }
    
    // Actualizar gráfico de colonias
    if (data.colonias && data.colonias.length > 0 && charts.colonias) {
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.colonias.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.colonia || 'Sin colonia');
            const valores = datosFiltrados.map(item => parseInt(item.total));
            
            console.log('Datos de colonias filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.colonias.data.labels = labels;
            charts.colonias.data.datasets[0].data = valores;
            charts.colonias.update();
        } else {
            charts.colonias.data.labels = ['Sin datos'];
            charts.colonias.data.datasets[0].data = [0];
            charts.colonias.update();
        }
    }
    
    // Actualizar gráfico de área responsable
    if (data.area_responsable && data.area_responsable.length > 0 && charts.areaResponsable) {
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.area_responsable.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            const labels = datosFiltrados.map(item => item.area || item.nombre_area || 'Sin área');
            const valores = datosFiltrados.map(item => parseInt(item.total));
            
            console.log('Datos de área responsable filtrados - Labels:', labels, 'Valores:', valores);
            
            charts.areaResponsable.data.labels = labels;
            charts.areaResponsable.data.datasets[0].data = valores;
            charts.areaResponsable.update();
        } else {
            charts.areaResponsable.data.labels = ['Sin datos'];
            charts.areaResponsable.data.datasets[0].data = [0];
            charts.areaResponsable.update();
        }
    }
    
    // Actualizar gráfico comparativo
    if (data.comparativa && data.comparativa.length > 0 && charts.comparativa) {
        console.log('Datos de comparativa:', data.comparativa);
        
        // Filtrar datos con valores mayores a 0
        const datosFiltrados = data.comparativa.filter(item => parseInt(item.total) > 0);
        
        if (datosFiltrados.length > 0) {
            // Agrupar datos por categoría
            const categorias = {};
            datosFiltrados.forEach(item => {
                const nombreCategoria = item.categoria || item.nombre_categoria || 'Sin categoría';
                if (!categorias[nombreCategoria]) {
                    categorias[nombreCategoria] = [];
                }
                categorias[nombreCategoria].push({
                    mes: item.mes || 'Sin fecha',
                    total: parseInt(item.total)
                });
            });
            
            // Filtrar categorías que tengan al menos un valor significativo
            const categoriasRelevantes = {};
            Object.keys(categorias).forEach(categoria => {
                const totalCategoria = categorias[categoria].reduce((sum, item) => sum + item.total, 0);
                if (totalCategoria > 0) {
                    categoriasRelevantes[categoria] = categorias[categoria];
                }
            });
            
            if (Object.keys(categoriasRelevantes).length > 0) {
                // Obtener todos los meses únicos de datos filtrados
                const meses = [...new Set(datosFiltrados.map(item => item.mes))].sort();
                
                // Actualizar labels
                charts.comparativa.data.labels = meses;
                
                // Actualizar datasets
                charts.comparativa.data.datasets = [];
                const colores = [colors.primary, colors.success, colors.accent, colors.forest, colors.info, colors.warning, colors.secondary];
                let colorIndex = 0;
                
                Object.keys(categoriasRelevantes).forEach(categoria => {
                    const datos = meses.map(mes => {
                        const item = categoriasRelevantes[categoria].find(c => c.mes === mes);
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
            } else {
                charts.comparativa.data.labels = ['Sin datos'];
                charts.comparativa.data.datasets = [{
                    label: 'Sin datos',
                    data: [0],
                    borderColor: colors.secondary,
                    backgroundColor: colors.secondary + '20'
                }];
                charts.comparativa.update();
            }
        } else {
            charts.comparativa.data.labels = ['Sin datos'];
            charts.comparativa.data.datasets = [{
                label: 'Sin datos',
                data: [0],
                borderColor: colors.secondary,
                backgroundColor: colors.secondary + '20'
            }];
            charts.comparativa.update();
        }
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

// Variables globales para el comparador de categorías
let categoriasComparatorChart = null;
let categoriasData = [];
let selectedCategorias = [];

// Función para mostrar el modal de comparación de categorías
function showCategoriasComparator() {
    // Cargar las categorías disponibles
    fetch('<?= base_url('dashboard/getCategorias') ?>')
    .then(response => response.json())
    .then(data => {
        categoriasData = data;
        const selector = document.getElementById('categoriasComparatorSelector');
        selector.innerHTML = '';
        
        data.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id;
            option.textContent = categoria.nombre;
            selector.appendChild(option);
        });
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('categoriasModal'));
        modal.show();
    })
    .catch(error => {
        console.error('Error al cargar categorías:', error);
    });
}

// Función para actualizar la gráfica comparativa de categorías
function updateComparativaCategorias() {
    const selector = document.getElementById('categoriasComparatorSelector');
    selectedCategorias = Array.from(selector.selectedOptions).map(option => ({
        id: option.value,
        nombre: option.textContent
    }));
    
    if (selectedCategorias.length === 0) {
        alert('Por favor selecciona al menos una categoría para comparar.');
        return;
    }
    
    // Actualizar el contador en el botón
    document.getElementById('categoriasSelectedCount').textContent = selectedCategorias.length;
    
    // Obtener datos de comparación
    const categoriaIds = selectedCategorias.map(cat => cat.id);
    const formData = new FormData();
    formData.append('categoriaIds', JSON.stringify(categoriaIds));
    
    fetch('<?= base_url('dashboard/getComparacionCategorias') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta de comparación de categorías:', data);
        // Crear o actualizar la gráfica comparativa
        const ctx = document.getElementById('categoriasComparatorChart').getContext('2d');
        
        if (categoriasComparatorChart) {
            categoriasComparatorChart.destroy();
        }
        
        categoriasComparatorChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparación de Categorías Seleccionadas'
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Actualizar también la gráfica principal con las categorías seleccionadas
        updateCategoriaChart(selectedCategorias.map(cat => cat.id));
    })
    .catch(error => {
        console.error('Error al obtener datos de comparación:', error);
    });
}

// Función para actualizar la gráfica principal con filtro de categorías
function updateCategoriaChart(categoriaIds = null) {
    const formData = new FormData();
    
    if (categoriaIds && categoriaIds.length > 0) {
        categoriaIds.forEach(id => {
            formData.append('categorias[]', id);
        });
    }
    
    fetch('<?= base_url('dashboard/getIncidenciasPorCategoria') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            categoria.data.labels = data.data.labels;
            categoria.data.datasets[0].data = data.data.values;
            categoria.update();
        }
    })
    .catch(error => {
        console.error('Error al actualizar gráfica de categorías:', error);
    });
}

// Funciones para el selector de dashboard
function navigateToDashboard() {
    const selector = document.getElementById('dashboardSelector');
    const selectedValue = selector.value;
    
    if (selectedValue === 'panel') {
        window.location.href = '<?= base_url('panel') ?>';
    } else if (selectedValue === 'dashboard-metrix') {
        window.location.href = '<?= base_url('dashboard-metrix') ?>';
    } else if (selectedValue === 'dashboard-eventos') {
        window.location.href = '<?= base_url('dashboard-eventos') ?>';
    }
}

// Establecer el valor inicial del selector basado en la URL actual
document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('dashboardSelector');
    const currentUrl = window.location.pathname;
    
    if (currentUrl.includes('panel') && !currentUrl.includes('dashboard')) {
        selector.value = 'panel';
    } else if (currentUrl.includes('dashboard-metrix')) {
        selector.value = 'dashboard-metrix';
    } else if (currentUrl.includes('dashboard-eventos')) {
        selector.value = 'dashboard-eventos';
    }
});
</script>

<!-- Modal para comparar categorías -->
<div class="modal fade" id="categoriasModal" tabindex="-1" aria-labelledby="categoriasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriasModalLabel">Comparar Categorías de Incidencias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Seleccionar Categorías a Comparar:</label>
                    <select class="form-select" id="categoriasComparatorSelector" multiple>
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                </div>
                <canvas id="categoriasComparatorChart" width="250" height="120"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="updateComparativaCategorias()">Comparar</button>
            </div>
        </div>
    </div>
</div>