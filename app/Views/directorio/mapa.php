<div class="container mt-4">
    <h3>Ubicación de <?= esc($contacto['nombre']) ?> <?= esc($contacto['primer_apellido']) ?></h3>

    <a href="<?= site_url('directorio') ?>" class="btn btn-success mb-3">
        ← Regresar al Directorio
    </a>

    <div id="mapa" style="height: 400px; margin-top: 3cm;" class="rounded border"></div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var lat = <?= $contacto['latitud'] ?>;
        var lng = <?= $contacto['longitud'] ?>;

        var map = L.map('mapa').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("Ubicación de <?= esc($contacto['nombre']) ?>")
            .openPopup();
    });
</script>
