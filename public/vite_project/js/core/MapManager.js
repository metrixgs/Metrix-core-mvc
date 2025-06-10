class MapManager {
    constructor() {
        this.map = null;
        this.layerControl = null;
        this.isInitialized = false;
        this.baseLayerInstances = new Map();
        this.eventHandlers = new Map();
    }

    initialize(containerId, config) {
        if (this.isInitialized) {
            console.warn('Map already initialized');
            return this.map;
        }

        try {
            this.map = L.map(containerId, config).setView([14.0906, -87.2054], 6);
            this.isInitialized = true;
            this.setupErrorHandling();
            this.initializeBaseLayers();
            this.initializeControls();
            return this.map;
        } catch (error) {
            console.error('Error initializing map:', error);
            this.isInitialized = false;
            throw error;
        }
    }

    initializeBaseLayers() {
        // Definir capas base
        const baseLayers = {
            "Jawg Maps": L.tileLayer('https://tile.jawg.io/6ce62bcb-c195-4d31-a3ce-421b1d40f3bd/{z}/{x}/{y}{r}.png?access-token=xpGCLKVCsTyKo9B2QbcI9mKUWCpJdS4PEpT1rsVCeZoENPdujT3KjjiEe9YLIwCO', {}),
            "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }),
            "Dark Matter": L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }),
            "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Esri'
            })
        };

        // Almacenar instancias
        Object.entries(baseLayers).forEach(([name, layer]) => {
            this.baseLayerInstances.set(name, layer);
        });

        // Añadir capa por defecto
        this.baseLayerInstances.get("Jawg Maps").addTo(this.map);

        // Crear control de capas
        this.layerControl = L.control.layers(baseLayers, {}, {
            position: "topright",
            collapsed: true
        }).addTo(this.map);
    }

    initializeControls() {
        // Añadir control de zoom
        L.control.zoom({
            position: "topright"
        }).addTo(this.map);

        // Añadir control de localización
        L.control.locate({
            position: 'topright',
            strings: {
                title: "Show my location"
            },
            flyTo: true,
            cacheLocation: true,
            showCompass: true,
            showPopup: false
        }).addTo(this.map);
    }

    switchToSatellite() {
        const satellite = this.baseLayerInstances.get("Satellite");
        const jawg = this.baseLayerInstances.get("Jawg Maps");
        if (satellite && !this.map.hasLayer(satellite)) {
            if (jawg) this.map.removeLayer(jawg);
            this.map.addLayer(satellite);
        }
    }

    switchToDefault() {
        const satellite = this.baseLayerInstances.get("Satellite");
        const jawg = this.baseLayerInstances.get("Jawg Maps");
        if (jawg && !this.map.hasLayer(jawg)) {
            if (satellite) this.map.removeLayer(satellite);
            this.map.addLayer(jawg);
        }
    }

    setupErrorHandling() {
        this.map.on('error', (error) => {
            console.error('Map error:', error);
        });

        window.addEventListener('offline', () => {
            console.warn('Connection lost - some map features may be unavailable');
        });

        window.addEventListener('online', () => {
            console.log('Connection restored - refreshing map features');
            this.refreshVisibleLayers();
        });
    }

    refreshVisibleLayers() {
        if (!this.map) return;
        
        this.map.eachLayer((layer) => {
            if (layer instanceof L.TileLayer) {
                layer.redraw();
            }
        });
    }

    getMap() {
        if (!this.isInitialized) {
            console.warn('Map not initialized');
        }
        return this.map;
    }

    setLayerControl(control) {
        this.layerControl = control;
    }

    getLayerControl() {
        return this.layerControl;
    }

    addEventHandler(event, handler) {
        if (!this.map) return;
        
        if (!this.eventHandlers.has(event)) {
            this.eventHandlers.set(event, new Set());
        }
        this.eventHandlers.get(event).add(handler);
        this.map.on(event, handler);
    }

    removeEventHandler(event, handler) {
        if (!this.map) return;
        
        const handlers = this.eventHandlers.get(event);
        if (handlers?.has(handler)) {
            this.map.off(event, handler);
            handlers.delete(handler);
        }
    }

    destroy() {
        if (this.map) {
            this.map.remove();
            this.map = null;
            this.layerControl = null;
            this.isInitialized = false;
            this.baseLayerInstances.clear();
            this.eventHandlers.clear();
        }
    }
}

export const mapManager = new MapManager();