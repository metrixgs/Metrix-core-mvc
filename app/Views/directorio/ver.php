<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Ciudadano</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
   
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 10px 25px rgba(0,0,0,0.1);
            --border-radius: 15px;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 0;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            margin-top: 2cm; /* <-- Añadido para bajar el header 2cm del techo */
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-right: 1rem;
        }

        .nav-tabs {
            border: none;
            background: white;
            border-radius: var(--border-radius);
            padding: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background: #f8f9fa;
            color: #495057;
        }

        .tab-content {
            margin-top: 2rem;
        }

        .info-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .info-item {
            padding: 1rem;
            border-bottom: 1px solid #f1f3f4;
            transition: background 0.2s ease;
        }

        .info-item:hover {
            background: #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #6c757d;
        }

        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 500;
        }

        /* Carnet Digital Styles */
        .carnet-container {
            perspective: 1000px;
            max-width: 400px;
            margin: 0 auto;
        }

        .carnet {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 20px;
            padding: 2rem;
            color: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            transform-style: preserve-3d;
            transition: transform 0.6s;
            position: relative;
            overflow: hidden;
        }

        .carnet::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
            background-size: 20px 20px;
        }

        .carnet-header {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .carnet-logo {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .carnet-title {
            font-size: 0.9rem;
            font-weight: 300;
            opacity: 0.9;
            margin: 0;
        }

        .carnet-body {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .carnet-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .carnet-id {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .qr-container {
            background: white;
            padding: 1rem;
            border-radius: 15px;
            margin: 1rem 0;
            display: flex;
            justify-content: center;
        }

        .carnet-footer {
            text-align: center;
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 1rem;
            position: relative;
            z-index: 2;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .btn-float {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-gradient);
            border: none;
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .btn-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .main-header {
                margin: -1rem -15px 2rem -15px;
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                margin-top: 2cm; /* También en móvil */
            }
            
            .carnet {
                margin: 0 1rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .scale-in {
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head></body>
<body>

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="profile-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold">
                        <?= esc($contacto['nombre'] ?? 'Sin nombre') ?> 
                        <?= esc($contacto['primer_apellido'] ?? '') ?> 
                        <?= esc($contacto['segundo_apellido'] ?? '') ?>
                    </h2>
                    <small class="opacity-75">Última conexión: <?= esc($contacto['fecha_actualizacion'] ?? 'N/D') ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#generales">
                    <i class="bi bi-person-lines-fill me-2"></i>Generales
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#id_digital" onclick="generateCarnet()">
                    <i class="bi bi-credit-card me-2"></i>ID Digital
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#ubicacion">
                    <i class="bi bi-geo-alt-fill me-2"></i>Ubicación
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#documentacion">
                    <i class="bi bi-file-earmark-text me-2"></i>Documentos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#actividad">
                    <i class="bi bi-activity me-2"></i>Actividad
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Datos Generales -->
            <div class="tab-pane fade show active" id="generales">
                <div class="info-card card fade-in">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Nombre Completo</div>
                                    <div class="info-value">
                                        <?= esc($contacto['nombre'] ?? '—') ?> 
                                        <?= esc($contacto['primer_apellido'] ?? '') ?> 
                                        <?= esc($contacto['segundo_apellido'] ?? '') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Correo Electrónico</div>
                                    <div class="info-value"><?= esc($contacto['email'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Teléfono</div>
                                    <div class="info-value"><?= esc($contacto['telefono'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Empresa</div>
                                    <div class="info-value"><?= esc($contacto['empresa'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Cargo</div>
                                    <div class="info-value"><?= esc($contacto['cargo'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Ocupación</div>
                                    <div class="info-value"><?= esc($contacto['ocupacion'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Nivel de Estudios</div>
                                    <div class="info-value"><?= esc($contacto['nivel_estudios'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Tipo de Cliente</div>
                                    <div class="info-value"><?= esc($contacto['tipo_cliente'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Tipo de Discapacidad</div>
                                    <div class="info-value"><?= esc($contacto['tipo_discapacidad'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Grupo Étnico</div>
                                    <div class="info-value"><?= esc($contacto['grupo_etnico'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Acepta Avisos</div>
                                    <div class="info-value">
                                        <span class="badge <?= ($contacto['acepta_avisos'] ?? 0) ? 'bg-success' : 'bg-secondary' ?> badge-custom">
                                            <?= ($contacto['acepta_avisos'] ?? 0) ? '✓ Sí' : '❌ No' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Acepta Términos</div>
                                    <div class="info-value">
                                        <span class="badge <?= ($contacto['acepta_terminos'] ?? 0) ? 'bg-success' : 'bg-secondary' ?> badge-custom">
                                            <?= ($contacto['acepta_terminos'] ?? 0) ? '✓ Sí' : '❌ No' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ID Digital -->
            <div class="tab-pane fade" id="id_digital">
                <div class="carnet-container fade-in">
                    <div class="carnet scale-in">
                        <div class="carnet-header">
                            <!-- Foto de perfil (vacía por defecto) -->
                            <div class="carnet-photo mb-3" style="width:80px;height:80px;margin:0 auto 1rem auto;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                <img src="https://i.ibb.co/VpYSG5vM/12225881.png" alt="Foto de perfil" style="width:100%;height:100%;object-fit:cover;">
                                <!-- <img src="ruta/a/foto.jpg" alt="Foto" style="width:100%;height:100%;object-fit:cover;"> -->
                            </div>
                            <div class="carnet-logo">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4 class="carnet-title">IDENTIFICACIÓN DIGITAL CIUDADANA</h4>
                        </div>
                        
                        <div class="carnet-body">
                            <h3 class="carnet-name">
                                <?= esc($contacto['nombre'] ?? 'Sin nombre') ?> 
                                <?= esc($contacto['primer_apellido'] ?? '') ?> 
                                <?= esc($contacto['segundo_apellido'] ?? '') ?>
                            </h3>
                            <div class="carnet-id">
                                <strong>ID: </strong><?= esc($contacto['codigo_ciudadano'] ?? 'N/D') ?>
                            </div>
                            
                            <div class="qr-container">
                                <canvas id="qr-code"></canvas>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <small>CURP</small>
                                    <div><?= esc($contacto['curp'] ?? 'N/D') ?></div>
                                </div>
                                <div class="col-6">
                                    <small>Nacimiento</small>
                                    <div><?= esc($contacto['fecha_nacimiento'] ?? 'N/D') ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="carnet-footer">
                            <p class="mb-0">Válido hasta: Dic <?= date('Y') + 1 ?></p>
                            <small>Portal Ciudadano Digital</small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary me-2" onclick="downloadCarnet()">
                        <i class="bi bi-download me-2"></i>Descargar
                    </button>
                    <button class="btn btn-outline-secondary" onclick="shareCarnet()">
                        <i class="bi bi-share me-2"></i>Compartir
                    </button>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="tab-pane fade" id="ubicacion">
                <div class="info-card card fade-in">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value"><?= esc($contacto['estado'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Municipio</div>
                                    <div class="info-value"><?= esc($contacto['municipio'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Localidad</div>
                                    <div class="info-value"><?= esc($contacto['localidad'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Colonia</div>
                                    <div class="info-value"><?= esc($contacto['colonia'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Calle</div>
                                    <div class="info-value"><?= esc($contacto['calle'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Código Postal</div>
                                    <div class="info-value"><?= esc($contacto['codigo_postal'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Número Exterior</div>
                                    <div class="info-value"><?= esc($contacto['numero_exterior'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Número Interior</div>
                                    <div class="info-value"><?= esc($contacto['numero_interior'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-item">
                                    <div class="info-label">Dirección Completa</div>
                                    <div class="info-value"><?= esc($contacto['direccion'] ?? '—') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentación -->
            <div class="tab-pane fade" id="documentacion">
                <div class="info-card card fade-in">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">CURP</div>
                                    <div class="info-value"><?= esc($contacto['curp'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Código Ciudadano</div>
                                    <div class="info-value"><?= esc($contacto['codigo_ciudadano'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Estado del Documento</div>
                                    <div class="info-value">
                                        <span class="badge bg-success badge-custom">✓ Verificado</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Fecha de Verificación</div>
                                    <div class="info-value"><?= esc($contacto['fecha_actualizacion'] ?? '—') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad -->
            <div class="tab-pane fade" id="actividad">
                <div class="info-card card fade-in">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Fecha de Registro</div>
                                    <div class="info-value"><?= esc($contacto['fecha_creacion'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Última Actualización</div>
                                    <div class="info-value"><?= esc($contacto['fecha_actualizacion'] ?? '—') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Estado de la Cuenta</div>
                                    <div class="info-value">
                                        <span class="badge <?= ($contacto['activo'] ?? 0) ? 'bg-success' : 'bg-secondary' ?> badge-custom">
                                            <?= ($contacto['activo'] ?? 0) ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">ID del Registro</div>
                                    <div class="info-value"><?= esc($contacto['id'] ?? '—') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="text-center" style="margin-top: 3.5rem; margin-bottom: 2.5rem;">
            <a href="<?= base_url('directorio') ?>" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Volver al listado
            </a>
        </div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button class="btn-float" data-bs-toggle="tooltip" title="Acciones rápidas">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Datos del ciudadano desde PHP
    const citizenData = {
        nombre: '<?= esc($contacto['nombre'] ?? '') ?> <?= esc($contacto['primer_apellido'] ?? '') ?> <?= esc($contacto['segundo_apellido'] ?? '') ?>',
        codigo_ciudadano: '<?= esc($contacto['codigo_ciudadano'] ?? '') ?>',
        curp: '<?= esc($contacto['curp'] ?? '') ?>',
        fecha_nacimiento: '<?= esc($contacto['fecha_nacimiento'] ?? '') ?>',
        id: '<?= esc($contacto['id'] ?? '') ?>',
        email: '<?= esc($contacto['email'] ?? '') ?>'
    };

    // Generar código QR para el carnet
    function generateCarnet() {
        setTimeout(() => {
            const qrData = JSON.stringify({
                id: citizenData.codigo_ciudadano || citizenData.id,
                name: citizenData.nombre.trim(),
                curp: citizenData.curp,
                email: citizenData.email,
                issued: new Date().toISOString()
            });
            
            const canvas = document.getElementById('qr-code');
            if (canvas) {
                QRCode.toCanvas(canvas, qrData, {
                    width: 120,
                    height: 120,
                    margin: 0,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                }, function (error) {
                    if (error) console.error('Error generando QR:', error);
                });
            }
        }, 100);
    }

    // Función para descargar el carnet
    function downloadCarnet() {
        const carnet = document.querySelector('.carnet');
        
        // Usar html2canvas si está disponible, sino mostrar mensaje
        if (typeof html2canvas !== 'undefined') {
            html2canvas(carnet).then(canvas => {
                const link = document.createElement('a');
                link.download = `carnet-${citizenData.codigo_ciudadano || citizenData.id}.png`;
                link.href = canvas.toDataURL();
                link.click();
            });
        } else {
            alert('Para descargar el carnet como imagen, es necesario incluir la librería html2canvas.');
        }
    }

    // Función para compartir el carnet
    function shareCarnet() {
        if (navigator.share) {
            navigator.share({
                title: 'Mi ID Digital',
                text: `ID Digital de ${citizenData.nombre}`,
                url: window.location.href
            });
        } else {
            // Fallback: copiar al portapapeles
            const shareData = `ID Digital: ${citizenData.nombre}\nCódigo: ${citizenData.codigo_ciudadano || citizenData.id}`;
            navigator.clipboard.writeText(shareData).then(() => {
                alert('Información copiada al portapapeles');
            }).catch(() => {
                // Fallback manual
                const textArea = document.createElement('textarea');
                textArea.value = shareData;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Información copiada al portapapeles');
            });
        }
    }

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Animaciones suaves al cambiar de pestaña
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            const target = document.querySelector(e.target.getAttribute('href'));
            if (target) {
                target.classList.add('fade-in');
            }
        });
    });
</script>
</body>
</html>