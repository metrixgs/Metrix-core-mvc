import { wfsLayers } from './config/wfsConfig.js';
import { zoomBehaviors } from './config/zoomConfig.js';
import { municipalityLayer } from './MunicipalityLayer.js';
import { styleModeManager } from '../core/StyleModeManager.js';

class WFSLayerManager {
    constructor() {
        this.layers = new Map();
        this.map = null;
        this.activeLayerIds = new Set();
        this.incidentsLayer = null;
        this.selectedFeature = null;
    }

    init(map) {
        this.map = map;
        this.setupZoomHandler();
        this.setupThemeObserver();
    }

    setupThemeObserver() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'data-theme') {
                    this.updateLayerStyles();
                }
            });
        });

        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['data-theme']
        });
    }

    updateLayerStyles() {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        
        this.layers.forEach((layerInfo, layerId) => {
            const config = wfsLayers.adminBoundaries.layers.find(l => l.id === layerId);
            if (config) {
                const style = isDarkTheme && config.darkStyle ? config.darkStyle : config.style;
                layerInfo.layer.setStyle(style);
            }
        });
    }

    setupZoomHandler() {
        if (!this.map) return;
        
        this.map.on('zoomend', () => {
            this.handleZoomChange(this.map.getZoom());
        });
    }

    handleZoomChange(currentZoom) {
        this.layers.forEach((layerInfo, layerId) => {
            const behavior = zoomBehaviors[layerId];
            if (!behavior) return;

            const { layer } = layerInfo;
            const { minZoom, maxZoom } = behavior;
            const shouldBeVisible = currentZoom >= minZoom && currentZoom <= maxZoom;
            const isVisible = this.map.hasLayer(layer);

            if (shouldBeVisible !== isVisible) {
                if (shouldBeVisible) {
                    if (this.activeLayerIds.has(layerId)) {
                        this.map.addLayer(layer);
                    }
                } else {
                    this.map.removeLayer(layer);
                }
            }

            if (shouldBeVisible && isVisible) {
                const style = this.getZoomBasedStyle(currentZoom, layerId);
                layer.setStyle(style);
            }
        });
    }

    getZoomBasedStyle(zoom, layerId) {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        const config = wfsLayers.adminBoundaries.layers.find(l => l.id === layerId);
        const baseStyle = isDarkTheme && config?.darkStyle ? config.darkStyle : config?.style || {};
        
        if (zoom <= 6) {
            return {
                ...baseStyle,
                weight: 2.0,
                fillOpacity: 0.03
            };
        } else if (zoom >= 8) {
            return {
                ...baseStyle,
                weight: 2.0,
                fillOpacity: 0.12
            };
        }
        
        return baseStyle;
    }

    toggleLayer(layerId) {
        if (layerId === 'incidencias') {
            return this.toggleIncidentsLayer();
        }

        const layerInfo = this.layers.get(layerId);
        if (!layerInfo) return false;

        const { layer } = layerInfo;
        const currentZoom = this.map.getZoom();
        const behavior = zoomBehaviors[layerId];
        const isInZoomRange = behavior ? 
            (currentZoom >= behavior.minZoom && currentZoom <= behavior.maxZoom) : 
            true;

        if (this.activeLayerIds.has(layerId)) {
            this.activeLayerIds.delete(layerId);
            if (isInZoomRange) {
                this.map.removeLayer(layer);
            }
            return false;
        } else {
            this.activeLayerIds.add(layerId);
            if (isInZoomRange) {
                this.map.addLayer(layer);
            }
            return true;
        }
    }

    toggleIncidentsLayer() {
        if (!this.incidentsLayer) return false;

        if (this.map.hasLayer(this.incidentsLayer)) {
            this.map.removeLayer(this.incidentsLayer);
            return false;
        } else {
            this.map.addLayer(this.incidentsLayer);
            return true;
        }
    }

    registerIncidentsLayer(layer) {
        this.incidentsLayer = layer;
    }

    loadLayer(layerConfig) {
        if (!this.map) {
            console.error('Map not initialized');
            return;
        }

        if (this.layers.has(layerConfig.id)) {
            console.log(`Layer ${layerConfig.id} already loaded`);
            return;
        }

        const jsonpCallback = `wfsCallback_${layerConfig.id}`;
        window[jsonpCallback] = (data) => {
            this.handleWFSData(data, layerConfig);
            delete window[jsonpCallback];
        };

        const script = document.createElement('script');
        script.src = `${layerConfig.url}?callback=${jsonpCallback}`;
        document.body.appendChild(script);
    }

    handleWFSData(data, layerConfig) {
        if (!data || !data.features) {
            console.error(`Invalid WFS data for layer ${layerConfig.id}`);
            return;
        }

        const isDarkTheme = document.body.dataset.theme === 'dark';
        const initialStyle = isDarkTheme && layerConfig.darkStyle ? layerConfig.darkStyle : layerConfig.style;

        const layer = L.geoJSON(data, {
            style: initialStyle,
            interactive: true,
            layerId: layerConfig.id,
            layerName: layerConfig.name,
            onEachFeature: (feature, layer) => {
                if (feature.properties) {
                    layer.bindPopup(this.createPopupContent(feature.properties));
                }
                
                layer.on('mouseover', (e) => {
                    if (!e.target.isSelected && !styleModeManager.isActive()) {
                        const hoverStyle = {
                            ...initialStyle,
                            weight: initialStyle.weight + 0.5,
                            fillOpacity: initialStyle.fillOpacity * 2
                        };
                        e.target.setStyle(hoverStyle);
                    }
                });
                
                layer.on('mouseout', (e) => {
                    if (!e.target.isSelected && !styleModeManager.isActive()) {
                        e.target.setStyle(initialStyle);
                    }
                });
                
                layer.on('click', (e) => {
                    if (styleModeManager.isActive()) {
                        styleModeManager.selectFeature(feature, e.target);
                    } else {
                        if (this.selectedFeature) {
                            this.selectedFeature.isSelected = false;
                            this.selectedFeature.setStyle(initialStyle);
                        }
                        
                        e.target.isSelected = true;
                        const selectedStyle = {
                            ...initialStyle,
                            weight: initialStyle.weight + 0.5,
                            fillOpacity: initialStyle.fillOpacity * 3
                        };
                        e.target.setStyle(selectedStyle);
                        this.selectedFeature = e.target;
                    }
                });

                layer.on('dblclick', (e) => {
                    if (styleModeManager.isActive()) {
                        styleModeManager.selectLayer(layerConfig.id, layer);
                        return;
                    }

                    L.DomEvent.stopPropagation(e);
                    
                    this.map.fitBounds(e.target.getBounds(), {
                        padding: [50, 50]
                    });

                    this.map.fire('featureselected', {
                        feature: feature,
                        layer: e.target,
                        layerId: layerConfig.id
                    });

                    if (layerConfig.id === 'limite_mxestados' && feature.properties.entidad) {
                        municipalityLayer.loadMunicipalities(
                            feature.properties.entidad,
                            feature.properties.nombre
                        );
                    }
                });
            }
        });

        this.layers.set(layerConfig.id, {
            layer,
            config: layerConfig
        });

        const currentZoom = this.map.getZoom();
        const behavior = zoomBehaviors[layerConfig.id];
        
        if (behavior && currentZoom >= behavior.minZoom && currentZoom <= behavior.maxZoom) {
            layer.addTo(this.map);
            const style = this.getZoomBasedStyle(currentZoom, layerConfig.id);
            layer.setStyle(style);
        }

        this.map.fire('wfslayerloaded', {
            layerId: layerConfig.id,
            layer: layer
        });
    }

    createPopupContent(properties) {
        const isDarkTheme = document.body.dataset.theme === 'dark';
        const bgColor = isDarkTheme ? '#2d2d2d' : '#ffffff';
        const textColor = isDarkTheme ? '#ffffff' : '#333333';
        const borderColor = isDarkTheme ? '#404040' : '#e2e8f0';
        const buttonBgHover = isDarkTheme ? '#2c5282' : '#2b6cb0';
        
        return `
            <div style="
                background: ${bgColor};
                color: ${textColor};
                padding: 1.5rem;
                border-radius: 0.5rem;
                min-width: 250px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            ">
                <h3 style="
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: #00A650;
                    margin: 0 0 1rem 0;
                    border-bottom: 2px solid #00A650;
                    padding-bottom: 0.5rem;
                ">${properties.nombre || 'Sin nombre'}</h3>
                
                <div style="
                    margin-bottom: 1rem;
                    padding: 0.75rem;
                    background: ${isDarkTheme ? '#363636' : '#f8fafc'};
                    border-radius: 0.375rem;
                    border: 1px solid ${borderColor};
                ">
                    <p style="
                        margin: 0;
                        font-size: 0.875rem;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <span style="font-weight: 500;">ID Entidad:</span>
                        <span style="color: #00A650;">${properties.entidad || 'N/A'}</span>
                    </p>
                </div>

                <button 
                    onclick="window.loadStateMunicipalities('${properties.entidad}', '${properties.nombre}')"
                    style="
                        width: 100%;
                        padding: 0.75rem;
                        background: #00A650;
                        color: white;
                        border: none;
                        border-radius: 0.375rem;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                    "
                    onmouseover="this.style.background='${buttonBgHover}'"
                    onmouseout="this.style.background='#00A650'"
                >
                    <i class="bi bi-geo-alt"></i>
                    Ver Municipios
                </button>
            </div>
        `;
    }

    loadAllLayers() {
        if (!this.map) {
            console.error('Map not initialized');
            return;
        }

        Object.values(wfsLayers).forEach(category => {
            category.layers.forEach(layer => {
                this.loadLayer(layer);
            });
        });
    }

    getEditableLayers() {
        const layers = [];
        this.layers.forEach((layerInfo, layerId) => {
            layers.push({
                id: layerId,
                name: layerInfo.config.name,
                layer: layerInfo.layer
            });
        });
        return layers;
    }

    setLayerStyle(layerId, style) {
        const layerInfo = this.layers.get(layerId);
        if (layerInfo) {
            layerInfo.layer.setStyle(style);
            layerInfo.config.style = { ...layerInfo.config.style, ...style };
        }
    }

    getLayerStyle(layerId) {
        const layerInfo = this.layers.get(layerId);
        return layerInfo ? layerInfo.config.style : null;
    }

    getSelectedFeature() {
        return this.selectedFeature;
    }

    clearSelection() {
        if (this.selectedFeature) {
            const layerId = this.selectedFeature.feature?.layerId;
            const layerInfo = this.layers.get(layerId);
            if (layerInfo) {
                this.selectedFeature.setStyle(layerInfo.config.style);
            }
            this.selectedFeature.isSelected = false;
            this.selectedFeature = null;
        }
    }

    isLayerActive(layerId) {
        return this.activeLayerIds.has(layerId);
    }

    clearAllLayers() {
        this.activeLayerIds.clear();
        this.layers.forEach((layerInfo) => {
            if (this.map.hasLayer(layerInfo.layer)) {
                this.map.removeLayer(layerInfo.layer);
            }
        });
    }
}

window.loadStateMunicipalities = (stateId, stateName) => {
    municipalityLayer.loadMunicipalities(stateId, stateName);
};

export const wfsLayerManager = new WFSLayerManager();