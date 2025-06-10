<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

    <head>
        <meta charset="utf-8" />
        <title>Metrix - Sistema de Gestión de Soporte y Requerimientos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Metrix es un sistema integral para gestionar tickets de soporte y requerimientos dentro de empresas. Permite la creación, asignación, trazabilidad, evaluación y gestión eficiente de tareas y solicitudes." />
        <meta name="author" content="Metrix" />

        <!-- SEO Meta Tags -->
        <meta name="keywords" content="sistema de tickets, gestión de soporte, trazabilidad de requerimientos, asignación de tareas, gestión empresarial, tickets de soporte, evaluación de gestión, control de tareas, software de soporte, gestión de requerimientos, creación de clientes, gestión en tiempo real" />
        <meta name="robots" content="index, follow" />
        <meta name="robots" content="noarchive">

        <!-- Open Graph Meta Tags (para redes sociales) -->
        <meta property="og:title" content="Metrix - Sistema de Gestión de Soporte y Requerimientos" />
        <meta property="og:description" content="Metrix permite gestionar tickets de soporte y requerimientos, asignarlos a áreas, evaluar su gestión, agregar comentarios, adjuntar archivos y más, todo en tiempo real." />
        <meta property="og:image" content="<?= base_url() . 'public/files/'; ?>images/logo-light.png?t=<?= time(); ?>" />
        <meta property="og:url" content="<?= base_url(); ?>" />
        <meta property="og:type" content="website" />

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Metrix - Sistema de Gestión de Soporte y Requerimientos" />
        <meta name="twitter:description" content="Metrix facilita la gestión de soporte técnico y requerimientos con herramientas avanzadas para asignar, rastrear, evaluar y gestionar tareas dentro de empresas." />
        <meta name="twitter:image" content="<?= base_url() . 'public/files/'; ?>images/logo-light.png?t=<?= time(); ?>" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url() . 'public/files/'; ?>images/favicon.ico?t=<?= time(); ?>">

        <!-- Layout config Js -->
        <script src="<?= base_url() . 'public/files/'; ?>js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="<?= base_url() . 'public/files/'; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?= base_url() . 'public/files/'; ?>css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= base_url() . 'public/files/'; ?>css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="<?= base_url() . 'public/files/'; ?>css/custom.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body>

        <!-- auth-page wrapper -->
        <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
            <div class="bg-overlay"></div>
            <!-- auth-page content -->
            <div class="auth-page-content overflow-hidden pt-lg-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4 auth-one-bg h-100">
                                            <div class="bg-overlay"></div>
                                            <div class="position-relative h-100 d-flex flex-column">
                                                <div class="mb-4 text-center">
                                                    <a href="index.html" class="d-block">
                                                        <img src="<?= base_url() . "public/files/"; ?>images/logo-dark.png?t=<?= time(); ?>" alt="" height="50">
                                                    </a>
                                                </div>
                                                <div class="mt-auto">
                                                    <div class="mb-3">
                                                        <i class="ri-double-quotes-l display-4 text-success"></i>
                                                    </div>

                                                    <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-indicators">
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                        </div>
                                                        <div class="carousel-inner text-center text-white-50 pb-5">
                                                            <div class="carousel-item active">
                                                                <p class="fs-15 fst-italic">"Metrix nos ha permitido centralizar la gestión de tickets de soporte y requerimientos, mejorando significativamente la colaboración entre áreas y optimizando los tiempos de respuesta."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">"Con Metrix hemos logrado una trazabilidad completa en la gestión de requerimientos, facilitando la asignación de tareas y la evaluación del desempeño de nuestro equipo."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">"La plataforma Metrix ha revolucionado nuestra gestión interna, permitiéndonos integrar comentarios, archivos y seguimiento en tiempo real desde cualquier lugar."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">"Gracias a Metrix, nuestros clientes pueden crear y gestionar tickets de soporte fácilmente, lo que ha mejorado nuestra eficiencia y la satisfacción del cliente."</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- end carousel -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4">
                                            <div>
                                                <h5 class="text-primary">¡Bienvenido de nuevo!</h5>
                                                <p class="text-muted">Recupera facilmente tu acceso a Metrix.</p>
                                            </div>

                                            <?= mostrar_alerta(); ?>

                                            <div class="mt-4">
                                                <form action="<?= base_url() . "autenticacion/recovery"; ?>" method="post">

                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Correo electronico</label>
                                                        <input type="mail" autofocus="" name="correo" value="<?= old('correo'); ?>" autocomplete="off" class="form-control" id="correo" placeholder="Escribe el correo" required="">
                                                    </div>

                                                    <div class="mt-4">
                                                        <button class="btn btn-success w-100" type="submit">Recuperar cuenta</button>
                                                    </div>

                                                </form>
                                            </div>

                                            <div class="mt-5 text-center">
                                                <p class="mb-0">¿Ya estas registrado? <a href="<?= base_url() . "autenticacion/inicio"; ?>" class="fw-semibold text-primary text-decoration-underline"> Iniciar sesion</a> </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->

                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end auth page content -->

            <!-- footer -->
            <footer class="footer galaxy-border-none">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0">&copy;
                                    <script>document.write(new Date().getFullYear())</script> Metrix.   Creado por <a class="text-white-50" href="https://wa.me/573027479401">Robinson H.V</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="<?= base_url() . "public/files/"; ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() . "public/files/"; ?>libs/simplebar/simplebar.min.js"></script>
        <script src="<?= base_url() . "public/files/"; ?>libs/node-waves/waves.min.js"></script>
        <script src="<?= base_url() . "public/files/"; ?>libs/feather-icons/feather.min.js"></script>
        <script src="<?= base_url() . "public/files/"; ?>js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="<?= base_url() . "public/files/"; ?>js/plugins.js"></script>

        <!-- password-addon init -->
        <script src="<?= base_url() . "public/files/"; ?>js/pages/password-addon.init.js"></script>
    </body>


</html>