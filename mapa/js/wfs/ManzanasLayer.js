import { mapManager } from '../core/MapManager.js';
import { styleModeManager } from '../core/StyleModeManager.js';
import { stylePersistenceManager } from '../core/StylePersistenceManager.js';
import { manzanasConfig } from './config/manzanasConfig.js';
import { ErrorHandler } from '../core/ErrorHandler.js';

class ManzanasLayer {
    constructor() {
        this.currentMunicipioId = null;
        this.isLoading = false;
        this.layerId = 'manzanas_queretaro';
        this.layerName = 'Manzanas';
        this.featureGroup = L.featureGroup();
        this.cleanupHandlers = new Set();
        this.initializeStyles();
    }

    initializeStyles() {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        this.baseStyle = isDarkTheme ? manzanasConfig.darkStyle : manzanasConfig.style;

        // Observer for theme changes
        const observer = new MutationObserver(() => this.updateStyles());
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['data-theme']
        });
        this.cleanupHandlers.add(() => observer.disconnect());
    }

    updateStyles() {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        this.baseStyle = isDarkTheme ? manzanasConfig.darkStyle : manzanasConfig.style;

        this.featureGroup.eachLayer(layer => {
            if (layer.setStyle) {
                const savedStyle = stylePersistenceManager.getStyle('feature', layer.feature?.id);
                layer.setStyle(savedStyle || this.baseStyle);
            }
        });
    }

    async loadManzanas(municipioData) {
        if (this.isLoading) return;

        const map = mapManager.getMap();
        if (!map) throw new Error('Map not initialized');

        try {
            this.isLoading = true;
            this.clear();

            const data = await this.fetchManzanasData(municipioData.nombre);
            this.handleManzanasData(data);

        } catch (error) {
            ErrorHandler.handleAPIError(error, 'loadManzanas');
            throw error;
        } finally {
            this.isLoading = false;
        }
    }

    async fetchManzanasData(municipioNombre) {
        return new Promise((resolve, reject) => {
            const callbackName = `manzanasCallback_${Date.now()}`;
            
            window[callbackName] = (data) => {
                delete window[callbackName];
                resolve(data);
            };

            // Codificar el nombre del municipio para la URL
            const encodedNombre = encodeURIComponent(municipioNombre);
            const script = document.createElement('script');
            script.src = `${manzanasConfig.baseUrl}/?nombre_mun=${encodedNombre}&callback=${callbackName}`;
            script.onerror = () => {
                delete window[callbackName];
                reject(new Error('Failed to load manzanas'));
            };
            document.body.appendChild(script);
            script.remove();
        });
    }

    handleManzanasData(data) {
        const map = mapManager.getMap();
        if (!map || !data?.features?.length) return;

        try {
            const geoJsonLayer = L.geoJSON(data, {
                style: this.baseStyle,
                onEachFeature: (feature, layer) => {
                    feature.id = feature.properties.id;
                    feature.layerId = this.layerId;
                    
                    const savedStyle = stylePersistenceManager.getStyle('feature', feature.id);
                    if (savedStyle) layer.setStyle(savedStyle);

                    this.setupLayerEvents(layer, feature);
                }
            });

            this.featureGroup.addLayer(geoJsonLayer);
            this.featureGroup.addTo(map);

            const layerControl = mapManager.getLayerControl();
            if (layerControl) {
                layerControl.addOverlay(this.featureGroup, 'Manzanas');
            }

            this.currentMunicipioId = data.features[0]?.properties?.municipio;

        } catch (error) {
            ErrorHandler.handleAPIError(error, 'handleManzanasData');
            this.clear();
        }
    }

    setupLayerEvents(layer, feature) {
        const events = {
            mouseover: (e) => {
                if (!e.target.isSelected && !styleModeManager.isActive()) {
                    e.target.setStyle({
                        ...this.baseStyle,
                        fillOpacity: this.baseStyle.fillOpacity * 2,
                        weight: this.baseStyle.weight * 1.5
                    });
                }
            },
            mouseout: (e) => {
                if (!e.target.isSelected && !styleModeManager.isActive()) {
                    const style = stylePersistenceManager.getStyle('feature', feature.id) || this.baseStyle;
                    e.target.setStyle(style);
                }
            },
            click: (e) => {
                if (styleModeManager.isActive()) {
                    styleModeManager.selectFeature(feature, e.target);
                }
            }
        };

        Object.entries(events).forEach(([event, handler]) => {
            layer.on(event, handler);
            this.cleanupHandlers.add(() => layer.off(event, handler));
        });

        layer.bindPopup(this.createPopupContent(feature.properties));
    }

    createPopupContent(properties) {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        return `
            <div class="custom-popup ${isDarkTheme ? 'dark' : 'light'}">
                <div class="popup-header">
                    <h3>Manzana</h3>
                </div>
                <div class="popup-content">
                    <div class="info-row">
                        <span class="label">ID:</span>
                        <span class="value">${properties.id}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Nombre:</span>
                        <span class="value">${properties.nombre}</span>
                    </div>
                </div>
            </div>
        `;
    }

    clear() {
        const map = mapManager.getMap();
        if (!map) return;
        
        if (map.hasLayer(this.featureGroup)) {
            map.removeLayer(this.featureGroup);
        }
        
        this.featureGroup.clearLayers();
        
        const layerControl = mapManager.getLayerControl();
        if (layerControl) {
            layerControl.removeLayer(this.featureGroup);
        }

        this.currentMunicipioId = null;
    }

    destroy() {
        this.clear();
        this.cleanupHandlers.forEach(cleanup => cleanup());
        this.cleanupHandlers.clear();
    }

    isActive() {
        return this.currentMunicipioId !== null;
    }

    getCurrentMunicipioId() {
        return this.currentMunicipioId;
    }

    getFeatureGroup() {
        return this.featureGroup;
    }
}

export const manzanasLayer = new ManzanasLayer();