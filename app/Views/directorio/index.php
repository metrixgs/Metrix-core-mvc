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
                                <select class="form-select form-select-sm border-2" style="width: 80px;" onchange="window.location.href = '<?= base_url('directorio') ?>?perPage=' + this.value;">
                                    <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
                                    <option value="-1" <?= ($perPage == $totalContactos && $totalContactos > 0) ? 'selected' : '' ?>>Todos</option>
                                </select>
                            </div>
                            <div class="vr d-none d-md-block"></div>
                           <!-- Botón Exportar con Dropdown -->
 <div class="dropdown">
    <button class="btn btn-success dropdown-toggle fw-bold d-flex align-items-center gap-2 px-3 py-2" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-box-arrow-down"></i> EXPORTAR
    </button>
    <ul class="dropdown-menu shadow-sm" aria-labelledby="exportDropdown">
        <li><a class="dropdown-item fw-semibold" href="#" onclick="abrirModalExport('excel')">EXCEL</a></li>
        <li><a class="dropdown-item fw-semibold" href="#" onclick="abrirModalExport('pdf')">PDF</a></li>
        <li><a class="dropdown-item fw-semibold" href="#" onclick="abrirModalExport('csv')">CSV</a></li>
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
                            <?= $totalContactos ?> contactos
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Agregar -->
       <!-- Botón Agregar estilo dividido, tamaño ajustado al contenido -->
<div class="mb-4 d-flex gap-2">
    <a href="<?= base_url('directorio/crear'); ?>" class="btn btn-lg d-inline-flex align-items-stretch p-0 shadow-sm rounded overflow-hidden" style="background-color: #4CAF87;">
        <span class="d-flex align-items-center justify-content-center px-3" style="background-color: #2E7D62;">
            <i class="bi bi-plus-lg text-white fs-5"></i>
        </span>
        <span class="d-flex align-items-center justify-content-center px-4 fw-semibold text-white">
            ADD
        </span>
    </a>
    <!-- Botón para importar CSV -->
    <button type="button" class="btn btn-lg btn-info d-inline-flex align-items-center gap-2 px-4 fw-semibold text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#importCsvModal">
        <i class="bi bi-upload fs-5"></i> IMPORTAR CSV
    </button>
</div>

<!-- Modal de Importación CSV -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-success text-white"> <!-- Cambiado a bg-success -->
                <h5 class="modal-title" id="importCsvModalLabel"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Importar Ciudadanos desde CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('directorio/importarCsv'); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="text-muted">Sube un archivo CSV para agregar masivamente nuevos ciudadanos al directorio. Asegúrate de que el archivo tenga el formato correcto.</p>
                    <div class="mb-3">
                        <label for="csvFile" class="form-label fw-semibold">Seleccionar archivo CSV:</label>
                        <input class="form-control" type="file" id="csvFile" name="csvFile" accept=".csv" required>
                        <div class="form-text">El archivo debe ser un CSV y no exceder los 5MB.</div>
                    </div>
                    <div class="alert alert-info border-0" role="alert">
                        <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle me-2"></i>Formato del CSV esperado:</h6>
                        <p class="mb-0">Las columnas deben coincidir con los campos de la base de datos. El orden no importa, pero los nombres de las columnas sí.</p>
                        <ul class="mt-2 mb-0 small">
                            <li><code>NOMBRE(S) DE PILA</code>, <code>APELLIDO PATERNO</code>, <code>APELLIDO MATERNO</code>, <code>GENERO</code>, <code>LIDERAZGO</code>, <code>COORDINADOR</code>, <code>EDAD</code>, <code>TELEFONO</code>, <code>CURP (18 DIGITOS)</code>, <code>CLAVE DE ELECTOR (18 DIGITOS)</code>, <code>FECHA DE NACIMIENTO</code>, <code>SECCION</code>, <code>VIGENCIA</code>, <code>CALLE</code>, <code>NO. EXTERIOR</code>, <code>NO. INTERIOR</code>, <code>COLONIA</code>, <code>CODIGO POSTAL</code>, <code>DELEGACION</code>, <code>DIRECCION</code>, <code>MUNICIPIO</code>, <code>ESTADO</code>, <code>LATITUD</code>, <code>LONGITUD</code>, <code>TIPO DE SANGRE</code>, <code>SERVICIOS</code>, <code>TARIFA</code>, <code>CATEGORIA</code>, <code>DIAS</code>, <code>HORARIOS</code>, <code>DISCAPACIDAD</code>, <code>TIPO DE DISC</code>, <code>DESCUENTO</code>, <code>ESTATUS</code>, <code>AÑO</code>, <code>PAQUETE</code>, <code>TAGS</code></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-cloud-arrow-up me-2"></i>Importar</button> <!-- Cambiado a btn-success -->
                </div>
            </form>
        </div>
    </div>
