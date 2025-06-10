import { mapManager } from '../core/MapManager.js';
import { discreteModal } from './DiscreteModal.js';

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

        // Preparar datos para maptiler3d
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

        // Guardar datos en sessionStorage
        sessionStorage.setItem('metrix3DPoints', JSON.stringify(geoJSON));

        // Abrir vista 3D en modal discreto
        discreteModal.open('./maptiler3d.html');
    }
}