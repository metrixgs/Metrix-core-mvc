<?php
// ⚡ Datos estáticos por defecto para mostrar la vista
$ronda = [
    'id' => 102,
    'estado' => 'Activa',
    'fecha_actividad' => '2025-01-21',
    'hora_actividad' => '10:00:00',
    'nombre_campania' => 'Entrega de Tarjeta de Salud (+60 años)',
    'territorio' => 'San José de los Olvera',
    'sectorizacion' => 'Colonia',
    'nombre' => 'El Pocito',
    'universo' => 190,
    'entregable' => '#0341-04',
    'encargado' => 'Julián Martínez',
    'brigada' => 'DS110-112',
    'operadores' => 12
];

// Cuando lo conectes al controlador, puedes usar:
// $ronda = $ronda ?? [];
?>

<div class="page-content">
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <div class="row mb-3">
            <div class="col-12">
                <h4 class="text-dark">Datos de la Ronda</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('campanias') ?>" class="text-primary">Campañas</a></li>
                    <li class="breadcrumb-item active text-dark">#CAM-28302</li>
                    <li class="breadcrumb-item active text-dark">#RDA-<?= esc($ronda['id']) ?></li>
                </ol>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <div class="row g-2">

                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-between border rounded p-2">
                            <div>
                                <strong>Nombre de la Campaña:</strong> <?= esc($ronda['nombre_campania']) ?>
                            </div>
                            <button class="btn btn-sm btn-primary">Editar</button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>ID Ronda:</strong> #RDA-<?= esc($ronda['id']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Fecha:</strong> <?= esc($ronda['fecha_actividad']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Horario:</strong> <?= esc($ronda['hora_actividad']) ?> horas
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Territorio:</strong> <?= esc($ronda['territorio']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Sectorización:</strong> <?= esc($ronda['sectorizacion']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Nombre:</strong> <?= esc($ronda['nombre']) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Universo:</strong> <?= esc($ronda['universo']) ?> puntos
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Entregable:</strong> <?= esc($ronda['entregable']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-sm btn-secondary w-100">Ver Asignación</button>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Encargado(a):</strong> <?= esc($ronda['encargado']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Brigada(s):</strong> <?= esc($ronda['brigada']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <strong>Operadores:</strong> <?= esc($ronda['operadores']) ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-2 mb-2">
                    <div id="map" style="height: 250px;"></div>
                </div>
                       <div class="d-flex align-items-center gap-2 mt-2">
    <div class="d-flex align-items-center" style="border: 2px solid black; border-radius: 5px; overflow: hidden; width: 100px; height: 20px;">
        <?php 
            // Creamos un array de "bloques" para simular la barra llena
            $bloques = floor(64 / 10); // 64% -> 6 bloques llenos
            for ($i = 0; $i < $bloques; $i++) {
                echo '<div style="width: 8px; height: 100%; background-color: black; margin: 0 1px;"></div>';
            }
        ?>
    </div>
    <span class="fw-bold">64%</span>
    <a href="#" class="btn btn-sm btn-light">Registro de Actividad</a>
    <a href="#" class="btn btn-sm btn-light">+ Indicadores</a>
</div>
            </div>
        </div>

        <!-- Indicadores -->
        <div class="row text-center mt-4">
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-team-fill fs-4"></i>
                    <div>02 Brigadas</div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-eye-line fs-4"></i>
                    <div>13/190 Vistas Realizadas</div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-error-warning-line fs-4"></i>
                    <div>135 Incidencias</div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-truck-line fs-4"></i>
                    <div>47 Entregas</div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-archive-line fs-4"></i>
                    <div>45 Botagos</div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2">
                    <i class="ri-heart-line fs-4"></i>
                    <div>453 Peticiones</div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Asegurar visibilidad del texto */
    body {
        color: #333 !important;
    }
    .text-dark {
        color: #212529 !important;
    }
    .text-muted {
        color: #6c757d !important;
    }
    .badge {
        color: #fff !important;
    }
    .badge.border {
        color: #333 !important;
    }
    .btn-light {
        color: #212529 !important;
    }
    .btn-primary, .btn-success, .btn-danger, .btn-secondary {
        color: #fff !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Configurar modal de eliminación
        document.querySelectorAll('.eliminar-ronda').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var nombre = this.getAttribute('data-nombre');

                document.getElementById('nombre-ronda-eliminar').textContent = nombre;
                document.getElementById('btn-confirmar-eliminar').setAttribute('href', '<?= base_url('rondas/eliminar/') ?>' + id);

                var modal = new bootstrap.Modal(document.getElementById('modal-eliminar-ronda'));
                modal.show();
            });
        });
    });
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Crear el mapa con Leaflet (OpenStreetMap)
        const map = L.map('map').setView([19.432608, -99.133209], 15);

        // Añadir capa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Añadir marcador en la ubicación central
        L.marker([19.432608, -99.133209]).addTo(map)
                .bindPopup('Ubicación de la Ronda')
                .openPopup();

        // Simular área de la ronda con un polígono
        const zoneCoordinates = [
            [19.435608, -99.136209],
            [19.433608, -99.129209],
            [19.430608, -99.131209],
            [19.429608, -99.135209]
        ];

        L.polygon(zoneCoordinates, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.35
        }).addTo(map);

        // Manejar el botón de pantalla completa
        document.getElementById('fullscreen-btn').addEventListener('click', function () {
            const mapElement = document.getElementById('map').parentElement;

            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                mapElement.requestFullscreen();
            }
        });
    });
</script>
