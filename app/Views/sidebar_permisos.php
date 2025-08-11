<?= view('incl/head-application', ['titulo_pagina' => $titulo]) ?>
    <!-- App css -->
    <link href="<?= base_url() . "public/files/"; ?>css/app.min.css?=t<?= time(); ?>" rel="stylesheet" type="text/css" />
    
    <style>
        .permiso-activo {
            background-color: rgba(0, 166, 80, 0.1);
            border-color: #00A650;
        }
        
        .permiso-inactivo {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .modulo-row {
            transition: all 0.3s ease;
        }
        
        .modulo-row:hover {
            background-color: #f8f9fa;
        }
        
        /* Estilos para el switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #00A650;
        }
        
        .slider:hover {
            background-color: #bbb;
        }
        
        input:checked + .slider:hover {
            background-color: #008C44;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        /* Alertas temporales */
        .alert-temp {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #00A650 0%, #008C44 100%);
            color: white;
        }
        
        .btn-config {
            background: linear-gradient(135deg, #00A650 0%, #3E8914 100%);
            border: none;
            color: white;
        }
        
        .btn-config:hover {
            background: linear-gradient(135deg, #3E8914 0%, #00A650 100%);
            color: white;
        }
        
        /* Estilos para tabla de permisos similar a la imagen */
        .table-responsive {
            max-height: calc(100vh - 280px);
            overflow-y: scroll;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        
        .permissions-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }
        
        .permissions-table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-align: center;
            padding: 15px 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .permissions-table td {
            text-align: center;
            padding: 12px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .permissions-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .module-name {
            text-align: left !important;
            font-weight: 500;
            color: #495057;
        }
        
        .module-name i {
            color: #6c757d;
            margin-right: 8px;
        }
        
        /* Checkbox personalizado para que se vea como en la imagen */
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #dee2e6;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .form-check-input:checked {
            background-color: #00A650;
            border-color: #00A650;
        }
        
        .form-check-input:hover {
            border-color: #00A650;
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 80, 0.25);
            border-color: #00A650;
        }
    </style>
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">

        <?= view('incl/header-application') ?>

        <!-- ========== App Menu ========== -->
        <?= view('incl/menu-admin') ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="">

            <div class="page-content">
                <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>
                                <?= $titulo ?>
                            </h4>
                            <div>
                                <button type="button" class="btn btn-config btn-sm" onclick="configurarDefecto()">
                                    <i class="fas fa-cog me-1"></i>
                                    Configurar por Defecto
                                </button>
                                <a href="<?= base_url() ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-home me-1"></i>
                                    Inicio
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$success): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= $mensaje ?>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table permissions-table">
                                    <thead>
                                        <tr>
                                            <th class="module-name">Módulo del Sidebar</th>
                                            <?php foreach ($roles as $rol): ?>
                                                <th class="text-center">
                                                    <i class="fas fa-user-tag me-1"></i>
                                                    <?= $rol['nombre'] ?>
                                                </th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($modulos as $moduloNombre => $moduloTitulo): ?>
                                            <tr>
                                                <td class="module-name">
                                                    <i class="fas fa-cube me-2"></i>
                                                    <strong><?= $moduloTitulo ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= $moduloNombre ?></small>
                                                </td>
                                                <?php foreach ($roles as $rol): ?>
                                                    <td class="text-center">
                                                        <div class="form-check d-flex justify-content-center">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   data-rol="<?= $rol['id'] ?>" 
                                                                   data-modulo="<?= $moduloNombre ?>"
                                                                   <?= isset($permisos[$rol['id']][$moduloNombre]) && $permisos[$rol['id']][$moduloNombre] ? 'checked' : '' ?>
                                                                   onchange="actualizarPermiso(this)">
                                                        </div>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>


                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?= view('incl/footer-application') ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- JAVASCRIPT -->
    <script src="<?= base_url() . "public/files/"; ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . "public/files/"; ?>libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url() . "public/files/"; ?>libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url() . "public/files/"; ?>libs/feather-icons/feather.min.js"></script>
    <script src="<?= base_url() . "public/files/"; ?>js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="<?= base_url() . "public/files/"; ?>js/plugins.js"></script>

    <!-- App js -->
    <script src="<?= base_url() . "public/files/"; ?>js/app.js"></script>
    <script>
        function actualizarPermiso(checkbox) {
            const rolId = checkbox.dataset.rol;
            const moduloNombre = checkbox.dataset.modulo;
            const valor = checkbox.checked;
            
            // Mostrar indicador de carga
            checkbox.disabled = true;
            
            fetch('<?= base_url() ?>sidebar-permisos/actualizar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `rol_id=${rolId}&modulo_nombre=${moduloNombre}&valor=${valor ? 1 : 0}`
            })
            .then(response => response.json())
            .then(data => {
                checkbox.disabled = false;
                
                if (data.success) {
                    mostrarMensaje('success', data.message);
                    // Actualizar estadísticas
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    mostrarMensaje('error', data.message);
                    // Revertir el checkbox si hay error
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(error => {
                checkbox.disabled = false;
                mostrarMensaje('error', 'Error de conexión');
                // Revertir el checkbox si hay error
                checkbox.checked = !checkbox.checked;
            });
        }
        
        function configurarDefecto() {
            if (confirm('¿Está seguro de que desea configurar los permisos por defecto? Esto sobrescribirá la configuración actual.')) {
                fetch('<?= base_url() ?>sidebar-permisos/configurar-defecto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        mostrarMensaje('error', data.message);
                    }
                })
                .catch(error => {
                    mostrarMensaje('error', 'Error de conexión');
                });
            }
        }
        
        function mostrarMensaje(tipo, mensaje) {
            // Remover mensajes anteriores
            const alertasAnteriores = document.querySelectorAll('.alert-temp');
            alertasAnteriores.forEach(alerta => alerta.remove());
            
            // Crear nueva alerta
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show alert-temp`;
            alerta.innerHTML = `
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alerta);
            
            // Auto-remover después de 3 segundos
            setTimeout(() => {
                if (alerta.parentNode) {
                    alerta.remove();
                }
            }, 3000);
        }
    </script>
</body>
</html>