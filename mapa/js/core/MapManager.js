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
            this.map = L.map(containerId, config).setView([19.4326, -99.1332], 11);
            this.isInitialized = true;
            this.setupErrorHandling();
            this.initializeBaseLayers();
            this.initializeControls();
            this.setupThemeListener();
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
            }),
            "Stadia Alidade Smooth": L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
                minZoom: 0,
                maxZoom: 20,
                attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }),
            "CartoDB Positron": L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            })
        };

        // Almacenar instancias
        Object.entries(baseLayers).forEach(([name, layer]) => {
            this.baseLayerInstances.set(name, layer);
        });

        // Determinar capa inicial basada en el tema actual
        const isDarkTheme = document.body.dataset.theme === 'dark';
        const initialLayer = isDarkTheme ? "Jawg Maps" : "CartoDB Positron";
        this.baseLayerInstances.get(initialLayer).addTo(this.map);

        // Crear control de capas con estilo mejorado
        this.layerControl = L.control.layers(baseLayers, {}, {
            position: "topright",
            collapsed: true,
            className: 'leaflet-control-layers-improved'
        }).addTo(this.map);

        // Agregar estilos para mejorar la visibilidad del control
        const style = document.createElement('style');
        style.textContent = `
            .leaflet-control-layers {
                background: var(--bg-color) !important;
                border: 1px solid var(--panel-border) !important;
            }
            .leaflet-control-layers-toggle {
                background-color: var(--bg-color) !important;
                border: 1px solid var(--panel-border) !important;
                width: 36px !important;
                height: 36px !important;
                line-height: 36px !important;
                text-align: center !important;
                background-image: none !important;
            }
            .leaflet-control-layers-toggle::before {
                content: "\\F229" !important;
                font-family: "bootstrap-icons" !important;
                font-size: 18px !important;
                color: var(--text-color) !important;
            }
            .leaflet-control-layers-toggle:hover {
                background-color: var(--primary-color) !important;
                color: white !important;
            }
            .leaflet-control-layers-toggle:hover::before {
                color: white !important;
            }
        `;
        document.head.appendChild(style);
    }

    setupThemeListener() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'data-theme') {
                    const isDarkTheme = document.body.dataset.theme === 'dark';
                    this.updateBaseLayer(isDarkTheme);
                }
            });
        });

        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['data-theme']
        });
    }

    updateBaseLayer(isDarkTheme) {
        this.baseLayerInstances.forEach(layer => {
            if (this.map.hasLayer(layer)) {
                this.map.removeLayer(layer);
            }
        });

        const layerName = isDarkTheme ? "Jawg Maps" : "CartoDB Positron";
        this.baseLayerInstances.get(layerName).addTo(this.map);
    }

    initializeControls() {
        L.control.zoom({
            position: "topright"
        }).addTo(this.map);

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