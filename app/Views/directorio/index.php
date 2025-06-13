<!-- resources/views/directorio/index.php -->
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
                           <!-- Botón Exportar con Dropdown -->
<div class="dropdown">
    <button class="btn btn-success dropdown-toggle fw-bold d-flex align-items-center gap-2 px-3 py-2" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-box-arrow-down"></i> EXPORTAR
    </button>
    <ul class="dropdown-menu shadow-sm" aria-labelledby="exportDropdown">
        <li><a class="dropdown-item fw-semibold" href="<?= base_url('export/excel') ?>">EXCEL</a></li>
        <li><a class="dropdown-item fw-semibold" href="<?= base_url('export/pdf') ?>">PDF</a></li>
        <li><a class="dropdown-item fw-semibold" href="<?= base_url('export/csv') ?>">CSV</a></li>
    </ul>
</div>

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
       <!-- Botón Agregar estilo dividido, tamaño ajustado al contenido -->
<div class="mb-4">
    <a href="<?= base_url('directorio/crear'); ?>" class="btn btn-lg d-inline-flex align-items-stretch p-0 shadow-sm rounded overflow-hidden" style="background-color: #4CAF87;">
        <span class="d-flex align-items-center justify-content-center px-3" style="background-color: #2E7D62;">
            <i class="bi bi-plus-lg text-white fs-5"></i>
        </span>
        <span class="d-flex align-items-center justify-content-center px-4 fw-semibold text-white">
            ADD
        </span>
    </a>
</div>


        <!-- Tabla -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-table me-2"></i>Lista de Ciudadanos</h5>
                <span class="badge bg-primary rounded-pill px-3 py-2"><?= isset($contactos) ? count($contactos) : 0 ?> contactos</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px; text-align: center;">#</th>
                                <th style="width: 60px; text-align: center;">Tipo</th>
                                <th style="width: 120px;">Nombre(s)</th>
                                <th style="width: 140px;">Apellido Paterno</th>
                                <th style="width: 140px;">Apellido Materno</th>
                                <th style="width: 120px;">ID Ciudadano</th>
                                <th style="width: 150px;">Residencia</th>
                                <th style="width: 100px;">Líder</th>
                                <th style="width: 100px;">Enlace</th>
                                <th style="width: 120px; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="contactsTable">
                            <?php if (!empty($contactos)): ?>
                                <?php $i = 1; foreach ($contactos as $c): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $i ?></td>
                                        <td class="text-center">
                                            <?php 
                                                // Determinar tipo basado en algún criterio de tu base de datos
                                                // Por ejemplo, si tienes campo tipo_cliente o puedes usar otro criterio
                                                $tipo = !empty($c['tipo_cliente']) && $c['tipo_cliente'] == 'Comprador' ? 'BNF' : 'RED';
                                                $colorClass = $tipo == 'RED' ? 'bg-danger' : 'bg-primary';
                                            ?>
                                            <span class="badge <?= $colorClass ?> px-2 py-1" style="font-size: 0.7rem;">
                                                <?= $tipo ?>
                                            </span>
                                        </td>
                                        <td style="font-weight: 500;"><?= esc($c['nombre']) ?></td>
                                        <td><?= esc($c['primer_apellido'] ?? '—') ?></td>
                                        <td><?= esc($c['segundo_apellido'] ?? '—') ?></td>
                                        <td>
                                            <span class="text-primary fw-medium"><?= esc($c['codigo_ciudadano'] ?? '—') ?></span>
                                        </td>
                                        <td><?= esc($c['residencia'] ?? '—') ?></td>
                                        <td>
                                            <small class="text-muted"><?= esc($c['empresa'] ?? '—') ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= esc($c['cargo'] ?? '—') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-3">
                                               
                                                
                                                <!-- Ver perfil -->
                                                <a href="<?= base_url('directorio/ver/' . $c['id']) ?>" class="btn p-0 border-0 bg-transparent" title="Ver perfil" style="color: #6c757d;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <circle cx="12" cy="8" r="4"/>
                                                        <path d="M12 14c-6 0-8 3-8 6v2h16v-2c0-3-2-6-8-6z"/>
                                                    </svg>
                                                </a>
                                                
                                                <!-- Ubicación -->
                                                <button type="button" class="btn p-0 border-0 bg-transparent" title="Ubicación" style="color: #6c757d;" disabled>
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                                    </svg>
                                                </button>
                                                
                                            
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
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
    </script>
</div>