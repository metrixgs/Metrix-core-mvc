import { mapManager } from '../core/MapManager.js';

export class MapTiler3DButton {
    constructor() {
        this.init();
    }

    init() {
        const button = document.createElement('button');
        button.id = 'view3DButton';
        button.className = 'control-btn';
        button.title = 'Ver en 3D';
        button.innerHTML = '<i class="bi bi-badge-3d"></i>';

        // Insertar después del botón de exportar puntos
        const pointsExportBtn = document.getElementById('exportPoints');
        if (pointsExportBtn && pointsExportBtn.parentNode) {
            pointsExportBtn.parentNode.insertBefore(button, pointsExportBtn.nextSibling);
        }

        this.setupEventListeners(button);
    }

    setupEventListeners(button) {
        button.addEventListener('click', () => this.open3DView());
    }

    open3DView() {
        const dataLoader = window.dataLoader;
        if (!dataLoader || !dataLoader.points) return;

        // Convertir puntos a formato compatible con MapTiler
        const geoJSON = {
            type: 'FeatureCollection',
            features: dataLoader.points.map(point => ({
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [
                        parseFloat(point.properties.longitud),
                        parseFloat(point.properties.latitud)
                    ]
                },
                properties: point.properties
            }))
        };

        // Guardar en sessionStorage
        sessionStorage.setItem('metrix3DPoints', JSON.stringify(geoJSON));

        // Abrir nueva pestaña con la URL relativa
        window.open('./maptiler3d.html', '_blank');
    }
}