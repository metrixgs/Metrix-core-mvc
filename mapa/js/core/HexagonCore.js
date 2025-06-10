class HexagonCore {
    constructor() {
        this.map = null;
        this.hexLayer = null;
        this.points = [];
        this.isVisible = false;
        this.resolution = 7;
        this.hexagons = new Map();
        this.colorScale = [
            '#ff0000',  // Rojo intenso para alta densidad
            '#ff3300',
            '#ff6600',
            '#ff9900',
            '#ffcc00',
            '#ffff00'   // Amarillo para baja densidad
        ];
        this.zoomResolutions = {
            0: 3,  // Zoom muy lejano
            4: 4,  // Zoom lejano
            6: 5,  // Zoom medio-lejano
            8: 6,  // Zoom medio
            10: 7, // Zoom medio-cercano
            12: 8, // Zoom cercano
            14: 9  // Zoom muy cercano
        };
    }

    setMap(map) {
        this.map = map;
        if (map) {
            map.on('zoomend', () => this.handleZoomChange());
        }
    }

    handleZoomChange() {
        if (!this.isVisible || !this.map) return;

        const currentZoom = this.map.getZoom();
        const newResolution = this.getResolutionForZoom(currentZoom);
        
        if (newResolution !== this.resolution) {
            this.resolution = newResolution;
            this.processHexagons();
            this.updateLayer();
        }
    }

    getResolutionForZoom(zoom) {
        let resolution = 7; // valor por defecto
        
        // Encontrar la resolución apropiada para el nivel de zoom actual
        for (const [zoomLevel, res] of Object.entries(this.zoomResolutions)) {
            if (zoom >= parseInt(zoomLevel)) {
                resolution = res;
            } else {
                break;
            }
        }
        
        return resolution;
    }

    setData(points) {
        this.points = points.filter(point => {
            const lat = parseFloat(point.latitud);
            const lng = parseFloat(point.longitud);
            return !isNaN(lat) && !isNaN(lng);
        });
        
        if (this.isVisible) {
            this.processHexagons();
            this.updateLayer();
        }
    }

    processHexagons() {
        this.hexagons.clear();
        
        this.points.forEach(point => {
            const lat = parseFloat(point.latitud);
            const lng = parseFloat(point.longitud);
            const hexId = window.h3.latLngToCell(lat, lng, this.resolution);
            
            if (!this.hexagons.has(hexId)) {
                this.hexagons.set(hexId, { 
                    count: 0, 
                    points: [],
                    center: window.h3.cellToLatLng(hexId)
                });
            }
            
            const hexInfo = this.hexagons.get(hexId);
            hexInfo.count++;
            hexInfo.points.push(point);
        });
    }

    getHexagonStyle(count) {
        const maxCount = Math.max(...Array.from(this.hexagons.values()).map(h => h.count));
        const normalizedCount = count / maxCount;
        const colorIndex = Math.floor(normalizedCount * (this.colorScale.length - 1));
        const color = this.colorScale[colorIndex];
        
        return {
            fillColor: color,
            fillOpacity: 0.7,
            color: '#ffffff',
            weight: 1,
            opacity: 0.8
        };
    }

    updateLayer() {
        if (!this.map || !this.isVisible) return;

        if (this.hexLayer) {
            this.map.removeLayer(this.hexLayer);
        }

        const hexFeatures = [];
        
        this.hexagons.forEach((info, hexId) => {
            const coordinates = window.h3.cellToBoundary(hexId, true);
            const feature = {
                type: 'Feature',
                properties: {
                    hexId,
                    count: info.count,
                    points: info.points,
                    center: info.center
                },
                geometry: {
                    type: 'Polygon',
                    coordinates: [coordinates]
                }
            };
            hexFeatures.push(feature);
        });

        this.hexLayer = L.geoJSON({
            type: 'FeatureCollection',
            features: hexFeatures
        }, {
            style: (feature) => this.getHexagonStyle(feature.properties.count),
            onEachFeature: (feature, layer) => {
                const count = feature.properties.count;
                layer.bindPopup(this.createPopupContent(feature.properties));

                layer.on('mouseover', () => {
                    layer.setStyle({
                        fillOpacity: 0.9,
                        weight: 2,
                        opacity: 1
                    });
                });

                layer.on('mouseout', () => {
                    layer.setStyle(this.getHexagonStyle(count));
                });
            }
        }).addTo(this.map);
    }

    createPopupContent(properties) {
        return `
            <div class="p-3">
                <h3 class="text-lg font-semibold mb-2">Densidad de Incidencias</h3>
                <p class="text-sm font-medium">Total: ${properties.count} incidencia${properties.count !== 1 ? 's' : ''}</p>
                <p class="text-xs text-gray-500 mt-1">Resolución H3: ${this.resolution}</p>
            </div>
        `;
    }

    show() {
        if (!this.map || this.isVisible) return;
        
        this.isVisible = true;
        this.resolution = this.getResolutionForZoom(this.map.getZoom());
        this.processHexagons();
        this.updateLayer();
    }

    hide() {
        if (this.hexLayer && this.map) {
            this.map.removeLayer(this.hexLayer);
            this.hexLayer = null;
        }
        this.isVisible = false;
    }

    toggle() {
        if (this.isVisible) {
            this.hide();
        } else {
            this.show();
        }
        return this.isVisible;
    }
}

export const hexagonCore = new HexagonCore();