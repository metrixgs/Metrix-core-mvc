 <!-- resources/views/directorio/index.php -->
<div class="container-fluid px-0">
    <div class="px-3 py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-4 fw-bold text-primary mb-2 d-flex align-items-center gap-3" style="margin-top: 1cm;">
                    <i class="bi bi-people-fill text-primary"></i>
                    Directorio 
                </h1>
                <p class="text-muted fs-5 mb-0">Gestiona todos tus contactos y clientes desde un solo lugar</p>
            </div>
        </div>

        <!-- Flash Messages -->
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

        <!-- Controles -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small fw-medium">Mostrar:</span>
                                <select class="form-select form-select-sm border-2" style="width: 80px;">
                                    <option selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="vr d-none d-md-block"></div>
                            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 px-3">
                                <i class="bi bi-download"></i><span class="d-none d-sm-inline">Exportar</span>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 px-3">
                                <i class="bi bi-lightning-fill text-warning"></i><span class="d-none d-sm-inline">Acciones masivas</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex gap-3 align-items-center">
                        <div class="input-group input-group-lg shadow-sm rounded flex-grow-1">
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-search text-secondary fs-5"></i>
                            </span>
                            <input type="text" class="form-control border-0 ps-2" id="searchInput" placeholder="Buscar por empresa, contacto, email...">
                            <span class="input-group-text bg-white border-0">
                                <kbd class="bg-light text-secondary border rounded px-2 py-1" style="font-size: 0.8rem;">Ctrl + F</kbd>
                            </span>
                        </div>
                        <div id="searchCount" class="text-muted small fw-semibold" style="min-width: 140px;">
                            <?= isset($contactos) ? count($contactos) : 0 ?> contactos
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Agregar -->
        <div class="mb-4">
            <a href="<?= base_url('directorio/crear'); ?>" class="btn btn-success btn-lg d-inline-flex align-items-center gap-3 px-4 py-3 shadow-sm">
                <i class="bi bi-plus-circle-fill fs-4"></i>Agregar Nuevo Contacto
            </a>
        </div>

        <!-- Tabla -->
<!-- Tabla -->
 <div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-table me-2"></i>Lista de Ciudadanos</h5>
        <span class="badge bg-primary rounded-pill px-3 py-2"><?= isset($contactos) ? count($contactos) : 0 ?> contactos</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="selectAllCheckbox" /></th>
                        <th>#</th>
                        <th>Nombre(s) de Pila</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Código Ciudadano</th>
                        <th>Residencia</th>
                        <th>Estado</th>
                        <th>Expediente digital</th>
                        <th class="text-center"><i class="bi bi-gear me-1"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="contactsTable">
                    <?php if (!empty($contactos)): ?>
                        <?php $i = 1; foreach ($contactos as $c): ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="selectCheckbox" value="<?= esc($c['id']) ?>">
                                </td>
                                <td class="fw-bold"><?= $i++ ?></td>
                                <td><?= esc($c['nombre']) ?></td>
                                <td><?= esc($c['primer_apellido'] ?? '—') ?></td>
                                <td><?= esc($c['segundo_apellido'] ?? '—') ?></td>
                                <td><?= esc($c['codigo_ciudadano'] ?? '—') ?></td>
                                <td><?= esc($c['residencia'] ?? '—') ?></td>
                                <!-- Columna Estado con switch -->
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input estado-switch" type="checkbox"
                                               role="switch"
                                               data-id="<?= esc($c['id']) ?>"
                                               <?= !empty($c['activo']) ? 'checked' : '' ?>
                                               disabled>
                                        <label class="form-check-label">
                                            <?= !empty($c['activo']) ? 'Activo' : 'Inactivo' ?>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $nivel = rand(1, 5); // reemplaza si usas campo real
                                        for ($j = 1; $j <= 5; $j++) {
                                            $color = $j <= $nivel ? '#198754' : '#ccc';
                                            echo "<span style='display:inline-block;width:12px;height:12px;margin:2px;background:$color;border-radius:2px;'></span>";
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Acciones">
                                        <a href="<?= base_url('directorio/ver/' . $c['id']) ?>" class="btn btn-info">
                                            <i class="bi bi-eye-fill me-1"></i> Ver más
                                        </a>
                                        <a href="<?= base_url('directorio/editar/' . $c['id']) ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil-fill me-1"></i> Editar
                                        </a>
                                        <a href="<?= base_url('directorio/eliminar/' . $c['id']) ?>" class="btn btn-danger" onclick="return confirm('¿Deseas eliminar este contacto?')">
                                            <i class="bi bi-trash-fill me-1"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">No hay ciudadanos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Select all checkboxes (mantengo el original)
    document.getElementById('selectAllCheckbox').addEventListener('change', function(e) {
        const check = e.target.checked;
        document.querySelectorAll('.selectCheckbox').forEach(cb => cb.checked = check);
    });

    // Listener para switch de estado (requiere método AJAX en controlador)
    document.querySelectorAll('.estado-switch').forEach(sw => {
        sw.addEventListener('change', function() {
            const id = this.dataset.id;
            const activo = this.checked ? 1 : 0;

            fetch("<?= base_url('directorio/toggle-estado') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({ id, activo })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.nextElementSibling.textContent = activo ? 'Activo' : 'Inactivo';
                } else {
                    alert('No se pudo actualizar el estado');
                    this.checked = !activo;
                }
            })
            .catch(() => {
                alert('Error de red');
                this.checked = !activo;
            });
        });
    });
</script>



        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <?= $pager->links('group1', 'default_full') ?>
        </div>
    </div>

    <script>
        // Búsqueda
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#contactsTable tr');
            let visible = 0;

            rows.forEach(row => {
                const show = row.textContent.toLowerCase().includes(term);
                row.style.display = show ? '' : 'none';
                if (show && !row.querySelector('[colspan]')) visible++;
            });

            document.getElementById('searchCount').textContent = term === ''
                ? '<?= count($contactos ?? []) ?> contactos'
                : `${visible} contacto${visible !== 1 ? 's' : ''} encontrado${visible !== 1 ? 's' : ''}`;
        });

        // Atajo Ctrl + F
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        });

        // Select all
        document.getElementById('selectAllCheckbox').addEventListener('change', function(e) {
            const check = e.target.checked;
            document.querySelectorAll('.selectCheckbox').forEach(cb => cb.checked = check);
        });
    </script>
</div>
