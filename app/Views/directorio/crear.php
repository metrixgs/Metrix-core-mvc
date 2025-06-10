<div class="card shadow-sm border-0" style="margin-top: 2cm;"></div>
    <div class="card-header bg-light border-bottom py-3">
        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-person-plus-fill me-2"></i>Registrar nuevo ciudadano</h5>
    </div>
    <div class="card-body">
        <?php if (session('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= base_url('directorio/guardar') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre(s)</label>
                    <input type="text" name="nombre" class="form-control" value="<?= old('nombre') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="primer_apellido" class="form-label">Apellido Paterno</label>
                    <input type="text" name="primer_apellido" class="form-control" value="<?= old('primer_apellido') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="segundo_apellido" class="form-label">Apellido Materno</label>
                    <input type="text" name="segundo_apellido" class="form-control" value="<?= old('segundo_apellido') ?>">
                    <small class="text-muted">Escriba N/D si no aplica</small>
                </div>
            </div>
<div class="row mb-3">
    <div class="col-md-12">
        <label for="residencia" class="form-label">Residencia</label>
        <input type="text" name="residencia" class="form-control" value="<?= old('residencia') ?>" placeholder="Ciudad o localidad donde reside">
    </div>
</div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="genero" class="form-label">Género</label>
                    <select name="genero" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="Femenino" <?= old('genero') == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                        <option value="Masculino" <?= old('genero') == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option value="Otro" <?= old('genero') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="<?= old('fecha_nacimiento') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="lugar_nacimiento" class="form-label">Lugar de nacimiento (Municipio, Estado)</label>
                    <input type="text" name="lugar_nacimiento" class="form-control" value="<?= old('lugar_nacimiento') ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" name="estado" class="form-control" value="<?= old('estado') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="municipio" class="form-label">Municipio</label>
                    <input type="text" name="municipio" class="form-control" value="<?= old('municipio') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="<?= old('telefono') ?>" placeholder="XXX-XXX-XXXX">
                </div>
                <div class="col-md-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="ejemplo@gmail.com">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="calle" class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control" value="<?= old('calle') ?>">
                </div>
                <div class="col-md-2">
                    <label for="numero_exterior" class="form-label">Número exterior</label>
                    <input type="text" name="numero_exterior" class="form-control" value="<?= old('numero_exterior') ?>">
                </div>
                <div class="col-md-2">
                    <label for="numero_interior" class="form-label">Número interior</label>
                    <input type="text" name="numero_interior" class="form-control" value="<?= old('numero_interior') ?>">
                </div>
                <div class="col-md-2">
                    <label for="colonia" class="form-label">Colonia</label>
                    <input type="text" name="colonia" class="form-control" value="<?= old('colonia') ?>">
                </div>
                <div class="col-md-2">
                    <label for="codigo_postal" class="form-label">Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control" value="<?= old('codigo_postal') ?>">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('directorio') ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar ciudadano</button>
            </div>
        </form>
    </div>
</div>
