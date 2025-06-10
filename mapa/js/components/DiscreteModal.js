// Modal discreto y minimalista para vistas full-page
export class DiscreteModal {
    constructor() {
        this.modalId = 'discreteModal';
        this.init();
    }

    init() {
        // Crear estructura del modal si no existe
        if (!document.getElementById(this.modalId)) {
            const modalHTML = `
                <div id="${this.modalId}" class="discrete-modal">
                    <div class="discrete-modal-header"></div>
                    <div class="discrete-modal-content">
                        <button class="discrete-close">×</button>
                        <div class="discrete-loading">
                            <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <div class="discrete-content-container"></div>
                    </div>
                    <div class="discrete-modal-footer"></div>
                </div>
            `;

            const modalStyles = `
                .discrete-modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: var(--bg-color);
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .discrete-modal.active {
                    opacity: 1;
                }

                .discrete-modal-header,
                .discrete-modal-footer {
                    position: absolute;
                    left: 0;
                    right: 0;
                    height: 2px;
                    background: rgba(255, 255, 255, 0.1);
                    z-index: 10001;
                }

                .discrete-modal-header {
                    top: 0;
                }

                .discrete-modal-footer {
                    bottom: 0;
                }

                .discrete-modal-content {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                }

                .discrete-content-container {
                    width: 100%;
                    height: 100%;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .discrete-content-container.loaded {
                    opacity: 1;
                }

                .discrete-close {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    width: 40px;
                    height: 40px;
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    color: white;
                    font-size: 24px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10001;
                    transition: all 0.3s ease;
                    backdrop-filter: blur(4px);
                }

                .discrete-close:hover {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: rgba(255, 255, 255, 0.3);
                    transform: rotate(90deg);
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                }

                .discrete-close:active {
                    background: rgba(255, 255, 255, 0.3);
                    transform: rotate(90deg) scale(0.95);
                }

                .discrete-loading {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: var(--primary-color);
                    font-size: 24px;
                    opacity: 0;
                    transition: opacity 0.3s;
                    z-index: 10001;
                }

                .discrete-loading.active {
                    opacity: 1;
                }

                .discrete-loading i {
                    animation: discreteSpin 1s linear infinite;
                }

                @keyframes discreteSpin {
                    100% { transform: rotate(360deg); }
                }

                /* Dark theme adjustments */
                [data-theme="dark"] .discrete-modal-header,
                [data-theme="dark"] .discrete-modal-footer {
                    background: rgba(255, 255, 255, 0.05);
                }

                [data-theme="dark"] .discrete-close {
                    background: rgba(0, 0, 0, 0.6);
                    border-color: rgba(255, 255, 255, 0.1);
                }

                [data-theme="dark"] .discrete-close:hover {
                    background: rgba(0, 0, 0, 0.8);
                    border-color: rgba(255, 255, 255, 0.2);
                }
            `;

            // Agregar estilos
            const styleSheet = document.createElement('style');
            styleSheet.textContent = modalStyles;
            document.head.appendChild(styleSheet);

            // Agregar modal al DOM
            const modalElement = document.createElement('div');
            modalElement.innerHTML = modalHTML;
            document.body.appendChild(modalElement.firstElementChild);

            // Configurar eventos
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        const modal = document.getElementById(this.modalId);
        const closeBtn = modal.querySelector('.discrete-close');
        const contentContainer = modal.querySelector('.discrete-content-container');
        const loading = modal.querySelector('.discrete-loading');

        closeBtn.addEventListener('click', () => this.close());

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                this.close();
            }
        });

        contentContainer.addEventListener('transitionend', () => {
            if (!contentContainer.classList.contains('loaded')) {
                contentContainer.innerHTML = '';
            }
        });
    }

    open(url) {
        const modal = document.getElementById(this.modalId);
        const loading = modal.querySelector('.discrete-loading');
        const contentContainer = modal.querySelector('.discrete-content-container');

        // Reset state
        contentContainer.classList.remove('loaded');
        loading.classList.add('active');
        
        // Show modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Create iframe
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.src = url;
        
        // Handle iframe load
        iframe.onload = () => {
            loading.classList.remove('active');
            contentContainer.classList.add('loaded');
        };

        // Clear previous content and add iframe
        contentContainer.innerHTML = '';
        contentContainer.appendChild(iframe);
        
        // Activate transition
        requestAnimationFrame(() => {
            modal.classList.add('active');
        });
    }

    setContent(content) {
        const modal = document.getElementById(this.modalId);
        const contentContainer = modal.querySelector('.discrete-content-container');
        const loading = modal.querySelector('.discrete-loading');

        // Insertar nuevo contenido
        contentContainer.innerHTML = content;

        // Ocultar loading y mostrar contenido
        loading.classList.remove('active');
        requestAnimationFrame(() => {
            contentContainer.classList.add('loaded');
        });
    }

    close() {
        const modal = document.getElementById(this.modalId);
        const loading = modal.querySelector('.discrete-loading');
        const contentContainer = modal.querySelector('.discrete-content-container');

        modal.classList.remove('active');
        loading.classList.remove('active');
        contentContainer.classList.remove('loaded');

        setTimeout(() => {
            modal.style.display = 'none';
            contentContainer.innerHTML = '';
            document.body.style.overflow = '';
        }, 300);
    }
}

// Exportar instancia única
export const discreteModal = new DiscreteModal();