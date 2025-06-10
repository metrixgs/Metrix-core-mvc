import { mapManager } from './core/MapManager.js';
import { heatmapCore } from './core/HeatmapCore.js';
import { hexagonCore } from './core/HexagonCore.js';
import { dataLoader } from './dataLoader.js';
import { initMapBehaviors } from './behaviors.js';
import { initWFSBehavior } from './behaviors/wfsBehavior.js';
import { themeManager } from './core/ThemeManager.js';
import { FilterBar } from './components/FilterBar.js';
import { SymbologyTable } from './components/SymbologyTable.js';
import { MapExport } from './components/MapExport.js';
import { PointsExport } from './components/PointsExport.js';
import { MapTiler3DButton } from './components/MapTiler3DButton.js';
import { DashboardPanel } from './components/DashboardPanel.js';
import { StateManager } from './components/StateManager.js';
import { StyleEditor } from './components/StyleEditor.js';
import { mapStateManager } from './core/MapStateManager.js';
import { stylePersistenceManager } from './core/StylePersistenceManager.js';
import { trackingManager } from './core/TrackingManager.js';
import { LocationSelector } from './components/LocationSelector.js';

// Make dataLoader available globally for FilterBar
window.dataLoader = dataLoader;

// Map instance to export
let mapInstance;

async function initializeApp() {
    try {
        // 1. Initialize map with base configuration
        mapInstance = mapManager.initialize('map', {
            zoomControl: false,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topright'
            }
        });

        // 2. Set map reference in cores
        heatmapCore.map = mapInstance;
        hexagonCore.setMap(mapInstance);

        // 3. Initialize map behaviors first
        initMapBehaviors(mapInstance);
        initWFSBehavior(mapInstance);

        // 4. Load styles from storage
        await stylePersistenceManager.loadFromStorage();

        // 5. Check for state parameter in URL and load if present
        const urlParams = new URLSearchParams(window.location.search);
        const stateId = urlParams.get('state');
        if (stateId) {
            await mapStateManager.loadState(stateId);
        } else {
            // If no state, load initial data
            await dataLoader.loadPoints();
        }

        // 6. Initialize UI components after data is loaded
        const filterBar = new FilterBar();
        
        // 7. Initialize LocationSelector after FilterBar
        setTimeout(() => {
            const locationSelector = new LocationSelector();
        }, 500);

        const symbologyTable = new SymbologyTable();
        const mapExport = new MapExport();
        const pointsExport = new PointsExport();
        const maptiler3DButton = new MapTiler3DButton();
        const dashboardPanel = new DashboardPanel();
        const stateManager = new StateManager();
        const styleEditor = new StyleEditor();

        // 8. Add home button
        addHomeButton();

        // 9. Setup coordinates functionality
        setupCoordinatesHandling();

        // 10. Setup map event listeners
        setupMapEventListeners(mapInstance);

        console.log('Inicialización completa');
    } catch (error) {
        console.error('Error durante la inicialización:', error);
        trackingManager.trackEvent('ERROR', {
            context: 'initialization',
            error: error.message
        });
    }
}

function addHomeButton() {
    const homeButton = document.createElement('button');
    homeButton.className = 'control-btn';
    homeButton.title = 'Inicio';
    homeButton.innerHTML = '<i class="bi bi-house"></i>';
    
    homeButton.addEventListener('click', () => {
        // Remove state parameter and reload
        const url = new URL(window.location);
        url.searchParams.delete('state');
        window.history.pushState({}, '', url);
        
        // Clear current state and reload points
        mapStateManager.clearCurrentState();
    });

    // Insert at the beginning of the toolbar
    const toolbar = document.querySelector('.nav-content .flex.items-center.gap-4');
    if (toolbar) {
        toolbar.insertBefore(homeButton, toolbar.firstChild);
    }
}

function setupCoordinatesHandling() {
    const coordsSection = document.querySelector('.footer-section:nth-child(2)');
    const coordInput = document.querySelector('.coord-input');

    coordsSection.addEventListener('click', (e) => {
        if (!e.target.closest('.coord-input')) {
            coordInput.classList.toggle('active');
        }
    });

    document.getElementById('goToCoord').addEventListener('click', () => {
        const input = document.getElementById('coordInput').value;
        const coords = input.split(',').map(coord => parseFloat(coord.trim()));
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            mapManager.getMap().setView(coords, mapManager.getMap().getZoom());
            coordInput.classList.remove('active');
            
            trackingManager.trackEvent('NAVIGATION', {
                action: 'GOTO_COORDS',
                coordinates: coords
            });
        }
    });

    document.getElementById('coordInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            document.getElementById('goToCoord').click();
        }
    });
}

function setupMapEventListeners(map) {
    function updateInfo(e) {
        const zoom = map.getZoom();
        document.getElementById('currentZoom').textContent = zoom;
        
        const scale = Math.round(zoom * 100000);
        document.getElementById('mapScale').textContent = `1:${scale}`;
        
        if (e && e.latlng) {
            const { lat, lng } = e.latlng;
            document.getElementById('currentCoords').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }

        // Switch base layer based on zoom level
        if (zoom >= 10) {
            mapManager.switchToSatellite();
        } else {
            mapManager.switchToDefault();
        }
    }

    map.on('zoomend', (e) => {
        updateInfo(e);
        trackingManager.trackEvent('NAVIGATION', {
            action: 'ZOOM',
            level: map.getZoom()
        });
    });

    map.on('mousemove', updateInfo);

    map.on('moveend', (e) => {
        updateInfo(e);
        const center = map.getCenter();
        trackingManager.trackEvent('NAVIGATION', {
            action: 'PAN',
            center: [center.lat, center.lng]
        });
    });
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', initializeApp);

// Export the map instance
export { mapInstance as map };