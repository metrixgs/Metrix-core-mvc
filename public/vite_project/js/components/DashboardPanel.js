import { dataLoader } from '../dataLoader.js';

export class DashboardPanel {
    constructor() {
        this.container = null;
        this.isVisible = false;
        this.init();
    }

    init() {
        this.createToggleButton();
        this.createContainer();
        this.setupEventListeners();
        this.update();
    }

    createToggleButton() {
        const footerSection = document.createElement('div');
        footerSection.className = 'footer-section';
        
        const button = document.createElement('button');
        button.className = 'dashboard-toggle';
        button.innerHTML = `
            <i class="bi bi-graph-up"></i>
            <span>Informe</span>
        `;
        
        footerSection.appendChild(button);
        document.querySelector('.footer-content').appendChild(footerSection);
    }

    createContainer() {
        this.container = document.createElement('div');
        this.container.className = 'dashboard-container';
        this.container.innerHTML = `
            <div class="dashboard-header">
                <h3>Dashboard de Incidencias</h3>
                <button class="control-btn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="dashboard-content">
                <div class="stat-card">
                    <h4>Total de Incidencias</h4>
                    <div class="stat-value" id="totalIncidencias">0</div>
                </div>

                <div class="stat-grid">
                    <div class="mini-stat">
                        <div class="value" id="incidenciasAbiertas">0</div>
                        <div class="label">Abiertas</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasProceso">0</div>
                        <div class="label">En Proceso</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasCerradas">0</div>
                        <div class="label">Cerradas</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasHoy">0</div>
                        <div class="label">Hoy</div>
                    </div>
                </div>

                <div class="stat-card">
                    <h4>Por Prioridad</h4>
                    <div class="stat-list">
                        <div class="stat-item">
                            <span class="label">Alta</span>
                            <span class="value" id="prioridadAlta">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Media</span>
                            <span class="value" id="prioridadMedia">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Baja</span>
                            <span class="value" id="prioridadBaja">0</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <h4>Estadísticas Temporales</h4>
                    <div class="stat-list">
                        <div class="stat-item">
                            <span class="label">Esta Semana</span>
                            <span class="value" id="estaSemana">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Este Mes</span>
                            <span class="value" id="esteMes">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Vencidas</span>
                            <span class="value" id="vencidas">0</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(this.container);
    }

    setupEventListeners() {
        const toggle = document.querySelector('.dashboard-toggle');
        const closeBtn = this.container.querySelector('.control-btn');

        toggle.addEventListener('click', () => {
            this.toggle();
            toggle.classList.toggle('active');
        });

        closeBtn.addEventListener('click', () => this.hide());

        // Escuchar eventos de filtrado
        document.addEventListener('filterApplied', () => this.update());
        document.addEventListener('filterCleared', () => this.update());
    }

    toggle() {
        if (this.isVisible) {
            this.hide();
        } else {
            this.show();
        }
    }

    show() {
        this.container.classList.add('active');
        this.isVisible = true;
        this.update();
    }

    hide() {
        this.container.classList.remove('active');
        this.isVisible = false;
    }

    update() {
        const points = dataLoader.points;
        if (!points) return;

        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const weekStart = new Date(today);
        weekStart.setDate(today.getDate() - today.getDay());
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);

        const stats = {
            total: points.length,
            estados: {
                Abierto: 0,
                'En Proceso': 0,
                Cerrado: 0
            },
            prioridad: {
                Alta: 0,
                Media: 0,
                Baja: 0
            },
            temporal: {
                hoy: 0,
                semana: 0,
                mes: 0,
                vencidas: 0
            }
        };

        points.forEach(point => {
            const props = point.properties;
            
            // Conteo por estado
            stats.estados[props.estado] = (stats.estados[props.estado] || 0) + 1;
            
            // Conteo por prioridad
            stats.prioridad[props.prioridad] = (stats.prioridad[props.prioridad] || 0) + 1;
            
            // Estadísticas temporales
            const fecha = new Date(props.fecha_creacion);
            if (fecha >= today) stats.temporal.hoy++;
            if (fecha >= weekStart) stats.temporal.semana++;
            if (fecha >= monthStart) stats.temporal.mes++;
            
            // Vencidas
            if (props.fecha_vencimiento) {
                const vencimiento = new Date(props.fecha_vencimiento);
                if (vencimiento < now && props.estado !== 'Cerrado') {
                    stats.temporal.vencidas++;
                }
            }
        });

        // Actualizar UI
        document.getElementById('totalIncidencias').textContent = stats.total;
        document.getElementById('incidenciasAbiertas').textContent = stats.estados.Abierto;
        document.getElementById('incidenciasProceso').textContent = stats.estados['En Proceso'];
        document.getElementById('incidenciasCerradas').textContent = stats.estados.Cerrado;
        document.getElementById('incidenciasHoy').textContent = stats.temporal.hoy;
        
        document.getElementById('prioridadAlta').textContent = stats.prioridad.Alta;
        document.getElementById('prioridadMedia').textContent = stats.prioridad.Media;
        document.getElementById('prioridadBaja').textContent = stats.prioridad.Baja;
        
        document.getElementById('estaSemana').textContent = stats.temporal.semana;
        document.getElementById('esteMes').textContent = stats.temporal.mes;
        document.getElementById('vencidas').textContent = stats.temporal.vencidas;
    }
}