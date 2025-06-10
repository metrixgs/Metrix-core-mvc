import { mapManager } from '../core/MapManager.js';
import { dataLoader } from '../dataLoader.js';
import { pulseLayer } from './PulseLayer.js';
import { discreteModal } from './DiscreteModal.js';

export class PointsList {
    constructor() {
        this.container = null;
        this.points = [];
        this.highlightedMarker = null;
        this.originalStyle = null;
        this.init();
    }

    init() {
        this.createContainer();
        this.setupEventListeners();
        this.loadPoints();
        pulseLayer.setMap(mapManager.getMap());
    }

    createContainer() {
        const tab2Content = document.getElementById('tab2');
        if (!tab2Content) return;

        tab2Content.innerHTML = `
            <div class="points-list-container">
                <button class="table-button" onclick="window.showPointsTable()">
                    <i class="bi bi-table"></i>
                    <span>Tabla de POIs</span>
                </button>
                <div class="points-list-header">
                    <h3 class="points-list-title">Puntos de Interés</h3>
                    <span class="points-count">0 / 0 puntos</span>
                </div>
                <div class="points-list"></div>
            </div>
        `;

        this.container = tab2Content.querySelector('.points-list');
        this.showLoading();

        // Agregar función global para mostrar tabla
        window.showPointsTable = () => {
            discreteModal.open();
            discreteModal.setContent(`<iframe src="/tablapois.html" style="width:100%; height:100%; border:none;"></iframe>`);
        };
    }

    setupEventListeners() {
        const map = mapManager.getMap();
        if (!map) return;

        map.on('moveend', () => this.loadPoints());
        map.on('zoomend', () => this.loadPoints());

        document.addEventListener('filterApplied', () => this.loadPoints());
        document.addEventListener('filterCleared', () => this.loadPoints());
    }

    loadPoints() {
        const map = mapManager.getMap();
        if (!map) return;

        const bounds = map.getBounds();
        const allPoints = dataLoader.points || [];
        
        this.points = allPoints.filter(point => {
            const coords = [
                parseFloat(point.properties.latitud),
                parseFloat(point.properties.longitud)
            ];
            return bounds.contains(coords);
        });

        this.updatePointsCount(this.points.length, allPoints.length);
        this.renderPoints();
    }

    updatePointsCount(visibleCount, totalCount) {
        const countElement = document.querySelector('.points-count');
        if (countElement) {
            countElement.textContent = `${visibleCount} / ${totalCount} puntos`;
        }
    }

    getIconClass(estado) {
        switch (estado.toLowerCase()) {
            case 'abierto': return 'open';
            case 'en proceso': return 'in-progress';
            case 'cerrado': return 'closed';
            default: return 'open';
        }
    }

    getIconContent(estado) {
        switch (estado.toLowerCase()) {
            case 'abierto': return '<i class="bi bi-exclamation-circle"></i>';
            case 'en proceso': return '<i class="bi bi-arrow-repeat"></i>';
            case 'cerrado': return '<i class="bi bi-check-circle"></i>';
            default: return '<i class="bi bi-geo-alt"></i>';
        }
    }

    highlightMarker(pointId) {
        const map = mapManager.getMap();
        if (!map) return;

        if (this.highlightedMarker) {
            this.highlightedMarker.setStyle(this.originalStyle);
            this.highlightedMarker = null;
            this.originalStyle = null;
            pulseLayer.clearPulse();
        }

        map.eachLayer((layer) => {
            if (layer.feature && layer.feature.properties && 
                layer.feature.properties.identificador === pointId) {
                
                this.originalStyle = {
                    radius: layer.options.radius,
                    fillOpacity: layer.options.fillOpacity
                };
                
                this.highlightedMarker = layer;
                
                layer.setStyle({
                    fillOpacity: 0.9,
                    radius: layer.options.radius
                });

                // Mostrar efecto de pulso
                const lat = parseFloat(layer.feature.properties.latitud);
                const lng = parseFloat(layer.feature.properties.longitud);
                pulseLayer.showPulse(lat, lng);
            }
        });
    }

    renderPoints() {
        if (!this.container) return;

        if (this.points.length === 0) {
            this.showEmpty();
            return;
        }

        const pointsHTML = this.points.map(point => {
            const properties = point.properties;
            const iconClass = this.getIconClass(properties.estado);
            const iconContent = this.getIconContent(properties.estado);

            return `
                <div class="point-item" 
                     onmouseenter="window.highlightMapPoint('${properties.identificador}')"
                     onmouseleave="window.clearHighlightedPoint()">
                    <div class="point-icon ${iconClass}">
                        ${iconContent}
                    </div>
                    <div class="point-info">
                        <div class="point-title">${properties.titulo}</div>
                        <div class="point-description">${properties.descripcion}</div>
                    </div>
                    <button class="point-preview" onclick="window.previewPoint(${properties.latitud}, ${properties.longitud}, '${properties.identificador}')">
                        Preview
                    </button>
                </div>
            `;
        }).join('');

        this.container.innerHTML = pointsHTML;
    }

    showEmpty() {
        if (!this.container) return;
        this.container.innerHTML = `
            <div class="points-empty">
                <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p>No hay puntos visibles en esta área</p>
            </div>
        `;
    }

    showLoading() {
        if (!this.container) return;
        this.container.innerHTML = `
            <div class="points-loading">
                <i class="bi bi-arrow-clockwise"></i>
                <p>Cargando puntos...</p>
            </div>
        `;
    }
}

// Funciones globales
window.previewPoint = (lat, lng, id) => {
    const map = mapManager.getMap();
    if (!map) return;

    document.querySelector('.sidebar-left').classList.remove('active');
    document.getElementById('map').style.marginLeft = '0';

    map.flyTo([lat, lng], 16, {
        duration: 1.5,
        easeLinearity: 0.25
    });

    map.eachLayer((layer) => {
        if (layer.feature && layer.feature.properties && layer.feature.properties.identificador === id) {
            layer.openPopup();
        }
    });

    setTimeout(() => {
        map.invalidateSize({
            animate: true,
            pan: false
        });
    }, 400);
};

let pointsListInstance = null;

window.highlightMapPoint = (pointId) => {
    if (!pointsListInstance) {
        const elements = document.getElementsByClassName('points-list');
        if (elements.length > 0) {
            pointsListInstance = new PointsList();
        }
    }
    if (pointsListInstance) {
        pointsListInstance.highlightMarker(pointId);
    }
};

window.clearHighlightedPoint = () => {
    if (pointsListInstance && pointsListInstance.highlightedMarker) {
        pointsListInstance.highlightedMarker.setStyle(pointsListInstance.originalStyle);
        pointsListInstance.highlightedMarker = null;
        pointsListInstance.originalStyle = null;
        pulseLayer.clearPulse();
    }
};

window.filterPointsTable = (value) => {
    const rows = document.querySelectorAll('.points-table tbody tr');
    const searchTerm = value.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
};