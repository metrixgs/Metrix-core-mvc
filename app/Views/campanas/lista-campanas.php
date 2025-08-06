<div class="page-content">
    <div class="container-fluid">
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a
                                    href="<?= base_url() . obtener_rol() . "panel/" ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Campañas</li>
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
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de Campañas</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal -->
                                    <div class="search-box ms-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalNuevaCampana">
                                            Nueva
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">#</th>
                                        <th>Nombre de Campaña</th>
                                        <th>Tipo de Campaña</th>
                                        <th>Estatus</th>
                                        <th>Área Responsable</th>
                                        <th>Coordinador(a)</th>
                                        <th>Fecha de Inicio</th>
                                        <th>Fecha de Término</th>
                                        <th class="text-center">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($campanas) && !empty($campanas)) { ?>
                                        <?php $contador = 1; ?>
                                        <?php foreach ($campanas as $index => $campana): ?>
                                            <tr>
                                                <!-- Número de fila -->
                                                <td class="text-center">
                                                    <?= $contador; ?>
                                                </td>

                                                <!-- Nombre de la campaña -->
                                                <td><?= htmlspecialchars($campana['nombre']); ?></td>

                                                <!-- Tipo de Campaña -->
                                                <td>
                                                    <?php if (!empty($campana['nombre_tipo_campana']) && !empty($campana['nombre_subtipo_campana'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_tipo_campana']); ?> /
                                                        <?= htmlspecialchars($campana['nombre_subtipo_campana']); ?>
                                                    <?php elseif (!empty($campana['nombre_tipo_campana'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_tipo_campana']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No especificado</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Estatus -->
                                                <td>
                                                    <?php
                                                    $badgeClass = '';
                                                    switch ($campana['estado']) {
                                                        case 'Programada':
                                                            $badgeClass = 'bg-warning';
                                                            break;
                                                        case 'Activa':
                                                            $badgeClass = 'bg-success';
                                                            break;
                                                        case 'Finalizada':
                                                            $badgeClass = 'bg-info';
                                                            break;
                                                        case 'Propuesta':
                                                            $badgeClass = 'bg-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'bg-secondary';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass; ?> rounded-pill">
                                                        <?= htmlspecialchars($campana['estado']); ?>
                                                    </span>
                                                </td>

                                                <!-- Área Responsable -->
                                                <td>
                                                    <?php if (!empty($campana['nombre_area'])): ?>
                                                        <?= htmlspecialchars($campana['nombre_area']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No especificada</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Coordinador -->
                                                <td><?= esc($campana['nombre_coordinador']) ?></td>

                                                <!-- Fecha de inicio -->
                                                <td><?= date('d/M/Y', strtotime($campana['fecha_inicio'])); ?></td>

                                                <!-- Fecha de fin -->
                                                <td><?= date('d/M/Y', strtotime($campana['fecha_fin'])); ?></td>

                                                <!-- Detalles -->
                                                <td class="text-center">
                                                    <a href="<?= base_url() . "campanas/detalle/{$campana['id']}"; ?>"
                                                        class="btn btn-primary btn-sm">
                                                        Más Información
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $contador++; ?>
                                        <?php endforeach; ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay campañas disponibles.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>

<!-- Modal para crear una nueva campaña -->
 <div class="modal fade" id="modalNuevaCampana" tabindex="-1" aria-labelledby="modalNuevaCampanaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #8bc34a;">
        <h5 class="modal-title fw-bold" id="modalNuevaCampanaLabel">Nueva Campaña</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <input type="hidden" name="estado" value="Programada">
      <form method="post" action="<?= base_url() . 'campanas/crear'; ?>" id="formNuevaCampana">
        <?= csrf_field(); ?>
        <div class="modal-body bg-light">
          <div class="row g-3">
            <!-- DATOS GENERALES -->
            <div class="col-md-12">
              <div class="card shadow" style="border-color: #8bc34a;">
                <div class="card-header text-white" style="background-color: #8bc34a;">
                  <h6 class="mb-0 fw-semibold">📋 Datos Generales</h6>
                </div>
                <div class="card-body bg-white">
                  <div class="row">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" style="color: #8bc34a;">Nombre de la Campaña <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" style="border-color: #8bc34a;" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                      <label for="fecha_inicio" class="form-label fw-semibold" style="color: #8bc34a;">Vigencia <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required style="border-color: #8bc34a;">
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required style="border-color: #8bc34a;">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <label for="tipo_id" class="form-label fw-semibold" style="color: #8bc34a;">Tipo <span class="text-danger">*</span></label>
                      <select class="form-select select2" style="border-color: #8bc34a;" id="tipo_id" name="tipo_id" required>
                        <?php foreach ($tipos_campanas as $tipo): ?>
                          <option value="<?= $tipo['id']; ?>"><?= htmlspecialchars($tipo['nombre']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <!-- NUEVOS CAMPOS TEMPORALES -->
                  <div class="row mt-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" style="color: #8bc34a;">Canal de difusión</label>
                      <input type="text" class="form-control" name="canal_difusion" placeholder="Ej: Redes Sociales" style="border-color: #8bc34a;">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" style="color: #8bc34a;">Objetivo de la campaña</label>
                      <textarea class="form-control" name="objetivo" rows="1" style="border-color: #8bc34a;"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- DELIMITACIÓN TERRITORIAL -->
            <div class="col-md-6">
              <div class="card shadow" style="border-color: #8bc34a;">
                <div class="card-header text-white" style="background-color: #8bc34a;">
                  <h6 class="mb-0 fw-semibold">🗺️ Delimitación Territorial</h6>
                </div>
                <div class="card-body bg-white">
                  <div class="mb-3">
                    <label for="sector_electoral" class="form-label fw-semibold" style="color: #8bc34a;">Sector Electoral</label>
                    <select class="form-select select2" id="sector_electoral" name="sector_electoral" style="border-color: #8bc34a;">
                      <option value="">Seleccione una opción</option>
                      <option value="distrito">Distrito</option>
                      <option value="seccion">Sección</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="territorio_local" class="form-label fw-semibold" style="color: #8bc34a;">Territorio Local</label>
                    <select class="form-select select2" id="territorio_local" name="territorio_local" style="border-color: #8bc34a;">
                      <option value="">Seleccione una opción</option>
                      <option value="municipio">Municipio</option>
                      <option value="delegacion">Delegación</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- IMPACTOS -->
            <div class="col-md-6">
              <div class="card shadow" style="border-color: #8bc34a;">
                <div class="card-header text-white" style="background-color: #8bc34a;">
                  <h6 class="mb-0 fw-semibold">🎯 Impactos</h6>
                </div>
                <div class="card-body bg-white">
                  <label id="labelUniverso" class="form-label fw-semibold" style="color: #8bc34a;">Universo (0)</label>
                  <button type="button" class="btn btn-outline-success w-100" id="btnAbrirUniverso">Seleccionar Universo</button>
                  <input type="hidden" id="universo" name="universo">
                  <div id="universoSeleccionado" class="mt-2 text-muted small">Ningún universo seleccionado</div>
                  <div class="mt-3">
                    <span class="badge bg-light border text-dark">#Adultos Mayores (681)</span>
                    <span class="badge bg-light border text-dark">#Jubilados (344)</span>
                    <span class="badge bg-light border text-dark">Mayores +60 años (231)</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- RESPONSABLES -->
            <div class="col-md-6">
              <div class="card shadow" style="border-color: #8bc34a;">
                <div class="card-header text-white" style="background-color: #8bc34a;">
                  <h6 class="mb-0 fw-semibold">👥 Responsables</h6>
                </div>
                <div class="card-body bg-white">
                  <div class="mb-3">
                    <label for="area_id" class="form-label fw-semibold" style="color: #8bc34a;">Área Responsable <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="area_id" name="area_id" required style="border-color: #8bc34a;">
                      <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['id']; ?>"><?= htmlspecialchars($area['nombre']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="coordinador" class="form-label fw-semibold" style="color: #8bc34a;">Coordinador(a) <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="coordinador" name="coordinador" required style="border-color: #8bc34a;">
                      <?php foreach ($usuarios_desde_2 as $usuario): ?>
                        <option value="<?= htmlspecialchars($usuario['id']); ?>"><?= htmlspecialchars($usuario['nombre']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- INTERACCIONES -->
            <div class="col-md-6">
              <div class="card shadow" style="border-color: #8bc34a;">
                <div class="card-header text-white" style="background-color: #8bc34a;">
                  <h6 class="mb-0 fw-semibold">🔗 Interacciones</h6>
                </div>
                <div class="card-body bg-white">
                  <div class="mb-3">
                    <label for="encuesta" class="form-label fw-semibold" style="color: #8bc34a;">Encuesta <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="encuesta" name="encuesta" style="border-color: #8bc34a;">
                      <option value="">Seleccione una encuesta</option>
                      <?php foreach ($surveys as $survey): ?>
                        <option value="<?= htmlspecialchars($survey['id']); ?>">#<?= htmlspecialchars($survey['id']); ?> <?= htmlspecialchars($survey['title']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="entregables" class="form-label fw-semibold" style="color: #8bc34a;">Entregables <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="entregables" name="entregables" required style="border-color: #8bc34a;">
                      <option value="00001">Orden # 00001</option>
                      <option value="00002">Orden # 00002</option>
                      <option value="00003">Orden # 00003</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="col-md-12">
              <div class="mb-3">
                <label for="descripcion" class="form-label fw-semibold" style="color: #8bc34a;">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline me-2" style="border-color: #8bc34a; color: #8bc34a;" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancelar
          </button>
          <button type="submit" form="formNuevaCampana" class="btn text-white" style="background-color: #8bc34a; border-color: #8bc34a;">
            <i class="fas fa-plus me-1"></i>Crear Campaña
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



                <!-- Modal de Universo -->
 <!-- Modal de Filtros -->
<div class="modal fade" id="modalUniverso" tabindex="-1" aria-labelledby="modalUniversoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="modalUniversoLabel">Filtros para: Todas las cuentas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="row">

          <!-- Columna de Filtros Generales -->
          <div class="col-md-4 border-end">
            <h6 class="fw-semibold mb-3">Filtros</h6>

            <div class="mb-3">
              <label class="form-label">Ubicación</label>
              <select class="form-select" multiple>
                <option selected>Querétaro</option>
                <option selected>México</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Ingresos</label>
              <select class="form-select">
                <option selected>Más de 1 millón</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Tecnología</label>
              <select class="form-select">
                <option selected>Cualquiera</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Año de fundación</label>
              <select class="form-select" multiple>
                <option selected>2023</option>
                <option selected>2024</option>
              </select>
            </div>
          </div>

          <!-- Columna de Financiamiento -->
          <div class="col-md-8 ps-4">
            <h6 class="fw-semibold mb-3">Financiamiento</h6>

            <div class="mb-3">
              <label class="form-label">Selecciona tipo y fecha del financiamiento</label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="tipoFinanciamiento" id="cualquierRonda" checked>
                  <label class="form-check-label" for="cualquierRonda">Cualquier ronda</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="tipoFinanciamiento" id="ultimaRonda">
                  <label class="form-check-label" for="ultimaRonda">Última ronda</label>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha de financiamiento</label>
              <select class="form-select">
                <option selected>Todos los tiempos</option>
              </select>
            </div>

            <div class="accordion" id="filtrosAvanzados">
              <div class="accordion-item">
                <h2 class="accordion-header" id="encabezadoAvanzado">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colapsarAvanzado">
                    Avanzado
                  </button>
                </h2>
                <div id="colapsarAvanzado" class="accordion-collapse collapse" data-bs-parent="#filtrosAvanzados">
                  <div class="accordion-body">

                    <div class="mb-3">
                      <label class="form-label">Último monto de financiamiento</label>
                      <div class="d-flex gap-2">
                        <input type="number" class="form-control" placeholder="Mínimo">
                        <input type="number" class="form-control" placeholder="Máximo">
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Monto total de financiamiento</label>
                      <div class="d-flex gap-2">
                        <input type="number" class="form-control" placeholder="Mínimo">
                        <input type="number" class="form-control" placeholder="Máximo">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div> <!-- Fin col-md-8 -->

        </div> <!-- Fin row -->
      </div>

      <div class="modal-footer bg-light">
        <span class="me-auto text-muted">Resultados: 2,240</span>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success">Aplicar filtros</button>
      </div>

    </div>
  </div>
</div>

         
<style>
    .custom-multiselect .selected-tags {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        min-height: 38px;
        margin-bottom: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    #territorio-electorales-options .dropdown-toggle,
    #territorio-geograficos-options .dropdown-toggle {
        background-color: #fff;
        border: 1px solid #ced4da;
        color: #495057;
    }

    #territorio-electorales-options .dropdown-menu,
    #territorio-geograficos-options .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }

    .modal-dialog.modal-lg {
        max-width: 1200px;
        width: 90%;
    }




#subModalUniverso {
  background-color: #fff;
  box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
  border: 1px solid #8bc34a;
}



    a.page-link {
        color: #000000 !important;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formNuevaCampana');
        const territorioInput = document.getElementById('territorio');
        const territorioElectoralesRadio = document.getElementById('territorio-electorales');
        const territorioGeograficosRadio = document.getElementById('territorio-geograficos');
        const territorioElectoralesOptions = document.getElementById('territorio-electorales-options');
        const territorioGeograficosOptions = document.getElementById('territorio-geograficos-options');
        const territorioElectoralesSubSelect = document.getElementById('territorio-electorales-subtype');
        const territorioGeograficosLevelSelect = document.getElementById('territorio-geograficos-level');
        const territorioGeograficosSubSelect = document.getElementById('territorio-geograficos-subtype');
        const territorioElectoralesSubOptionsList = document.getElementById('territorio-electorales-suboptions-list');
        const territorioGeograficosSubOptionsList = document.getElementById('territorio-geograficos-suboptions-list');
        const territorioElectoralesDropdown = document.getElementById('territorioElectoralesDropdown');
        const territorioGeograficosDropdown = document.getElementById('territorioGeograficosDropdown');
        const mapLoading = document.getElementById('map-loading');
        let map;

        const geographicLevels = {
            '01': { label: 'País', subOptions: ['Estados', 'Municipios', 'Delegaciones', 'Localidades'] },
            '02': { label: 'Estados', subOptions: ['Municipios', 'Delegaciones', 'Localidades'] },
            '03': { label: 'Municipios', subOptions: ['Delegaciones', 'Localidades'] },
            '04': { label: 'Delegaciones', subOptions: ['Localidades'] },
            '05': { label: 'Localidades', subOptions: [] }
        };

        function updateTerritorioSelection() {
            const selectedValue = territorioInput.value;

            territorioElectoralesOptions.style.display = 'none';
            territorioGeograficosOptions.style.display = 'none';

            territorioElectoralesSubSelect.removeAttribute('required');
            territorioGeograficosSubSelect.removeAttribute('required');
            territorioGeograficosLevelSelect.removeAttribute('required');

            territorioElectoralesSubSelect.value = '';
            territorioGeograficosLevelSelect.value = '';
            territorioGeograficosSubSelect.value = '';

            if (selectedValue === 'electorales') {
                territorioElectoralesOptions.style.display = 'block';
                territorioElectoralesSubSelect.setAttribute('required', 'required');
                loadElectoralesSubOptions();
            } else if (selectedValue === 'geograficos') {
                territorioGeograficosOptions.style.display = 'block';
                territorioGeograficosLevelSelect.setAttribute('required', 'required');
                territorioGeograficosSubSelect.setAttribute('required', 'required');
                loadGeograficosLevelOptions();
            }
        }

        function loadElectoralesSubOptions() {
            territorioElectoralesSubOptionsList.innerHTML = '';
            const subOptions = [
                { value: '1.1', text: 'Distrito Federal' },
                { value: '1.2', text: 'Distrito Local' },
                { value: '1.3', text: 'Sección Electoral' }
            ];

            subOptions.forEach(opt => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.className = 'dropdown-item';
                a.href = '#';
                a.setAttribute('data-value', opt.value);
                a.textContent = opt.text;
                li.appendChild(a);
                territorioElectoralesSubOptionsList.appendChild(li);
            });

            const selectedOption = territorioElectoralesSubSelect.value || '';
            territorioElectoralesDropdown.textContent = selectedOption
                ? subOptions.find(opt => opt.value === selectedOption)?.text || 'Seleccione Sectorización'
                : 'Seleccione Sectorización';

            territorioElectoralesSubOptionsList.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    territorioElectoralesSubSelect.value = value;
                    territorioElectoralesDropdown.textContent = this.textContent;
                    updateMapLayer(value);
                });
            });
        }

        function loadGeograficosLevelOptions() {
            loadGeograficosSubOptions(territorioGeograficosLevelSelect.value || '01');
        }

        function loadGeograficosSubOptions(level) {
            territorioGeograficosSubOptionsList.innerHTML = '';

            if (level && geographicLevels[level]) {
                const subOptions = geographicLevels[level].subOptions;
                subOptions.forEach(opt => {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.className = 'dropdown-item';
                    a.href = '#';
                    a.setAttribute('data-value', opt);
                    a.textContent = opt;
                    li.appendChild(a);
                    territorioGeograficosSubOptionsList.appendChild(li);
                });

                const selectedOption = territorioGeograficosSubSelect.value || '';
                territorioGeograficosDropdown.textContent = selectedOption
                    ? subOptions.find(opt => opt === selectedOption) || 'Seleccione Sectorización'
                    : 'Seleccione Sectorización';

                territorioGeograficosSubOptionsList.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.preventDefault();
                        const value = this.getAttribute('data-value');
                        territorioGeograficosSubSelect.value = value;
                        territorioGeograficosDropdown.textContent = this.textContent;
                        updateMapLayer(value);
                    });
                });
            } else {
                territorioGeograficosDropdown.textContent = 'Seleccione Sectorización';
            }
        }

        territorioElectoralesRadio.addEventListener('change', function () {
            if (this.checked) {
                territorioInput.value = 'electorales';
                updateTerritorioSelection();
            }
        });

        territorioGeograficosRadio.addEventListener('change', function () {
            if (this.checked) {
                territorioInput.value = 'geograficos';
                updateTerritorioSelection();
            }
        });

        territorioGeograficosLevelSelect.addEventListener('change', function () {
            loadGeograficosSubOptions(this.value);
        });

        form.addEventListener('submit', function (e) {
            const territorioValue = territorioInput.value;

            if (!territorioValue) {
                e.preventDefault();
                alert('Por favor, seleccione un tipo de territorio.');
                return;
            }

            if (territorioValue === 'electorales' && !territorioElectoralesSubSelect.value) {
                e.preventDefault();
                alert('Seleccione una sectorización para Electorales.');
                return;
            }

            if (territorioValue === 'geograficos' && (!territorioGeograficosLevelSelect.value || !territorioGeograficosSubSelect.value)) {
                e.preventDefault();
                alert('Seleccione nivel y sectorización para Geográficos.');
                return;
            }
        });

        updateTerritorioSelection();

        function initializeMap() {
            if (!map) {
                mapLoading.style.display = 'flex';
                map = L.map('map').setView([23.6345, -102.5528], 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                mapLoading.style.display = 'none';
            }
        }

        const layerGroups = {
            'Estados': L.layerGroup(),
            'Municipios': L.layerGroup(),
            'Delegaciones': L.layerGroup(),
            'Localidades': L.layerGroup(),
            '1.1': L.layerGroup(),
            '1.2': L.layerGroup(),
            '1.3': L.layerGroup()
        };

        function loadGeoJSON(layerName, url, callback) {
            mapLoading.style.display = 'flex';
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    L.geoJSON(data, {
                        style: { color: '#3388ff', weight: 1, fillOpacity: 0.1 },
                        onEachFeature: function (feature, layer) {
                            layer.bindPopup(layerName + ': ' + (feature.properties.name || feature.properties.nombre || 'Sin nombre'));
                        }
                    }).addTo(layerGroups[layerName]);
                    if (callback) callback();
                })
                .catch(error => console.error('Error al cargar GeoJSON:', error))
                .finally(() => {
                    mapLoading.style.display = 'none';
                });
        }

        const geoJSONUrls = {
            'Estados': 'https://cors-anywhere.herokuapp.com/https://softteca.com/geojson/estados.geojson',
            'Municipios': 'https://cors-anywhere.herokuapp.com/https://softteca.com/geojson/municipios.geojson',
            'Delegaciones': 'https://cors-anywhere.herokuapp.com/https://softteca.com/geojson/delegacion.geojson',
            'Localidades': 'https://cors-anywhere.herokuapp.com/https://softteca.com/geojson/localidades.geojson',
        };

        function loadInitialLayers() {
            Object.keys(geoJSONUrls).forEach(layerName => {
                loadGeoJSON(layerName, geoJSONUrls[layerName]);
            });
        }

        function addLayerControl() {
            const baseLayers = {
                'OpenStreetMap': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
            };
            L.control.layers(baseLayers, layerGroups).addTo(map);
        }

        $('#modalNuevaCampana').on('shown.bs.modal', function () {
            initializeMap();
            loadInitialLayers();
            addLayerControl();
        });

        function updateMapLayer(selectedValue) {
            Object.values(layerGroups).forEach(group => group.clearLayers());
            if (geoJSONUrls[selectedValue]) {
                loadGeoJSON(selectedValue, geoJSONUrls[selectedValue], () => {
                    layerGroups[selectedValue].addTo(map);
                });
            }
        }

        if (territorioInput.value === 'geograficos' && territorioGeograficosLevelSelect.value === '01') {
            territorioGeograficosSubSelect.value = 'Estados';
            updateMapLayer('Estados');
        }
    });
