import { dataLoader } from '../dataLoader.js';
import { mapManager } from './MapManager.js';

class MapStateManager {
    constructor() {
        this.state = {
            currentState: null,
            drawnItems: new L.FeatureGroup(),
            currentPolygon: null,
            lastUpdate: null
        };
        this.statePrefix = 'MAP_';
        this.init();
    }

    init() {
        const map = mapManager.getMap();
        if (map) {
            this.state.drawnItems.addTo(map);
            this.setupEventListeners();
            this.checkUrlState();
        }
    }

    setupEventListeners() {
        const map = mapManager.getMap();
        if (!map) return;

        map.on('layeradd', (e) => {
            if (e.layer === this.state.drawnItems) {
                console.log('DrawnItems layer added to map');
            }
        });

        // Escuchar cambios de zoom para actualizar estado
        map.on('zoomend', () => {
            if (this.state.currentState) {
                this.updateViewportState();
            }
        });

        // Escuchar movimientos del mapa
        map.on('moveend', () => {
            if (this.state.currentState) {
                this.updateViewportState();
            }
        });
    }

    updateViewportState() {
        const map = mapManager.getMap();
        if (!map || !this.state.currentState) return;

        this.state.currentState.viewport = {
            center: map.getCenter(),
            zoom: map.getZoom()
        };
        this.state.lastUpdate = new Date().toISOString();
    }

    validateState(state) {
        if (!state) return false;

        const requiredFields = ['sessionId', 'timestamp', 'viewport'];
        const hasRequiredFields = requiredFields.every(field => field in state);

        if (!hasRequiredFields) return false;

        // Validar viewport
        if (!state.viewport.center || !state.viewport.zoom) return false;
        if (isNaN(state.viewport.center.lat) || isNaN(state.viewport.center.lng)) return false;
        if (isNaN(state.viewport.zoom)) return false;

        return true;
    }

    async saveCurrentState(geometry = null) {
        const map = mapManager.getMap();
        if (!map) return null;

        try {
            let polygonCoords = [];
            if (geometry && geometry.coordinates && geometry.coordinates[0]) {
                polygonCoords = geometry.coordinates[0].map(coord => ({
                    lat: coord[1],
                    lng: coord[0]
                }));
            }

            // Modificación aquí: Cambiar el formato de los puntos para incluir coordenadas
            const pointsWithCoords = dataLoader.points?.map(point => ({
                id: point.properties.identificador,
                lat: parseFloat(point.properties.latitud),
                lon: parseFloat(point.properties.longitud)
            })) || [];

            const state = {
                sessionId: this.generateSessionId(),
                timestamp: new Date().toISOString(),
                polygon: polygonCoords,
                points: pointsWithCoords,
                viewport: {
                    center: map.getCenter(),
                    zoom: map.getZoom()
                }
            };

            if (!this.validateState(state)) {
                throw new Error('Invalid state structure');
            }

            localStorage.setItem(state.sessionId, JSON.stringify(state));
            this.state.currentState = state;
            this.state.lastUpdate = new Date().toISOString();

            const url = new URL(window.location);
            url.searchParams.set('state', state.sessionId);
            window.history.pushState({}, '', url);

            // Disparar evento de estado guardado
            document.dispatchEvent(new CustomEvent('stateSaved', {
                detail: { stateId: state.sessionId }
            }));

            return state.sessionId;
        } catch (error) {
            console.error('Error saving state:', error);
            return null;
        }
    }