</div>


        <!-- Tabla -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-table me-2"></i>Lista de Ciudadanos</h5>
                <span class="badge bg-primary rounded-pill px-3 py-2"><?= $totalContactos ?> contactos</span>
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
                                <th style="width: 150px;">Tags</th> <!-- Nueva columna para Tags -->
                                <th style="width: 120px; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="contactsTable">
                            <?php if (!empty($contactos)): ?>
                                <?php
                                $currentPage = $pager ? $pager->getCurrentPage('group1') : 1;
                                $perPageValue = $perPage;
                                $startIndex = ($currentPage - 1) * $perPageValue;
                                $i = 1;
                                foreach ($contactos as $c):
                                ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $startIndex + $i ?></td>
                                        <td class="text-center">
                                      <?php
    $tipo = $c['tipo_red'] ?? '—';
    switch ($tipo) {
        case 'CDN': $colorClass = 'bg-success'; break;
        case 'BNF': $colorClass = 'bg-primary'; break;
        case 'RED': $colorClass = 'bg-danger'; break;
        case 'EMP': $colorClass = 'bg-warning text-dark'; break;
        default: $colorClass = 'bg-secondary';
    }
?>
<span class="badge <?= $colorClass ?> px-2 py-1" style="font-size: 0.7rem;">
    <?= esc($tipo) ?>
</span>

                                        </td>
                                        <td style="font-weight: 500;"><?= esc($c['nombre']) ?></td>
                                        <td><?= esc($c['primer_apellido'] ?? '—') ?></td>
                                        <td><?= esc($c['segundo_apellido'] ?? '—') ?></td>
                                        <td>
                                            <span class="text-primary fw-medium"><?= esc($c['codigo_ciudadano'] ?? '—') ?></span>
  <td>
    <?php if (!empty($c['municipio']) || !empty($c['estado'])): ?>
        <?= esc(trim($c['municipio']) . ',' . trim($c['estado'])) ?>
    <?php else: ?>
        &mdash;
    <?php endif; ?>
</td>
<td>
    <?php if (!empty($c['lider_nombre'])): ?>
        <small class="text-muted">
            <?= esc($c['lider_nombre'] . ' ' . $c['lider_apellido'] . ' ' . $c['lider_segundo']) ?>
        </small>
    <?php else: ?>
        <small class="text-muted">—</small>
    <?php endif; ?>
</td>

                                        <td>
                                            <small class="text-muted"><?= esc($c['cargo'] ?? '—') ?></small>
                                        </td>
                                        <td>
                                            <?php if (!empty($c['tags_asociados'])): ?>
                                                <?php foreach (explode(', ', $c['tags_asociados']) as $tag): ?>
                                                    <span class="badge bg-info text-white me-1 mb-1"><?= esc($tag) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                &mdash;
                                            <?php endif; ?>
                                            <a href="<?= base_url('directorio/editarTags/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary mt-1" title="Editar Tags">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
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
                                                <a href="<?= base_url('directorio/mapa/' . $c['id']) ?>" class="btn p-0 border-0 bg-transparent" title="Ubicación" style="color: #6c757d;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                                    </svg>
                                                </a>
                                                
                                            
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-center py-5 text-muted">No hay ciudadanos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <?php if ($pager): ?>
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links('group1', 'default_full', ['perPage' => $perPage]) ?>
            </div>
        <?php endif; ?>
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
                ? '<?= $totalContactos ?> contactos'
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

<script>
  let tipoArchivo = '';

  function abrirModalExport(tipo) {
    tipoArchivo = tipo;
    document.getElementById('tipoArchivoSeleccionado').value = tipo;
    document.getElementById('formCorreo').classList.add('d-none');
    const modal = new bootstrap.Modal(document.getElementById('modalExportar'));
    modal.show();
  }

  function descargarArchivo() {
    const tipo = document.getElementById('tipoArchivoSeleccionado').value;
    window.location.href = `<?= base_url('export') ?>/${tipo}`;
  }

  function mostrarFormularioCorreo() {
    document.getElementById('formCorreo').classList.remove('d-none');
  }

  function enviarPorCorreo() {
    const tipo = document.getElementById('tipoArchivoSeleccionado').value;
    const email = document.getElementById('correoDestino').value;

    if (!email || !email.includes('@')) {
      alert("Ingresa un correo válido.");
      return;
    }

    fetch("<?= base_url('export/enviar-correo') ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
      },
      body: JSON.stringify({ tipo: tipo, email: email })
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message || 'Correo enviado');
      bootstrap.Modal.getInstance(document.getElementById('modalExportar')).hide();
      document.getElementById('correoDestino').value = '';
    })
    .catch(err => {
      alert('Ocurrió un error al enviar el correo.');
    });
  }
</script>



<!-- Modal de exportación -->
<div class="modal fade" id="modalExportar" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title">¿Qué deseas hacer con el archivo?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="tipoArchivoSeleccionado">
        <p>Selecciona si deseas descargar el archivo o enviarlo por correo electrónico.</p>
        <div class="d-grid gap-2">
          <button class="btn btn-primary" onclick="descargarArchivo()">📥 Descargar</button>
          <button class="btn btn-outline-success" onclick="mostrarFormularioCorreo()">✉️ Enviar por correo</button>
        </div>
        <div class="mt-4 d-none" id="formCorreo">
          <input type="email" id="correoDestino" class="form-control mb-2" placeholder="Correo electrónico">
          <button class="btn btn-success w-100" onclick="enviarPorCorreo()">Enviar</button>
        </div>
      </div>
    </div>
  </div>
</div>
