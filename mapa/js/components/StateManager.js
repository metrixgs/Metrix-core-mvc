import { mapStateManager } from '../core/MapStateManager.js';
import { FilterBar } from './FilterBar.js';
import { discreteModal } from './DiscreteModal.js';

export class StateManager {
    constructor() {
        this.init();
    }

    init() {
        const footerSection = document.createElement('div');
        footerSection.className = 'footer-section';
        
        const button = document.createElement('button');
        button.className = 'state-toggle';
        button.innerHTML = `
            <i class="bi bi-share"></i>
            <span>Compartir</span>
        `;
        
        footerSection.appendChild(button);
        document.querySelector('.footer-content').appendChild(footerSection);

        this.setupEventListeners(button);
    }

    setupEventListeners(button) {
        button.addEventListener('click', async () => {
            await this.showStateDialog();
        });

        document.addEventListener('filterApplied', (e) => {
            if (e.detail && e.detail.filters) {
                mapStateManager.saveCurrentState(e.detail.filters);
            }
        });
    }

    async showStateDialog() {
        try {
            const states = await mapStateManager.getLocalStates();
            
            const modal = document.createElement('div');
            modal.className = 'modal-overlay active';
            modal.innerHTML = `
                <div class="modal-container">
                    <div class="modal-header">
                        <h2>Estados Guardados</h2>
                        <button class="modal-close">×</button>
                    </div>
                    <div class="modal-content">
                        <div class="p-4">
                            ${states.length === 0 ? `
                                <div class="text-center text-gray-500 py-4">
                                    No hay estados guardados
                                </div>
                            ` : `
                                <div class="states-list">
                                    ${states.map(state => `
                                        <div class="state-item">
                                            <div class="state-info">
                                                <div class="state-id">${state.sessionId}</div>
                                                <div class="state-time">${new Date(state.timestamp).toLocaleString()}</div>
                                                <div class="state-points">Puntos: ${state.points.length}</div>
                                            </div>
                                            <div class="state-actions">
                                                <button onclick="window.loadMapState('${state.sessionId}')" class="modal-btn-secondary">
                                                    Cargar
                                                </button>
                                                <button onclick="window.shareMapState('${state.sessionId}')" class="modal-btn-primary">
                                                    Compartir
                                                </button>
                                                <button onclick="window.view3DMapState('${state.sessionId}')" class="modal-btn-primary">
                                                    <i class="bi bi-badge-3d"></i>
                                                </button>
                                                <button onclick="window.viewRoutesState('${state.sessionId}')" class="modal-btn-primary">
                                                    <i class="bi bi-map"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            const closeBtn = modal.querySelector('.modal-close');
            closeBtn.addEventListener('click', () => modal.remove());

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.remove();
            });

            // Funciones globales para los botones
            window.loadMapState = async (sessionId) => {
                await mapStateManager.loadState(sessionId);
                modal.remove();
            };

            window.shareMapState = (sessionId) => {
                const shareUrl = `${window.location.origin}${window.location.pathname}?state=${sessionId}`;
                navigator.clipboard.writeText(shareUrl);
                alert('URL copiada al portapapeles');
            };

            window.view3DMapState = async (sessionId) => {
                const state = await mapStateManager.getStateById(sessionId);
                if (state && state.points) {
                    const geoJSON = {
                        type: 'FeatureCollection',
                        features: state.points.map(point => ({
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: [point.lon, point.lat]
                            },
                            properties: {
                                id: point.id
                            }
                        }))
                    };
                    
                    sessionStorage.setItem('metrix3DPoints', JSON.stringify(geoJSON));
                    const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '');
                    discreteModal.open(`${baseUrl}/maptiler3d.html`);
                    modal.remove();
                }
            };

            window.viewRoutesState = async (sessionId) => {
                const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '');
                discreteModal.open(`${baseUrl}/osm55/index.html?mapid=${sessionId}`);
                modal.remove();
            };

        } catch (error) {
            console.error('Error showing state dialog:', error);
            alert('Error al cargar los estados guardados');
        }
    }
}