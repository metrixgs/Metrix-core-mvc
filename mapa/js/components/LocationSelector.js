import { mapManager } from '../core/MapManager.js';
import { locationHighlight } from './LocationHighlight.js';
import { municipalityLayer } from '../wfs/MunicipalityLayer.js';
import { manzanasLayer } from '../wfs/ManzanasLayer.js';
import { manzanasConfig } from '../wfs/config/manzanasConfig.js';
import { spatialFilter } from '../core/SpatialFilter.js';

export class LocationSelector {
    constructor() {
        this.states = [];
        this.municipalities = [];
        this.panel = null;
        this.init();
    }

    async init() {
        this.createLocationButton();
        this.createLocationPanel();
    }

    createLocationButton() {
        const button = document.createElement('button');
        button.id = 'locationSelectorToggle';
        button.className = 'control-btn';
        button.title = 'Selector de Ubicación';
        button.innerHTML = '<i class="bi bi-geo-alt"></i>';

        const filterToggle = document.getElementById('filterToggle');
        if (filterToggle && filterToggle.parentNode) {
            filterToggle.parentNode.insertBefore(button, filterToggle.nextSibling);
        }

        button.addEventListener('click', () => this.togglePanel());
    }

    createLocationPanel() {
        this.panel = document.createElement('div');
        this.panel.className = 'dashboard-container location-selector';
        this.panel.innerHTML = `
            <div class="dashboard-header">
                <h3>Ubicación Territorial</h3>
                <button class="control-btn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="dashboard-content">
                <div class="select-section">
                    <label class="select-label">Estado</label>
                    <div class="select-wrapper">
                        <select id="stateSelect" class="modern-select">
                            <option value="">Seleccione un estado</option>
                        </select>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
                
                <div class="select-section">
                    <label class="select-label">Municipio</label>
                    <div class="select-wrapper">
                        <select id="municipalitySelect" class="modern-select" disabled>
                            <option value="">Seleccione un municipio</option>
                        </select>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(this.panel);
        
        const closeBtn = this.panel.querySelector('.control-btn');
        closeBtn.addEventListener('click', () => this.hidePanel());
    }

    togglePanel() {
        const button = document.getElementById('locationSelectorToggle');
        const map = mapManager.getMap();
        
        if (this.panel.classList.contains('active')) {
            this.hidePanel();
        } else {
            this.panel.classList.add('active');
            button.classList.add('active');
            this.loadStates();
            this.setupEventListeners();
            
            setTimeout(() => {
                if (map) {
                    map.invalidateSize({
                        animate: true,
                        pan: false,
                        debounceMoveend: true
                    });
                }
            }, 300);
        }
    }

    hidePanel() {
        const button = document.getElementById('locationSelectorToggle');
        const map = mapManager.getMap();
        
        this.panel.classList.remove('active');
        button.classList.remove('active');
        
        setTimeout(() => {
            if (map) {
                map.invalidateSize({
                    animate: true,
                    pan: false,
                    debounceMoveend: true
                });
            }
        }, 300);
    }

    loadStates() {
        const callbackName = `statesCallback_${Date.now()}`;
        
        window[callbackName] = (data) => {
            this.states = data;
            const stateSelect = document.getElementById('stateSelect');
            if (!stateSelect) return;

            while (stateSelect.options.length > 1) {
                stateSelect.remove(1);
            }

            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.entidad;
                option.textContent = state.nombre;
                stateSelect.appendChild(option);
            });
        };

        const script = document.createElement('script');
        script.src = `https://espacialhn.com/slim4/api/api/sinit/listestados/?callback=${callbackName}`;
        script.onerror = () => {
            console.error('Error loading states');
            delete window[callbackName];
        };
        document.body.appendChild(script);
    }

    async loadMunicipalities(stateId) {
        return new Promise((resolve, reject) => {
            const callbackName = `municipalitiesCallback_${stateId}_${Date.now()}`;
            
            window[callbackName] = (data) => {
                try {
                    this.municipalities = data;
                    const municipalitySelect = document.getElementById('municipalitySelect');
                    if (!municipalitySelect) return reject('Select not found');

                    municipalitySelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                    data.forEach(municipality => {
                        const option = document.createElement('option');
                        option.value = municipality.municipio;
                        option.textContent = municipality.nombre;
                        municipalitySelect.appendChild(option);
                    });

                    municipalitySelect.disabled = false;
                    resolve(data);
                } catch (error) {
                    reject(error);
                }
            };

            const script = document.createElement('script');
            script.src = `https://espacialhn.com/slim4/api/api/sinit/listmunicipios/${stateId}?callback=${callbackName}`;
            script.onerror = () => {
                console.error('Error loading municipalities');
                delete window[callbackName];
                reject('Failed to load municipalities');
            };
            document.body.appendChild(script);
        });
    }

    setupEventListeners() {
        const stateSelect = document.getElementById('stateSelect');
        const municipalitySelect = document.getElementById('municipalitySelect');

        if (!stateSelect || !municipalitySelect) return;

        stateSelect.addEventListener('change', async (e) => {
            const stateId = e.target.value;
            const state = this.states.find(s => s.entidad === stateId);
            
            municipalitySelect.value = '';
            municipalitySelect.disabled = true;
            manzanasLayer.clear();

            if (!stateId || !state) {
                municipalitySelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                municipalityLayer.clear();
                spatialFilter.clearFilter();
                return;
            }

            try {
                await locationHighlight.highlightState(stateId);
                await municipalityLayer.loadMunicipalities(stateId, state.nombre);
                await this.loadMunicipalities(stateId);
                spatialFilter.filterByState(stateId);

                if (state.centroid) {
                    const map = mapManager.getMap();
                    map.flyTo([state.centroid.lat, state.centroid.lon], 8);
                }
            } catch (error) {
                console.error('Error loading state data:', error);
                spatialFilter.clearFilter();
            }
        });

        municipalitySelect.addEventListener('change', async (e) => {
            const municipalityId = e.target.value;
            const municipality = this.municipalities.find(m => m.municipio === municipalityId);
            const stateId = stateSelect.value;

            if (!municipalityId || !municipality) {
                manzanasLayer.clear();
                return;
            }

            locationHighlight.highlightMunicipality(municipalityId);

            if (municipality.centroid) {
                const map = mapManager.getMap();
                map.flyTo([municipality.centroid.lat, municipality.centroid.lon], 12);
            }

            // Load manzanas only for Querétaro state
            if (stateId === manzanasConfig.enabledStateId) {
                try {
                    await manzanasLayer.loadManzanas(municipality);
                } catch (error) {
                    console.error('Error loading manzanas:', error);
                }
            } else {
                manzanasLayer.clear();
            }
        });
    }
}