<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
        
        <!-- Estilos adicionales para centrar el formulario -->
        <style>
            /* Estilos para centrar verticalmente en todos los dispositivos */
            .auth-page-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 0;
            }
            
            .auth-page-content {
                padding: 0 !important;
                margin: auto;
                width: 100%;
            }
            
            .content-container {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            
            /* Estilo específico para dispositivos móviles */
            @media (max-width: 768px) {
                .auth-one-bg {
                    background-size: cover;
                    background-position: center;
                    min-height: 200px;
                }
                
                .card {
                    margin: 15px;
                }
                
                .p-lg-5 {
                    padding: 1.5rem !important;
                }
            }
        </style>
    </head>

    <body>
        <!-- auth-page wrapper -->
        <div class="auth-page-wrapper auth-bg-cover d-flex justify-content-center align-items-center min-vh-100">
            <div class="bg-overlay"></div>
            <!-- auth-page content -->
            <div class="auth-page-content overflow-hidden">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4 auth-one-bg h-100">
                                            <!-- Aquí iría el contenido de la imagen o se aplicaría como background -->
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4">
                                            <div>
                                                <h5 class="text-primary">¡Bienvenido de nuevo!</h5>
                                                <p class="text-muted">Inicia sesión para gestionar Tickets y Requerimientos con Metrix.</p>
                                            </div>

                                            <?= mostrar_alerta(); ?>

                                            <div class="mt-4">
                                                <form id="login-form" action="<?= base_url() . "login/validar"; ?>" method="post">

                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Correo electrónico</label>
                                                        <input type="email" name="usuario" value="<?= old('usuario'); ?>" autocomplete="off" class="form-control" id="username" placeholder="Escribe el correo" required="">
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="float-end">
                                                            <a href="<?= base_url() . "recuperar"; ?>" class="text-muted">¿Olvidaste la contraseña?</a>
                                                        </div>
                                                        <label class="form-label" for="password-input">Contraseña</label>
                                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                                            <input type="password" value="<?= old('contrasena'); ?>" name="contrasena" class="form-control pe-5 password-input" placeholder="*******" id="password-input" required="">
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon">
                                                                <i class="ri-eye-fill align-middle"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                                        <label class="form-check-label" for="auth-remember-check">Recordar</label>
                                                    </div>

                                                    <div class="mt-4">
                                                        <button class="btn btn-success text-dark w-100" type="submit">Iniciar sesión</button>
                                                    </div>
                                                </form>
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

        </div>
        <!-- end auth-page wrapper -->

        <script>
            // --- Mostrar/Ocultar contraseña ---
            document.getElementById('password-addon').addEventListener('click', function () {
                const passwordInput = document.getElementById('password-input');
                const icon = this.querySelector('i');
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                icon.classList.toggle('ri-eye-fill');
                icon.classList.toggle('ri-eye-off-fill');
            });

            // --- Cargar datos almacenados al abrir la página ---
            window.addEventListener('DOMContentLoaded', function () {
                const savedUser = localStorage.getItem('usuario');
                const savedPassword = localStorage.getItem('contrasena');
                const remember = localStorage.getItem('recordar') === 'true';

                if (remember && savedUser && savedPassword) {
                    document.getElementById('username').value = savedUser;
                    document.getElementById('password-input').value = savedPassword;
                    document.getElementById('auth-remember-check').checked = true;
                }
            });

            // --- Guardar o eliminar datos en localStorage al enviar el formulario ---
            document.getElementById('login-form').addEventListener('submit', function () {
                const usuario = document.getElementById('username').value;
                const contrasena = document.getElementById('password-input').value;
                const recordar = document.getElementById('auth-remember-check').checked;

                if (recordar) {
                    localStorage.setItem('usuario', usuario);
                    localStorage.setItem('contrasena', contrasena);
                    localStorage.setItem('recordar', true);
                } else {
                    localStorage.removeItem('usuario');
                    localStorage.removeItem('contrasena');
                    localStorage.removeItem('recordar');
                }
            });
        </script>

        <!-- jQuery -->
        <script src="<?= base_url() . "public/files/"; ?>js/jquery.min.js"></script>
        <!-- Bootstrap Bundle -->
        <script src="<?= base_url() . "public/files/"; ?>js/bootstrap.bundle.min.js"></script>
        <!-- App Js -->
        <script src="<?= base_url() . "public/files/"; ?>js/app.min.js"></script>
    </body>
</html>