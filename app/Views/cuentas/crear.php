<div class="container mt-5" style="max-width: 600px;">
    <h2 class="header-title mb-4 text-center">Crear Perfil y Usuario de Cliente</h2>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('cuentas/store') ?>" method="post" class="needs-validation" novalidate>
        <h4 class="mb-3">Datos de la Cuenta</h4>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Cuenta:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
            <div class="invalid-feedback">Por favor, ingresa el nombre de la cuenta.</div>
        </div>

        <h4 class="mb-3">Datos del Usuario Administrador</h4>
        <div class="mb-3">
            <label for="admin_nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="admin_nombre" name="admin_nombre" required>
            <div class="invalid-feedback">Por favor, ingresa el nombre del administrador.</div>
        </div>

        <div class="mb-3">
            <label for="admin_email" class="form-label">Correo:</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
            <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
        </div>

        <div class="mb-3">
            <label for="admin_telefono" class="form-label">Teléfono:</label>
            <input type="tel" class="form-control" id="admin_telefono" name="admin_telefono" required>
            <div class="invalid-feedback">Por favor, ingresa un teléfono válido.</div>
        </div>

        <div class="mb-4">
            <label for="admin_password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            <div class="invalid-feedback">Por favor, ingresa una contraseña.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crear Usuario y Perfil</button>
    </form>
</div>

<style>
/* Estilos mejorados pero simples */
.container {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.header-title {
    color: #38a935;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: relative;
    padding-bottom: 15px;
}

.header-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(to right, #38a935, #2d8a2b);
    border-radius: 2px;
}

form {
    padding: 15px 0;
}

h4 {
    color: #2d8a2b;
    font-weight: 600;
    margin-top: 10px;
    padding-left: 10px;
    border-left: 4px solid #38a935;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #38a935;
    box-shadow: 0 0 0 0.2rem rgba(56, 169, 53, 0.25);
}

.btn-primary {
    background-color: #38a935;
    border-color: #38a935;
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.5px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #2d8a2b;
    border-color: #2d8a2b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(56, 169, 53, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

.alert-success {
    background-color: #e8f8e8;
    border-color: #38a935;
    color: #2d8a2b;
    border-radius: 8px;
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
}
</style>

<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>