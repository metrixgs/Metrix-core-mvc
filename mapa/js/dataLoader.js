import { mapManager } from './core/MapManager.js';
import { PopupHandler } from './ui/PopupHandler.js';
import { heatmapCore } from './core/HeatmapCore.js';
import { hexagonCore } from './core/HexagonCore.js';
import { dataCore } from './core/DataCore.js';
import { ErrorHandler } from './core/ErrorHandler.js';
import { wfsLayerManager } from './wfs/WFSLayerManager.js';

class DataLoader {
    constructor() {
        this.shapedPointsLayer = null;
        this.clusterLayer = null;
        this.points = [];
        this.originalPoints = [];
        this.totalPoints = 0;
        this.visiblePointsCount = 0;
        this.setupToggles();
    }

    setupToggles() {
        const heatmapToggle = document.getElementById('heatmapToggle');
        const hexagonToggle = document.getElementById('hexagonToggle');

        if (heatmapToggle) {
            heatmapToggle.addEventListener('click', () => {
                const isHeatmapVisible = heatmapCore.toggle();
                const map = mapManager.getMap();

                if (isHeatmapVisible) {
                    hexagonCore.hide();
                    this.removeAllLayers();
                } else if (!hexagonCore.isVisible) {
                    this.applyZoomLayer(map.getZoom());
                }

                heatmapToggle.classList.toggle('active', isHeatmapVisible);
                hexagonToggle?.classList.remove('active');
            });
        }

        if (hexagonToggle) {
            hexagonToggle.addEventListener('click', () => {
                const isHexagonVisible = hexagonCore.toggle();
                const map = mapManager.getMap();

                if (isHexagonVisible) {
                    heatmapCore.hide();
                    this.removeAllLayers();
                } else if (!heatmapCore.isVisible) {
                    this.applyZoomLayer(map.getZoom());
                }

                hexagonToggle.classList.toggle('active', isHexagonVisible);
                heatmapToggle?.classList.remove('active');
            });
        }
    }

    async loadPoints() {
        try {
            console.log('Iniciando carga de datos...');
            const response = await fetch('https://lightsteelblue-spoonbill-227005.hostingersite.com/api/incidencias');

            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

            const result = await response.json();
            if (!result?.data || !Array.isArray(result.data)) throw new Error('Formato de respuesta inválido');

            const validPoints = result.data.filter(point => ErrorHandler.validatePoint(point));
            if (validPoints.length === 0) {
                console.warn('No se encontraron puntos válidos');
                return [];
            }

            console.log(`Datos recibidos: ${validPoints.length}`);
            this.originalPoints = validPoints;
            this.handlePoints(validPoints);

            heatmapCore.setData(validPoints);
            hexagonCore.setData(validPoints);
            wfsLayerManager.registerIncidentsLayer(this.clusterLayer);

            return validPoints;
        } catch (error) {
            console.error('Error al cargar datos:', error);
            this.handlePoints([]);
            throw error;
        }
    }

