import { stylePersistenceManager } from './StylePersistenceManager.js';

class StyleModeManager {
    constructor() {
        this.active = false;
        this.selectedFeature = null;
        this.selectedLayer = null;
        this.editingMode = null;
        this.stylePanel = null;
        this.init();
    }

    init() {
        this.createStylePanel();
        this.setupEventListeners();
    }

    createStylePanel() {
        this.stylePanel = document.createElement('div');
        this.stylePanel.className = 'style-panel';
        this.stylePanel.innerHTML = `
            <div class="style-panel-header">
                <h3>Editor de Estilos</h3>
                <div class="style-panel-actions">
                    <button class="control-btn" title="Exportar Estilos">
                        <i class="bi bi-download"></i>
                    </button>
                    <button class="control-btn" title="Importar Estilos">
                        <i class="bi bi-upload"></i>
                    </button>
                    <button class="control-btn close-btn">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
            <div class="style-panel-content">
                <div class="style-section">
                    <h4>Relleno</h4>
                    <div class="style-control">
                        <label>Color de Relleno</label>
                        <input type="color" id="fillColor" value="#3388ff">
                    </div>
                    <div class="style-control">
                        <label>Opacidad de Relleno</label>
                        <input type="range" id="fillOpacity" min="0" max="1" step="0.1" value="0.2">
                    </div>
                </div>

                <div class="style-section">
                    <h4>Borde</h4>
                    <div class="style-control">
                        <label>Color de Borde</label>
                        <input type="color" id="strokeColor" value="#3388ff">
                    </div>
                    <div class="style-control">
                        <label>Ancho de Borde</label>
                        <input type="number" id="strokeWidth" min="0" max="10" value="2">
                    </div>
                    <div class="style-control">
                        <label>Tipo de Línea</label>
                        <select id="strokeStyle">
                            <option value="solid">Sólida</option>
                            <option value="dashed">Punteada</option>
                            <option value="dotted">Discontinua</option>
                        </select>
                    </div>
                    <div class="style-control">
                        <label>Opacidad de Borde</label>
                        <input type="range" id="strokeOpacity" min="0" max="1" step="0.1" value="1">
                    </div>
                </div>
            </div>
            <div class="style-actions">
                <button class="style-btn style-btn-secondary" id="resetStyle">
                    Restaurar
                </button>
                <button class="style-btn style-btn-primary" id="applyStyle">
                    Aplicar
                </button>
            </div>
        `;
        document.body.appendChild(this.stylePanel);
    }

