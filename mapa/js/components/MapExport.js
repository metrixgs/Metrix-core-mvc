import { mapManager } from '../core/MapManager.js';

export class MapExport {
    constructor() {
        this.isExporting = false;
        this.init();
    }

    init() {
        this.createExportButton();
        this.setupEventListeners();
    }

    createExportButton() {
        const exportButton = document.createElement('button');
        exportButton.id = 'exportMap';
        exportButton.className = 'control-btn';
        exportButton.title = 'Exportar Mapa';
        exportButton.innerHTML = `
            <i class="bi bi-download"></i>
        `;

        // Insertar antes del botón de filtro
        const filterToggle = document.getElementById('filterToggle');
        if (filterToggle && filterToggle.parentNode) {
            filterToggle.parentNode.insertBefore(exportButton, filterToggle);
        }
    }

    setupEventListeners() {
        const exportButton = document.getElementById('exportMap');
        if (exportButton) {
            exportButton.addEventListener('click', () => this.exportMap());
        }
    }

    async exportMap() {
        if (this.isExporting) return;

        try {
            this.isExporting = true;
            const exportButton = document.getElementById('exportMap');
            exportButton.classList.add('loading');
            exportButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';

            const map = mapManager.getMap();
            if (!map) throw new Error('Map not initialized');

            const mapContainer = document.getElementById('map');
            
            // Usar html2canvas desde window (cargado por CDN)
            const canvas = await window.html2canvas(mapContainer, {
                useCORS: true,
                allowTaint: true,
                foreignObjectRendering: true,
                logging: false,
                scale: 2, // Mayor calidad
                backgroundColor: null
            });

            // Convertir canvas a blob
            canvas.toBlob((blob) => {
                if (!blob) throw new Error('Failed to create blob');
                
                // Generar nombre de archivo con fecha
                const date = new Date().toISOString().split('T')[0];
                const filename = `mapa_${date}.png`;
                
                // Usar saveAs desde window (cargado por CDN)
                window.saveAs(blob, filename);
                
                // Restaurar botón
                exportButton.classList.remove('loading');
                exportButton.innerHTML = '<i class="bi bi-download"></i>';
                this.isExporting = false;
            }, 'image/png');

        } catch (error) {
            console.error('Error exporting map:', error);
            const exportButton = document.getElementById('exportMap');
            exportButton.classList.remove('loading');
            exportButton.innerHTML = '<i class="bi bi-download"></i>';
            this.isExporting = false;
        }
    }
}