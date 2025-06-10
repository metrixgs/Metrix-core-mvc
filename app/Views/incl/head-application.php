<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-preloader="disable" data-theme="default" data-theme-colors="default" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show" data-sidebar-user-show="" data-sidebar="light" data-sidebar-image="none" data-sidebar-size="">
    <head>

        <meta charset="utf-8" />
        <title><?= $titulo_pagina; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Metrix - Plataforma de Gestión de Condominios y Servicios Empresariales" name="description" />
        <meta content="Metrix" name="author" />

        <!-- SEO Meta Tags -->
        <meta name="keywords" content="gestión de condominios, administración de propiedades, control de accesos, servicios empresariales, reservas, seguridad, software de administración, plataformas para condominios, software para empresas, gestión de servicios, control de acceso para empresas, administración de propiedades inteligentes" />
        <meta name="robots" content="index, follow" />
        <meta name="robots" content="noarchive">

        <!-- Open Graph Meta Tags (para redes sociales) -->
        <meta property="og:title" content="Metrix - Plataforma de Gestión de Condominios y Servicios Empresariales" />
        <meta property="og:description" content="Metrix es una plataforma integral para gestionar condominios, propiedades y servicios empresariales, mejorando la administración, seguridad y eficiencia." />
        <meta property="og:image" content="<?= base_url() . "public/files/"; ?>images/logo-light.png?t=<?= time(); ?>" />
        <meta property="og:url" content="<?= base_url(); ?>" />
        <meta property="og:type" content="website" />

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Metrix - Plataforma de Gestión de Condominios y Servicios Empresariales" />
        <meta name="twitter:description" content="Metrix es la solución para la gestión eficiente de condominios y servicios empresariales, ofreciendo herramientas de control de acceso, reservas y más." />
        <meta name="twitter:image" content="<?= base_url() . "public/files/"; ?>images/logo-light.png?t=<?= time(); ?>" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url() . "public/files/"; ?>images/favicon.ico?t=<?= time(); ?>">

        <link href="<?= base_url() . "public/files/css/select2.min.css"; ?>?=t<?= time(); ?>" rel="stylesheet">

        <!-- Enlaces CSS de DataTables desde CDN -->
        <link rel="stylesheet" href="<?= base_url() . "public/files/"; ?>css/dataTables.bootstrap5.min.css?t=<?= time(); ?>">
        <link rel="stylesheet" href="<?= base_url() . "public/files/"; ?>css/responsive.bootstrap.min.css?t=<?= time(); ?>">
        <link rel="stylesheet" href="<?= base_url() . "public/files/"; ?>css/buttons.dataTables.min.css?t=<?= time(); ?>">

        <!-- jsvectormap css -->
        <link href="<?= base_url() . "public/files/"; ?>libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- gridjs css -->
        <link rel="stylesheet" href="<?= base_url() . "public/files/"; ?>libs/gridjs/theme/mermaid.min.css?=t<?= time(); ?>">

        <!-- Layout config Js -->
        <script src="<?= base_url() . "public/files/"; ?>js/layout.js?=t<?= time(); ?>"></script>
        <!-- Bootstrap Css -->
        <link href="<?= base_url() . "public/files/"; ?>css/bootstrap.min.css?=t<?= time(); ?>" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?= base_url() . "public/files/"; ?>css/icons.min.css?=t<?= time(); ?>" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= base_url() . "public/files/"; ?>css/app.min.css?=t<?= time(); ?>" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="<?= base_url() . "public/files/"; ?>css/custom.min.css?=t<?= time(); ?>" rel="stylesheet" type="text/css" />

        <!-- Estilos personalizados -->
        <link href="<?= base_url() . "public/files/"; ?>css/style.css?=t<?= time(); ?>" rel="stylesheet" type="text/css"/>

        <!-- multi.js css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() . "public/files/"; ?>libs/multi.js/multi.min.css?=t<?= time(); ?>" />
        <!-- autocomplete css -->
        <link rel="stylesheet" href="<?= base_url() . "public/files/"; ?>libs/%40tarekraafat/autocomplete.js/css/autoComplete.css?=t<?= time(); ?>">


        <!-- Para generar codigos QR -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <!-- Libreria para escanear -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

        <!-- Script para graficos -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    </head>

    <body>

        <!-- Begin page -->
        <div id="layout-wrapper">