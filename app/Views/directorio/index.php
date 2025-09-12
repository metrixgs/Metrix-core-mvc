<!-- resources/views/directorio/index.php -->
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<!-- Estilos personalizados para botones compactos -->
<style>
.btn-compact {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.2;
}

.btn-group-compact .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.vr-custom {
    width: 1px;
    background-color: #dee2e6;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .btn-responsive-text {
        font-size: 0;
    }
    .btn-responsive-text i {
        font-size: 1rem;
    }
}

.action-buttons-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-bottom: 1rem;
}

/* Estilos para sugerencias de búsqueda */
.suggestion-item {
    cursor: pointer;
    transition: all 0.2s ease;
}

.suggestion-item:hover,
.suggestion-item.active {
    background-color: #e9ecef !important;
    transform: translateX(2px);
}

.suggestion-item:last-child {
    border-bottom: none !important;
}



/* Animaciones suaves */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.animate__animated {
    animation-duration: 0.3s;
    animation-fill-mode: both;
}

.animate__pulse {
    animation-name: pulse;
}

/* Mejoras en la barra de búsqueda compacta */
.input-group .form-control {
    font-size: 0.95rem;
    padding: 0.5rem 0.75rem;
}

.input-group .input-group-text {
    padding: 0.5rem 0.5rem;
    font-size: 0.9rem;
}

.input-group .btn {
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
}

