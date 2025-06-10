import { dataLoader } from '../dataLoader.js';

export class SymbologyTable {
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
        button.className = 'symbology-toggle';
        button.innerHTML = `
            <i class="bi bi-chevron-left"></i>
            <span>Simbología</span>
        `;
        
        footerSection.appendChild(button);
        document.querySelector('.footer-content').appendChild(footerSection);
    }

    createContainer() {
        this.container = document.createElement('div');
        this.container.className = 'symbology-container';
        this.container.innerHTML = `
            <div class="symbology-header">
                <h3>Simbología del Mapa</h3>
            </div>
            <div class="symbology-content">
                <table class="symbology-table">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Símbolo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        `;
        document.body.appendChild(this.container);
    }

    setupEventListeners() {
        const toggle = document.querySelector('.symbology-toggle');

        toggle.addEventListener('click', () => {
            this.toggle();
            toggle.classList.toggle('active');
        });

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
        const counts = {
            'Abierto': { count: 0, color: '#FFA500' },
            'En Proceso': { count: 0, color: '#3B82F6' },
            'Cerrado': { count: 0, color: '#10B981' }
        };

        // Contar puntos por estado
        points.forEach(point => {
            const estado = point.properties.estado;
            if (counts[estado]) {
                counts[estado].count++;
            }
        });

        // Actualizar tabla
        const tbody = this.container.querySelector('tbody');
        tbody.innerHTML = Object.entries(counts)
            .map(([estado, data]) => `
                <tr>
                    <td>${estado}</td>
                    <td>
                        <span class="symbol-icon" style="background-color: ${data.color}"></span>
                    </td>
                    <td class="symbol-count">${data.count}</td>
                </tr>
            `).join('');
    }
}