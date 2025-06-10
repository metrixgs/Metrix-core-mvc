import { mapManager } from '../core/MapManager.js';
import { discreteModal } from './DiscreteModal.js';

export class StreetViewControl {
    constructor() {
        this.map = mapManager.getMap();
        this.active = false;
        this.streetViewService = new google.maps.StreetViewService();
        this.init();
    }

    init() {
        this.setupToggleButton();
        this.setupMapClickHandler();
    }

    setupToggleButton() {
        const toggleButton = document.getElementById('streetViewToggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                this.toggleStreetViewMode();
            });
        }
    }

    toggleStreetViewMode() {
        this.active = !this.active;
        const toggleButton = document.getElementById('streetViewToggle');
        
        if (this.active) {
            toggleButton.classList.add('active');
            this.map.getContainer().style.cursor = 'crosshair';
        } else {
            toggleButton.classList.remove('active');
            this.map.getContainer().style.cursor = '';
        }
    }

    setupMapClickHandler() {
        this.map.on('click', (e) => {
            if (!this.active) return;
            
            const latLng = e.latlng;
            this.checkStreetViewAvailability(latLng);
        });
    }

    checkStreetViewAvailability(latLng) {
        const location = new google.maps.LatLng(latLng.lat, latLng.lng);
        
        this.streetViewService.getPanorama({ location, radius: 50 }, (data, status) => {
            if (status === google.maps.StreetViewStatus.OK) {
                this.showStreetView(data.location.latLng);
                this.toggleStreetViewMode(); // Desactivar modo después de mostrar
            } else {
                this.showError('No hay Street View disponible en esta ubicación');
            }
        });
    }

    showStreetView(location) {
        const modalContent = `
            <div class="street-view-container" id="street-view">
                <div class="street-view-loading">
                    <i class="bi bi-arrow-clockwise"></i> Cargando Street View...
                </div>
            </div>
        `;

        discreteModal.open();
        discreteModal.setContent(modalContent);

        setTimeout(() => {
            const panorama = new google.maps.StreetViewPanorama(
                document.getElementById('street-view'),
                {
                    position: location,
                    pov: {
                        heading: 34,
                        pitch: 10
                    },
                    addressControl: true,
                    linksControl: true,
                    panControl: true,
                    enableCloseButton: true,
                    fullscreenControl: true
                }
            );
        }, 100);
    }

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'street-view-error';
        errorDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            <p>${message}</p>
        `;
        
        discreteModal.open();
        discreteModal.setContent(errorDiv.outerHTML);
        
        setTimeout(() => {
            discreteModal.close();
            this.toggleStreetViewMode(); // Desactivar modo después del error
        }, 3000);
    }
}