import { dataLoader } from '../dataLoader.js';

export class PointsExport {
    constructor() {
        this.init();
    }

    init() {
        this.createExportButton();
        this.setupEventListeners();
    }

    createExportButton() {
        const exportButton = document.createElement('button');
        exportButton.id = 'exportPoints';
        exportButton.className = 'control-btn';
        exportButton.title = 'Exportar Puntos CSV';
        exportButton.innerHTML = `
            <i class="bi bi-file-earmark-spreadsheet"></i>
        `;

        // Insertar después del botón de exportar mapa
        const mapExportBtn = document.getElementById('exportMap');
        if (mapExportBtn && mapExportBtn.parentNode) {
            mapExportBtn.parentNode.insertBefore(exportButton, mapExportBtn.nextSibling);
        }
    }

    setupEventListeners() {
        const exportButton = document.getElementById('exportPoints');
        if (exportButton) {
            exportButton.addEventListener('click', () => this.exportPoints());
        }
    }

    exportPoints() {
        try {
            // Obtener puntos visibles actuales
            const points = dataLoader.points;
            if (!points || points.length === 0) {
                console.warn('No hay puntos para exportar');
                return;
            }

            // Campos a exportar
            const fields = [
                'identificador',
                'titulo',
                'descripcion',
                'prioridad',
                'latitud',
                'longitud',
                'estado',
                'municipio',
                'colonia',
                'codigo_postal',
                'direccion_completa',
                'fecha_creacion',
                'fecha_vencimiento',
                'nombre_cliente',
                'nombre_area'
            ];

            // Crear contenido CSV
            const csvContent = [
                // Encabezados
                fields.join(','),
                // Datos
                ...points.map(point => {
                    const props = point.properties;
                    return fields.map(field => {
                        const value = props[field];
                        // Manejar valores nulos o indefinidos
                        if (value === null || value === undefined) return '';
                        // Escapar comas y comillas en los valores
                        const stringValue = String(value).replace(/"/g, '""');
                        return `"${stringValue}"`;
                    }).join(',');
                })
            ].join('\n');

            // Crear blob y descargar usando FileSaver.js desde window (cargado por CDN)
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const date = new Date().toISOString().split('T')[0];
            const filename = `puntos_${date}.csv`;
            
            // Usar saveAs desde window (cargado por CDN)
            window.saveAs(blob, filename);

        } catch (error) {
            console.error('Error exportando puntos:', error);
        }
    }
}