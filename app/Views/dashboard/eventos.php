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
                                <div class="d-flex align-items-center">
                                    <select class="form-select" id="dashboardSelector" onchange="navigateToDashboard()" style="max-width: 200px; font-size: 14px; font-weight: 500; border: 1px solid #ddd; padding: 6px 10px;">
                                        <option value="panel">Incidencias</option>
                                        <option value="dashboard-metrix">Beneficiarios</option>
                                        <option value="dashboard-eventos">Corregidora</option>
                                    </select>
                                </div>

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
                        <div class="col-xl col-md-4">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Total Ciudadanos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="totalCiudadanos" data-target="<?= $total_ciudadanos ?>"><?= $total_ciudadanos ?></span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle" style="background-color: #10b981;">
                                                <span class="avatar-title rounded-circle" style="background-color: #10b981;">
                                                    <i class="ri-group-line fs-16"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl col-md-4">
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
                        
                        <div class="col-xl col-md-4">
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
                        
                        <div class="col-xl col-md-4">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Promedio de Apoyos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="promedioApoyos" data-target="<?= $promedio_apoyos ?>"><?= number_format($promedio_apoyos * 100, 1, '.', '') ?>%</span>
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
                        
                        <div class="col-xl col-md-4">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Promedio de Eventos</p>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                <span class="counter-value" id="promedioEventos" data-target="<?= $promedio_eventos ?>"><?= number_format($promedio_eventos * 100, 1, '.', '') ?>%</span>
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
                                    <h5 class="card-title mb-0">Comparativa Líderes</h5>
                                    <div>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#lideresModal">
                                            <i class="fas fa-users me-2"></i>Comparar
                                        </button>
                                        <span id="selectedLideresCount" class="ms-2 text-muted">0 líderes seleccionados</span>
                                    </div>
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
                                    <div style="height: 500px; position: relative;">
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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Top Apoyos por Sección Electoral</h4>
                                    <select class="form-select form-select-sm" id="topApoyosSeccionSelector" style="width: auto;">
                                        <option value="5">Top 5</option>
                                        <option value="10" selected>Top 10</option>
                                    </select>
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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Top Eventos por Sección Electoral</h4>
                                    <select class="form-select form-select-sm" id="topEventosSeccionSelector" style="width: auto;">
                                        <option value="5">Top 5</option>
                                        <option value="10" selected>Top 10</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div style="height: 400px; position: relative;">
                                        <canvas id="topEventosSeccionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    Gráfica de Barras de Coordinador
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card chart-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Comparativa Coordinadores</h4>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#coordinadoresModal">
                                <i class="fas fa-users me-2"></i>Comparar
                            </button>
                            <span id="selectedCoordinadoresCount" class="ms-2 text-muted">0 coordinadores seleccionados</span>
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
        let selectedLideres = []; // Array para almacenar los líderes seleccionados
        let lideresModal; // Variable para el modal de Bootstrap
        let selectedCoordinadores = []; // Array para almacenar los coordinadores seleccionados
        let coordinadoresModal; // Variable para el modal de coordinadores


        // Datos iniciales del servidor
        let initialData;
        let originalLideresData; // Variable para almacenar datos originales de líderes sin filtros
        
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
            
            // Guardar datos originales de líderes sin filtros
            originalLideresData = [...top_lideres_data];
            
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
            const num = typeof value === 'string' ? parseFloat(value) : value;
            // Verificar que sea un número válido
            if (isNaN(num)) return '0';
            // Formatear con comas como separadores de miles
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        
        // Función específica para formatear promedios (valores decimales)
        function formatPromedio(value) {
            const num = parseFloat(value);
            // Formatear con 1 decimal
            const formatted = num.toFixed(1);
            // Separar parte entera y decimal
            const parts = formatted.split('.');
            // Agregar comas como separadores de miles a la parte entera
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            // Unir con punto como separador decimal para promedios
            return parts.join('.');
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
                // Remover solo las comas (separadores de miles) y mantener puntos decimales
                const cleanValue = value.replace(/,/g, '');
                return parseFloat(cleanValue) || 0;
            }
            return parseFloat(value) || 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar selección por defecto de coordinadores ANTES de las gráficas
            if (initialData.coordinador_data && initialData.coordinador_data.length > 0) {
                selectedCoordinadores = initialData.coordinador_data.slice(0, 5).map(coord => coord.coordinador);
            }
            
            initializeCharts();

            setupEventListeners();
        });

        function initializeCharts() {
            createGeneroChart();
            createEdadChart();
            createTopLideresChart();
            createSectoresChart();
            createCoordinadorChart();
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
                                    size: 14
                                },
                                boxWidth: 15,
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
                
                // Actualizar colores usando la paleta azul
                charts.coordinador.data.datasets[0].backgroundColor = greenPalette[4] + 'B3';
                charts.coordinador.data.datasets[0].borderColor = greenPalette[4];
                charts.coordinador.data.datasets[1].backgroundColor = greenPalette[6] + 'B3';
                charts.coordinador.data.datasets[1].borderColor = greenPalette[6];
                charts.coordinador.data.datasets[2].backgroundColor = greenPalette[8] + 'B3';
                charts.coordinador.data.datasets[2].borderColor = greenPalette[8];
                
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
                            display: true,
                            position: 'top',
                            labels: {
                                generateLabels: function(chart) {
                                    return [{
                                        text: ' ',
                                        fillStyle: 'transparent',
                                        strokeStyle: 'transparent',
                                        lineWidth: 0
                                    }];
                                },
                                padding: 20
                            }
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
            
            // Inicializar el modal con todos los líderes
            initializeLideresModal();
            
            // Seleccionar todos los líderes por defecto usando datos filtrados
            selectedLideres = initialData.top_lideres_data.map(lider => lider.liderazgo);
            updateSelectedLideresCount();
            
            charts.topLideres = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Total Apoyos',
                        data: [],
                        backgroundColor: [],
                        borderColor: [],
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
                                color: '#000'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.r;
                                    return `${context.label}: ${formatToThousands(value)}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#000',
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
            
            // Actualizar la gráfica con la selección inicial
            updateComparativaLideres();
        }
        
        function updateComparativaLideres() {
            // Usar el array global selectedLideres en lugar del selector
            // Filtrar datos según la selección
            const filteredData = initialData.top_lideres_data.filter(item => 
                selectedLideres.includes(item.liderazgo)
            );
            
            // Calcular el total de apoyos para los porcentajes
            const total = filteredData.reduce((sum, item) => sum + parseToNumber(item.total_apoyos), 0);
            
            // Generar colores dinámicos
            const colors = filteredData.map((_, index) => {
                return greenPalette[index % greenPalette.length];
            });
            
            // Actualizar la gráfica con etiquetas que incluyen porcentajes
            charts.topLideres.data.labels = filteredData.map(item => {
                const percentage = total > 0 ? ((item.total_apoyos / total) * 100).toFixed(0) : 0;
                return `${item.liderazgo} (${percentage}%)`;
            });
            charts.topLideres.data.datasets[0].data = filteredData.map(item => item.total_apoyos);
            charts.topLideres.data.datasets[0].backgroundColor = colors.map(color => color.replace('60%)', '50%)'));
            charts.topLideres.data.datasets[0].borderColor = colors;
            
            // Actualizar las opciones de la leyenda para mostrar porcentajes
            charts.topLideres.options.plugins.legend.labels.generateLabels = function(chart) {
                const chartData = chart.data;
                return chartData.labels.map((label, index) => {
                    const percentage = total > 0 ? ((chartData.datasets[0].data[index] / total) * 100).toFixed(0) : 0;
                    return {
                        text: `${filteredData[index].liderazgo} (${percentage}%)`,
                        fillStyle: chartData.datasets[0].backgroundColor[index],
                        strokeStyle: chartData.datasets[0].backgroundColor[index],
                        pointStyle: 'circle',
                        index: index
                    };
                });
            };
            
            // Actualizar callbacks del tooltip para mostrar información detallada
            charts.topLideres.options.plugins.tooltip.callbacks.label = function(context) {
                const index = context.dataIndex;
                const item = filteredData[index];
                const percentage = total > 0 ? ((item.total_apoyos / total) * 100).toFixed(1) : 0;
                return `${item.liderazgo}: ${formatToThousands(item.total_apoyos)} apoyos (${percentage}%)`;
            };
            
            // Agregar o actualizar total como referencia
            const chartContainer = document.getElementById('topLideresChart').parentElement;
            let totalElement = chartContainer.querySelector('.chart-total');
            if (!totalElement) {
                totalElement = document.createElement('div');
                totalElement.className = 'chart-total';
                totalElement.style.cssText = 'position: absolute; bottom: 10px; right: 10px; background: #fff; border: 2px solid #ffd700; border-radius: 15px; padding: 5px 10px; font-weight: bold; font-size: 12px;';
                chartContainer.style.position = 'relative';
                chartContainer.appendChild(totalElement);
            }
            totalElement.textContent = `Total: ${formatToThousands(total)}`;
            
            charts.topLideres.update();
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

        function createColoniasChart() {
            const ctx = document.getElementById('coloniasChart').getContext('2d');
            
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
                                color: '#000',
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
                            color: '#000',
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
            
            // Agregar elemento de total al contenedor de la gráfica
            const chartContainer = ctx.canvas.parentElement;
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
            
            // Usar los coordinadores seleccionados globalmente o todos si no hay selección
            const filteredData = selectedCoordinadores.length > 0 
                ? initialData.coordinador_data.filter(item => selectedCoordinadores.includes(item.coordinador))
                : initialData.coordinador_data.slice(0, 5); // Mostrar los primeros 5 por defecto
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
                        backgroundColor: greenPalette[4] + 'B3', // 70% opacity
                        borderColor: greenPalette[4],
                        borderWidth: 2,
                        yAxisID: 'y'
                    }, {
                        label: 'Total Apoyos',
                        data: totalApoyos,
                        backgroundColor: greenPalette[6] + 'B3', // 70% opacity
                        borderColor: greenPalette[6],
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }, {
                        label: 'Total Eventos',
                        data: totalEventos,
                        backgroundColor: greenPalette[8] + 'B3', // 70% opacity
                        borderColor: greenPalette[8],
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
            // Event listener para el selector de líderes ya no es necesario
            // La comparativa se actualiza con el botón "Comparar Líderes"
            
            // Event listeners para filtros globales con actualización dinámica
            document.getElementById('liderazgoFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
            document.getElementById('coordinadorFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
            document.getElementById('seccionFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
            document.getElementById('coloniaFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
            document.getElementById('sectorFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
            document.getElementById('anioAltaFilter').addEventListener('change', function() {
                updateDynamicFilters();
                applyFilters();
            });
        }

        // Función updateTopLideresChart() removida - ahora se usa updateComparativaLideres()

        function updateDynamicFilters() {
            // Recopilar filtros actuales
            const currentFilters = {
                liderazgo: document.getElementById('liderazgoFilter').value,
                coordinador: document.getElementById('coordinadorFilter').value,
                seccion_electoral: document.getElementById('seccionFilter').value,
                colonia: document.getElementById('coloniaFilter').value,
                sector: document.getElementById('sectorFilter').value,
                anio_alta: document.getElementById('anioAltaFilter').value
            };

            // Llamada AJAX para obtener opciones actualizadas
            fetch('<?= base_url() ?>dashboard-eventos/selectors-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(currentFilters)
            })
            .then(response => response.json())
            .then(data => {
                updateSelectorsOptions(data);
            })
            .catch(error => {
                console.error('Error al actualizar filtros dinámicos:', error);
            });
        }

        function updateSelectorsOptions(data) {
            // Guardar valores seleccionados actuales
            const currentValues = {
                liderazgo: document.getElementById('liderazgoFilter').value,
                coordinador: document.getElementById('coordinadorFilter').value,
                seccion_electoral: document.getElementById('seccionFilter').value,
                colonia: document.getElementById('coloniaFilter').value,
                sector: document.getElementById('sectorFilter').value,
                anio_alta: document.getElementById('anioAltaFilter').value
            };

            // Actualizar cada selector
            updateSelectorOptions('liderazgoFilter', data.liderazgos, 'liderazgo', 'Todos los Liderazgos', currentValues.liderazgo);
            updateSelectorOptions('coordinadorFilter', data.coordinadores, 'coordinador', 'Todos los Coordinadores', currentValues.coordinador);
            updateSelectorOptions('seccionFilter', data.secciones_electorales, 'seccion', 'Todas las Secciones', currentValues.seccion_electoral);
            updateSelectorOptions('coloniaFilter', data.colonias, 'colonia', 'Todas las Colonias', currentValues.colonia);
            updateSelectorOptions('sectorFilter', data.sectores, 'sector', 'Todos los Sectores', currentValues.sector);
            updateSelectorOptions('anioAltaFilter', data.anios_alta, 'anio_alta', 'Todos los Años', currentValues.anio_alta);
        }

        function updateSelectorOptions(selectorId, options, valueField, defaultText, currentValue) {
            const selector = document.getElementById(selectorId);
            if (!selector) return;

            // Limpiar opciones existentes
            selector.innerHTML = '';

            // Agregar opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = defaultText;
            selector.appendChild(defaultOption);

            // Agregar nuevas opciones
            options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option[valueField];
                optionElement.textContent = option[valueField];
                
                // Mantener selección si la opción aún existe
                if (option[valueField] === currentValue) {
                    optionElement.selected = true;
                }
                
                selector.appendChild(optionElement);
            });
        }

        function applyFilters() {
            console.log('applyFilters() ejecutándose...');
            
            // Recopilar filtros
            currentFilters = {
                liderazgo: document.getElementById('liderazgoFilter').value,
                coordinador: document.getElementById('coordinadorFilter').value,
                seccion_electoral: document.getElementById('seccionFilter').value,
                colonia: document.getElementById('coloniaFilter').value,
                sector: document.getElementById('sectorFilter').value,
                anio_alta: document.getElementById('anioAltaFilter').value
            };
            
            console.log('Filtros aplicados:', currentFilters);

            // Llamada AJAX para actualizar métricas
            fetch('<?= base_url() ?>dashboard-eventos/update-metrics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(currentFilters)
            })
            .then(response => {
                console.log('Respuesta recibida:', response);
                return response.json();
            })
            .then(data => {
                console.log('=== RESPUESTA COMPLETA DEL SERVIDOR ===');
                console.log('Datos completos:', data);
                console.log('=== VERIFICACIÓN DE DATOS ESPECÍFICOS ===');
                console.log('genero_data:', data.genero_data);
                console.log('coordinador_data:', data.coordinador_data);
                console.log('top_lideres_data:', data.top_lideres_data);
                console.log('sectores_treemap_data:', data.sectores_treemap_data);
                console.log('=== INICIANDO ACTUALIZACIÓN ===');
                updateKPIs(data);
                updateAllCharts(data);
            })
            .catch(error => {
                console.error('Error en applyFilters:', error);
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
            
            // Actualizar filtros dinámicos para mostrar todas las opciones
            updateDynamicFilters();
            
            currentFilters = {};
            
            // Restaurar datos iniciales
            updateKPIs({
                total_apoyos: <?= $total_apoyos ?>,
                total_eventos: <?= $total_eventos ?>,
                total_ciudadanos: <?= $total_ciudadanos ?>,
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
            document.getElementById('totalCiudadanos').textContent = formatToThousands(data.total_ciudadanos || 0);
            document.getElementById('promedioApoyos').textContent = (data.promedio_apoyos || 0).toFixed(1);
            document.getElementById('promedioEventos').textContent = (data.promedio_eventos || 0).toFixed(1);
        }

        function updateAllCharts(data) {
            console.log('=== INICIO updateAllCharts ===');
            console.log('updateAllCharts() ejecutándose con datos:', data);
            console.log('Charts disponibles:', Object.keys(charts));
            console.log('Tipo de datos recibidos:', typeof data);
            console.log('¿Datos están vacíos?:', Object.keys(data).length === 0);
            
            // Actualizar gráfico de género
            if (data.genero_data && charts.genero) {
                console.log('Actualizando gráfico de género:', data.genero_data);
                console.log('Datos de género antes del filtro:', data.genero_data);
                console.log('Chart de género existe:', !!charts.genero);
                const total = data.genero_data.reduce((sum, item) => sum + parseToNumber(item.total_personas), 0);
                charts.genero.data.labels = data.genero_data.map(item => {
                    const percentage = ((item.total_personas / total) * 100).toFixed(1);
                    return `${item.genero}: ${item.total_personas} (${percentage}%)`;
                });
                charts.genero.data.datasets[0].data = data.genero_data.map(item => item.total_personas);
                
                // Actualizar las opciones de la leyenda para usar datos filtrados
                charts.genero.options.plugins.legend.labels.generateLabels = function(chart) {
                    const chartData = chart.data;
                    return chartData.labels.map((label, index) => {
                        const percentage = ((chartData.datasets[0].data[index] / total) * 100).toFixed(0);
                        return {
                            text: `${data.genero_data[index].genero} (${percentage}%)`,
                            fillStyle: chartData.datasets[0].backgroundColor[index],
                            strokeStyle: chartData.datasets[0].backgroundColor[index],
                            pointStyle: 'circle',
                            index: index
                        };
                    });
                };
                
                // Actualizar callbacks del tooltip para usar datos filtrados
                charts.genero.options.plugins.tooltip.callbacks.label = function(context) {
                    const index = context.dataIndex;
                    const item = data.genero_data[index];
                    const percentage = ((item.total_personas / total) * 100).toFixed(1);
                    return `${item.genero}: ${formatToThousands(item.total_personas)} personas (${percentage}%)`;
                };
                
                charts.genero.options.plugins.tooltip.callbacks.afterLabel = function(context) {
                    const index = context.dataIndex;
                    const apoyos = data.genero_data[index].total_apoyos;
                    return `Apoyos: ${formatToThousands(apoyos)}`;
                };
                
                // Actualizar total en el contenedor
                const chartContainer = document.querySelector('#generoChart').closest('.card-body');
                const totalElement = chartContainer.querySelector('.chart-total');
                if (totalElement) {
                    totalElement.textContent = `Total: ${formatToThousands(total)}`;
                }
                
                charts.genero.update();
                 console.log('Gráfico de género actualizado exitosamente');
            }
            
            // Actualizar gráfico de edad
            if (data.edad_data && charts.edad) {
                // Filtrar datos para excluir el rango 0-17 años
                const filteredEdadData = data.edad_data.filter(item => item.rango_edad !== '0-17');
                charts.edad.data.labels = filteredEdadData.map(item => `${item.rango_edad} años`);
                charts.edad.data.datasets[0].data = filteredEdadData.map(item => item.total_personas);
                charts.edad.update();
            }
            

            

            // Actualizar datos globales de líderes (sin actualizar la gráfica)
            if (data.top_lideres_data) {
                console.log('Actualizando datos globales de líderes:', data.top_lideres_data);
                // Actualizar los datos globales
                initialData.top_lideres_data = data.top_lideres_data;
                
                // Actualizar las opciones del modal con los nuevos datos
                populateLideresCheckboxes();
                
                // Actualizar selectedLideres para incluir solo los líderes disponibles en los datos filtrados
                const availableLideres = data.top_lideres_data.map(lider => lider.liderazgo);
                selectedLideres = availableLideres;
                updateSelectedLideresCount();
                
                // Actualizar la gráfica con los nuevos líderes filtrados
                updateComparativaLideres();
                
                // No actualizar automáticamente la gráfica con filtros generales
                // La gráfica solo se actualiza cuando se usa el botón "Comparar" del modal
                // if (selectedLideres && selectedLideres.length > 0) {
                //     updateComparativaLideres();
                // }
            }
            
            // Actualizar gráfica de sectores
             if (data.sectores_treemap_data) {
                 updateSectoresChart(data.sectores_treemap_data);
             }
            
            // Actualizar gráfica de apoyos por sector
            if (data.apoyos_por_sector_data && charts.apoyosPorSector) {
                charts.apoyosPorSector.data.labels = data.apoyos_por_sector_data.map(item => item.sector);
                charts.apoyosPorSector.data.datasets[0].data = data.apoyos_por_sector_data.map(item => parseToNumber(item.total_apoyos));
                
                // Actualizar total en el contenedor
                const total = data.apoyos_por_sector_data.reduce((sum, item) => sum + parseToNumber(item.total_apoyos), 0);
                const chartContainer = document.querySelector('#apoyosPorSectorChart').closest('.card-body');
                const totalElement = chartContainer.querySelector('.chart-total');
                if (totalElement) {
                    totalElement.textContent = `Total: ${formatToThousands(total)}`;
                }
                
                charts.apoyosPorSector.update();
            }
            
            // Actualizar gráfica de colonias
            if (data.colonias_data && charts.colonias) {
                // Actualizar datos globales
                initialData.colonias_data = data.colonias_data;
                
                // Actualizar gráfico con datos filtrados
                updateColoniasChart();
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
                const total = data.eventos_por_sector_data.reduce((sum, d) => sum + parseToNumber(d.total_eventos), 0);
                
                // Generar colores dinámicamente para los nuevos datos
                const colors = data.eventos_por_sector_data.map((_, index) => {
                    return greenPalette[index % greenPalette.length];
                });
                
                charts.eventosPorSector.data.labels = data.eventos_por_sector_data.map(item => item.sector);
                charts.eventosPorSector.data.datasets[0].data = data.eventos_por_sector_data.map(item => item.total_eventos);
                charts.eventosPorSector.data.datasets[0].backgroundColor = colors;
                
                // Actualizar las funciones de callback con los nuevos datos
                charts.eventosPorSector.options.plugins.legend.labels.generateLabels = function(chart) {
                    const chartData = chart.data;
                    return chartData.labels.map((label, index) => {
                        const value = chartData.datasets[0].data[index];
                        const percentage = ((value / total) * 100).toFixed(0);
                        return {
                            text: `${label} (${percentage}%)`,
                            fillStyle: chartData.datasets[0].backgroundColor[index],
                            strokeStyle: chartData.datasets[0].backgroundColor[index],
                            pointStyle: 'circle',
                            index: index
                        };
                    });
                };
                
                charts.eventosPorSector.options.plugins.tooltip.callbacks.label = function(context) {
                    const index = context.dataIndex;
                    const item = data.eventos_por_sector_data[index];
                    const percentage = ((item.total_eventos / total) * 100).toFixed(1);
                    return `${item.sector}: ${formatToThousands(item.total_eventos)} (${percentage}%)`;
                };
                
                // Actualizar el elemento de total
                const chartContainer = charts.eventosPorSector.canvas.parentElement;
                let totalElement = chartContainer.querySelector('.chart-total');
                if (!totalElement) {
                    totalElement = document.createElement('div');
                    totalElement.className = 'chart-total';
                    totalElement.style.cssText = 'position: absolute; bottom: 10px; right: 10px; background: #fff; border: 2px solid #ffd700; border-radius: 15px; padding: 5px 10px; font-weight: bold; font-size: 12px;';
                    chartContainer.style.position = 'relative';
                    chartContainer.appendChild(totalElement);
                }
                totalElement.textContent = `Total: ${formatToThousands(total)}`;
                
                charts.eventosPorSector.update();
            }
            
            if (data.eventos_por_lider_data && charts.eventosPorLider) {
                charts.eventosPorLider.data.labels = data.eventos_por_lider_data.map(item => item.liderazgo);
                charts.eventosPorLider.data.datasets[0].data = data.eventos_por_lider_data.map(item => item.promedio_eventos);
                charts.eventosPorLider.update();
            }
            

            
            // Actualizar datos globales de coordinadores
            if (data.coordinador_data) {
                console.log('Actualizando datos globales de coordinadores:', data.coordinador_data);
                // Solo actualizar los datos globales, la gráfica se actualiza únicamente con el botón comparar
                initialData.coordinador_data = data.coordinador_data;
                
                // Actualizar las opciones del modal con los nuevos datos
                populateCoordinadoresCheckboxes();
            }
            
            // Actualizar gráficos de top secciones electorales
            if (data.top_apoyos_seccion_data && charts.topApoyosSeccion) {
                // Actualizar datos globales
                initialData.top_apoyos_seccion_data = data.top_apoyos_seccion_data;
                
                // Actualizar gráfico con datos filtrados respetando el selector
                updateTopApoyosSeccionChart();
            }
            
            if (data.top_eventos_seccion_data && charts.topEventosSeccion) {
                // Actualizar datos globales
                initialData.top_eventos_seccion_data = data.top_eventos_seccion_data;
                
                // Actualizar gráfico con datos filtrados respetando el selector
                updateTopEventosSeccionChart();
            }
            
            console.log('=== FIN updateAllCharts ===');
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
            
            // Event listener para el selector
            document.getElementById('topApoyosSeccionSelector').addEventListener('change', updateTopApoyosSeccionChart);
        }
        
        function updateTopApoyosSeccionChart() {
            const topCount = parseInt(document.getElementById('topApoyosSeccionSelector').value);
            const sortedData = [...initialData.top_apoyos_seccion_data]
                .sort((a, b) => parseToNumber(b.total_apoyos) - parseToNumber(a.total_apoyos))
                .slice(0, topCount);
            
            // Generar colores diferentes para cada sección actualizada
            const seccionColors = sortedData.map((_, index) => {
                return greenPaletteInverted[index % greenPaletteInverted.length];
            });
            
            charts.topApoyosSeccion.data.labels = sortedData.map(item => `Sección ${item.seccion}`);
            charts.topApoyosSeccion.data.datasets[0].data = sortedData.map(item => parseToNumber(item.total_apoyos));
            charts.topApoyosSeccion.data.datasets[0].backgroundColor = seccionColors;
            charts.topApoyosSeccion.data.datasets[0].borderColor = seccionColors;
            charts.topApoyosSeccion.update();
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
            
            // Event listener para el selector
            document.getElementById('topEventosSeccionSelector').addEventListener('change', updateTopEventosSeccionChart);
        }
        
        function updateTopEventosSeccionChart() {
            const topCount = parseInt(document.getElementById('topEventosSeccionSelector').value);
            const sortedData = [...initialData.top_eventos_seccion_data]
                .sort((a, b) => parseToNumber(b.total_eventos) - parseToNumber(a.total_eventos))
                .slice(0, topCount);
            
            // Generar colores diferentes para cada sección actualizada
            const seccionColors = sortedData.map((_, index) => {
                return greenPaletteInverted[index % greenPaletteInverted.length];
            });
            
            charts.topEventosSeccion.data.labels = sortedData.map(item => `Sección ${item.seccion}`);
            charts.topEventosSeccion.data.datasets[0].data = sortedData.map(item => parseToNumber(item.total_eventos));
            charts.topEventosSeccion.data.datasets[0].backgroundColor = seccionColors;
            charts.topEventosSeccion.data.datasets[0].borderColor = seccionColors;
            charts.topEventosSeccion.update();
        }

        // Función para animar contadores con decimales
        function animateCounters() {
            const counters = document.querySelectorAll('.counter-value');
            
            counters.forEach(counter => {
                const targetStr = counter.getAttribute('data-target');
                const target = parseFloat(targetStr);
                const isDecimal = counter.id === 'promedioApoyos' || counter.id === 'promedioEventos';
                const duration = 2000; // 2 segundos
                const increment = target / (duration / 16); // 60 FPS
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    
                    if (isDecimal) {
                        // Para promedios, usar formatPromedio
                        counter.textContent = formatPromedio(current);
                    } else {
                        // Para enteros, usar formatToThousands
                        counter.textContent = formatToThousands(Math.floor(current));
                    }
                }, 16);
            });
        }

        // Funciones para el modal de selección de líderes
        function initializeLideresModal() {
            lideresModal = new bootstrap.Modal(document.getElementById('lideresModal'));
            populateLideresCheckboxes();
        }

        function populateLideresCheckboxes() {
            const container = document.getElementById('lideresCheckboxContainer');
            container.innerHTML = '';
            
            // Usar datos filtrados para mostrar solo los líderes disponibles según los filtros
            initialData.top_lideres_data.forEach((lider, index) => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-6 col-lg-4 mb-2';
                
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'form-check';
                
                const checkbox = document.createElement('input');
                checkbox.className = 'form-check-input';
                checkbox.type = 'checkbox';
                checkbox.id = `lider_${index}`;
                checkbox.value = lider.liderazgo;
                checkbox.checked = selectedLideres.includes(lider.liderazgo);
                
                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.setAttribute('for', `lider_${index}`);
                label.textContent = lider.liderazgo;
                
                checkboxDiv.appendChild(checkbox);
                checkboxDiv.appendChild(label);
                colDiv.appendChild(checkboxDiv);
                container.appendChild(colDiv);
            });
        }

        function openLideresModal() {
            populateLideresCheckboxes();
            lideresModal.show();
        }

        function selectAllLideres() {
            const checkboxes = document.querySelectorAll('#lideresCheckboxContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function clearAllLideres() {
            const checkboxes = document.querySelectorAll('#lideresCheckboxContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        function updateSelectedLideresCount() {
            document.getElementById('selectedLideresCount').textContent = selectedLideres.length;
        }

        function applyLideresSelection() {
            const checkboxes = document.querySelectorAll('#lideresCheckboxContainer input[type="checkbox"]:checked');
            selectedLideres = Array.from(checkboxes).map(checkbox => checkbox.value);
            
            // Actualizar el contador de líderes seleccionados
            updateSelectedLideresCount();
            
            // Actualizar el gráfico
            updateComparativaLideres();
            
            // Cerrar el modal
            lideresModal.hide();
        }

        // Funciones para el modal de selección de coordinadores
        function initializeCoordinadoresModal() {
            coordinadoresModal = new bootstrap.Modal(document.getElementById('coordinadoresModal'));
            populateCoordinadoresCheckboxes();
        }

        function populateCoordinadoresCheckboxes() {
            const container = document.getElementById('coordinadoresCheckboxContainer');
            container.innerHTML = '';
            
            initialData.coordinador_data.forEach((coordinador, index) => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-6 col-lg-4 mb-2';
                
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'form-check';
                
                const checkbox = document.createElement('input');
                checkbox.className = 'form-check-input';
                checkbox.type = 'checkbox';
                checkbox.id = `coordinador_${index}`;
                checkbox.value = coordinador.coordinador;
                checkbox.checked = selectedCoordinadores.includes(coordinador.coordinador);
                
                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.setAttribute('for', `coordinador_${index}`);
                label.textContent = coordinador.coordinador;
                
                checkboxDiv.appendChild(checkbox);
                checkboxDiv.appendChild(label);
                colDiv.appendChild(checkboxDiv);
                container.appendChild(colDiv);
            });
        }

        function openCoordinadoresModal() {
            populateCoordinadoresCheckboxes();
            coordinadoresModal.show();
        }

        function selectAllCoordinadores() {
            const checkboxes = document.querySelectorAll('#coordinadoresCheckboxContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function clearAllCoordinadores() {
            const checkboxes = document.querySelectorAll('#coordinadoresCheckboxContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        function updateSelectedCoordinadoresCount() {
            const count = selectedCoordinadores.length;
            document.getElementById('selectedCoordinadoresCount').textContent = `${count} coordinadores seleccionados`;
        }

        function applyCoordinadoresSelection() {
            const checkboxes = document.querySelectorAll('#coordinadoresCheckboxContainer input[type="checkbox"]:checked');
            selectedCoordinadores = Array.from(checkboxes).map(checkbox => checkbox.value);
            
            // Actualizar el contador de coordinadores seleccionados
            updateSelectedCoordinadoresCount();
            
            // Actualizar el gráfico
            updateComparativaCoordinadores();
            
            // Cerrar el modal
            coordinadoresModal.hide();
        }

        function updateComparativaCoordinadores() {
            if (charts.coordinador) {
                // Filtrar datos según la selección global
                const filteredData = initialData.coordinador_data.filter(item => 
                    selectedCoordinadores.includes(item.coordinador)
                );
                
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

        // Inicializar animación de contadores al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Resetear contadores a 0 antes de animar
            document.querySelectorAll('.counter-value').forEach(counter => {
                counter.textContent = '0';
            });
            
            // Iniciar animación después de un pequeño delay
            setTimeout(animateCounters, 500);
            
            // Inicializar el modal de líderes
            initializeLideresModal();
            
            // Inicializar el modal de coordinadores
            initializeCoordinadoresModal();
            
            // Actualizar contador de coordinadores ya seleccionados
            updateSelectedCoordinadoresCount();
        });

    </script>

    <!-- Modal para Selección de Líderes -->
    <div class="modal fade" id="lideresModal" tabindex="-1" aria-labelledby="lideresModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lideresModalLabel">
                        <i class="fas fa-users me-2"></i>Seleccionar Líderes para Comparar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="selectAllLideres()">
                                <i class="fas fa-check-double me-1"></i>Seleccionar Todo
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllLideres()">
                                <i class="fas fa-times me-1"></i>Limpiar Selección
                            </button>
                        </div>
                    </div>
                    <div class="row" id="lideresCheckboxContainer">
                        <!-- Los checkboxes se generarán dinámicamente aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="applyLideresSelection()">
                        <i class="fas fa-chart-bar me-2"></i>Aplicar Comparación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Selección de Coordinadores -->
    <div class="modal fade" id="coordinadoresModal" tabindex="-1" aria-labelledby="coordinadoresModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="coordinadoresModalLabel">
                        <i class="fas fa-user-tie me-2"></i>Seleccionar Coordinadores para Comparar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="selectAllCoordinadores()">
                                <i class="fas fa-check-double me-1"></i>Seleccionar Todo
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllCoordinadores()">
                                <i class="fas fa-times me-1"></i>Limpiar Selección
                            </button>
                        </div>
                    </div>
                    <div class="row" id="coordinadoresCheckboxContainer">
                        <!-- Los checkboxes se generarán dinámicamente aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="applyCoordinadoresSelection()">
                        <i class="fas fa-chart-bar me-2"></i>Aplicar Comparación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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

</body>
</html>