/* Estilos para el contador de resultados */
.badge.fs-6 {
    font-size: 0.9rem !important;
    font-weight: 500;
    border: 1px solid #dee2e6;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .suggestion-item {
        padding: 0.75rem 1rem !important;
    }
    
    .input-group-lg .form-control {
        font-size: 1rem;
    }
}
</style>
<div class="page-content">
<div class="container-fluid">
    <div class="">
        <!-- Header -->
       <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Directorio</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

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

        <!-- Controles y Filtros -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <!-- Barra de búsqueda principal optimizada -->
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-4">
                        <div class="position-relative d-flex align-items-center" style="height: 31px;">
                            <div class="input-group input-group-sm w-100">
                                <span class="input-group-text bg-white border-end-0 px-2">
                                    <i class="bi bi-search text-success"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 border-end-0 shadow-sm" id="searchInput" placeholder="Buscar..." autocomplete="off">
                                <button class="btn btn-outline-secondary px-2" type="button" id="clearSearch" style="display: none;" title="Limpiar búsqueda">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <button class="btn btn-success px-2" type="button" id="advancedFilterToggle" title="Filtros avanzados" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                                    <i class="bi bi-funnel"></i>
                                </button>
                            </div>
                            <!-- Sugerencias de búsqueda -->
                            <div id="searchSuggestions" class="position-absolute w-100 bg-white border rounded-bottom shadow-lg" style="top: 100%; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex gap-2 align-items-center justify-content-end">
                            <!-- Botones de Acción -->
                            <a href="<?= base_url('directorio/crear'); ?>" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                                <i class="bi bi-plus-circle"></i> <span class="d-none d-lg-inline">Agregar</span>
                            </a>
                            <button type="button" class="btn btn-success btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                <i class="bi bi-upload"></i> <span class="d-none d-lg-inline">Importar</span>
                            </button>
                            
                            <!-- Separador -->
                            <div class="vr d-none d-md-block mx-1" style="height: 24px;"></div>
                            
                            <!-- Contador de resultados mejorado -->
                            <div class="d-flex align-items-center gap-2">
                               
                            </div>
                            <!-- Controles de vista -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-list"></i> <span class="d-none d-lg-inline"><?= $perPage == 'all' ? 'Todos' : $perPage ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= current_url() ?>?per_page=25">25 por página</a></li>
                                    <li><a class="dropdown-item" href="<?= current_url() ?>?per_page=50">50 por página</a></li>
                                    <li><a class="dropdown-item" href="<?= current_url() ?>?per_page=100">100 por página</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= current_url() ?>?per_page=all">Mostrar todos</a></li>
                                </ul>
                            </div>
                            <!-- Exportar -->
                            <div class="dropdown">
                                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-download"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="abrirModalExport('excel')"><i class="bi bi-file-earmark-excel me-2 text-success"></i>Excel</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="abrirModalExport('pdf')"><i class="bi bi-file-earmark-pdf me-2 text-success"></i>PDF</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="abrirModalExport('csv')"><i class="bi bi-file-earmark-text me-2 text-success"></i>CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            
        </div>



                <!-- Filtros avanzados colapsables -->
                <div class="collapse" id="advancedFilters">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label small text-muted fw-medium mb-1">Tipo de Red</label>
                                    <select class="form-select form-select-sm" id="filtroTipo" onchange="aplicarFiltros()">
                                        <option value="">Todos los tipos</option>
                                        <option value="CDN">CDN - Coordinador</option>
                                        <option value="BNF">BNF - Beneficiario</option>
                                        <option value="RED">RED - Red</option>
                                        <option value="EMP">EMP - Empresa</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small text-muted fw-medium mb-1">Líder</label>
                                    <select class="form-select form-select-sm" id="filtroLider" onchange="aplicarFiltros()">
                                        <option value="">Todos los líderes</option>
                                        <!-- Opciones dinámicas se cargarán aquí -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted fw-medium mb-1">Tags</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100 text-start" type="button" id="filtroTagsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="selectedTagsText">Seleccionar tags...</span>
                                        </button>
                                        <div class="dropdown-menu p-3" style="min-width: 300px; max-height: 300px; overflow-y: auto;" onclick="event.stopPropagation();">
                                            <div class="mb-2">
                                                <input type="text" class="form-control form-control-sm" id="tagSearchInput" placeholder="Buscar tags..." autocomplete="off">
                                            </div>
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-link btn-sm p-0 me-2" onclick="selectAllTags()">Seleccionar todos</button>
                                                <button type="button" class="btn btn-link btn-sm p-0" onclick="clearAllTags()">Limpiar</button>
                                            </div>
                                            <div id="tagsList">
                                                <?php if (isset($tags) && !empty($tags)): ?>
                                                    <?php foreach ($tags as $tag): ?>
                                                        <div class="form-check tag-item" data-tag-name="<?= strtolower(esc($tag['tag'])) ?>">
                                                            <input class="form-check-input tag-checkbox" type="checkbox" value="<?= $tag['id'] ?>" id="tag_<?= $tag['id'] ?>" onchange="updateSelectedTags()">
                                                            <label class="form-check-label" for="tag_<?= $tag['id'] ?>">
                                                                <?= esc($tag['tag']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="text-muted small">No hay tags disponibles</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted fw-medium mb-1">Acciones</label>
                                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="limpiarFiltros()" title="Limpiar filtros">
                                        <i class="bi bi-x-circle"></i> <span class="d-none d-xl-inline">Limpiar</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Indicadores de filtros activos -->
                <div id="filtrosActivos" class="mt-2" style="display: none;">
                    <div class="d-flex flex-wrap gap-1 align-items-center">
                        <div id="badgesFiltros" class="d-flex flex-wrap gap-1"></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabla -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-table me-2"></i>Lista de Ciudadanos</h5>
                <span class="badge bg-success rounded-pill px-3 py-2"><?= $totalContactos ?> contactos</span>
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
                                <th style="width: 150px;">Tags</th>
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
                                        </td>
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
                                                    <span class="badge bg-success text-white me-1 mb-1"><?= esc($tag) ?></span>
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

    // Variables globales para filtros dinámicos
    let currentPage = 1;
    let currentPerPage = 25;
    let isLoading = false;

    // Funciones para filtros avanzados
    function aplicarFiltros(page = 1) {
        if (isLoading) return;
        
        const filtroTipoEl = document.getElementById('filtroTipo');
        const filtroResidenciaEl = document.getElementById('filtroResidencia');
        
        const filtroTipo = filtroTipoEl ? filtroTipoEl.value : '';
        const filtroResidencia = filtroResidenciaEl ? filtroResidenciaEl.value : '';
        const filtroLider = document.getElementById('filtroLider') ? document.getElementById('filtroLider').value : '';
        const filtroTags = selectedTags.length > 0 ? selectedTags.join(',') : '';
        
        // Mostrar indicador de carga
        mostrarCargando(true);
        
        // Construir parámetros para AJAX
        const params = new URLSearchParams();
        params.set('page', page);
        params.set('perPage', currentPerPage);
        
        if (filtroTipo) params.set('tipo', filtroTipo);
        if (filtroResidencia) params.set('residencia', filtroResidencia);
        if (filtroLider) params.set('lider', filtroLider);
        if (filtroTags) params.set('tags', filtroTags);
        
        // Realizar petición AJAX
        fetch(`<?= base_url('directorio/getDatosFiltrados') ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                actualizarTabla(data.data);
                actualizarPaginacion(data.pagination);
                currentPage = data.pagination.current_page;
                actualizarIndicadoresFiltros();
                
                // Actualizar filtros dinámicos
                actualizarFiltrosDinamicos();
            })
            .catch(error => {
                console.error('Error al aplicar filtros:', error);
                alert('Error al aplicar filtros. Por favor, intente nuevamente.');
            })
            .finally(() => {
                mostrarCargando(false);
            });
    }

    function limpiarFiltros() {
        const filtroTipo = document.getElementById('filtroTipo');
        const filtroResidencia = document.getElementById('filtroResidencia');
        const filtroLider = document.getElementById('filtroLider');
        
        if (filtroTipo) filtroTipo.value = '';
        if (filtroResidencia) filtroResidencia.value = '';
        if (filtroLider) filtroLider.value = '';
        
        // Limpiar tags seleccionados
        clearAllTags();
        
        // Limpiar residencias
        if (filtroResidencia) {
            filtroResidencia.innerHTML = '<option value="">Todas las residencias</option>';
        }
        
        // Recargar todos los líderes y tags disponibles
        cargarLideres();
        
        // Aplicar filtros vacíos (mostrar todos los datos)
        aplicarFiltros(1);
    }

    // Función para mostrar/ocultar indicador de carga
    function mostrarCargando(mostrar) {
        isLoading = mostrar;
        const tabla = document.getElementById('contactsTable');
        const paginacion = document.querySelector('.d-flex.justify-content-center.mt-4');
        
        if (mostrar) {
            tabla.innerHTML = '<tr><td colspan="11" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><br><span class="text-muted">Aplicando filtros...</span></td></tr>';
            if (paginacion) paginacion.style.display = 'none';
        } else {
            if (paginacion) paginacion.style.display = 'flex';
        }
    }

    // Función para actualizar la tabla con nuevos datos
    function actualizarTabla(contactos) {
        const tabla = document.getElementById('contactsTable');
        let html = '';
        
        if (contactos && contactos.length > 0) {
            const startIndex = (currentPage - 1) * currentPerPage;
            contactos.forEach((c, index) => {
                const i = startIndex + index + 1;
                let tipo = c.tipo_red || '—';
                let colorClass = 'bg-secondary';
                
                switch (tipo) {
                    case 'CDN': colorClass = 'bg-success'; break;
                    case 'BNF': colorClass = 'bg-primary'; break;
                    case 'RED': colorClass = 'bg-danger'; break;
                    case 'EMP': colorClass = 'bg-warning text-dark'; break;
                }
                
                const residencia = (c.municipio || c.estado) ? 
                    `${c.municipio || ''}, ${c.estado || ''}`.replace(/^,\s*|,\s*$/g, '') : '—';
                
                const lider = c.lider_nombre ? 
                    `${c.lider_nombre} ${c.lider_apellido || ''} ${c.lider_segundo || ''}`.trim() : '—';
                
                let tagsHtml = '—';
                if (c.tags_asociados) {
                    const tags = c.tags_asociados.split(', ');
                    tagsHtml = tags.map(tag => `<span class="badge bg-success text-white me-1 mb-1">${tag}</span>`).join('');
                }
                
                html += `
                    <tr>
                        <td class="text-center fw-bold">${i}</td>
                        <td class="text-center">
                            <span class="badge ${colorClass} px-2 py-1" style="font-size: 0.7rem;">${tipo}</span>
                        </td>
                        <td style="font-weight: 500;">${c.nombre || '—'}</td>
                        <td>${c.primer_apellido || '—'}</td>
                        <td>${c.segundo_apellido || '—'}</td>
                        <td><span class="text-primary fw-medium">${c.codigo_ciudadano || '—'}</span></td>
                        <td>${residencia}</td>
                        <td><small class="text-muted">${lider}</small></td>
                        <td><small class="text-muted">${c.cargo || '—'}</small></td>
                        <td>
                            ${tagsHtml}
                            <a href="<?= base_url('directorio/editarTags/') ?>${c.id}" class="btn btn-sm btn-outline-primary mt-1" title="Editar Tags">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <a href="<?= base_url('directorio/ver/') ?>${c.id}" class="btn p-0 border-0 bg-transparent" title="Ver perfil" style="color: #6c757d;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M12 14c-6 0-8 3-8 6v2h16v-2c0-3-2-6-8-6z"/>
                                    </svg>
                                </a>
                                <a href="<?= base_url('directorio/mapa/') ?>${c.id}" class="btn p-0 border-0 bg-transparent" title="Ubicación" style="color: #6c757d;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="11" class="text-center py-5 text-muted">No hay ciudadanos que coincidan con los filtros aplicados.</td></tr>';
        }
        
        tabla.innerHTML = html;
        
        // Actualizar contador de contactos
        const badge = document.querySelector('.badge.bg-primary.rounded-pill');
        if (badge) {
            const total = contactos ? contactos.length : 0;
            badge.textContent = `${total} contactos`;
        }
    }

    // Función para actualizar la paginación
    function actualizarPaginacion(pagination) {
        const paginacionContainer = document.querySelector('.d-flex.justify-content-center.mt-4');
        if (!paginacionContainer || !pagination) return;
        
        let html = '<nav aria-label="Paginación"><ul class="pagination">';
        
        // Botón anterior
        if (pagination.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="aplicarFiltros(${pagination.current_page - 1}); return false;">Anterior</a></li>`;
        } else {
            html += '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
        }
        
        // Páginas
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === pagination.current_page) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="aplicarFiltros(${i}); return false;">${i}</a></li>`;
            }
        }
        
        // Botón siguiente
        if (pagination.current_page < pagination.total_pages) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="aplicarFiltros(${pagination.current_page + 1}); return false;">Siguiente</a></li>`;
        } else {
            html += '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
        }
        
        html += '</ul></nav>';
        paginacionContainer.innerHTML = html;
    }

    // Función para actualizar indicadores visuales de filtros activos
    function actualizarIndicadoresFiltros() {
        const filtros = ['filtroTipo', 'filtroResidencia', 'filtroTags'];
        let filtrosActivos = 0;
        
        filtros.forEach(filtroId => {
            const elemento = document.getElementById(filtroId);
            if (elemento && elemento.value) {
                filtrosActivos++;
                elemento.style.borderColor = '#0d6efd';
                elemento.style.boxShadow = '0 0 0 0.2rem rgba(13, 110, 253, 0.25)';
            } else if (elemento) {
                elemento.style.borderColor = '';
                elemento.style.boxShadow = '';
            }
        });
        
        // Actualizar botón de limpiar filtros
        const btnLimpiar = document.querySelector('button[onclick="limpiarFiltros()"]');
        if (btnLimpiar) {
            if (filtrosActivos > 0) {
                btnLimpiar.classList.remove('btn-outline-secondary');
                btnLimpiar.classList.add('btn-warning');
                btnLimpiar.innerHTML = `<i class="bi bi-x-circle me-1"></i>Limpiar Filtros (${filtrosActivos})`;
            } else {
                btnLimpiar.classList.remove('btn-warning');
                btnLimpiar.classList.add('btn-outline-secondary');
                btnLimpiar.innerHTML = '<i class="bi bi-x-circle me-1"></i>Limpiar Filtros';
            }
        }
    }
    

    
    function cargarResidencias() {
        const residenciaSelect = document.getElementById('filtroResidencia');
        const residenciaSeleccionada = document.getElementById('residenciaSeleccionada').value;
        
        // Limpiar opciones actuales
        residenciaSelect.innerHTML = '<option value="">Todas las residencias</option>';
        
        if (estadoSeleccionado) {
            // Datos estáticos temporales de municipios por estado
            const municipiosPorEstado = {
                'Aguascalientes': ['Aguascalientes', 'Asientos', 'Calvillo', 'Cosío', 'Jesús María', 'Pabellón de Arteaga', 'Rincón de Romos', 'San José de Gracia', 'Tepezalá', 'El Llano', 'San Francisco de los Romo'],
                'Baja California': ['Mexicali', 'Tijuana', 'Ensenada', 'Playas de Rosarito', 'Tecate'],
                'Chihuahua': ['Chihuahua', 'Juárez', 'Delicias', 'Parral', 'Cuauhtémoc'],
                'Jalisco': ['Guadalajara', 'Zapopan', 'Tlaquepaque', 'Tonalá', 'Puerto Vallarta']
            };
            
            const municipios = municipiosPorEstado[estadoSeleccionado] || [];
            
            municipios.forEach(municipio => {
                const option = document.createElement('option');
                const residencia = municipio + ', ' + estadoSeleccionado;
                option.value = residencia;
                option.textContent = residencia;
                if (residencia === residenciaSeleccionada) {
                    option.selected = true;
                }
                residenciaSelect.appendChild(option);
            });
        }
    }

    // Función para actualizar filtros dinámicos basándose en los filtros aplicados
    function actualizarFiltrosDinamicos() {
        // Obtener filtros actuales (excluyendo líder y tags para evitar dependencias circulares)
        const filtroTipo = document.getElementById('filtroTipo') ? document.getElementById('filtroTipo').value : '';
        const filtroResidencia = document.getElementById('filtroResidencia') ? document.getElementById('filtroResidencia').value : '';
        
        // Construir parámetros para obtener filtros disponibles
        const params = new URLSearchParams();
        if (filtroTipo) params.set('tipo', filtroTipo);
        if (filtroResidencia) params.set('residencia', filtroResidencia);
        
        // Obtener valores seleccionados actualmente
        const liderSeleccionado = document.getElementById('filtroLider') ? document.getElementById('filtroLider').value : '';
        const tagSeleccionado = document.getElementById('filtroTags') ? document.getElementById('filtroTags').value : '';
        
        fetch(`<?= base_url('directorio/getFiltrosDisponibles') ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Actualizar filtro de líderes
                const liderSelect = document.getElementById('filtroLider');
                if (liderSelect) {
                    liderSelect.innerHTML = '<option value="">Todos los líderes</option>';
                    
                    data.lideres.forEach(lider => {
                        const option = document.createElement('option');
                        option.value = lider.id;
                        option.textContent = lider.nombre_completo;
                        
                        // Mantener selección si el líder aún está disponible
                        if (lider.id == liderSeleccionado) {
                            option.selected = true;
                        }
                        
                        liderSelect.appendChild(option);
                    });
                }
                
                // Actualizar filtro de tags
                const tagsSelect = document.getElementById('filtroTags');
                if (tagsSelect) {
                    tagsSelect.innerHTML = '<option value="">Todos los tags</option>';
                    
                    data.tags.forEach(tag => {
                        const option = document.createElement('option');
                        option.value = tag.id;
                        option.textContent = tag.nombre;
                        
                        // Mantener selección si el tag aún está disponible
                        if (tag.id == tagSeleccionado) {
                            option.selected = true;
                        }
                        
                        tagsSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error al actualizar filtros dinámicos:', error);
            });
    }

    function cargarLideres() {
        fetch('<?= base_url('directorio/getLideres') ?>')
            .then(response => response.json())
            .then(lideres => {
                const liderSelect = document.getElementById('filtroLider');
                const liderSeleccionado = new URLSearchParams(window.location.search).get('lider');
                
                // Limpiar opciones actuales
                liderSelect.innerHTML = '<option value="">Todos los líderes</option>';
                
                lideres.forEach(lider => {
                    const option = document.createElement('option');
                    option.value = lider.id;
                    option.textContent = lider.nombre_completo;
                    if (lider.id == liderSeleccionado) {
                        option.selected = true;
                    }
                    liderSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar líderes:', error);
            });
    }



    // Funciones para el selector múltiple de tags
    let selectedTags = [];

    function updateSelectedTags() {
        const checkboxes = document.querySelectorAll('.tag-checkbox:checked');
        selectedTags = Array.from(checkboxes).map(cb => cb.value);
        
        const selectedTagsText = document.getElementById('selectedTagsText');
        if (selectedTagsText) {
            if (selectedTags.length === 0) {
                selectedTagsText.textContent = 'Seleccionar tags...';
            } else if (selectedTags.length === 1) {
                const tagLabel = document.querySelector(`label[for="tag_${selectedTags[0]}"]`);
                selectedTagsText.textContent = tagLabel ? tagLabel.textContent : '1 tag seleccionado';
            } else {
                selectedTagsText.textContent = `${selectedTags.length} tags seleccionados`;
            }
        }
        
        // Aplicar filtros automáticamente
        aplicarFiltros();
    }

    function selectAllTags() {
        const checkboxes = document.querySelectorAll('.tag-checkbox');
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                cb.checked = true;
            }
        });
        updateSelectedTags();
    }

    function clearAllTags() {
        const checkboxes = document.querySelectorAll('.tag-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedTags();
    }

    function filterTags() {
        const searchInput = document.getElementById('tagSearchInput');
        if (!searchInput) return;
        
        const searchTerm = searchInput.value.toLowerCase();
        const tagItems = document.querySelectorAll('.tag-item');
        
        tagItems.forEach(item => {
            const tagName = item.getAttribute('data-tag-name');
            if (tagName && tagName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Inicializar filtros al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener parámetros de la URL
        const params = new URLSearchParams(window.location.search);
        
        // Establecer valores de filtros desde URL
        const filtroTipo = document.getElementById('filtroTipo');
        const filtroLider = document.getElementById('filtroLider');
        
        if (params.get('tipo') && filtroTipo) {
            filtroTipo.value = params.get('tipo');
        }
        
        // Configurar búsqueda de tags
        const tagSearchInput = document.getElementById('tagSearchInput');
        if (tagSearchInput) {
            tagSearchInput.addEventListener('input', filterTags);
        }
        
        // Cargar líderes
        cargarLideres();
        
        // Inicializar tags seleccionados desde URL
        const tagsParam = params.get('tags');
        if (tagsParam) {
            const tagIds = tagsParam.split(',');
            tagIds.forEach(tagId => {
                const checkbox = document.getElementById(`tag_${tagId}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
            updateSelectedTags();
        }
        
        // Mostrar indicadores de filtros activos
        actualizarIndicadoresFiltros();
    });

    function actualizarIndicadoresFiltros() {
        const params = new URLSearchParams(window.location.search);
        const indicadores = document.getElementById('filtrosActivos');
        
        if (indicadores) {
            if (params.get('tipo') || params.get('estado') || params.get('municipio') || params.get('lider') || params.get('tags') || params.get('estatus') || params.get('liderazgo') || params.get('coordinador')) {
                indicadores.style.display = 'block';
            } else {
                indicadores.style.display = 'none';
            }
        }
    }
</script>

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

<!-- Modal de Importación CSV -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-success text-white">
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
                    <button type="submit" class="btn btn-success"><i class="bi bi-cloud-arrow-up me-2"></i>Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>
