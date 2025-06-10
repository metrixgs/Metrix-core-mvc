import { mapManager } from '../core/MapManager.js';
import { wfsLayerManager } from '../wfs/WFSLayerManager.js';
import { municipalityLayer } from '../wfs/MunicipalityLayer.js';

class LocationHighlight {
    constructor() {
        this.map = null;
        this.highlightLayer = null;
        this.animationInterval = null;
    }

    initialize(map) {
        this.map = map;
    }

    async highlightState(stateId) {
        if (!this.map) return;
        
        this.clearHighlight();

        const statesLayer = wfsLayerManager.layers.get('limite_mxestados')?.layer;
        if (!statesLayer) return;

        const selectedState = statesLayer.getLayers().find(layer => 
            layer.feature.properties.entidad === stateId
        );

        if (selectedState) {
            this.highlightLayer = L.geoJSON(selectedState.toGeoJSON(), {
                style: {
                    color: '#00A650',
                    weight: 3,
                    opacity: 1,
                    fillColor: '#00A650',
                    fillOpacity: 0.3
                }
            }).addTo(this.map);

            this.startBlinking();
        }
    }

    highlightMunicipality(municipalityId) {
        if (!this.map) return;

        this.clearHighlight();

        // Obtener la capa de municipios del MunicipalityLayer
        const municipalityFeature = municipalityLayer.findFeatureInTemporaryLayer(municipalityId);
        
        if (municipalityFeature) {
            this.highlightLayer = L.geoJSON(municipalityFeature.toGeoJSON(), {
                style: {
                    color: '#00A650',
                    weight: 3,
                    opacity: 1,
                    fillColor: '#00A650',
                    fillOpacity: 0.3
                }
            }).addTo(this.map);

            this.startBlinking();
            return true;
        }
        return false;
    }

    startBlinking() {
        if (this.animationInterval) {
            clearInterval(this.animationInterval);
        }

        let visible = true;
        let count = 0;

        this.animationInterval = setInterval(() => {
            if (!this.highlightLayer) {
                clearInterval(this.animationInterval);
                return;
            }

            this.highlightLayer.setStyle({
                opacity: visible ? 1 : 0,
                fillOpacity: visible ? 0.3 : 0
            });

            visible = !visible;
            count++;

            if (count >= 6) {
                clearInterval(this.animationInterval);
                this.clearHighlight();
            }
        }, 300);
    }

    clearHighlight() {
        if (this.animationInterval) {
            clearInterval(this.animationInterval);
            this.animationInterval = null;
        }

        if (this.highlightLayer && this.map) {
            this.map.removeLayer(this.highlightLayer);
            this.highlightLayer = null;
        }
    }
}

export const locationHighlight = new LocationHighlight();