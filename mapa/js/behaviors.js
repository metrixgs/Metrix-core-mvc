import { dataLoader } from './dataLoader.js';
import { mapStateManager } from './core/MapStateManager.js';
import { openExportDialog } from './components/exportModal.js';


class MapBehaviors {
    constructor(map) {
        this.map = map;
        this.isFiltering = false;
        this.setupDrawControl();
        this.setupClearFilterButton();
        this.setupExportButtons();
    }

    setupDrawControl() {
        const drawnItems = mapStateManager.getDrawnItems();
        this.map.addLayer(drawnItems);

        const drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                marker: false,
                circle: false,
                circlemarker: false,
                rectangle: {
                    shapeOptions: {
                        color: '#00A650',
                        weight: 2
                    }
                },
                polygon: {
                    shapeOptions: {
                        color: '#00A650',
                        weight: 2
                    }
                },
                polyline: false
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });

        this.map.addControl(drawControl);

        this.map.on('draw:created', (event) => {
            const layer = event.layer;
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
            mapStateManager.setCurrentPolygon(layer);
            const geometry = layer.toGeoJSON().geometry;
            this.filterPoints(geometry);
        });

        this.map.on('draw:deleted', () => {
            mapStateManager.setCurrentPolygon(null);
            this.showAllPoints();
        });
    }

    setupClearFilterButton() {
        const ClearFilterControl = L.Control.extend({
            options: { position: 'topright' },
            onAdd: () => {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                const button = L.DomUtil.create('a', 'clear-filter-button', container);
                button.innerHTML = '×';
                button.href = '#';
                button.title = 'Limpiar Filtro';
                L.DomEvent.on(button, 'click', (e) => {
                    L.DomEvent.preventDefault(e);
                    this.showAllPoints();
                });
                return container;
            }
        });

        this.clearFilterControl = new ClearFilterControl();
        this.map.addControl(this.clearFilterControl);
    }

    setupExportButtons() {
        const polygonBtn = document.getElementById('exportPolygonBtn');
        const csvBtn = document.getElementById('exportCSVBtn');

        if (polygonBtn) {
            polygonBtn.addEventListener('click', () => {
                const polygon = mapStateManager.getCurrentPolygon();
                if (!polygon) return alert("No hay polígono seleccionado");

                openExportDialog({
                    type: 'geojson',
                    onExport: ({ filename, customer }) => {
                        const geojson = polygon.toGeoJSON();
                        geojson.properties = geojson.properties || {};
                        geojson.properties.customer = customer;
                        const blob = new Blob([JSON.stringify(geojson, null, 2)], { type: 'application/json' });
                        saveAs(blob, filename.endsWith(".geojson") ? filename : `${filename}.geojson`);
                    }
                });
            });
        }

        if (csvBtn) {
            csvBtn.addEventListener('click', () => {
                const polygon = mapStateManager.getCurrentPolygon();
                if (!polygon) return alert("No hay polígono seleccionado");

                const geometry = polygon.toGeoJSON().geometry;
                const filtered = dataLoader.filterPoints(geometry);
                if (!filtered || filtered.length === 0) return alert("No hay datos dentro del polígono");

                openExportDialog({
                    type: 'csv',
                    onExport: ({ filename, customer }) => {
                        const rows = filtered.map(f => ({ ...f.properties, customer }));
                        const header = Object.keys(rows[0]).join(',');
                        const csv = rows.map(row => Object.values(row).join(',')).join('\n');
                        const blob = new Blob([`${header}\n${csv}`], { type: 'text/csv;charset=utf-8' });
                        saveAs(blob, filename.endsWith('.csv') ? filename : `${filename}.csv`);
                    }
                });
            });
        }
    }

    filterPoints(geometry) {
        if (!geometry) return;

        this.isFiltering = true;
        const filteredPoints = dataLoader.filterPoints(geometry);
        if (!filteredPoints || filteredPoints.length === 0) return;
        dataLoader.handlePoints(filteredPoints.map(f => f.properties));
        mapStateManager.saveCurrentState(geometry);
    }

    showAllPoints() {
        if (!this.isFiltering) return;
        const drawnItems = mapStateManager.getDrawnItems();
        drawnItems.clearLayers();
        mapStateManager.setCurrentPolygon(null);
        this.isFiltering = false;
        dataLoader.loadPoints();
        mapStateManager.clearCurrentState();
    }
}

let mapBehaviorsInstance = null;

export function initMapBehaviors(map) {
    if (!mapBehaviorsInstance) {
        mapBehaviorsInstance = new MapBehaviors(map);
    }
    return mapBehaviorsInstance;
}
