<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Configuracion</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-12">
                <?= mostrar_alerta(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Configuracion SMTP</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url() . "configuracion/actualizar"; ?>" method="post">
                            <?= csrf_field(); ?>

                            <!-- Campo Correo Remitente -->
                            <div class="row mb-3">
                                <label for="fromEmail" class="col-sm-2 col-form-label">Correo Remitente</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control <?= session('validation.fromEmail') ? 'is-invalid' : '' ?>" id="fromEmail" name="fromEmail" value="<?= old('fromEmail', esc($configuracion['from_email'] ?? '')); ?>" required>
                                    <?php if (session('validation.fromEmail')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.fromEmail') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Nombre Remitente -->
                            <div class="row mb-3">
                                <label for="fromName" class="col-sm-2 col-form-label">Nombre Remitente</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control <?= session('validation.fromName') ? 'is-invalid' : '' ?>" id="fromName" name="fromName" value="<?= old('fromName', esc($configuracion['from_name'] ?? '')); ?>" required>
                                    <?php if (session('validation.fromName')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.fromName') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Host SMTP -->
                            <div class="row mb-3">
                                <label for="SMTPHost" class="col-sm-2 col-form-label">Host SMTP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control <?= session('validation.SMTPHost') ? 'is-invalid' : '' ?>" id="SMTPHost" name="SMTPHost" value="<?= old('SMTPHost', esc($configuracion['smtp_host'] ?? '')); ?>" required>
                                    <?php if (session('validation.SMTPHost')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.SMTPHost') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Usuario SMTP -->
                            <div class="row mb-3">
                                <label for="SMTPUser" class="col-sm-2 col-form-label">Usuario SMTP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control <?= session('validation.SMTPUser') ? 'is-invalid' : '' ?>" id="SMTPUser" name="SMTPUser" value="<?= old('SMTPUser', esc($configuracion['smtp_user'] ?? '')); ?>" required>
                                    <?php if (session('validation.SMTPUser')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.SMTPUser') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Contraseña SMTP -->
                            <div class="row mb-3">
                                <label for="SMTPPass" class="col-sm-2 col-form-label">Contraseña SMTP</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" class="form-control <?= session('validation.SMTPPass') ? 'is-invalid' : '' ?>" id="SMTPPass" name="SMTPPass" value="<?= old('SMTPPass', esc($configuracion['smtp_pass'] ?? '')); ?>" required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility()">
                                            <i class="ri-eye-close-line"></i>️
                                        </button>
                                    </div>
                                    <?php if (session('validation.SMTPPass')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.SMTPPass') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Puerto SMTP -->
                            <div class="row mb-3">
                                <label for="SMTPPort" class="col-sm-2 col-form-label">Puerto SMTP</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control <?= session('validation.SMTPPort') ? 'is-invalid' : '' ?>" id="SMTPPort" name="SMTPPort" value="<?= old('SMTPPort', esc($configuracion['smtp_port'] ?? '')); ?>" required>
                                    <?php if (session('validation.SMTPPort')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.SMTPPort') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campo Cifrado -->
                            <div class="row mb-3">
                                <label for="SMTPCrypto" class="col-sm-2 col-form-label">Cifrado</label>
                                <div class="col-sm-10">
                                    <select class="form-control <?= session('validation.SMTPCrypto') ? 'is-invalid' : '' ?>" id="SMTPCrypto" name="SMTPCrypto">
                                        <option value="" <?= ($configuracion['smtp_crypto'] ?? '') == '' ? 'selected' : ''; ?>>Ninguno</option>
                                        <option value="tls" <?= ($configuracion['smtp_crypto'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                        <option value="ssl" <?= ($configuracion['smtp_crypto'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                    </select>
                                    <?php if (session('validation.SMTPCrypto')): ?>
                                        <div class="text-danger">
                                            <?= session('validation.SMTPCrypto') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"> Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<!-- Para ver u ocultar la contraseña -->
<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('SMTPPass');
        const toggleButton = document.getElementById('togglePassword');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.innerHTML = '<i class="ri-eye-2-line"></i>'; // Cambia el icono a "ojo abierto"
        } else {
            passwordField.type = 'password';
            toggleButton.innerHTML = '<i class="ri-eye-close-line"></i>'; // Cambia el icono a "ojo cerrado"
        }
    }
</script>


