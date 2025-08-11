<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= esc($titulo_pagina ?? 'Bitácora'); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('bitacora') ?>">Bitácora</a></li>
                            <li class="breadcrumb-item active">Bitácora</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Tarjetas de estadísticas -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Total Actividades</span>
                            <i class="bi bi-activity text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $total_actividades ?></div>
                        <small class="text-muted">Desde el inicio</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Hoy</span>
                            <i class="bi bi-clock text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $actividades_hoy ?></div>
                        <small class="text-muted">Actividades de hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Inicios de Sesión</span>
                            <i class="bi bi-box-arrow-in-right text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $inicios_sesion ?></div>
                        <small class="text-muted">Total de accesos</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">Creaciones</span>
                            <i class="bi bi-plus-circle text-muted small"></i>
                        </div>
                        <div class="fs-5 fw-bold"><?= $creaciones ?></div>
                        <small class="text-muted">Elementos creados</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros avanzados -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-funnel me-1"></i> Filtros Avanzados
            </div>
            <div class="card-body">
                <form id="filtrosForm" method="get" action="<?= base_url('bitacora') ?>" class="row g-3">
                    <!-- Buscador -->
                    <div class="col-md-3">
                        <label class="form-label small">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="searchInput" class="form-control form-control-sm" 
                                   placeholder="Buscar actividades..." value="<?= esc($filtros_actuales['search'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Tipo de actividad -->
                    <div class="col-md-3">
                        <label class="form-label small">Tipo de Actividad</label>
                        <select name="type" id="typeSelect" class="form-select form-select-sm">
                            <option value="Todas" <?= ($filtros_actuales['type'] ?? '') == 'Todas' ? 'selected' : '' ?>>Todas</option>
                            <?php foreach ($tipos_accion as $tipo): ?>
                                <option value="<?= esc($tipo['accion']) ?>" <?= ($filtros_actuales['type'] ?? '') == $tipo['accion'] ? 'selected' : '' ?>>
                                    <?= esc($tipo['accion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Usuario -->
                    <div class="col-md-3">
                        <label class="form-label small">Usuario</label>
                        <select name="user_id" id="userSelect" class="form-select form-select-sm">
                            <option value="Todos">Todos los usuarios</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= esc($usuario['id']) ?>" <?= ($filtros_actuales['user_id'] ?? '') == $usuario['id'] ? 'selected' : '' ?>>
                                    <?= esc($usuario['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Módulo -->
                    <div class="col-md-3">
                        <label class="form-label small">Módulo</label>
                        <select name="module" id="moduleSelect" class="form-select form-select-sm">
                            <option value="Todos" <?= ($filtros_actuales['module'] ?? '') == 'Todos' ? 'selected' : '' ?>>Todos los módulos</option>
                            <?php foreach ($modulos as $modulo): ?>
                                <option value="<?= esc($modulo['modulo']) ?>" <?= ($filtros_actuales['module'] ?? '') == $modulo['modulo'] ? 'selected' : '' ?>>
                                    <?= esc($modulo['modulo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Rango de fechas -->
                    <div class="col-md-4">
                        <label class="form-label small">Rango de Fechas</label>
                        <div class="d-flex gap-2">
                            <input type="date" name="start_date" id="startDate" class="form-control form-control-sm" 
                                   value="<?= esc($filtros_actuales['start_date'] ?? '') ?>">
                            <span class="align-self-center">a</span>
                            <input type="date" name="end_date" id="endDate" class="form-control form-control-sm" 
                                   value="<?= esc($filtros_actuales['end_date'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100" id="applyButton" style="display: none;">
                            <i class="bi bi-funnel me-1"></i> Aplicar
                        </button>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <a href="<?= base_url('bitacora') ?>" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de actividades -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <i class="bi bi-list"></i> Registro de Actividades
                </div>
                <div>
                    <small class="text-muted">
                        Mostrando <?= min($perPage, count($bitacoras)) ?> de <?= $total_registros ?> registros
                        (Página <?= $currentPage ?> de <?= $total_paginas ?>)
                    </small>
                </div>
            </div>
            <style>
                .bitacora-table-container {
                    max-height: 600px;
                    overflow-y: auto;
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                }
                .bitacora-table-container .table {
                    margin-bottom: 0;
                }
                .bitacora-table-container thead th {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 10 !important;
                    background-color: #f8f9fa !important;
                    border-bottom: 2px solid #dee2e6 !important;
                    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                }
            </style>
            <div class="bitacora-table-container">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario Ejecutor</th>
                            <th scope="col">Actividad</th>
                            <th scope="col">Módulo</th>
                            <th scope="col">Detalles</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bitacoras)) : ?>
                            <?php 
                                $startIndex = ($currentPage - 1) * $perPage;
                                foreach ($bitacoras as $index => $bitacora) : 
                            ?>
                                <tr>
                                    <td><?= $startIndex + $index + 1 ?></td>
                                    <td><?= esc($bitacora['nombre_usuario']) ?></td>
                                    <td><?= esc($bitacora['accion']) ?></td>
                                    <td><?= esc($bitacora['modulo']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bitacora='<?= htmlspecialchars(json_encode($bitacora), ENT_QUOTES, 'UTF-8') ?>'
                                                onclick="mostrarDetalles(this)">
                                            <i class="bi bi-eye"></i> Ver Detalles
                                        </button>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($bitacora['fecha_creacion'])) ?></td>
                                    <td><?= date('H:i:s', strtotime($bitacora['fecha_creacion'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron actividades.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Mostrando <?= min($perPage, count($bitacoras ?? [])) ?> registros de <?= $total_registros ?? 0 ?> en total
    </small>
    <!-- Paginación -->
    <nav>
        <ul class="pagination pagination-sm mb-0">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= base_url('bitacora?' . http_build_query(array_merge($filtros_actuales, ['page' => $currentPage - 1]))) ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php
                $startPage = max(1, $currentPage - 2);
                $endPage   = min($total_paginas, $currentPage + 2);

                if ($startPage > 1) {
                    echo '<li class="page-item"><a class="page-link" href="'.base_url('bitacora?' . http_build_query(array_merge($filtros_actuales, ['page' => 1]))).'">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                for ($i = $startPage; $i <= $endPage; $i++):
            ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="<?= base_url('bitacora?' . http_build_query(array_merge($filtros_actuales, ['page' => $i]))) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($endPage < $total_paginas): ?>
                <?php if ($endPage < $total_paginas - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item"><a class="page-link" href="<?= base_url('bitacora?' . http_build_query(array_merge($filtros_actuales, ['page' => $total_paginas]))) ?>"><?= $total_paginas ?></a></li>
            <?php endif; ?>

            <?php if ($currentPage < $total_paginas): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= base_url('bitacora?' . http_build_query(array_merge($filtros_actuales, ['page' => $currentPage + 1]))) ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

        </div>
    </div>
</div>

<!-- Modal para mostrar detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesModalLabel">
                    <i class="bi bi-info-circle"></i> Detalles de la Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-person"></i> Información del Usuario</h6>
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <p><strong>Usuario Ejecutor:</strong> <span id="modal-usuario"></span></p>
                                <p><strong>Usuario Afectado:</strong> <span id="modal-usuario-afectado"></span></p>
                                <p><strong>IP Address:</strong> <span id="modal-ip"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="bi bi-activity"></i> Información de la Actividad</h6>
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <p><strong>Módulo:</strong> <span id="modal-modulo"></span></p>
                                <p><strong>Acción:</strong> <span id="modal-accion"></span></p>
                                <p><strong>Fecha y Hora:</strong> <span id="modal-fecha"></span></p>
                                <p><strong>Prioridad:</strong> <span id="modal-prioridad"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6 class="text-info"><i class="bi bi-file-text"></i> Detalles Específicos</h6>
                    <div class="card">
                        <div class="card-body">
                            <div id="modal-detalles-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar detalles en el modal
function mostrarDetalles(button) {
    try {
        const bitacoraJson = button.getAttribute('data-bitacora');
        const bitacora = JSON.parse(bitacoraJson);
        
        // Llenar información básica
        document.getElementById('modal-usuario').textContent = bitacora.nombre_usuario || 'N/A';
        document.getElementById('modal-usuario-afectado').textContent = bitacora.nombre_usuario_afectado || 'N/A';
        document.getElementById('modal-ip').textContent = bitacora.ip_address || 'N/A';
        document.getElementById('modal-modulo').textContent = bitacora.modulo || 'N/A';
        document.getElementById('modal-accion').textContent = bitacora.accion || 'N/A';
        document.getElementById('modal-prioridad').textContent = bitacora.priority || 'info';
        
        // Formatear fecha
        if (bitacora.fecha_creacion) {
            const fecha = new Date(bitacora.fecha_creacion);
            document.getElementById('modal-fecha').textContent = fecha.toLocaleString('es-ES');
        } else {
            document.getElementById('modal-fecha').textContent = 'N/A';
        }
        
        // Procesar detalles
        const detallesContainer = document.getElementById('modal-detalles-content');
        
        if (bitacora.detalles) {
            try {
                // Intentar parsear como JSON
                const detallesObj = JSON.parse(bitacora.detalles);
                
                if (typeof detallesObj === 'object' && detallesObj !== null) {
                    // Es un objeto JSON, mostrar de forma estructurada
                    let html = '<div class="row">';
                    
                    Object.keys(detallesObj).forEach(key => {
                        const value = detallesObj[key];
                        const displayKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        html += `
                            <div class="col-md-6 mb-2">
                                <strong>${displayKey}:</strong>
                                <span class="text-muted">${value !== null && value !== undefined ? value : 'N/A'}</span>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    detallesContainer.innerHTML = html;
                } else {
                    // Es un valor simple
                    detallesContainer.innerHTML = `<p class="mb-0">${detallesObj}</p>`;
                }
            } catch (e) {
                // No es JSON válido, mostrar como texto plano
                detallesContainer.innerHTML = `<p class="mb-0">${bitacora.detalles}</p>`;
            }
        } else {
            detallesContainer.innerHTML = '<p class="text-muted mb-0">No hay detalles disponibles</p>';
        }
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('detallesModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error al procesar los detalles:', error);
        alert('Error al cargar los detalles de la actividad');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filtrosForm');
    const inputs = form.querySelectorAll('select, input');
    
    // Ocultar el botón de aplicar
    document.getElementById('applyButton').style.display = 'none';
    
    // Función para enviar el formulario automáticamente
    function submitForm() {
        // Agregar parámetro de página al formulario
        const pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = '1'; // Volver a la primera página al cambiar filtros
        form.appendChild(pageInput);
        
        form.submit();
    }
    
    // Para los selects y fechas: cambio inmediato
    inputs.forEach(input => {
        if (input.type !== 'text') {
            input.addEventListener('change', submitForm);
        }
    });
    
    // Para el campo de búsqueda: esperar a que deje de escribir
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(submitForm, 500); // 500 ms después de la última tecla
    });
});
</script>