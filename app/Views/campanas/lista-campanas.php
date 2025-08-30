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
  <?php if (!empty($breadcrumb)) : ?>
    <?= $breadcrumb ?>
  <?php endif; ?>
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
                   <a href="<?= base_url('campanas/nueva'); ?>" class="btn btn-primary">
  Nueva
</a>

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

                        <!-- Fecha de inicio -->
                        <td><?= date('d/M/Y', strtotime($campana['fecha_inicio'])); ?></td>

                        <!-- Fecha de fin -->
                        <td><?= date('d/M/Y', strtotime($campana['fecha_fin'])); ?></td>

                        <!-- Detalles -->
                        <td class="text-center">
                          <a href="<?= base_url() . "campanas/detalle/{$campana['id']}"; ?>" class="btn btn-primary btn-sm">
                            Más Información
                          </a>
                        </td>
                      </tr>
                      <?php $contador++; ?>
                    <?php endforeach; ?>
                  <?php } else { ?>
                    <tr>
                      <td colspan="8" class="text-center">No hay campañas disponibles.</td>
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
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#colapsarAvanzado">
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
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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

<style>
  .modal.show {
  padding-top: 5vh !important;
}

.modal-dialog {
  margin-top: 5vh;
}

</style>