    setupEventListeners() {
        const closeBtn = this.stylePanel.querySelector('.close-btn');
        const applyBtn = this.stylePanel.querySelector('#applyStyle');
        const resetBtn = this.stylePanel.querySelector('#resetStyle');
        const exportBtn = this.stylePanel.querySelector('.bi-download').parentElement;
        const importBtn = this.stylePanel.querySelector('.bi-upload').parentElement;

        closeBtn.addEventListener('click', () => this.hidePanel());
        applyBtn.addEventListener('click', () => this.applyStyles());
        resetBtn.addEventListener('click', () => this.resetStyles());
        
        exportBtn.addEventListener('click', () => this.exportStyles());
        importBtn.addEventListener('click', () => this.importStyles());

        // Aplicar estilos en tiempo real
        this.stylePanel.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', () => this.applyStyles());
        });
    }

    exportStyles() {
        const styles = stylePersistenceManager.exportStyles();
        if (styles) {
            const blob = new Blob([JSON.stringify(styles, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `metrix_styles_${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
        }
    }

    importStyles() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json';
        
        input.onchange = async (e) => {
            try {
                const file = e.target.files[0];
                const text = await file.text();
                const data = JSON.parse(text);
                
                if (stylePersistenceManager.importStyles(data)) {
                    this.loadCurrentStyles();
                    alert('Estilos importados correctamente');
                }
            } catch (error) {
                console.error('Error importing styles:', error);
                alert('Error al importar estilos');
            }
        };
        
        input.click();
    }

    activate() {
        this.active = true;
        document.body.classList.add('style-mode-active');
    }

    deactivate() {
        this.active = false;
        this.clearSelection();
        document.body.classList.remove('style-mode-active');
        this.hidePanel();
    }

    toggle() {
        if (this.active) {
            this.deactivate();
        } else {
            this.activate();
        }
        return this.active;
    }

    selectFeature(feature, layer) {
        if (!this.active) return;
        
        this.clearSelection();
        this.selectedFeature = { feature, layer, id: feature.id };
        this.editingMode = 'feature';
        
        layer.setStyle({
            className: 'feature-selected'
        });

        // Cargar estilo guardado si existe
        const savedStyle = stylePersistenceManager.getStyle('feature', feature.id);
        if (savedStyle) {
            layer.setStyle(savedStyle);
        }

        this.showPanel();
        this.loadCurrentStyles();
    }

    selectLayer(layerId, layer) {
        if (!this.active) return;
        
        this.clearSelection();
        this.selectedLayer = { id: layerId, layer };
        this.editingMode = 'layer';

        // Cargar estilo guardado si existe
        const savedStyle = stylePersistenceManager.getStyle('layer', layerId);
        if (savedStyle) {
            layer.setStyle(savedStyle);
        }

        this.showPanel();
        this.loadCurrentStyles();
    }

    clearSelection() {
        if (this.selectedFeature) {
            this.selectedFeature.layer.setStyle({
                className: ''
            });
            this.selectedFeature = null;
        }
        this.selectedLayer = null;
        this.editingMode = null;
    }

    showPanel() {
        this.stylePanel.classList.add('active');
    }

    hidePanel() {
        this.stylePanel.classList.remove('active');
    }

    getCurrentStyles() {
        return {
            fillColor: this.stylePanel.querySelector('#fillColor').value,
            fillOpacity: parseFloat(this.stylePanel.querySelector('#fillOpacity').value),
            color: this.stylePanel.querySelector('#strokeColor').value,
            weight: parseInt(this.stylePanel.querySelector('#strokeWidth').value),
            opacity: parseFloat(this.stylePanel.querySelector('#strokeOpacity').value),
            dashArray: this.getStrokeDashArray()
        };
    }

    getStrokeDashArray() {
        const style = this.stylePanel.querySelector('#strokeStyle').value;
        switch (style) {
            case 'dashed': return '10, 10';
            case 'dotted': return '3, 3';
            default: return null;
        }
    }

    loadCurrentStyles() {
        const target = this.editingMode === 'feature' ? this.selectedFeature : this.selectedLayer;
        if (!target) return;

        let currentStyle = target.layer.options;
        
        // Intentar cargar estilo guardado
        const savedStyle = stylePersistenceManager.getStyle(
            this.editingMode,
            target.id
        );
        
        if (savedStyle) {
            currentStyle = savedStyle;
        }
        
        this.stylePanel.querySelector('#fillColor').value = currentStyle.fillColor || '#3388ff';
        this.stylePanel.querySelector('#fillOpacity').value = currentStyle.fillOpacity || 0.2;
        this.stylePanel.querySelector('#strokeColor').value = currentStyle.color || '#3388ff';
        this.stylePanel.querySelector('#strokeWidth').value = currentStyle.weight || 2;
        this.stylePanel.querySelector('#strokeOpacity').value = currentStyle.opacity || 1;
        
        const dashArray = currentStyle.dashArray;
        let strokeStyle = 'solid';
        if (dashArray) {
            if (dashArray === '10, 10') strokeStyle = 'dashed';
            else if (dashArray === '3, 3') strokeStyle = 'dotted';
        }
        this.stylePanel.querySelector('#strokeStyle').value = strokeStyle;
    }

    applyStyles() {
        const styles = this.getCurrentStyles();
        
        if (this.editingMode === 'feature' && this.selectedFeature) {
            this.selectedFeature.layer.setStyle(styles);
            stylePersistenceManager.saveStyle('feature', this.selectedFeature.id, styles);
        } else if (this.editingMode === 'layer' && this.selectedLayer) {
            this.selectedLayer.layer.setStyle(styles);
            stylePersistenceManager.saveStyle('layer', this.selectedLayer.id, styles);
        }
    }

    resetStyles() {
        if (!this.editingMode) return;

        const target = this.editingMode === 'feature' ? this.selectedFeature : this.selectedLayer;
        if (!target) return;

        // Eliminar estilo guardado
        stylePersistenceManager.deleteStyle(this.editingMode, target.id);

        const defaultStyle = {
            fillColor: '#3388ff',
            fillOpacity: 0.2,
            color: '#3388ff',
            weight: 2,
            opacity: 1
        };

        target.layer.setStyle(defaultStyle);
        this.loadCurrentStyles();
    }

    isActive() {
        return this.active;
    }

    getEditingMode() {
        return this.editingMode;
    }
}

export const styleModeManager = new StyleModeManager();