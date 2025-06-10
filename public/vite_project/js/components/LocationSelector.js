import { mapManager } from '../core/MapManager.js';

export class LocationSelector {
    constructor() {
        this.states = [];
        this.municipalities = [];
        this.currentState = null;
        this.init();
    }

    async init() {
        await this.createLocationSelectors();
        this.loadStates();
        this.setupEventListeners();
    }

    async createLocationSelectors() {
        const tab2Content = document.getElementById('tab2');
        if (!tab2Content) return;

        const locationContainer = document.createElement('div');
        locationContainer.className = 'location-container';
        locationContainer.innerHTML = `
            <h3 class="sidebar-section-title">Ubicación</h3>
            <div class="location-section">
                <label class="location-label">Estado</label>
                <select id="stateSelect" class="location-select">
                    <option value="">Seleccione un estado</option>
                </select>
            </div>
            <div class="location-section">
                <label class="location-label">Municipio</label>
                <select id="municipalitySelect" class="location-select" disabled>
                    <option value="">Seleccione un municipio</option>
                </select>
            </div>
        `;

        // Insertar al principio del contenido
        tab2Content.insertBefore(locationContainer, tab2Content.firstChild);
    }

    loadStates() {
        const callbackName = `statesCallback_${Date.now()}`;
        
        window[callbackName] = (data) => {
            this.states = data;
            const stateSelect = document.getElementById('stateSelect');
            if (!stateSelect) return;

            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.entidad;
                option.textContent = state.nombre;
                stateSelect.appendChild(option);
            });

            delete window[callbackName];
        };

        const script = document.createElement('script');
        script.src = `https://espacialhn.com/slim4/api/api/sinit/listestados/?callback=${callbackName}`;
        script.onerror = () => {
            console.error('Error loading states');
            delete window[callbackName];
        };
        document.body.appendChild(script);
    }

    loadMunicipalities(stateId) {
        const callbackName = `municipalitiesCallback_${stateId}_${Date.now()}`;
        
        window[callbackName] = (data) => {
            this.municipalities = data;
            const municipalitySelect = document.getElementById('municipalitySelect');
            if (!municipalitySelect) return;

            municipalitySelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            data.forEach(municipality => {
                const option = document.createElement('option');
                option.value = municipality.municipio;
                option.textContent = municipality.nombre;
                municipalitySelect.appendChild(option);
            });

            municipalitySelect.disabled = false;
            delete window[callbackName];
        };

        const script = document.createElement('script');
        script.src = `https://espacialhn.com/slim4/api/api/sinit/listmunicipios/${stateId}?callback=${callbackName}`;
        script.onerror = () => {
            console.error('Error loading municipalities');
            delete window[callbackName];
        };
        document.body.appendChild(script);
    }

    setupEventListeners() {
        const stateSelect = document.getElementById('stateSelect');
        const municipalitySelect = document.getElementById('municipalitySelect');

        if (!stateSelect || !municipalitySelect) return;

        stateSelect.addEventListener('change', (e) => {
            const stateId = e.target.value;
            if (!stateId) {
                municipalitySelect.disabled = true;
                municipalitySelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                return;
            }

            const state = this.states.find(s => s.entidad === stateId);
            if (state && state.centroid) {
                const map = mapManager.getMap();
                map.flyTo([state.centroid.lat, state.centroid.lon], 8, {
                    duration: 1.5
                });
            }

            this.loadMunicipalities(stateId);
        });

        municipalitySelect.addEventListener('change', (e) => {
            const municipalityId = e.target.value;
            if (!municipalityId) return;

            const municipality = this.municipalities.find(m => m.municipio === municipalityId);
            if (municipality && municipality.centroid) {
                const map = mapManager.getMap();
                map.flyTo([municipality.centroid.lat, municipality.centroid.lon], 12, {
                    duration: 1.5
                });
            }
        });
    }
}