<!-- app/Views/directorio/editar_tags.php -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container-fluid px-0">
    <div class="px-3 py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-4 fw-bold text-primary mb-2 d-flex align-items-center gap-3" style="margin-top: 1cm;">
                    <i class="bi bi-tags-fill text-primary"></i>
                    Editar Tags para <?= esc($citizen['nombre'] . ' ' . $citizen['primer_apellido']); ?>
                </h1>
                <p class="text-muted fs-5 mb-0">Gestiona los tags asociados a este ciudadano.</p>
            </div>
        </div>

        <?php if(session()->getFlashdata('mensaje')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                    <div><?= session()->getFlashdata('mensaje'); ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                    <div><?= session()->getFlashdata('error'); ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="<?= base_url('directorio/guardarTags/' . $citizen['id']); ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="tagsSelect" class="form-label fw-semibold">Seleccionar Tags:</label>
                        <select class="form-control" id="tagsSelect" name="tags[]" multiple="multiple" style="width: 100%;">
                            <?php foreach ($allTags as $tag): ?>
                                <option value="<?= esc($tag['id']); ?>"
                                    <?= in_array($tag['id'], $currentTagIds) ? 'selected' : ''; ?>>
                                    <?= esc($tag['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Selecciona uno o más tags para este ciudadano.</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('directorio'); ?>" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Tags</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tagsSelect').select2({
            placeholder: "Selecciona o busca tags",
            allowClear: true,
            tags: true, // Permite crear nuevos tags si no existen
            tokenSeparators: [',', ' ']
        });
    });
</script>