    handlePoints(points) {
    const map = mapManager.getMap();
    if (!map) {
        console.error('Mapa no inicializado');
        return;
    }

    // Remove existing layers
    if (this.clusterLayer && map.hasLayer(this.clusterLayer)) {
        map.removeLayer(this.clusterLayer);
    }
    if (this.shapedLayer && map.hasLayer(this.shapedLayer)) {
        map.removeLayer(this.shapedLayer);
    }

    // Create GeoJSON FeatureCollection
    const geojsonData = {
        type: 'FeatureCollection',
        features: points
            .filter(p => !isNaN(parseFloat(p.latitud)) && !isNaN(parseFloat(p.longitud)))
            .map(p => ({
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(p.longitud), parseFloat(p.latitud)]
                },
                properties: p
            }))
    };

    this.points = geojsonData.features;
    this.totalPoints = this.points.length;

    // ✅ Layer 1: Circle markers inside clusters
    const basePoints = L.geoJSON(geojsonData, {
        pointToLayer: (feature, latlng) => {
            const estado = feature.properties.estado?.toLowerCase() || 'abierto';
            const colorSla = feature.properties.color_sla || '#ef4444';
            return L.circleMarker(latlng, {
                radius: 8,
                fillColor: colorSla,
                color: '#ffffff',
                weight: 1,
                opacity: 1,
                fillOpacity: estado === 'abierto' ? 0.8 : 0.4
            });
        },
        onEachFeature: (feature, layer) => {
            const popupContent = PopupHandler.createPopupContent(feature.properties);
            layer.bindPopup(popupContent);
        }
    });

    this.clusterLayer = L.markerClusterGroup({
        disableClusteringAtZoom: 8,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: true,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 40,
        iconCreateFunction: (cluster) => {
            const count = cluster.getChildCount();
            let size = 'small';
            if (count > 50) size = 'large';
            else if (count > 20) size = 'medium';

            return L.divIcon({
                html: `<div><span>${count}</span></div>`,
                className: `marker-cluster marker-cluster-${size}`,
                iconSize: L.point(40, 40)
            });
        }
    });

    this.clusterLayer.addLayer(basePoints);

    // ✅ Layer 2: shaped markers at high zoom
    this.shapedLayer = L.geoJSON(geojsonData, {
        pointToLayer: (feature, latlng) => {
            return L.marker(latlng, {
                icon: getCustomMarker(feature)
            });
        },
        onEachFeature: (feature, layer) => {
            const popupContent = PopupHandler.createPopupContent(feature.properties);
            layer.bindPopup(popupContent);
        }
    });

    // Initial display
    const applyZoomLayer = () => {
        const zoom = map.getZoom();

        if (map.hasLayer(this.clusterLayer)) map.removeLayer(this.clusterLayer);
        if (map.hasLayer(this.shapedLayer)) map.removeLayer(this.shapedLayer);

        if (zoom >= 8) {
            map.addLayer(this.shapedLayer);
        } else {
            map.addLayer(this.clusterLayer);
        }

        this.updateVisiblePoints();
    };

    map.on('zoomend moveend', applyZoomLayer);
    applyZoomLayer(); // Run once immediately
    }


    createGeoJsonLayer(geojsonData, useShapedMarkers = true) {
        return L.geoJSON(geojsonData, {
            pointToLayer: (feature, latlng) => {
                return L.marker(latlng, {
                    icon: useShapedMarkers ? getCustomMarker(feature) : L.divIcon({ className: 'marker-default', iconSize: [8, 8] })
                });
            },
            onEachFeature: (feature, layer) => {
                const popupContent = PopupHandler.createPopupContent(feature.properties);
                layer.bindPopup(popupContent, {
                    maxWidth: 300,
                    maxHeight: 400,
                    autoPan: true,
                    closeButton: true,
                    autoPanPadding: [40, 40]
                });
            }
        });
    }

    applyZoomLayer(zoom) {
        const map = mapManager.getMap();
        if (!map || !this.shapedPointsLayer || !this.clusterLayer) return;

        this.removeAllLayers();

        if (zoom >= 13) {
            map.addLayer(this.shapedPointsLayer);
        } else {
            map.addLayer(this.clusterLayer);
        }
    }

    removeAllLayers() {
        const map = mapManager.getMap();
        if (!map) return;
        if (this.shapedPointsLayer && map.hasLayer(this.shapedPointsLayer)) map.removeLayer(this.shapedPointsLayer);
        if (this.clusterLayer && map.hasLayer(this.clusterLayer)) map.removeLayer(this.clusterLayer);
    }

    updateVisiblePoints() {
        const map = mapManager.getMap();
        if (!map || !this.points) return;

        const bounds = map.getBounds();
        this.visiblePointsCount = this.points.filter(f => {
            const coords = f.geometry.coordinates;
            return bounds.contains([coords[1], coords[0]]);
        }).length;

        this.updatePointsCounter();
    }

    updatePointsCounter() {
        const el = document.getElementById('pointsCounter');
        if (el) el.textContent = `${this.visiblePointsCount} / ${this.totalPoints}`;
    }

    filterPoints(geometry) {
        if (!geometry || !this.points) return [];

        const filtered = turf.pointsWithinPolygon({
            type: 'FeatureCollection',
            features: this.points
        }, geometry);

        this.visiblePointsCount = filtered.features.length;
        this.updatePointsCounter();

        return filtered.features;
    }

    reloadOriginalPoints() {
        if (this.originalPoints.length > 0) {
            this.handlePoints(this.originalPoints);
            return true;
        }
        return false;
    }
}

function getCustomMarker(feature) {
  const area = feature?.properties?.nombre_area || '';
  const prioridad = feature?.properties?.prioridad || '';

  const shape = getMarkerShape(area);
  const color = getMarkerColor(prioridad);

  return L.divIcon ({
    className: '',
    html: `<div class="custom-marker ${shape} ${color}"></div>`,
    iconSize: [30, 30],
    iconAnchor: [15, 15]
  });

}


function getMarkerShape(area) {
    if (!area || typeof area !== 'string') return 'circle';
    const clean = area.trim().toLowerCase();
    if (clean.includes('secretara­a de gobierno') || clean.includes('secretara­a de seguridad')|| clean.includes('limpia')) return 'triangle';
    if (clean.includes('comercial')) return 'square';
    if (clean.includes('secretaria de servicios municipales')) return 'circle';
    if (clean.includes('oficina de aguas')) return 'diamond';
    if (clean.includes('poda') || clean.includes('bacheo') ) return 'star';
    if (clean.includes('auto abandonado')) return 'hexagon';
    return 'circle';
}

function getMarkerColor(prioridad) {
    if (!prioridad || typeof prioridad !== 'string') return 'gray';
    switch (prioridad.trim().toLowerCase()) {
        case 'critico': return 'red';
        case 'alta': return 'red';
        case 'medio': return 'yellow';
        case 'media': return 'yellow';
        case 'bajo': return 'green';
        default: return 'gray';
    }
}


export const dataLoader = new DataLoader();
