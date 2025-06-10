import { dataLoader } from '../dataLoader.js';
import { wfsLayerManager } from '../wfs/WFSLayerManager.js';

class SpatialFilter {
    constructor() {
        this.currentFilter = null;
    }

    filterByState(stateId) {
        // Primero recargar todos los puntos originales
        dataLoader.reloadOriginalPoints();

        // Get state polygon
        const statesLayer = wfsLayerManager.layers.get('limite_mxestados')?.layer;
        if (!statesLayer) return;

        const selectedState = statesLayer.getLayers().find(layer => 
            layer.feature.properties.entidad === stateId
        );

        if (!selectedState) return;

        // Create geometry for filtering
        const stateGeometry = selectedState.toGeoJSON().geometry;

        // Filter points
        const filteredPoints = turf.pointsWithinPolygon(
            {
                type: 'FeatureCollection',
                features: dataLoader.points
            },
            stateGeometry
        );

        // Update visible points
        if (filteredPoints.features.length > 0) {
            dataLoader.handlePoints(filteredPoints.features.map(f => f.properties));
            
            // Save current filter
            this.currentFilter = {
                type: 'state',
                id: stateId
            };
        } else {
            console.log('No se encontraron puntos en este estado');
            dataLoader.handlePoints([]); // Mostrar mapa vacío
        }
    }

    clearFilter() {
        if (this.currentFilter) {
            dataLoader.reloadOriginalPoints();
            this.currentFilter = null;
        }
    }

    getCurrentFilter() {
        return this.currentFilter;
    }
}

export const spatialFilter = new SpatialFilter();