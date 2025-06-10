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
        const baseStyle = wfsLayers.adminBoundaries.layers.find(l => l.id === layerId)?.style || {};
        
        if (zoom <= 6) {
            return {
                ...baseStyle,
                weight: 1,
                fillOpacity: 0.05
            };
        } else if (zoom >= 8) {
            return {
                ...baseStyle,
                weight: 2.5,
                fillOpacity: 0.15
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

        const layer = L.geoJSON(data, {
            style: layerConfig.style,
            interactive: true,
            layerId: layerConfig.id,
            layerName: layerConfig.name,
            onEachFeature: (feature, layer) => {
                if (feature.properties) {
                    layer.bindPopup(this.createPopupContent(feature.properties));
                }
                
                layer.on('mouseover', (e) => {
                    if (!e.target.isSelected && !styleModeManager.isActive()) {
                        e.target.setStyle({
                            color: '#00A650',
                            weight: 3,
                            fillOpacity: 0.2
                        });
                    }
                });
                
                layer.on('mouseout', (e) => {
                    if (!e.target.isSelected && !styleModeManager.isActive()) {
                        e.target.setStyle(layerConfig.style);
                    }
                });
                
                layer.on('click', (e) => {
                    if (styleModeManager.isActive()) {
                        // Si el modo estilo está activo, seleccionar para edición
                        styleModeManager.selectFeature(feature, e.target);
                    } else {
                        // Comportamiento normal de selección
                        if (this.selectedFeature) {
                            this.selectedFeature.isSelected = false;
                            this.selectedFeature.setStyle(layerConfig.style);
                        }
                        
                        e.target.isSelected = true;
                        e.target.setStyle({
                            color: '#00A650',
                            weight: 3,
                            fillOpacity: 0.3
                        });
                        this.selectedFeature = e.target;
                    }
                });

                // Doble clic para zoom y selección
                layer.on('dblclick', (e) => {
                    if (styleModeManager.isActive()) {
                        // Si el modo estilo está activo, seleccionar toda la capa
                        styleModeManager.selectLayer(layerConfig.id, layer);
                        return;
                    }

                    // Prevenir zoom del mapa
                    L.DomEvent.stopPropagation(e);
                    
                    // Hacer zoom al polígono
                    this.map.fitBounds(e.target.getBounds(), {
                        padding: [50, 50]
                    });

                    // Disparar evento de selección
                    this.map.fire('featureselected', {
                        feature: feature,
                        layer: e.target,
                        layerId: layerConfig.id
                    });

                    // Si es un estado, cargar sus municipios
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

        // Disparar evento de capa cargada
        this.map.fire('wfslayerloaded', {
            layerId: layerConfig.id,
            layer: layer
        });
    }

    createPopupContent(properties) {
        return `
            <div class="p-4">
                <h3 class="text-lg font-semibold text-emerald-600 mb-2">${properties.nombre || 'Sin nombre'}</h3>
                <div class="text-sm text-gray-600 mb-4">
                    <p>Entidad: ${properties.entidad || 'N/A'}</p>
                </div>
                <button onclick="window.loadStateMunicipalities('${properties.entidad}', '${properties.nombre}')" 
                        class="w-full py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors text-sm font-medium">
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