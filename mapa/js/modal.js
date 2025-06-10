class Modal {
    constructor(options = {}) {
        this.options = {
            title: '',
            subtitle: '',
            content: '',
            onClose: null,
            ...options
        };
        
        this.create();
        this.setupEventListeners();
    }

    create() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'modal-overlay';
        
        const modalHTML = `
            <div class="modal-container">
                <div class="modal-header">
                    <div>
                        <h2>${this.options.title}</h2>
                        ${this.options.subtitle ? `<div class="subtitle">${this.options.subtitle}</div>` : ''}
                    </div>
                    <button class="modal-close">×</button>
                </div>
                <div class="modal-content">
                    ${this.options.content}
                </div>
            </div>
        `;
        
        this.overlay.innerHTML = modalHTML;
        document.body.appendChild(this.overlay);
    }

    setupEventListeners() {
        const closeBtn = this.overlay.querySelector('.modal-close');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }

        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.close();
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.overlay.classList.contains('active')) {
                this.close();
            }
        });
    }

    open() {
        this.overlay.classList.add('active');
    }

    close() {
        this.overlay.classList.remove('active');
        setTimeout(() => {
            this.destroy();
            if (this.options.onClose) this.options.onClose();
        }, 200);
    }

    destroy() {
        this.overlay.remove();
    }
}

// Función global para mostrar detalles de la incidencia
window.showIncidentDetails = (properties) => {
    const getStatusClass = (status) => {
        const statusMap = {
            'abierto': 'open',
            'en proceso': 'in-progress',
            'cerrado': ''
        };
        return statusMap[status.toLowerCase()] || '';
    };

    const creationDate = new Date(properties.fecha_creacion).toLocaleString();
    const dueDate = properties.fecha_vencimiento ? 
        new Date(properties.fecha_vencimiento).toLocaleString() : 'No establecida';

    const modal = new Modal({
        title: 'Información del Ticket',
        subtitle: `#${properties.identificador}`,
        content: `
            <div class="incident-header">
                <div class="incident-badges">
                    <span class="incident-badge priority">${properties.prioridad}</span>
                    <span class="incident-badge status ${getStatusClass(properties.estado)}">${properties.estado}</span>
                </div>
            </div>

            <div class="incident-section">
                <h3><i class="bi bi-info-circle"></i> Información General</h3>
                <div class="incident-grid">
                    <div class="incident-info-item">
                        <div class="incident-info-label">Creado</div>
                        <div class="incident-info-value">${creationDate}</div>
                    </div>
                    <div class="incident-info-item">
                        <div class="incident-info-label">Vencimiento</div>
                        <div class="incident-info-value">${dueDate}</div>
                    </div>
                    <div class="incident-info-item">
                        <div class="incident-info-label">Cliente</div>
                        <div class="incident-info-value">${properties.nombre_cliente}</div>
                    </div>
                    <div class="incident-info-item">
                        <div class="incident-info-label">Área</div>
                        <div class="incident-info-value">${properties.nombre_area}</div>
                    </div>
                </div>
            </div>

            <div class="incident-section">
                <h3><i class="bi bi-card-text"></i> Detalles</h3>
                <div class="incident-info-item">
                    <div class="incident-info-label">Título</div>
                    <div class="incident-info-value">${properties.titulo}</div>
                </div>
                <div class="incident-info-item">
                    <div class="incident-info-label">Descripción</div>
                    <div class="incident-description">${properties.descripcion}</div>
                </div>
            </div>

            <div class="incident-section">
                <h3><i class="bi bi-geo-alt"></i> Ubicación</h3>
                <div class="incident-info-item">
                    <div class="incident-info-value">${properties.direccion_completa || 'No especificada'}</div>
                </div>
            </div>

            <div class="incident-section">
                <h3><i class="bi bi-camera"></i> Foto incidencia</h3>
                <div class="incident-info-item">
                    ${
                        properties.url 
                        ? `<img src="${properties.url}" alt="Foto de la incidencia" style="max-width: 100%; height: auto; border-radius: 5px;" />` 
                        : '<p>No hay foto disponible.</p>'
                    }
                </div>
            </div>
        `
    });

    modal.open();
};

export { Modal };
