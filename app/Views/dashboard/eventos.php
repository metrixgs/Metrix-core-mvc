<?php
$titulo_pagina = "Dashboard de Eventos";
include(APPPATH . 'Views/incl/head-application.php');
?>

<style>
        .kpi-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
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
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
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
                                        <li class="breadcrumb-item active">Eventos</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Filtros General -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Filtros Generales</h5>
                                        <button class="btn btn-outline-secondary" onclick="clearFilters()">Limpiar Filtros</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Liderazgo</label>
                                            <select class="form-select" id="liderazgoFilter">
                                                <option value="">Todos los Liderazgos</option>
                                                <?php foreach ($liderazgos as $liderazgo): ?>
                                                    <option value="<?= $liderazgo['liderazgo'] ?>"><?= $liderazgo['liderazgo'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Coordinador</label>
                                            <select class="form-select" id="coordinadorFilter">
                                                <option value="">Todos los Coordinadores</option>
                                                <?php foreach ($coordinadores as $coordinador): ?>
                                                    <option value="<?= $coordinador['coordinador'] ?>"><?= $coordinador['coordinador'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Sección Electoral</label>
                                            <select class="form-select" id="seccionFilter">
                                                <option value="">Todas las Secciones</option>
                                                <?php foreach ($secciones_electorales as $seccion): ?>
                                                    <option value="<?= $seccion['seccion'] ?>"><?= $seccion['seccion'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Colonia</label>
                                            <select class="form-select" id="coloniaFilter">
                                                <option value="">Todas las Colonias</option>
                                                <?php foreach ($colonias as $colonia): ?>
                                                    <option value="<?= $colonia['colonia'] ?>"><?= $colonia['colonia'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Sector</label>
                                            <select class="form-select" id="sectorFilter">
                                                <option value="">Todos los Sectores</option>
                                                <?php foreach ($sectores as $sector): ?>
                                                    <option value="<?= $sector['sector'] ?>"><?= $sector['sector'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Año Alta</label>
                                            <select class="form-select" id="anioAltaFilter">
                                                <option value="">Todos los Años</option>
                                                <?php foreach ($anios_alta as $anio): ?>
                                                    <option value="<?= $anio['anio_alta'] ?>"><?= $anio['anio_alta'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPIs Principales -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Total Apoyos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="totalApoyos" data-target="<?= $total_apoyos ?>"><?= $total_apoyos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #1e3a8a;">
                                                <span class="avatar-title rounded-circle" style="background-color: #1e3a8a;">
                                                    <i class="ri-hand-heart-line fs-16"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Total de Asistencias</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="totalEventos" data-target="<?= $total_eventos ?>"><?= $total_eventos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #3b82f6;">
                                                <span class="avatar-title rounded-circle" style="background-color: #3b82f6;">
                                                    <i class="ri-calendar-event-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="col-xl-2 col-md-4">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">% Apoyos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="porcentajeApoyos" data-target="<?= $porcentaje_apoyos ?>"><?= $porcentaje_apoyos ?></span>%
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #60a5fa;">
                                                <span class="avatar-title rounded-circle" style="background-color: #60a5fa;">
                                                    <i class="ri-percent-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
                        <!-- <div class="col-xl-2 col-md-4">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">% Eventos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="porcentajeEventos" data-target="<?= $porcentaje_eventos ?>"><?= $porcentaje_eventos ?></span>%
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #93c5fd;">
                                                <span class="avatar-title rounded-circle" style="background-color: #93c5fd;">
                                                    <i class="ri-pie-chart-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Promedio de Apoyos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="promedioApoyos" data-target="<?= $promedio_apoyos ?>"><?= $promedio_apoyos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #2563eb;">
                                                <span class="avatar-title rounded-circle" style="background-color: #2563eb;">
                                                    <i class="ri-bar-chart-line fs-16"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Promedio de Eventos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="promedioEventos" data-target="<?= $promedio_eventos ?>"><?= $promedio_eventos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #1d4ed8;">
                                                <span class="avatar-title rounded-circle" style="background-color: #1d4ed8;">
                                                    <i class="ri-line-chart-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Demografía -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Demografía</h5>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Distribución por Género</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 458px; position: relative;">
                                        <canvas id="generoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Distribución de Edades</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 458px; position: relative;">
                                        <canvas id="edadChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Liderazgo y Organización -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Liderazgo y Organización</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Top Líderes</h5>
                                    <select class="form-select form-select-sm" id="topLideresSelector" style="width: auto;">
                                        <option value="5">Top 5</option>
                                        <option value="10" selected>Top 10</option>
                                        <option value="20">Top 20</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div style="height: 300px; position: relative;">
                                        <canvas id="topLideresChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Sectores</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="sectoresChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>





                    <!-- Gráfica Principal -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">% de Apoyos por Sector (Gráfica Polar)</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 458px; position: relative;">
                                        <canvas id="apoyosPorSectorChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Apoyos -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Apoyos</h5>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Promedio de Apoyos por Sector</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 350px; position: relative;">
                                        <canvas id="promedioApoyosSectorChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Top Colonias por Apoyos</h4>
                                    <select class="form-select form-select-sm" id="topColoniasSelector" style="width: auto;">
                                        <option value="5">Top 5</option>
                                        <option value="10" selected>Top 10</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div style="height: 350px; position: relative;">
                                        <canvas id="coloniasChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Eventos -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Eventos</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Evolución de Eventos por Año</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 300px;">
                                        <canvas id="eventosPorAnioChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Eventos por Sector</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 300px;">
                                        <canvas id="eventosPorSectorChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Promedio de Eventos por Líder</h4>
                                    <select id="eventosPorLiderSelector" class="form-select form-select-sm" style="width: auto;">
                                        <option value="3">Top 3</option>
                                        <option value="all" selected>Todos</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div style="height: 300px;">
                                        <canvas id="eventosPorLiderChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Top 10 Secciones Electorales -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Top 10 Secciones Electorales</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Top 10 Apoyos por Sección Electoral</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 400px; position: relative;">
                                        <canvas id="topApoyosSeccionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card chart-card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Top 10 Eventos por Sección Electoral</h4>
                                </div>
                                <div class="card-body">
                                    <div style="height: 400px; position: relative;">
                                        <canvas id="topEventosSeccionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica de Barras de Coordinador -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Comparación por Coordinador</h4>
                                    <div class="d-flex align-items-center">
                                        <label for="coordinadorSelector" class="form-label me-2 mb-0">Seleccionar Coordinadores:</label>
                                        <select id="coordinadorSelector" class="form-select" multiple style="min-width: 250px; max-width: 300px;">
                                            <!-- Opciones se llenarán dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div style="height: 400px; position: relative;">
                                        <canvas id="coordinadorChart"></canvas>
                                    </div>
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

    <?php include(APPPATH . 'Views/incl/scripts-application.php'); ?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <!-- D3.js para treemap -->
    <script src="https://d3js.org/d3.v7.min.js"></script>


    <script>
        // Variables globales para los datos
        let currentFilters = {};
        let charts = {};


        // Datos iniciales del servidor
        let initialData;
        
        try {
            console.log('Iniciando definición de initialData');
            
            // Usar datos con escape completo para evitar problemas de sintaxis
            const genero_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($genero_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const edad_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($edad_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const coordinador_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($coordinador_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const top_lideres_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($top_lideres_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const sectores_treemap_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($sectores_treemap_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const apoyos_por_sector_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($apoyos_por_sector_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const promedio_apoyos_sector_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($promedio_apoyos_sector_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const colonias_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($colonias_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const eventos_por_anio_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($eventos_por_anio_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const eventos_por_sector_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($eventos_por_sector_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const eventos_por_lider_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($eventos_por_lider_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            
            // Datos reales para las gráficas de secciones electorales
            const top_apoyos_seccion_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($top_apoyos_seccion_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            const top_eventos_seccion_data = <?= str_replace(["\r\n", "\r", "\n"], '', json_encode($top_eventos_seccion_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>;
            
            initialData = {
                genero_data: genero_data,
                edad_data: edad_data,
                coordinador_data: coordinador_data,
                top_lideres_data: top_lideres_data,
                sectores_treemap_data: sectores_treemap_data,
                apoyos_por_sector_data: apoyos_por_sector_data,
                promedio_apoyos_sector_data: promedio_apoyos_sector_data,
                colonias_data: colonias_data,
                eventos_por_anio_data: eventos_por_anio_data,
                eventos_por_sector_data: eventos_por_sector_data,
                eventos_por_lider_data: eventos_por_lider_data,
                top_apoyos_seccion_data: top_apoyos_seccion_data,
                top_eventos_seccion_data: top_eventos_seccion_data
            };
            
            console.log('initialData definido correctamente:', initialData);
        } catch (error) {
            console.error('Error al definir initialData:', error);
            // Definir datos vacíos como fallback
            initialData = {
                genero_data: [],
                edad_data: [],
                coordinador_data: [],
                top_lideres_data: [],
                sectores_treemap_data: [],
                apoyos_por_sector_data: [],
                promedio_apoyos_sector_data: [],
                colonias_data: [],
                eventos_por_anio_data: [],
                eventos_por_sector_data: [],
                eventos_por_lider_data: [],
                top_apoyos_seccion_data: [],
                top_eventos_seccion_data: []
            };
        }

        // Paleta de colores azul
        const greenPalette = [
            '#E0F2FF', '#BAE6FD', '#7DD3FC', '#38BDF8', 
            '#0EA5E9', '#0284C7', '#0369A1', '#075985',
            '#0C4A6E', '#082F49', '#E0F2FF', '#BAE6FD'
        ];
        
        // Paleta de colores azul invertida para gráficas de secciones electorales
        const greenPaletteInverted = [
            '#082F49', '#0C4A6E', '#075985', '#0369A1',
            '#0284C7', '#0EA5E9', '#38BDF8', '#7DD3FC',
            '#BAE6FD', '#E0F2FF', '#082F49', '#0C4A6E'
        ];

        // Función para formatear valores con separadores de miles
        function formatToThousands(value) {
            // Convertir a número si es string
            const num = typeof value === 'string' ? parseFloat(value.replace(/[.,]/g, '')) : value;
            // Formatear con comas como separadores de miles
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        
        // Función específica para formatear promedios (valores decimales)
        function formatPromedio(value) {
            const num = parseFloat(value);
            return num.toFixed(3).replace('.', ',');
        }

        // Función para formatear tooltips con separadores de miles
        function formatTooltipValue(value, label) {
            return label + ': ' + formatToThousands(value);
        }
        
        // Función para formatear tooltips de promedios
        function formatPromedioTooltip(value, label) {
            return label + ': ' + formatPromedio(value);
        }

        // Función para convertir valores a números enteros
        function parseToNumber(value) {
            if (typeof value === 'string') {
                // Remover separadores de miles y convertir a número
                return parseInt(value.replace(/[.,]/g, '')) || 0;
            }
            return parseInt(value) || 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();

            setupEventListeners();
        });

        function initializeCharts() {
            createGeneroChart();
            createEdadChart();
            createTopLideresChart();
            createSectoresChart();
            createCoordinadorChart();
            createApoyosPorSectorChart();
            createPromedioApoyosSectorChart();
            createColoniasChart();
            createEventosPorAnioChart();
            createEventosPorSectorChart();
            createEventosPorLiderChart();
            createTopApoyosSeccionChart();
            createTopEventosSeccionChart();
        }

        function createGeneroChart() {
            const ctx = document.getElementById('generoChart').getContext('2d');
            const total = initialData.genero_data.reduce((sum, d) => sum + parseToNumber(d.total_personas), 0);
            
            charts.genero = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: initialData.genero_data.map(item => {
                        const percentage = ((item.total_personas / total) * 100).toFixed(0);
                        return `${item.genero} (${percentage}%)`;
                    }),
                    datasets: [{
                        label: 'Personas',
                        data: initialData.genero_data.map(item => item.total_personas),
                        backgroundColor: greenPalette.slice(0, initialData.genero_data.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            align: 'center',
                            maxWidth: 300,
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                },
                                boxWidth: 12,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, index) => {
                                        const percentage = ((data.datasets[0].data[index] / total) * 100).toFixed(0);
                                        return {
                                            text: `${initialData.genero_data[index].genero} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[index],
                                            strokeStyle: data.datasets[0].backgroundColor[index],
                                            pointStyle: 'circle',
                                            index: index
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const item = initialData.genero_data[index];
                                    const percentage = ((item.total_personas / total) * 100).toFixed(1);
                                    return `${item.genero}: ${formatToThousands(item.total_personas)} personas (${percentage}%)`;
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    const apoyos = initialData.genero_data[index].total_apoyos;
                                    return `Apoyos: ${formatToThousands(apoyos)}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 14
                            },
                            formatter: function(value, context) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            }
                        }
                    },
                    layout: {
                        padding: {
                            bottom: 50
                        }
                    }
                }
            });
            
            // Agregar total como referencia
            const chartContainer = document.getElementById('generoChart').parentElement;
            let totalElement = chartContainer.querySelector('.chart-total');
            if (!totalElement) {
                totalElement = document.createElement('div');
                totalElement.className = 'chart-total';
                totalElement.style.cssText = 'position: absolute; bottom: 10px; right: 10px; background: #fff; border: 2px solid #ffd700; border-radius: 15px; padding: 5px 10px; font-weight: bold; font-size: 12px;';
                chartContainer.style.position = 'relative';
                chartContainer.appendChild(totalElement);
            }
            totalElement.textContent = `Total: ${formatToThousands(total)}`;
        }

        function initializeCoordinadorSelector() {
            const selector = document.getElementById('coordinadorSelector');
            
            // Limpiar opciones existentes
            selector.innerHTML = '';
            
            // Agregar opciones de coordinadores
            initialData.coordinador_data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.coordinador;
                option.textContent = item.coordinador;
                option.selected = true; // Seleccionar todos por defecto
                selector.appendChild(option);
            });
            
            // Agregar event listener para actualizar el gráfico
            selector.addEventListener('change', function() {
                updateCoordinadorChart();
            });
        }

        function getFilteredCoordinadorData() {
            const selector = document.getElementById('coordinadorSelector');
            const selectedCoordinadores = Array.from(selector.selectedOptions).map(option => option.value);
            
            // Si no hay coordinadores seleccionados, mostrar todos
            if (selectedCoordinadores.length === 0) {
                return initialData.coordinador_data;
            }
            
            // Filtrar datos por coordinadores seleccionados
            return initialData.coordinador_data.filter(item => 
                selectedCoordinadores.includes(item.coordinador)
            );
        }

        function updateCoordinadorChart() {
            if (charts.coordinador) {
                const filteredData = getFilteredCoordinadorData();
                const labels = filteredData.map(item => item.coordinador);
                const totalPersonas = filteredData.map(item => item.total_personas);
                const totalApoyos = filteredData.map(item => item.total_apoyos);
                const totalEventos = filteredData.map(item => item.total_eventos);
                const promedioApoyos = filteredData.map(item => parseFloat(item.promedio_apoyos));
                const promedioEventos = filteredData.map(item => parseFloat(item.promedio_eventos));
                
                // Actualizar datos del gráfico
                charts.coordinador.data.labels = labels;
                charts.coordinador.data.datasets[0].data = totalPersonas;
                charts.coordinador.data.datasets[1].data = totalApoyos;
                charts.coordinador.data.datasets[2].data = totalEventos;
                
                // Actualizar tooltips con nuevos datos de promedio
                charts.coordinador.options.plugins.tooltip.callbacks.afterLabel = function(context) {
                    const index = context.dataIndex;
                    return [
                        `Promedio Apoyos: ${promedioApoyos[index].toFixed(1)}`,
                        `Promedio Eventos: ${promedioEventos[index].toFixed(1)}`
                    ];
                };
                
                charts.coordinador.update();
            }
        }

        function createEdadChart() {
            const ctx = document.getElementById('edadChart').getContext('2d');
            // Filtrar datos para excluir el rango 0-17 años
            const filteredEdadData = initialData.edad_data.filter(item => item.rango_edad !== '0-17');
            
            // Generar colores diferentes para cada barra de edad
            const edadColors = filteredEdadData.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.edad = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filteredEdadData.map(item => `${item.rango_edad} años`),
                    datasets: [{
                        label: 'Personas',
                        data: filteredEdadData.map(item => item.total_personas),
                        backgroundColor: edadColors,
                        borderColor: edadColors.map(color => color),
                        borderWidth: 1
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatPromedio(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatTooltipValue(context.parsed.y, context.dataset.label);
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    const apoyos = filteredEdadData[index].total_apoyos;
                                    return `Apoyos: ${formatToThousands(apoyos)}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return formatToThousands(value);
                            }
                        }
                    }
                }
            });
        }





        function createTopLideresChart() {
            const ctx = document.getElementById('topLideresChart').getContext('2d');
            const topN = parseInt(document.getElementById('topLideresSelector').value) || 10;
            const data = initialData.top_lideres_data.slice(0, topN);
            
            // Generar colores dinámicos para cada tipo de liderazgo usando la paleta azul
            const colors = data.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.topLideres = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: data.map(item => item.liderazgo),
                    datasets: [{
                        label: 'Total Apoyos',
                        data: data.map(item => item.total_apoyos),
                        backgroundColor: colors.map(color => color.replace('60%)', '50%)')),
                        borderColor: colors,
                        borderWidth: 2
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
                                callback: function(value) {
                                    return formatToThousands(value);
                                },
                                backdropColor: 'transparent'
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
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    const total = data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                                    return data.labels.map((label, index) => {
                                        const value = data.datasets[0].data[index];
                                        const percentage = ((value / total) * 100).toFixed(0);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[index],
                                            strokeStyle: data.datasets[0].backgroundColor[index],
                                            pointStyle: 'circle',
                                            index: index
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.r;
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${formatToThousands(value)} (${percentage}%)`;
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    const eventos = data[index].total_eventos;
                                    return `Eventos: ${formatToThousands(eventos)}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            anchor: 'center',
                            align: 'center',
                            formatter: function(value, context) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            }
                        }
                    }
                }
            });
        }

        function createSectoresChart() {
            const ctx = document.getElementById('sectoresChart').getContext('2d');
            
            // Generar colores dinámicamente usando la paleta azul
            const colors = initialData.sectores_treemap_data.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.sectores = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialData.sectores_treemap_data.map(item => item.sector),
                    datasets: [{
                        label: 'Apoyos por Sector',
                        data: initialData.sectores_treemap_data.map(item => item.total_apoyos),
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    const apoyos = context.parsed.y;
                                    const eventos = initialData.sectores_treemap_data[dataIndex].total_eventos;
                                    return [
                                        `Apoyos: ${formatToThousands(apoyos)}`,
                                        `Eventos: ${eventos}`
                                    ];
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return formatToThousands(value);
                            },
                            color: '#333',
                            font: {
                                weight: 'bold',
                                size: 10
                            }
                        }
                    }
                }
            });
        }

        function updateSectoresChart(newData) {
            if (charts.sectores) {
                // Generar colores dinámicamente para los nuevos datos
                const colors = newData.map((_, index) => {
                    return greenPalette[index % greenPalette.length];
                });
                
                charts.sectores.data.labels = newData.map(item => item.sector);
                charts.sectores.data.datasets[0].data = newData.map(item => item.total_apoyos);
                charts.sectores.data.datasets[0].backgroundColor = colors;
                charts.sectores.data.datasets[0].borderColor = colors;
                
                // Actualizar los datos para los tooltips
                charts.sectores.options.plugins.tooltip.callbacks.label = function(context) {
                    const dataIndex = context.dataIndex;
                    const apoyos = context.parsed.y;
                    const eventos = newData[dataIndex].total_eventos;
                    return [
                        `Apoyos: ${formatToThousands(apoyos)}`,
                        `Eventos: ${eventos}`
                    ];
                };
                
                charts.sectores.update();
            }
        }

        function createApoyosPorSectorChart() {
            const ctx = document.getElementById('apoyosPorSectorChart').getContext('2d');
            const total = initialData.apoyos_por_sector_data.reduce((sum, d) => sum + parseToNumber(d.total_apoyos), 0);
            
            // Generar colores dinámicamente para la gráfica polar usando la paleta azul
            const colors = initialData.apoyos_por_sector_data.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.apoyosPorSector = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: initialData.apoyos_por_sector_data.map(item => item.sector),
                    datasets: [{
                        data: initialData.apoyos_por_sector_data.map(item => item.total_apoyos),
                        backgroundColor: colors,
                        borderWidth: 2,
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
                            maxWidth: 500,
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                },
                                boxWidth: 12,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, index) => {
                                        const value = data.datasets[0].data[index];
                                        const percentage = ((value / total) * 100).toFixed(0);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[index],
                                            strokeStyle: data.datasets[0].backgroundColor[index],
                                            pointStyle: 'circle',
                                            index: index
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const item = initialData.apoyos_por_sector_data[index];
                                    const percentage = ((item.total_apoyos / total) * 100).toFixed(1);
                                    return `${item.sector}: ${formatToThousands(item.total_apoyos)} apoyos (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            formatter: function(value, context) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            }
                        }
                    },
                    layout: {
                        padding: {
                            bottom: 50
                        }
                    }
                }
            });
            
            // Agregar total como referencia
            const chartContainer = document.getElementById('apoyosPorSectorChart').parentElement;
            let totalElement = chartContainer.querySelector('.chart-total');
            if (!totalElement) {
                totalElement = document.createElement('div');
                totalElement.className = 'chart-total';
                totalElement.style.cssText = 'position: absolute; bottom: 10px; right: 10px; background: #fff; border: 2px solid #ffd700; border-radius: 15px; padding: 5px 10px; font-weight: bold; font-size: 12px;';
                chartContainer.style.position = 'relative';
                chartContainer.appendChild(totalElement);
            }
            totalElement.textContent = `Total: ${formatToThousands(total)}`;
        }

        function createPromedioApoyosSectorChart() {
            const ctx = document.getElementById('promedioApoyosSectorChart').getContext('2d');
            charts.promedioApoyosSector = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialData.promedio_apoyos_sector_data.map(item => item.sector),
                    datasets: [{
                        label: 'Promedio Apoyos',
                        data: initialData.promedio_apoyos_sector_data.map(item => item.promedio_apoyos),
                        backgroundColor: greenPalette[3],
                        borderColor: greenPalette[4],
                        borderWidth: 1
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatPromedio(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatPromedioTooltip(context.parsed.y, context.dataset.label);
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return formatPromedio(value);
                            }
                        }
                    }
                }
            });
        }

        function createColoniasChart() {
            const ctx = document.getElementById('coloniasChart').getContext('2d');
            
            // Función para actualizar la gráfica según el selector
            function updateColoniasChart() {
                const topCount = parseInt(document.getElementById('topColoniasSelector').value);
                const sortedData = [...initialData.colonias_data]
                    .sort((a, b) => parseToNumber(b.total_apoyos) - parseToNumber(a.total_apoyos))
                    .slice(0, topCount);
                
                // Generar colores diferentes para cada colonia actualizada
                const coloniasColors = sortedData.map((_, index) => {
                    return greenPalette[index % greenPalette.length];
                });
                
                charts.colonias.data.labels = sortedData.map(item => item.colonia);
                charts.colonias.data.datasets[0].data = sortedData.map(item => parseToNumber(item.total_apoyos));
                charts.colonias.data.datasets[0].backgroundColor = coloniasColors;
                charts.colonias.data.datasets[0].borderColor = coloniasColors;
                charts.colonias.update();
            }
            
            // Datos iniciales (Top 10)
            const topCount = 10;
            const sortedData = [...initialData.colonias_data]
                .sort((a, b) => parseToNumber(b.total_apoyos) - parseToNumber(a.total_apoyos))
                .slice(0, topCount);
            
            // Generar colores diferentes para cada colonia
            const coloniasColors = sortedData.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.colonias = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sortedData.map(item => item.colonia),
                    datasets: [{
                        label: 'Total Apoyos',
                        data: sortedData.map(item => parseToNumber(item.total_apoyos)),
                        backgroundColor: coloniasColors,
                        borderColor: coloniasColors.map(color => color),
                        borderWidth: 1
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 10
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Apoyos'
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label;
                                    const value = context.parsed.y;
                                    return `${label}: ${formatToThousands(value)}`;
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return formatToThousands(value);
                            },
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    }
                }
            });
            
            // Event listener para el selector
            document.getElementById('topColoniasSelector').addEventListener('change', updateColoniasChart);
        }

        function createEventosPorAnioChart() {
            const ctx = document.getElementById('eventosPorAnioChart').getContext('2d');
            charts.eventosPorAnio = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Cantidad de Altas',
                        data: initialData.eventos_por_anio_data.map(item => ({
                            x: item.anio_alta,
                            y: item.total_eventos
                        })),
                        backgroundColor: greenPalette[4],
                        borderColor: greenPalette[5],
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: {
                                display: true,
                                text: 'Año de Alta'
                            },
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Math.round(value);
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Registros'
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatTooltipValue(context.parsed.y, context.dataset.label);
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value, context) {
                                return formatToThousands(value.y);
                            }
                        }
                    }
                }
            });
        }

        function createEventosPorSectorChart() {
            const ctx = document.getElementById('eventosPorSectorChart').getContext('2d');
            const total = initialData.eventos_por_sector_data.reduce((sum, d) => sum + parseToNumber(d.total_eventos), 0);
            
            // Generar colores dinámicamente para la gráfica polar usando la paleta azul
            const colors = initialData.eventos_por_sector_data.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.eventosPorSector = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: initialData.eventos_por_sector_data.map(item => item.sector),
                    datasets: [{
                        data: initialData.eventos_por_sector_data.map(item => item.total_eventos),
                        backgroundColor: colors,
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
                            maxWidth: 500,
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                },
                                boxWidth: 12,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, index) => {
                                        const value = data.datasets[0].data[index];
                                        const percentage = ((value / total) * 100).toFixed(0);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[index],
                                            strokeStyle: data.datasets[0].backgroundColor[index],
                                            pointStyle: 'circle',
                                            index: index
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const item = initialData.eventos_por_sector_data[index];
                                    const percentage = ((item.total_eventos / total) * 100).toFixed(1);
                                    return `${item.sector}: ${formatToThousands(item.total_eventos)} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            formatter: function(value, context) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            }
                        }
                    }
                }
            });
        }

        function createEventosPorLiderChart() {
            const ctx = document.getElementById('eventosPorLiderChart').getContext('2d');
            
            // Inicializar selector de eventos por líder
            initializeEventosPorLiderSelector();
            
            // Obtener datos filtrados según el selector
            const filteredData = getFilteredEventosPorLiderData();
            
            charts.eventosPorLider = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filteredData.map(item => item.liderazgo),
                    datasets: [{
                        label: 'Promedio Eventos',
                        data: filteredData.map(item => item.promedio_eventos),
                        backgroundColor: greenPalette[6],
                        borderColor: greenPalette[7],
                        borderWidth: 1
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 40,
                            bottom: 15,
                            left: 10,
                            right: 10
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatTooltipValue(context.parsed.y, context.dataset.label);
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            anchor: 'end',
                            align: 'top',
                            offset: 8,
                            formatter: function(value) {
                                return parseFloat(value).toFixed(1);
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
        
        function initializeEventosPorLiderSelector() {
            const selector = document.getElementById('eventosPorLiderSelector');
            if (selector) {
                selector.addEventListener('change', function() {
                    updateEventosPorLiderChart();
                });
            }
        }
        
        function getFilteredEventosPorLiderData() {
            const selector = document.getElementById('eventosPorLiderSelector');
            const selectedValue = selector ? selector.value : 'all';
            
            if (selectedValue === 'all') {
                return initialData.eventos_por_lider_data;
            } else {
                const topN = parseInt(selectedValue);
                return initialData.eventos_por_lider_data.slice(0, topN);
            }
        }
        
        function updateEventosPorLiderChart() {
            if (charts.eventosPorLider) {
                const filteredData = getFilteredEventosPorLiderData();
                
                charts.eventosPorLider.data.labels = filteredData.map(item => item.liderazgo);
                charts.eventosPorLider.data.datasets[0].data = filteredData.map(item => item.promedio_eventos);
                charts.eventosPorLider.update();
            }
        }



        function createCoordinadorChart() {
            const ctx = document.getElementById('coordinadorChart').getContext('2d');
            
            // Inicializar selector de coordinadores
            initializeCoordinadorSelector();
            
            // Preparar datos para la gráfica de barras
            const filteredData = getFilteredCoordinadorData();
            const labels = filteredData.map(item => item.coordinador);
            const totalPersonas = filteredData.map(item => item.total_personas);
            const totalApoyos = filteredData.map(item => item.total_apoyos);
            const totalEventos = filteredData.map(item => item.total_eventos);
            const promedioApoyos = filteredData.map(item => parseFloat(item.promedio_apoyos));
            const promedioEventos = filteredData.map(item => parseFloat(item.promedio_eventos));
            
            charts.coordinador = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Personas',
                        data: totalPersonas,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 2,
                        yAxisID: 'y'
                    }, {
                        label: 'Total Apoyos',
                        data: totalApoyos,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }, {
                        label: 'Total Eventos',
                        data: totalEventos,
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatTooltipValue(context.parsed.y, context.dataset.label);
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    return [
                                        `Promedio Apoyos: ${promedioApoyos[index].toFixed(1)}`,
                                        `Promedio Eventos: ${promedioEventos[index].toFixed(1)}`
                                    ];
                                }
                            }
                        },
                        datalabels: {
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            },
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 10
                            },
                            formatter: function(value, context) {
                                return formatToThousands(value);
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Total Personas'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Apoyos / Eventos'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function setupEventListeners() {
            // Event listener para el selector de top líderes
            document.getElementById('topLideresSelector').addEventListener('change', function() {
                updateTopLideresChart();
            });
            
            // Event listeners para filtros globales
            document.getElementById('liderazgoFilter').addEventListener('change', applyFilters);
            document.getElementById('coordinadorFilter').addEventListener('change', applyFilters);
            document.getElementById('seccionFilter').addEventListener('change', applyFilters);
            document.getElementById('coloniaFilter').addEventListener('change', applyFilters);
            document.getElementById('sectorFilter').addEventListener('change', applyFilters);
            document.getElementById('anioAltaFilter').addEventListener('change', applyFilters);
        }

        function updateTopLideresChart() {
            const topN = parseInt(document.getElementById('topLideresSelector').value);
            const data = initialData.top_lideres_data.slice(0, topN);
            
            // Generar colores dinámicos para cada tipo de liderazgo usando la paleta azul
            const colors = data.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            charts.topLideres.data.labels = data.map(item => item.liderazgo);
            charts.topLideres.data.datasets[0].data = data.map(item => item.total_apoyos);
            charts.topLideres.data.datasets[0].backgroundColor = colors;
            charts.topLideres.data.datasets[0].borderColor = colors;
            charts.topLideres.update();
        }

        function applyFilters() {
            // Recopilar filtros
            currentFilters = {
                liderazgo: document.getElementById('liderazgoFilter').value,
                coordinador: document.getElementById('coordinadorFilter').value,
                seccion_electoral: document.getElementById('seccionFilter').value,
                colonia: document.getElementById('coloniaFilter').value,
                sector: document.getElementById('sectorFilter').value,
                anio_alta: document.getElementById('anioAltaFilter').value
            };

            // Llamada AJAX para actualizar métricas
            fetch('<?= base_url() ?>dashboard-eventos/update-metrics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(currentFilters)
            })
            .then(response => response.json())
            .then(data => {
                updateKPIs(data);
                updateAllCharts(data);

            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function clearFilters() {
            // Limpiar todos los selectores
            document.getElementById('liderazgoFilter').value = '';
            document.getElementById('coordinadorFilter').value = '';
            document.getElementById('seccionFilter').value = '';
            document.getElementById('coloniaFilter').value = '';
            document.getElementById('sectorFilter').value = '';
            document.getElementById('anioAltaFilter').value = '';
            
            currentFilters = {};
            
            // Restaurar datos iniciales
            updateKPIs({
                total_apoyos: <?= $total_apoyos ?>,
                total_eventos: <?= $total_eventos ?>,
                porcentaje_apoyos: 100,
                porcentaje_eventos: 100,
                promedio_apoyos: <?= $promedio_apoyos ?>,
                promedio_eventos: <?= $promedio_eventos ?>
            });
            
            // Restaurar gráficos con datos iniciales
            Object.keys(charts).forEach(chartKey => {
                if (charts[chartKey]) {
                    charts[chartKey].destroy();
                }
            });
            
            initializeCharts();
            
        }

        function updateKPIs(data) {
            document.getElementById('totalApoyos').textContent = formatToThousands(data.total_apoyos || 0);
            document.getElementById('totalEventos').textContent = formatToThousands(data.total_eventos || 0);
            document.getElementById('porcentajeApoyos').textContent = data.porcentaje_apoyos || 0;
            document.getElementById('porcentajeEventos').textContent = data.porcentaje_eventos || 0;
            document.getElementById('promedioApoyos').textContent = data.promedio_apoyos || 0;
            document.getElementById('promedioEventos').textContent = data.promedio_eventos || 0;
        }

        function updateAllCharts(data) {
            // Actualizar gráfico de género
            if (data.genero_data && charts.genero) {
                const total = data.genero_data.reduce((sum, item) => sum + item.total_personas, 0);
                charts.genero.data.labels = data.genero_data.map(item => {
                    const percentage = ((item.total_personas / total) * 100).toFixed(1);
                    return `${item.genero}: ${item.total_personas} (${percentage}%)`;
                });
                charts.genero.data.datasets[0].data = data.genero_data.map(item => item.total_personas);
                charts.genero.update();
            }
            
            // Actualizar gráfico de edad
            if (data.edad_data && charts.edad) {
                // Filtrar datos para excluir el rango 0-17 años
                const filteredEdadData = data.edad_data.filter(item => item.rango_edad !== '0-17');
                charts.edad.data.labels = filteredEdadData.map(item => `${item.rango_edad} años`);
                charts.edad.data.datasets[0].data = filteredEdadData.map(item => item.total_personas);
                charts.edad.update();
            }
            

            

            // Actualizar gráfico de top líderes
            if (data.top_lideres_data && charts.topLideres) {
                const topN = parseInt(document.getElementById('topLideresSelector').value) || 10;
                const topData = data.top_lideres_data.slice(0, topN);
                
                // Generar colores dinámicos para cada tipo de liderazgo usando la paleta azul
                const colors = topData.map((_, index) => {
                    return greenPalette[index % greenPalette.length];
                });
                
                charts.topLideres.data.labels = topData.map(item => item.liderazgo);
                charts.topLideres.data.datasets[0].data = topData.map(item => item.total_apoyos);
                charts.topLideres.data.datasets[0].backgroundColor = colors;
                charts.topLideres.data.datasets[0].borderColor = colors;
                charts.topLideres.update();
            }
            
            // Actualizar gráfica de sectores
             if (data.sectores_treemap_data) {
                 updateSectoresChart(data.sectores_treemap_data);
             }
            
            // Actualizar gráficos de apoyos
            if (data.apoyos_por_sector_data && charts.apoyosPorSector) {
                const total = data.apoyos_por_sector_data.reduce((sum, item) => sum + item.total_apoyos, 0);
                
                // Generar colores dinámicos para la actualización
                const colors = data.apoyos_por_sector_data.map((_, index) => {
                    return greenPalette[index % greenPalette.length];
                });
                
                charts.apoyosPorSector.data.labels = data.apoyos_por_sector_data.map(item => item.sector);
                charts.apoyosPorSector.data.datasets[0].data = data.apoyos_por_sector_data.map(item => item.total_apoyos);
                charts.apoyosPorSector.data.datasets[0].backgroundColor = colors;
                
                // Actualizar leyendas con porcentajes
                charts.apoyosPorSector.options.plugins.legend.labels.generateLabels = function(chart) {
                    const data = chart.data;
                    return data.labels.map((label, index) => {
                        const percentage = ((data.datasets[0].data[index] / total) * 100).toFixed(0);
                        return {
                            text: `${label} (${percentage}%)`,
                            fillStyle: data.datasets[0].backgroundColor[index],
                            strokeStyle: data.datasets[0].backgroundColor[index],
                            pointStyle: 'circle',
                            index: index
                        };
                    });
                };
                
                charts.apoyosPorSector.update();
                
                // Actualizar total de referencia
                const chartContainer = document.getElementById('apoyosPorSectorChart').parentElement;
                const totalElement = chartContainer.querySelector('.chart-total');
                if (totalElement) {
                    totalElement.textContent = `Total: ${formatToThousands(total)}`;
                }
            }
            
            if (data.promedio_apoyos_sector_data && charts.promedioApoyosSector) {
                charts.promedioApoyosSector.data.labels = data.promedio_apoyos_sector_data.map(item => item.sector);
                charts.promedioApoyosSector.data.datasets[0].data = data.promedio_apoyos_sector_data.map(item => item.promedio_apoyos);
                charts.promedioApoyosSector.update();
            }
            
            // Actualizar gráficos de eventos
            if (data.eventos_por_anio_data && charts.eventosPorAnio) {
                charts.eventosPorAnio.data.datasets[0].data = data.eventos_por_anio_data.map(item => ({
                    x: item.anio_alta,
                    y: item.total_eventos
                }));
                charts.eventosPorAnio.update();
            }
            
            if (data.eventos_por_sector_data && charts.eventosPorSector) {
                charts.eventosPorSector.data.labels = data.eventos_por_sector_data.map(item => item.sector);
                charts.eventosPorSector.data.datasets[0].data = data.eventos_por_sector_data.map(item => item.total_eventos);
                charts.eventosPorSector.update();
            }
            
            if (data.eventos_por_lider_data && charts.eventosPorLider) {
                charts.eventosPorLider.data.labels = data.eventos_por_lider_data.map(item => item.liderazgo);
                charts.eventosPorLider.data.datasets[0].data = data.eventos_por_lider_data.map(item => item.promedio_eventos);
                charts.eventosPorLider.update();
            }
            

            
            // Actualizar gráfica de barras de coordinador
            if (data.coordinador_data) {
                // Actualizar datos globales
                initialData.coordinador_data = data.coordinador_data;
                
                // Reinicializar selector con nuevos datos
                initializeCoordinadorSelector();
                
                // Actualizar gráfico con datos filtrados
                updateCoordinadorChart();
            }
            
            // Actualizar gráficos de top secciones electorales
            if (data.top_apoyos_seccion_data && charts.topApoyosSeccion) {
                charts.topApoyosSeccion.data.labels = data.top_apoyos_seccion_data.map(item => `Sección ${item.seccion}`);
                charts.topApoyosSeccion.data.datasets[0].data = data.top_apoyos_seccion_data.map(item => parseToNumber(item.total_apoyos));
                charts.topApoyosSeccion.update();
            }
            
            if (data.top_eventos_seccion_data && charts.topEventosSeccion) {
                charts.topEventosSeccion.data.labels = data.top_eventos_seccion_data.map(item => `Sección ${item.seccion}`);
                charts.topEventosSeccion.data.datasets[0].data = data.top_eventos_seccion_data.map(item => parseToNumber(item.total_eventos));
                charts.topEventosSeccion.update();
            }
        }

        function createTopApoyosSeccionChart() {
            console.log('Creando gráfica de apoyos por sección');
            console.log('initialData:', initialData);
            console.log('top_apoyos_seccion_data:', initialData.top_apoyos_seccion_data);
            
            const ctx = document.getElementById('topApoyosSeccionChart').getContext('2d');
            
            // Verificar si hay datos
            if (!initialData.top_apoyos_seccion_data || initialData.top_apoyos_seccion_data.length === 0) {
                console.error('No hay datos para la gráfica de apoyos por sección');
                return;
            }
            
            // Generar colores dinámicos para cada sección usando la paleta azul invertida
            const colors = initialData.top_apoyos_seccion_data.map((_, index) => {
                return greenPaletteInverted[index % greenPaletteInverted.length];
            });
            
            charts.topApoyosSeccion = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialData.top_apoyos_seccion_data.map(item => `Sección ${item.seccion}`),
                    datasets: [{
                        label: 'Total Apoyos',
                        data: initialData.top_apoyos_seccion_data.map(item => parseToNumber(item.total_apoyos)),
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 2
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    const apoyos = context.parsed.y;
                                    const personas = initialData.top_apoyos_seccion_data[dataIndex].total_personas;
                                    return [
                                        `Apoyos: ${formatToThousands(apoyos)}`,
                                        `Personas: ${personas}`
                                    ];
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: '#333',
                            font: {
                                weight: 'bold',
                                size: 10
                            },
                            formatter: function(value) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context && context.parsed && context.parsed.y > 0;
                            }
                        }
                    }
                }
            });
        }

        function createTopEventosSeccionChart() {
            console.log('Creando gráfica de eventos por sección');
            console.log('initialData:', initialData);
            console.log('top_eventos_seccion_data:', initialData.top_eventos_seccion_data);
            
            const ctx = document.getElementById('topEventosSeccionChart').getContext('2d');
            
            // Verificar si hay datos
            if (!initialData.top_eventos_seccion_data || initialData.top_eventos_seccion_data.length === 0) {
                console.error('No hay datos para la gráfica de eventos por sección');
                return;
            }
            
            // Generar colores dinámicos para cada sección usando la paleta azul invertida
            const colors = initialData.top_eventos_seccion_data.map((_, index) => {
                return greenPaletteInverted[index % greenPaletteInverted.length];
            });
            
            charts.topEventosSeccion = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: initialData.top_eventos_seccion_data.map(item => `Sección ${item.seccion}`),
                    datasets: [{
                        label: 'Total Eventos',
                        data: initialData.top_eventos_seccion_data.map(item => parseToNumber(item.total_eventos)),
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 2
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatToThousands(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    const eventos = context.parsed.y;
                                    const personas = initialData.top_eventos_seccion_data[dataIndex].total_personas;
                                    return [
                                        `Eventos: ${formatToThousands(eventos)}`,
                                        `Personas: ${personas}`
                                    ];
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: '#333',
                            font: {
                                weight: 'bold',
                                size: 10
                            },
                            formatter: function(value) {
                                return formatToThousands(value);
                            },
                            display: function(context) {
                                return context && context.parsed && context.parsed.y > 0;
                            }
                        }
                    }
                }
            });
        }


    </script>

</body>
</html>