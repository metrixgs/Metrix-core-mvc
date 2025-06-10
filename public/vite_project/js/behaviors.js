import { dataLoader } from './dataLoader.js';
import { mapStateManager } from './core/MapStateManager.js';

class MapBehaviors {
    constructor(map) {
        this.map = map;
        this.isFiltering = false;
        this.setupDrawControl();
        this.setupClearFilterButton();
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
            console.log('Polígono creado');
            const layer = event.layer;
            
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
            mapStateManager.setCurrentPolygon(layer);
            
            const geometry = layer.toGeoJSON().geometry;
            this.filterPoints(geometry);

            console.log('Polígono GeoJSON:', geometry);
        });

        this.map.on('draw:edited', (event) => {
            console.log('Polígono editado');
            const layers = event.layers;
            layers.eachLayer((layer) => {
                mapStateManager.setCurrentPolygon(layer);
                const geometry = layer.toGeoJSON().geometry;
                this.filterPoints(geometry);
                console.log('Polígono editado GeoJSON:', geometry);
            });
        });

        this.map.on('draw:deleted', () => {
            console.log('Polígono eliminado');
            mapStateManager.setCurrentPolygon(null);
            this.showAllPoints();
        });

        this.map.on('draw:drawstart', () => {
            console.log('Iniciando dibujo de polígono');
            drawnItems.clearLayers();
            mapStateManager.setCurrentPolygon(null);
        });

        this.map.on('draw:drawstop', () => {
            console.log('Dibujo de polígono completado');
            const currentPolygon = mapStateManager.getCurrentPolygon();
            if (currentPolygon) {
                const geometry = currentPolygon.toGeoJSON().geometry;
                console.log('Geometría final del polígono:', geometry);
            }
        });
    }

    setupClearFilterButton() {
        const ClearFilterControl = L.Control.extend({
            options: {
                position: 'topright'
            },

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

    filterPoints(geometry) {
        if (!geometry) return;

        try {
            this.isFiltering = true;
            console.log('Filtrando puntos dentro del polígono...');

            const filteredPoints = dataLoader.filterPoints(geometry);
            if (!filteredPoints || filteredPoints.length === 0) {
                console.warn('No se encontraron puntos dentro del polígono');
                return;
            }

            console.log(`Encontrados ${filteredPoints.length} puntos dentro del polígono`);
            dataLoader.handlePoints(filteredPoints.map(f => f.properties));

            mapStateManager.saveCurrentState(geometry);

        } catch (error) {
            console.error('Error al filtrar puntos:', error);
            this.showAllPoints();
        }
    }

    showAllPoints() {
        if (!this.isFiltering) return;

        console.log('Mostrando todos los puntos...');
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