    async loadState(sessionId) {
        try {
            const map = mapManager.getMap();
            if (!map) throw new Error('Map not initialized');

            const stored = localStorage.getItem(sessionId);
            if (!stored) throw new Error('State not found');

            const state = JSON.parse(stored);
            if (!this.validateState(state)) {
                throw new Error('Invalid state data');
            }

            this.clearCurrentState();
            this.state.currentState = state;

            if (state.viewport) {
                map.setView(
                    [state.viewport.center.lat, state.viewport.center.lng],
                    state.viewport.zoom
                );
            }

            if (state.polygon && state.polygon.length > 0) {
                this.state.currentPolygon = L.polygon(state.polygon, {
                    color: '#00A650',
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.2
                });

                if (!map.hasLayer(this.state.drawnItems)) {
                    this.state.drawnItems.addTo(map);
                }

                this.state.drawnItems.clearLayers();
                this.state.drawnItems.addLayer(this.state.currentPolygon);

                const coordinates = state.polygon.map(coord => [coord.lng, coord.lat]);
                const geometry = {
                    type: 'Polygon',
                    coordinates: [coordinates]
                };

                // Modificación aquí: Adaptar la carga de puntos al nuevo formato
                const points = await this.loadStatePoints(state.points.map(p => p.id));
                if (points && points.length > 0) {
                    const filteredPoints = turf.pointsWithinPolygon(
                        {
                            type: 'FeatureCollection',
                            features: points.map(point => ({
                                type: 'Feature',
                                geometry: {
                                    type: 'Point',
                                    coordinates: [point.longitud, point.latitud]
                                },
                                properties: point
                            }))
                        },
                        geometry
                    );

                    dataLoader.handlePoints(filteredPoints.features.map(f => f.properties));
                }
            } else {
                await dataLoader.loadPoints();
            }

            // Disparar evento de estado cargado
            document.dispatchEvent(new CustomEvent('stateLoaded', {
                detail: { stateId: sessionId }
            }));

            return true;
        } catch (error) {
            console.error('Error loading state:', error);
            return false;
        }
    }

    async loadStatePoints(pointIds) {
        try {
            const response = await fetch('https://lightsteelblue-spoonbill-227005.hostingersite.com/api/incidencias');
            const data = await response.json();
            return data.data.filter(point => pointIds.includes(point.identificador));
        } catch (error) {
            console.error('Error loading points:', error);
            return [];
        }
    }

    clearCurrentState() {
        const map = mapManager.getMap();
        if (map && this.state.drawnItems) {
            this.state.drawnItems.clearLayers();
        }
        
        this.state.currentPolygon = null;
        this.state.currentState = null;
        this.state.lastUpdate = null;

        dataLoader.loadPoints();

        const url = new URL(window.location);
        url.searchParams.delete('state');
        window.history.pushState({}, '', url);
    }

    getLocalStates() {
        const states = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key?.startsWith(this.statePrefix)) {
                try {
                    const state = JSON.parse(localStorage.getItem(key));
                    if (this.validateState(state)) {
                        states.push(state);
                    }
                } catch (error) {
                    console.error(`Error parsing state ${key}:`, error);
                }
            }
        }
        return states.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
    }

    checkUrlState() {
        const urlParams = new URLSearchParams(window.location.search);
        const stateId = urlParams.get('state');
        if (stateId) {
            this.loadState(stateId);
        }
    }

    generateSessionId() {
        const date = new Date();
        const timestamp = date.toISOString()
            .replace(/[-:]/g, '')
            .replace('T', '_')
            .split('.')[0];
        return `${this.statePrefix}${timestamp}`;
    }

    // Getters y setters
    getCurrentState() {
        return this.state.currentState;
    }

    getDrawnItems() {
        return this.state.drawnItems;
    }

    getCurrentPolygon() {
        return this.state.currentPolygon;
    }

    setCurrentPolygon(polygon) {
        this.state.currentPolygon = polygon;
        if (polygon) {
            this.state.lastUpdate = new Date().toISOString();
        }
    }

    getLastUpdate() {
        return this.state.lastUpdate;
    }

    isStateStale() {
        if (!this.state.lastUpdate) return true;
        const staleThreshold = 5 * 60 * 1000; // 5 minutos
        return (new Date() - new Date(this.state.lastUpdate)) > staleThreshold;
    }
}

export const mapStateManager = new MapStateManager();