</script>

<!-- ✅ Inicialización única de Select2 -->
<script>
    $(document).ready(function () {
        $('select.select2').select2({
            width: '100%',
            dropdownParent: $('#modalNuevaCampana')
        });

        // ✅ Cargar subtipos por tipo seleccionado
        $('#tipo_id').on('change', function () {
            const tipoId = $(this).val();
            const $subtipo = $('#subtipo_id');

            $subtipo.html('<option value="">Cargando...</option>');

            if (!tipoId) {
                $subtipo.html('<option value="">Seleccione un subtipo</option>');
                return;
            }

            $.ajax({
                url: `<?= base_url('campanas/obtener/subtipos/') ?>${tipoId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Seleccione un subtipo</option>';
                    data.forEach(function (subtipo) {
                        options += `<option value="${subtipo.id}">${subtipo.nombre}</option>`;
                    });
                    $subtipo.html(options).trigger('change');
                },
                error: function () {
                    $subtipo.html('<option value="">Error al cargar subtipos</option>');
                }
            });
        });
    });
</script>


 <script>
document.addEventListener('DOMContentLoaded', function () {
  // ✅ Abrir el modal con Bootstrap al hacer click en el botón
  document.getElementById('btnAbrirUniverso').addEventListener('click', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalUniverso'));
    modal.show();
  });

  // ✅ Botón para cancelar (opcional)
  var btnCancelar = document.getElementById('cancelarUniverso');
  if (btnCancelar) {
    btnCancelar.addEventListener('click', function () {
      var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalUniverso'));
      modalInstance.hide();
    });
  }

  // ✅ Botón para guardar selección
  var btnGuardar = document.getElementById('guardarUniverso');
  if (btnGuardar) {
    btnGuardar.addEventListener('click', function () {
      const valor = document.getElementById('selectUniverso').value;
      document.getElementById('universo').value = valor;
      document.getElementById('universoSeleccionado').textContent = valor
        ? `Seleccionado: ${valor}`
        : 'Ningún universo seleccionado';

      var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalUniverso'));
      modalInstance.hide();
    });
  }
});
</script>
