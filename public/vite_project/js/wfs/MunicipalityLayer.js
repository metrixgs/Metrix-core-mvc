import { mapManager } from '../core/MapManager.js';
import { styleModeManager } from '../core/StyleModeManager.js';
import { stylePersistenceManager } from '../core/StylePersistenceManager.js';

class MunicipalityLayer {
    constructor() {
        this.layer = null;
        this.currentStateId = null;
        this.currentStateName = null;
        this.isLoading = false;
        this.layerId = 'limite_mxmunicipios';
        this.layerName = 'Municipios';
    }

    loadMunicipalities(stateId, stateName) {
        if (this.isLoading) {
            console.warn('Ya hay una carga de municipios en proceso');
            return;
        }

        const map = mapManager.getMap();
        if (!map) {
            console.error('Mapa no inicializado');
            return;
        }

        try {
            this.isLoading = true;

            // Limpiar capa anterior si existe
            if (this.layer) {
                map.removeLayer(this.layer);
                const layerControl = mapManager.getLayerControl();
                if (layerControl && this.currentStateId) {
                    layerControl.removeLayer(this.layer);
                }
                this.layer = null;
            }

            // Crear callback único para JSONP
            const callbackName = `municipalityCallback_${stateId}_${Date.now()}`;
            
            window[callbackName] = (data) => {
                this.handleMunicipalityData(data, stateName);
                delete window[callbackName];
                this.isLoading = false;
            };

            // Crear y agregar script para JSONP
            const script = document.createElement('script');
            script.src = `https://espacialhn.com/slim4/api/api/sinit/limite_mxmunicipios/?entidad=${stateId}&callback=${callbackName}`;
            script.onerror = () => {
                console.error('Error al cargar datos de municipios');
                delete window[callbackName];
                this.isLoading = false;
            };
            document.body.appendChild(script);

        } catch (error) {
            console.error('Error en carga de municipios:', error);
            this.isLoading = false;
        }
    }

    handleMunicipalityData(data, stateName) {
        const map = mapManager.getMap();
        if (!map || !data || !data.features) {
            console.error('Datos de municipios inválidos o mapa no disponible');
            return;
        }

        try {
            // Cargar estilo guardado para la capa completa
            const layerStyle = stylePersistenceManager.getStyle('layer', this.layerId) || {
                color: '#00A650',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.1
            };

            this.layer = L.geoJSON(data, {
                style: layerStyle,
                onEachFeature: (feature, layer) => {
                    // Usar el ID del GeoJSON directamente
                    feature.id = feature.properties.id;
                    feature.layerId = this.layerId;
                    
                    // Cargar estilo individual guardado si existe
                    const savedStyle = stylePersistenceManager.getStyle('feature', feature.id);
                    if (savedStyle) {
                        layer.setStyle(savedStyle);
                    }

                    const properties = feature.properties;
                    layer.bindPopup(`
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-emerald-600 mb-2">
                                ${properties.nombre || 'Municipio'}
                            </h3>
                            <div class="text-sm text-gray-600">
                                <p>Estado: ${stateName}</p>
                                <p>ID: ${properties.id}</p>
                            </div>
                        </div>
                    `);

                    // Eventos para modo edición
                    layer.on('click', (e) => {
                        if (styleModeManager.isActive()) {
                            styleModeManager.selectFeature(feature, e.target);
                        } else {
                            if (!e.target.isSelected) {
                                layer.setStyle({
                                    fillOpacity: 0.3,
                                    weight: 3
                                });
                            }
                        }
                    });

                    layer.on('mouseover', (e) => {
                        if (!e.target.isSelected && !styleModeManager.isActive()) {
                            layer.setStyle({
                                fillOpacity: 0.3,
                                weight: 3
                            });
                        }
                    });

                    layer.on('mouseout', (e) => {
                        if (!e.target.isSelected && !styleModeManager.isActive()) {
                            const style = stylePersistenceManager.getStyle('feature', feature.id) || layerStyle;
                            layer.setStyle(style);
                        }
                    });

                    // Doble clic para editar toda la capa
                    layer.on('dblclick', (e) => {
                        if (styleModeManager.isActive()) {
                            L.DomEvent.stopPropagation(e);
                            styleModeManager.selectLayer(this.layerId, this.layer);
                        }
                    });
                }
            }).addTo(map);

            // Ajustar vista al bounds de la capa
            map.fitBounds(this.layer.getBounds());

            // Agregar al control de capas
            const layerControl = mapManager.getLayerControl();
            if (layerControl) {
                layerControl.addOverlay(this.layer, `Municipios de ${stateName}`);
            }

            this.currentStateId = data.features[0]?.properties?.entidad;
            this.currentStateName = stateName;
            console.log(`Municipios de ${stateName} cargados exitosamente`);

        } catch (error) {
            console.error('Error al procesar datos de municipios:', error);
            this.clear();
        }
    }

    clear() {
        const map = mapManager.getMap();
        if (!map) return;

        if (this.layer) {
            map.removeLayer(this.layer);
            const layerControl = mapManager.getLayerControl();
            if (layerControl) {
                layerControl.removeLayer(this.layer);
            }
            this.layer = null;
            this.currentStateId = null;
            this.currentStateName = null;
        }
    }

    isActive() {
        return this.layer !== null;
    }

    getCurrentStateId() {
        return this.currentStateId;
    }

    getCurrentStateName() {
        return this.currentStateName;
    }

    getLayer() {
        return this.layer;
    }

    getLayerId() {
        return this.layerId;
    }
}

export const municipalityLayer = new MunicipalityLayer();