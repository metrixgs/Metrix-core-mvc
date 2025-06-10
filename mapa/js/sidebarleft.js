import { map } from './map.js';
import { ModalManager } from './modals/modalManager.js';
import { LayerTree } from './components/LayerTree.js';
import { PointsList } from './components/PointsList.js';

// Sidebar functionality
const sidebar = document.querySelector('.sidebar-left');
const sidebarToggle = document.querySelector('.sidebar-toggle');
const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');
const mapContainer = document.getElementById('map');

let resizeTimeout;

// Toggle sidebar with proper map resize handling
sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    
    // Actualizar margen del mapa
    const isActive = sidebar.classList.contains('active');
    mapContainer.style.marginLeft = isActive ? '300px' : '0';
    
    // Cancelar timeout anterior si existe
    clearTimeout(resizeTimeout);
    
    // Esperar a que termine la transición CSS antes de actualizar el mapa
    resizeTimeout = setTimeout(() => {
        map.invalidateSize({
            animate: true,
            pan: false,
            debounceMoveend: true
        });
        
        // Forzar redibujado de capas base
        map.eachLayer((layer) => {
            if (layer instanceof L.TileLayer) {
                layer.redraw();
            }
        });
    }, 300); // Mismo tiempo que la transición CSS
});

// Tab functionality
tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        
        button.classList.add('active');
        const tabId = button.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

// Initialize components
document.addEventListener('DOMContentLoaded', () => {
    new LayerTree();
    new PointsList();
});