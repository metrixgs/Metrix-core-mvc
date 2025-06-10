import { mapManager } from '../core/MapManager.js';
import { styleModeManager } from '../core/StyleModeManager.js';
import { stylePersistenceManager } from '../core/StylePersistenceManager.js';
import { ErrorHandler } from '../core/ErrorHandler.js';

class MunicipalityLayer {
    constructor() {
        this.currentStateId = null;
        this.currentStateName = null;
        this.isLoading = false;
        this.layerId = 'limite_mxmunicipios';
        this.layerName = 'Municipios';
        
        // Grupos de capas
        this.featureGroup = L.featureGroup();
        this.highlightGroup = L.featureGroup();
        this.dropdownSelectionGroup = L.featureGroup();
        this.temporaryMunicipalitiesGroup = L.featureGroup();
        
        // Cache de datos
        this.municipalityCache = new Map();
        
        // Cleanup handlers
        this.cleanupHandlers = new Set();
        
        // Estilos
        this.initializeStyles();
    }

    initializeStyles() {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        
        this.baseStyle = {
            color: isDarkTheme ? '#00C853' : '#00A650',
            weight: 2,
            opacity: 1,
            fillOpacity: isDarkTheme ? 0.08 : 0.1,
            fillColor: isDarkTheme ? '#424242' : '#FFFFFF'
        };
        
        this.highlightStyle = {
            color: isDarkTheme ? '#00E676' : '#00A650',
            weight: 3,
            opacity: 1,
            fillOpacity: isDarkTheme ? 0.25 : 0.3,
            className: 'highlight-animation'
        };

        this.temporaryStyle = {
            color: isDarkTheme ? '#00C853' : '#00A650',
            weight: 1.5,
            opacity: 0.7,
            fillOpacity: isDarkTheme ? 0.05 : 0.08,
            fillColor: isDarkTheme ? '#424242' : '#FFFFFF'
        };

        // Observer para cambios de tema
        const observer = new MutationObserver(() => this.updateStyles());
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['data-theme']
        });
        this.cleanupHandlers.add(() => observer.disconnect());
    }

    updateStyles() {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        
        this.baseStyle = {
            ...this.baseStyle,
            color: isDarkTheme ? '#00C853' : '#00A650',
            fillOpacity: isDarkTheme ? 0.08 : 0.1,
            fillColor: isDarkTheme ? '#424242' : '#FFFFFF'
        };

        this.highlightStyle = {
            ...this.highlightStyle,
            color: isDarkTheme ? '#00E676' : '#00A650'
        };

        this.temporaryStyle = {
            ...this.temporaryStyle,
            color: isDarkTheme ? '#00C853' : '#00A650',
            fillOpacity: isDarkTheme ? 0.05 : 0.08,
            fillColor: isDarkTheme ? '#424242' : '#FFFFFF'
        };

        // Actualizar estilos de capas existentes
        this.featureGroup.eachLayer(layer => {
            if (layer.setStyle) {
                const savedStyle = stylePersistenceManager.getStyle('feature', layer.feature?.id);
                layer.setStyle(savedStyle || this.baseStyle);
            }
        });

        this.temporaryMunicipalitiesGroup.eachLayer(layer => {
            if (layer.setStyle) {
                layer.setStyle(this.temporaryStyle);
            }
        });
    }

    async loadMunicipalities(stateId, stateName) {
        if (this.isLoading) return;

        const map = mapManager.getMap();
        if (!map) throw new Error('Map not initialized');

        try {
            this.isLoading = true;
            this.clear();

            // Intentar usar cache
            if (this.municipalityCache.has(stateId)) {
                const cachedData = this.municipalityCache.get(stateId);
                this.handleMunicipalityData(cachedData, stateName);
                return;
            }

            const data = await this.fetchMunicipalityData(stateId);
            this.municipalityCache.set(stateId, data);
            this.handleMunicipalityData(data, stateName);

        } catch (error) {
            ErrorHandler.handleAPIError(error, 'loadMunicipalities');
            throw error;
        } finally {
            this.isLoading = false;
        }
    }

    async loadDropdownMunicipalities(stateId) {
        const map = mapManager.getMap();
        if (!map) return;

        try {
            this.clearTemporaryMunicipalities();

            // Intentar usar cache o cargar datos
            let data;
            if (this.municipalityCache.has(stateId)) {
                data = this.municipalityCache.get(stateId);
            } else {
                data = await this.fetchMunicipalityData(stateId);
                this.municipalityCache.set(stateId, data);
            }

            const geoJsonLayer = L.geoJSON(data, {
                style: this.temporaryStyle
            });

            this.temporaryMunicipalitiesGroup.addLayer(geoJsonLayer);
            this.temporaryMunicipalitiesGroup.addTo(map);

            return true;
        } catch (error) {
            console.error('Error loading temporary municipalities:', error);
            return false;
        }
    }

    async fetchMunicipalityData(stateId) {
        return new Promise((resolve, reject) => {
            const callbackName = `municipalityCallback_${stateId}_${Date.now()}`;
            
            window[callbackName] = (data) => {
                delete window[callbackName];
                resolve(data);
            };

            const script = document.createElement('script');
            script.src = `https://espacialhn.com/slim4/api/api/sinit/limite_mxmunicipios/?entidad=${stateId}&callback=${callbackName}`;
            script.onerror = () => {
                delete window[callbackName];
                reject(new Error('Failed to load municipalities'));
            };
            document.body.appendChild(script);
            script.remove();
        });
    }

    handleMunicipalityData(data, stateName) {
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

            map.fitBounds(this.featureGroup.getBounds(), {
                padding: [50, 50]
            });

            const layerControl = mapManager.getLayerControl();
            if (layerControl) {
                layerControl.addOverlay(this.featureGroup, `Municipios de ${stateName}`);
            }

            this.currentStateId = data.features[0]?.properties?.entidad;
            this.currentStateName = stateName;

        } catch (error) {
            ErrorHandler.handleAPIError(error, 'handleMunicipalityData');
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
                    <h3>${properties.nombre || 'Municipio'}</h3>
                </div>
                <div class="popup-content">
                    <div class="info-row">
                        <span class="label">Estado:</span>
                        <span class="value">${this.currentStateName}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">ID:</span>
                        <span class="value">${properties.id}</span>
                    </div>
                </div>
            </div>
        `;
    }

    async highlightDropdownSelection(municipalityId) {
        const map = mapManager.getMap();
        if (!map) return;

        this.clearDropdownSelection();

        try {
            const feature = this.findFeatureInTemporaryLayer(municipalityId);
            if (!feature) return;

            const highlightLayer = L.geoJSON(feature.toGeoJSON(), {
                style: {
                    ...this.highlightStyle,
                    className: 'highlight-animation municipality-active'
                }
            });

            this.dropdownSelectionGroup.addLayer(highlightLayer);
            this.dropdownSelectionGroup.addTo(map);

            return true;
        } catch (error) {
            console.error('Error highlighting municipality:', error);
            this.clearDropdownSelection();
            return false;
        }
    }

    findFeatureInTemporaryLayer(municipalityId) {
        let foundFeature = null;
        this.temporaryMunicipalitiesGroup.eachLayer(layer => {
            layer.eachLayer(feature => {
                if (feature.feature?.properties?.municipio === municipalityId) {
                    foundFeature = feature;
                }
            });
        });
        return foundFeature;
    }

    clearDropdownSelection() {
        const map = mapManager.getMap();
        if (map?.hasLayer(this.dropdownSelectionGroup)) {
            map.removeLayer(this.dropdownSelectionGroup);
        }
        this.dropdownSelectionGroup.clearLayers();
    }

    clearTemporaryMunicipalities() {
        const map = mapManager.getMap();
        if (map?.hasLayer(this.temporaryMunicipalitiesGroup)) {
            map.removeLayer(this.temporaryMunicipalitiesGroup);
        }
        this.temporaryMunicipalitiesGroup.clearLayers();
    }

    highlightMunicipality(municipalityId) {
        this.clearHighlight();

        const feature = this.getMunicipalityFeature(municipalityId);
        if (!feature) return false;

        const highlightLayer = L.geoJSON(feature.toGeoJSON(), {
            style: this.highlightStyle
        });

        this.highlightGroup.addLayer(highlightLayer);
        this.highlightGroup.addTo(mapManager.getMap());
        
        setTimeout(() => this.clearHighlight(), 3000);
        return true;
    }

    getMunicipalityFeature(municipalityId) {
        let foundFeature = null;
        this.featureGroup.eachLayer(layer => {
            layer.eachLayer(feature => {
                if (feature.feature?.properties?.municipio === municipalityId) {
                    foundFeature = feature;
                }
            });
        });
        return foundFeature;
    }

    clear() {
        const map = mapManager.getMap();
        if (!map) return;

        this.clearHighlight();
        this.clearDropdownSelection();
        this.clearTemporaryMunicipalities();
        
        if (map.hasLayer(this.featureGroup)) {
            map.removeLayer(this.featureGroup);
        }
        
        this.featureGroup.clearLayers();
        
        const layerControl = mapManager.getLayerControl();
        if (layerControl) {
            layerControl.removeLayer(this.featureGroup);
        }

        this.currentStateId = null;
        this.currentStateName = null;
    }

    clearHighlight() {
        const map = mapManager.getMap();
        if (map?.hasLayer(this.highlightGroup)) {
            map.removeLayer(this.highlightGroup);
        }
        this.highlightGroup.clearLayers();
    }

    destroy() {
        this.clear();
        this.municipalityCache.clear();
        this.cleanupHandlers.forEach(cleanup => cleanup());
        this.cleanupHandlers.clear();
    }

    isActive() {
        return this.currentStateId !== null;
    }

    getCurrentStateId() {
        return this.currentStateId;
    }

    getCurrentStateName() {
        return this.currentStateName;
    }

    getFeatureGroup() {
        return this.featureGroup;
    }
}

export const municipalityLayer = new MunicipalityLayer();