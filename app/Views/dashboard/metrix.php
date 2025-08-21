<?php
$titulo_pagina = "Dashboard de Métricas";
include(APPPATH . 'Views/incl/head-application.php');
?>

<style>
        .kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
        }
        .kpi-number {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .kpi-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        .chart-card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .chart-card:hover {
            transform: translateY(-1px);
        }
        .chart-card .card-body {
            padding: 12px;
        }
        .chart-card .card-header {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        .filter-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .table-responsive {
            border-radius: 6px;
            overflow: hidden;
        }
        .row {
            margin-bottom: 12px;
        }
        .col-md-4, .col-md-6 {
            margin-bottom: 12px;
        }
        .table-sm th, .table-sm td {
            padding: 0.4rem;
            font-size: 0.85rem;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
        }
        .form-select {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
        }
    </style>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include(APPPATH . 'Views/incl/header-application.php'); ?>

        <!-- ========== App Menu ========== -->
        <?php include(APPPATH . 'Views/incl/menu-admin.php'); ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Métricas</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Selector de Programa Social -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="card-title mb-0">Filtros de Análisis</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select" id="programaSelector">
                                                <option value="">Todos los Programas Sociales</option>
                                                <?php foreach ($programas as $programa): ?>
                                                    <option value="<?= $programa['GRUPO_POBLACIONAL'] ?>"><?= $programa['GRUPO_POBLACIONAL'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPIs Principales -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Total Beneficiarios</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="totalBeneficiarios" data-target="<?= $total_beneficiarios ?>"><?= $total_beneficiarios ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #059212;">
                                                <span class="avatar-title rounded-circle" style="background-color: #059212;">
                                                    <i class="ri-group-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Beneficiarios Únicos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="beneficiariosUnicos" data-target="<?= $beneficiarios_unicos ?>"><?= $beneficiarios_unicos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #06D001;">
                                                <span class="avatar-title rounded-circle" style="background-color: #06D001;">
                                                    <i class="ri-user-star-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Beneficiarios Recurrentes</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="beneficiariosRecurrentes" data-target="<?= $beneficiarios_recurrentes ?>"><?= $beneficiarios_recurrentes ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #9BEC00;">
                                                <span class="avatar-title rounded-circle" style="background-color: #9BEC00;">
                                                    <i class="ri-repeat-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos de Género, Edad y Secciones -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Beneficiarios por Género</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="generoChart" width="250" height="120"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Beneficiarios por Rangos de Edad</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="edadChart" width="250" height="120"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Top 15 Secciones Electorales</h5>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showSeccionesComparator()">Comparar</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="max-height: 415px; overflow-y: auto;">
                                        <table class="table table-striped table-sm" id="seccionesTable">
                                            <thead>
                                                <tr>
                                                    <th>Sección</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($secciones_data as $seccion): ?>
                                                    <tr>
                                                        <td><?= $seccion['SECCION'] ?></td>
                                                        <td><?= $seccion['total'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top 5 Municipios - Ancho Completo -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top 5 Municipios</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="municipiosChart" width="800" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparativa Distritos Federales y Liderazgos -->
                    <div class="row mb-4">
                        <!-- Comparativa Distritos Federales -->
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Comparativa Distritos Federales</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="dfsSelector" class="form-label">Seleccionar Distritos Federales:</label>
                                        <select class="form-select" id="dfsSelector" multiple>
                                            <?php foreach ($distritos_federales as $df): ?>
                                                <option value="<?= $df['DISTRITO_FEDERAL'] ?>"><?= $df['DISTRITO_FEDERAL'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-primary mt-2" onclick="updateComparativaDFs()">Comparar DFs</button>
                                    </div>
                                    <canvas id="dfsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comparativa Liderazgos -->
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Comparativa Liderazgos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="liderazgosSelector" class="form-label">Seleccionar Liderazgos:</label>
                                        <select class="form-select" id="liderazgosSelector" multiple>
                                            <?php foreach ($liderazgos as $liderazgo): ?>
                                                <option value="<?= $liderazgo['LIDERAZGO'] ?>"><?= $liderazgo['LIDERAZGO'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-primary mt-2" onclick="updateComparativaLiderazgos()">Comparar Liderazgos</button>
                                    </div>
                                    <canvas id="liderazgosChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparativa Distritos Locales - Ancho Completo -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Comparativa Distritos Locales</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="dlsSelector" class="form-label">Seleccionar Distritos Locales:</label>
                                        <select class="form-select" id="dlsSelector" multiple>
                                            <?php foreach ($distritos_locales as $dl): ?>
                                                <option value="<?= $dl['DISTRITO_LOCAL'] ?>"><?= $dl['DISTRITO_LOCAL'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-primary mt-2" onclick="updateComparativaDLs()">Comparar DLs</button>
                                    </div>
                                    <canvas id="dlsChart" width="800" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include(APPPATH . 'Views/incl/footer-application.php'); ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Modal para Comparar Secciones -->
    <div class="modal fade" id="seccionesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comparar Secciones Electorales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Secciones a Comparar:</label>
                        <select class="form-select" id="seccionesComparatorSelector" multiple>
                            <?php foreach ($secciones as $seccion): ?>
                                <option value="<?= $seccion['SECCION'] ?>">Sección <?= $seccion['SECCION'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <canvas id="seccionesComparatorChart" width="250" height="120"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="updateComparativaSecciones()">Comparar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Scripts de la aplicación -->
    <?php include(APPPATH . 'Views/incl/scripts-application.php'); ?>

    <script>
        // Variables globales para los gráficos
        let generoChart, edadChart, municipiosChart, dfsChart, dlsChart, liderazgosChart, seccionesComparatorChart;
        let currentPrograma = '';

        // Datos iniciales
        const initialGeneroData = <?= json_encode($genero_data) ?>;
        const initialEdadData = <?= json_encode($edad_data) ?>;
        const initialMunicipiosData = <?= json_encode($municipios_data) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupEventListeners();
        });

        function initializeCharts() {
            // Gráfico de Género (Pie)
            const generoCtx = document.getElementById('generoChart').getContext('2d');
            generoChart = new Chart(generoCtx, {
                type: 'pie',
                data: {
                    labels: initialGeneroData.map(item => item.GENERO),
                    datasets: [{
                        data: initialGeneroData.map(item => item.total),
                        backgroundColor: ['#059212', '#06D001', '#9BEC00', '#F3FF90'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de Edad (Polar)
            const edadCtx = document.getElementById('edadChart').getContext('2d');
            edadChart = new Chart(edadCtx, {
                type: 'polarArea',
                data: {
                    labels: initialEdadData.map(item => item.rango_edad),
                    datasets: [{
                        data: initialEdadData.map(item => item.total),
                        backgroundColor: ['#059212', '#06D001', '#9BEC00', '#F3FF90', '#01b810'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de Municipios (Barras con border radius)
            const municipiosCtx = document.getElementById('municipiosChart').getContext('2d');
            municipiosChart = new Chart(municipiosCtx, {
                type: 'bar',
                data: {
                    labels: initialMunicipiosData.map(item => item.MUNICIPIO),
                    datasets: [{
                        label: 'Beneficiarios',
                        data: initialMunicipiosData.map(item => item.total),
                        backgroundColor: '#06D001',
                        borderColor: '#059212',
                        borderWidth: 1,
                        borderRadius: 10,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Inicializar gráficos vacíos para comparativas
            initializeEmptyCharts();
        }

        function initializeEmptyCharts() {
            // Gráfico DFs (Polar)
            const dfsCtx = document.getElementById('dfsChart').getContext('2d');
            dfsChart = new Chart(dfsCtx, {
                type: 'polarArea',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: ['#059212', '#06D001', '#9BEC00', '#F3FF90', '#01b810'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico DLs (Bubble)
            const dlsCtx = document.getElementById('dlsChart').getContext('2d');
            dlsChart = new Chart(dlsCtx, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Distritos Locales',
                        data: [],
                        backgroundColor: 'rgba(6, 208, 1, 0.6)',
                        borderColor: 'rgba(5, 146, 18, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Beneficiarios'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Promedio Apoyos'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `DL ${context.raw.label}: ${context.raw.x} beneficiarios, ${context.raw.y.toFixed(2)} promedio apoyos`;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico Liderazgos (Polar)
            const liderazgosCtx = document.getElementById('liderazgosChart').getContext('2d');
            liderazgosChart = new Chart(liderazgosCtx, {
                type: 'polarArea',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: ['#059212', '#06D001', '#9BEC00', '#F3FF90', '#01b810'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function setupEventListeners() {
            // Selector de programa
            document.getElementById('programaSelector').addEventListener('change', function() {
                currentPrograma = this.value;
                updateAllMetrics();
            });
        }

        function updateAllMetrics() {
            fetch('<?= base_url('dashboard-metrix/update-metrics') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'programa=' + encodeURIComponent(currentPrograma)
            })
            .then(response => response.json())
            .then(data => {
                // Actualizar contadores
                document.getElementById('totalBeneficiarios').textContent = data.total_beneficiarios;
                document.getElementById('beneficiariosUnicos').textContent = data.beneficiarios_unicos;
                document.getElementById('beneficiariosRecurrentes').textContent = data.beneficiarios_recurrentes;

                // Actualizar gráficos
                updateGeneroChart(data.genero_data);
                updateEdadChart(data.edad_data);
                updateMunicipiosChart(data.municipios_data);
                updateSeccionesTable(data.secciones_data);
            })
            .catch(error => console.error('Error:', error));
        }

        function updateGeneroChart(data) {
            generoChart.data.labels = data.map(item => item.GENERO);
            generoChart.data.datasets[0].data = data.map(item => item.total);
            generoChart.update();
        }

        function updateEdadChart(data) {
            edadChart.data.labels = data.map(item => item.rango_edad);
            edadChart.data.datasets[0].data = data.map(item => item.total);
            edadChart.update();
        }

        function updateMunicipiosChart(data) {
            municipiosChart.data.labels = data.map(item => item.MUNICIPIO);
            municipiosChart.data.datasets[0].data = data.map(item => item.total);
            municipiosChart.update();
        }

        function updateSeccionesTable(data) {
            const tbody = document.querySelector('#seccionesTable tbody');
            tbody.innerHTML = '';
            data.forEach(seccion => {
                const row = tbody.insertRow();
                row.insertCell(0).textContent = seccion.SECCION;
                row.insertCell(1).textContent = seccion.total;
            });
        }

        function updateComparativaDFs() {
            const selectedDFs = Array.from(document.getElementById('dfsSelector').selectedOptions).map(option => option.value);
            
            if (selectedDFs.length === 0) {
                alert('Por favor selecciona al menos un Distrito Federal');
                return;
            }

            fetch('<?= base_url('dashboard-metrix/comparativa-dfs') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dfs=' + selectedDFs.join(',') + '&programa=' + encodeURIComponent(currentPrograma)
            })
            .then(response => response.json())
            .then(data => {
                dfsChart.data.labels = data.map(item => `DF ${item.DISTRITO_FEDERAL}`);
                dfsChart.data.datasets[0].data = data.map(item => item.total);
                dfsChart.update();
            })
            .catch(error => console.error('Error:', error));
        }

        function updateComparativaDLs() {
            const selectedDLs = Array.from(document.getElementById('dlsSelector').selectedOptions).map(option => option.value);
            
            if (selectedDLs.length === 0) {
                alert('Por favor selecciona al menos un Distrito Local');
                return;
            }

            fetch('<?= base_url('dashboard-metrix/comparativa-dls') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dls=' + selectedDLs.join(',') + '&programa=' + encodeURIComponent(currentPrograma)
            })
            .then(response => response.json())
            .then(data => {
                const bubbleData = data.map(item => ({
                    x: parseInt(item.beneficiarios),
                    y: parseFloat(item.promedio_apoyos),
                    r: Math.sqrt(parseInt(item.total_apoyos)) / 10,
                    label: item.DISTRITO_LOCAL
                }));
                
                dlsChart.data.datasets[0].data = bubbleData;
                dlsChart.update();
            })
            .catch(error => console.error('Error:', error));
        }

        function updateComparativaLiderazgos() {
            const selectedLiderazgos = Array.from(document.getElementById('liderazgosSelector').selectedOptions).map(option => option.value);
            
            if (selectedLiderazgos.length === 0) {
                alert('Por favor selecciona al menos un Liderazgo');
                return;
            }

            fetch('<?= base_url('dashboard-metrix/comparativa-liderazgos') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'liderazgos=' + selectedLiderazgos.join(',') + '&programa=' + encodeURIComponent(currentPrograma)
            })
            .then(response => response.json())
            .then(data => {
                liderazgosChart.data.labels = data.map(item => item.LIDERAZGO);
                liderazgosChart.data.datasets[0].data = data.map(item => item.total);
                liderazgosChart.update();
            })
            .catch(error => console.error('Error:', error));
        }

        function showSeccionesComparator() {
            const modal = new bootstrap.Modal(document.getElementById('seccionesModal'));
            modal.show();
            
            // Inicializar gráfico del modal si no existe
            if (!seccionesComparatorChart) {
                const ctx = document.getElementById('seccionesComparatorChart').getContext('2d');
                seccionesComparatorChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Beneficiarios',
                            data: [],
                            backgroundColor: '#06D001',
                            borderColor: '#059212',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        function updateComparativaSecciones() {
            const selectedSecciones = Array.from(document.getElementById('seccionesComparatorSelector').selectedOptions).map(option => option.value);
            
            if (selectedSecciones.length === 0) {
                alert('Por favor selecciona al menos una Sección Electoral');
                return;
            }

            fetch('<?= base_url('dashboard-metrix/comparativa-secciones') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'secciones=' + selectedSecciones.join(',') + '&programa=' + encodeURIComponent(currentPrograma)
            })
            .then(response => response.json())
            .then(data => {
                seccionesComparatorChart.data.labels = data.map(item => `Sección ${item.SECCION}`);
                seccionesComparatorChart.data.datasets[0].data = data.map(item => item.total);
                seccionesComparatorChart.update();
            })
            .catch(error => console.error('Error:', error));
        }

        // Función para animar contadores
        function animateCounters() {
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
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 16);
            });
        }

        // Inicializar animación de contadores al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Resetear contadores a 0 antes de animar
            document.querySelectorAll('.counter-value').forEach(counter => {
                counter.textContent = '0';
            });
            
            // Iniciar animación después de un pequeño delay
            setTimeout(animateCounters, 500);
        });
    </script>

</body>
</html>