import { mapManager } from '../core/MapManager.js';

export class FilterBar {
    constructor() {
        this.filters = {
            prioridad: '',
            estado: '',
            fechaInicio: '',
            fechaFin: '',
            keyword: ''
        };
        this.searchFields = [
            'identificador',
            'titulo',
            'descripcion',
            'direccion_completa'
        ];
        this.activeFiltersCount = 0;
        this.filterBar = null;
        this.init();
    }

    init() {
        const existingToggle = document.getElementById('filterToggle');
        if (!existingToggle) {
            this.createFilterToggle();
        }
        this.createFilterBar();
        this.setupEventListeners();
    }

    createFilterToggle() {
        const filterToggle = document.createElement('button');
        filterToggle.id = 'filterToggle';
        filterToggle.className = 'control-btn';
        filterToggle.title = 'Filtrar Puntos';
        filterToggle.innerHTML = `
            <i class="bi bi-funnel"></i>
            <span class="filter-badge">0</span>
        `;

        const controlsContainer = document.querySelector('.nav-content .flex.items-center.gap-4');
        if (controlsContainer) {
            controlsContainer.insertBefore(filterToggle, controlsContainer.firstChild);
        }
    }

    createFilterBar() {
        const existingBar = document.querySelector('.filter-bar');
        if (existingBar) {
            existingBar.remove();
        }

        this.filterBar = document.createElement('div');
        this.filterBar.className = 'filter-bar';
        this.filterBar.innerHTML = `
            <div class="filter-container">
                <div class="filter-sidebar">
                    <div class="filter-header">
                        <div class="filter-header-content">
                            <h3>Filtros</h3>
                            <span class="filter-applied">0 aplicados</span>
                        </div>
                        <button class="filter-close">×</button>
                    </div>
                    <div class="priority-buttons">
                        <button class="priority-button" data-priority="Alta">Alta</button>
                        <button class="priority-button" data-priority="Media">Media</button>
                        <button class="priority-button" data-priority="Baja">Baja</button>
                    </div>
                </div>
                <div class="filter-main">
                    <div class="filter-content">
                        <div class="filter-section">
                            <label class="filter-label">Buscar por palabra clave</label>
                            <input type="text" class="filter-input" id="keywordFilter" 
                                   placeholder="Buscar en identificador, título, descripción o dirección...">
                        </div>
                        <div class="filter-section">
                            <label class="filter-label">Estado</label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="Abierto">
                                    <span>Abierto</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="En Proceso">
                                    <span>En Proceso</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="Cerrado">
                                    <span>Cerrado</span>
                                </label>
                            </div>
                        </div>
                        <div class="filter-section">
                            <label class="filter-label">Rango de Fechas</label>
                            <div class="date-inputs">
                                <input type="date" id="fechaInicioFilter" placeholder="Desde">
                                <input type="date" id="fechaFinFilter" placeholder="Hasta">
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button class="filter-btn filter-btn-secondary" id="clearFilters">
                                Limpiar filtros
                            </button>
                            <button class="filter-btn filter-btn-primary" id="applyFilters">
                                Aplicar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(this.filterBar);
    }

    setupEventListeners() {
        const filterToggle = document.getElementById('filterToggle');
        const closeBtn = this.filterBar.querySelector('.filter-close');
        const applyBtn = this.filterBar.querySelector('#applyFilters');
        const clearBtn = this.filterBar.querySelector('#clearFilters');
        const keywordInput = this.filterBar.querySelector('#keywordFilter');
        const priorityButtons = this.filterBar.querySelectorAll('.priority-button');

        filterToggle?.addEventListener('click', () => {
            this.filterBar.classList.toggle('active');
        });

        closeBtn?.addEventListener('click', () => {
            this.filterBar.classList.remove('active');
        });

        applyBtn?.addEventListener('click', () => this.applyFilters());
        clearBtn?.addEventListener('click', () => this.clearFilters());

        keywordInput?.addEventListener('input', (e) => {
            if (e.target.value.length >= 3 || e.target.value.length === 0) {
                this.filters.keyword = e.target.value;
                this.applyFilters();
            }
        });

        priorityButtons.forEach(button => {
            button.addEventListener('click', () => {
                priorityButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                this.filters.prioridad = button.dataset.priority;
                this.applyFilters();
            });
        });

        document.addEventListener('click', (e) => {
            if (!this.filterBar.contains(e.target) && !filterToggle?.contains(e.target)) {
                this.filterBar.classList.remove('active');
            }
        });
    }

    applyFilters() {
        this.filters = {
            keyword: document.getElementById('keywordFilter').value,
            prioridad: this.filters.prioridad,
            estado: document.querySelector('input[name="estado"]:checked')?.value || '',
            fechaInicio: document.getElementById('fechaInicioFilter').value,
            fechaFin: document.getElementById('fechaFinFilter').value
        };

        this.updateActiveFiltersCount();
        this.filterPoints();
        
        // Disparar evento de filtro aplicado
        document.dispatchEvent(new CustomEvent('filterApplied'));
    }

    clearFilters() {
        document.getElementById('keywordFilter').value = '';
        document.querySelectorAll('.priority-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('input[name="estado"]').forEach(radio => radio.checked = false);
        document.getElementById('fechaInicioFilter').value = '';
        document.getElementById('fechaFinFilter').value = '';

        this.filters = {
            keyword: '',
            prioridad: '',
            estado: '',
            fechaInicio: '',
            fechaFin: ''
        };

        this.updateActiveFiltersCount();
        
        const { dataLoader } = window;
        if (dataLoader) {
            dataLoader.loadPoints();
        }
        
        this.filterBar.classList.remove('active');

        // Disparar evento de filtro limpiado
        document.dispatchEvent(new CustomEvent('filterCleared'));
    }

    updateActiveFiltersCount() {
        this.activeFiltersCount = Object.values(this.filters).filter(value => value !== '').length;
        const badge = document.querySelector('.filter-badge');
        const appliedCount = this.filterBar.querySelector('.filter-applied');
        
        if (this.activeFiltersCount > 0) {
            badge.textContent = this.activeFiltersCount;
            badge.classList.add('active');
            appliedCount.textContent = `${this.activeFiltersCount} aplicados`;
        } else {
            badge.textContent = '0';
            badge.classList.remove('active');
            appliedCount.textContent = 'Sin filtros';
        }
    }

    filterPoints() {
        const { dataLoader } = window;
        if (!dataLoader || !dataLoader.points) return;

        const filteredPoints = dataLoader.points.filter(point => {
            const properties = point.properties;
            
            if (this.filters.keyword) {
                const keyword = this.filters.keyword.toLowerCase();
                const matchesKeyword = this.searchFields.some(field => {
                    const value = String(properties[field] || '').toLowerCase();
                    return value.includes(keyword);
                });
                
                if (!matchesKeyword) return false;
            }

            if (this.filters.prioridad && properties.prioridad !== this.filters.prioridad) {
                return false;
            }

            if (this.filters.estado && properties.estado !== this.filters.estado) {
                return false;
            }

            if (this.filters.fechaInicio || this.filters.fechaFin) {
                const fecha = new Date(properties.fecha_creacion);
                
                if (this.filters.fechaInicio) {
                    const inicio = new Date(this.filters.fechaInicio);
                    if (fecha < inicio) return false;
                }
                
                if (this.filters.fechaFin) {
                    const fin = new Date(this.filters.fechaFin);
                    if (fecha > fin) return false;
                }
            }

            return true;
        });

        dataLoader.handlePoints(filteredPoints.map(f => f.properties));
    }
}