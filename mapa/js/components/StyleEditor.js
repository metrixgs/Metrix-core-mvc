import { styleModeManager } from '../core/StyleModeManager.js';

export class StyleEditor {
    constructor() {
        this.init();
    }

    init() {
        const button = document.createElement('button');
        button.id = 'styleEditorToggle';
        button.className = 'control-btn';
        button.innerHTML = '<i class="bi bi-palette"></i>';
        button.title = 'Modo Edición de Estilos';

        button.addEventListener('click', () => {
            const isActive = styleModeManager.toggle();
            button.classList.toggle('active', isActive);
        });

        this.addToToolbar(button);
    }

    addToToolbar(button) {
        const toolbar = document.querySelector('.nav-content .flex.items-center.gap-4');
        if (toolbar) {
            toolbar.appendChild(button);
        }
    }
}