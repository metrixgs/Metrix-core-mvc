import { mapManager } from './core/MapManager.js';
import { PopupHandler } from './ui/PopupHandler.js';
import { heatmapCore } from './core/HeatmapCore.js';
import { hexagonCore } from './core/HexagonCore.js';
import { dataCore } from './core/DataCore.js';
import { ErrorHandler } from './core/ErrorHandler.js';
import { wfsLayerManager } from './wfs/WFSLayerManager.js';

class DataLoader {
    constructor() {
        this.pointsLayer = null;
        this.clusterLayer = null;
        this.points = [];
        this.totalPoints = 0;
        this.visiblePointsCount = 0;
        this.setupToggles();
    }

    setupToggles() {
        const heatmapToggle = document.getElementById('heatmapToggle');
        if (heatmapToggle) {
            heatmapToggle.addEventListener('click', () => {
                const isHeatmapVisible = heatmapCore.toggle();
                const map = mapManager.getMap();
                
                if (isHeatmapVisible) {
                    hexagonCore.hide();
                    if (this.clusterLayer) {
                        map.removeLayer(this.clusterLayer);
                    }
                } else if (!hexagonCore.isVisible) {
                    if (this.clusterLayer) {
                        map.addLayer(this.clusterLayer);
                    }
                }

                heatmapToggle.classList.toggle('active', isHeatmapVisible);
                document.getElementById('hexagonToggle')?.classList.remove('active');
            });
        }

        const hexagonToggle = document.getElementById('hexagonToggle');
        if (hexagonToggle) {
            hexagonToggle.addEventListener('click', () => {
                const isHexagonVisible = hexagonCore.toggle();
                const map = mapManager.getMap();
                
                if (isHexagonVisible) {
                    heatmapCore.hide();
                    if (this.clusterLayer) {
                        map.removeLayer(this.clusterLayer);
                    }
                } else if (!heatmapCore.isVisible) {
                    if (this.clusterLayer) {
                        map.addLayer(this.clusterLayer);
                    }
                }

                hexagonToggle.classList.toggle('active', isHexagonVisible);
                document.getElementById('heatmapToggle')?.classList.remove('active');
            });
        }
    }

    async loadPoints() {
        try {
            console.log('Iniciando carga de datos...');
            const response = await fetch('https://api.metrixmobile.xyz/api/incidencias');
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();
            
            if (!result || !result.data || !Array.isArray(result.data)) {
                throw new Error('Formato de respuesta inválido');
            }

            const validPoints = result.data.filter(point => ErrorHandler.validatePoint(point));
            
            if (validPoints.length === 0) {
                console.warn('No se encontraron puntos válidos en la respuesta');
                return [];
            }

            console.log(`Datos recibidos: ${validPoints.length} puntos válidos`);
            this.handlePoints(validPoints);
            
            // Actualizar visualizaciones
            heatmapCore.setData(validPoints);
            hexagonCore.setData(validPoints);

            // Registrar la capa con el WFS Layer Manager
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

        // Limpiar capas existentes
        if (this.pointsLayer) {
            map.removeLayer(this.pointsLayer);
        }
        if (this.clusterLayer) {
            map.removeLayer(this.clusterLayer);
        }

        // Convertir puntos a GeoJSON
        const geojsonData = {
            type: 'FeatureCollection',
            features: points
                .filter(point => {
                    const lat = parseFloat(point.latitud);
                    const lng = parseFloat(point.longitud);
                    return !isNaN(lat) && !isNaN(lng);
                })
                .map(point => ({
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [
                            parseFloat(point.longitud),
                            parseFloat(point.latitud)
                        ]
                    },
                    properties: point
                }))
        };

        // Actualizar contadores
        this.points = geojsonData.features;
        this.totalPoints = this.points.length;
        this.updateVisiblePoints();

        // Crear capa de clusters
        this.clusterLayer = L.markerClusterGroup({
            disableClusteringAtZoom: 13,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: true,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 50,
            iconCreateFunction: (cluster) => {
                const count = cluster.getChildCount();
                let size = 'small';
                
                if (count > 50) {
                    size = 'large';
                } else if (count > 20) {
                    size = 'medium';
                }
                
                return L.divIcon({
                    html: `<div><span>${count}</span></div>`,
                    className: `marker-cluster marker-cluster-${size}`,
                    iconSize: L.point(40, 40)
                });
            }
        });

        // Crear capa de puntos
        this.pointsLayer = L.geoJSON(geojsonData, {
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
                layer.bindPopup(popupContent, {
                    maxWidth: 300,
                    maxHeight: 400,
                    autoPan: true,
                    closeButton: true,
                    autoPanPadding: [40, 40]
                });
            }
        });

        // Agregar capas al mapa
        this.clusterLayer.addLayer(this.pointsLayer);
        map.addLayer(this.clusterLayer);
        
        // Configurar eventos para actualizar contador
        map.on('moveend', () => this.updateVisiblePoints());
        map.on('zoomend', () => this.updateVisiblePoints());
        
        console.log('Capas de puntos agregadas al mapa');
    }

    updateVisiblePoints() {
        const map = mapManager.getMap();
        if (!map || !this.points) return;

        const bounds = map.getBounds();
        this.visiblePointsCount = this.points.filter(feature => {
            const coords = feature.geometry.coordinates;
            return bounds.contains([coords[1], coords[0]]);
        }).length;

        this.updatePointsCounter();
    }

    updatePointsCounter() {
        const counterElement = document.getElementById('pointsCounter');
        if (counterElement) {
            counterElement.textContent = `${this.visiblePointsCount} / ${this.totalPoints}`;
        }
    }

    filterPoints(geometry) {
        if (!geometry || !this.points) return;

        const filteredPoints = turf.pointsWithinPolygon({
            type: 'FeatureCollection',
            features: this.points
        }, geometry);

        this.visiblePointsCount = filteredPoints.features.length;
        this.updatePointsCounter();

        return filteredPoints.features;
    }
}

export const dataLoader = new DataLoader();

// Estilos para clusters
const style = document.createElement('style');
style.textContent = `
    .marker-cluster {
        background-clip: padding-box;
        border-radius: 20px;
        background-color: rgba(239, 68, 68, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        border: 2px solid white;
    }

    .marker-cluster div {
        width: 30px;
        height: 30px;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        border-radius: 15px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(239, 68, 68, 0.8);
    }

    .marker-cluster-small {
        background-color: rgba(239, 68, 68, 0.6);
    }

    .marker-cluster-medium {
        background-color: rgba(239, 68, 68, 0.7);
    }

    .marker-cluster-large {
        background-color: rgba(239, 68, 68, 0.8);
    }

    .control-btn.active {
        background-color: var(--primary-color);
        color: white;
    }
`;
document.head.appendChild(style);