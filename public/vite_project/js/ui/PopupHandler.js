import { priorityConfig } from '../config/apiConfig.js';

export class PopupHandler {
    static createPopupContent(properties) {
        const creationDate = new Date(properties.fecha_creacion).toLocaleString();
        const dueDate = properties.fecha_vencimiento ? 
            new Date(properties.fecha_vencimiento).toLocaleString() : 'No establecida';

        const getStatusClass = (status) => {
            const statusMap = {
                'abierto': 'open',
                'en proceso': 'in-progress',
                'cerrado': ''
            };
            return statusMap[status.toLowerCase()] || '';
        };

        return `
            <div class="popup-container">
                <div class="popup-header">
                    <span class="popup-id">#${properties.identificador}</span>
                    <div class="popup-badges">
                        <span class="popup-badge priority">${properties.prioridad}</span>
                        <span class="popup-badge status ${getStatusClass(properties.estado)}">${properties.estado}</span>
                    </div>
                </div>
                
                <h3 class="popup-title">${properties.titulo}</h3>
                
                <div class="popup-info">
                    <div class="popup-info-row">
                        <span class="popup-info-label">Creado:</span>
                        <span class="popup-info-value">${creationDate}</span>
                    </div>
                    <div class="popup-info-row">
                        <span class="popup-info-label">Vence:</span>
                        <span class="popup-info-value">${dueDate}</span>
                    </div>
                    <div class="popup-info-row">
                        <span class="popup-info-label">Cliente:</span>
                        <span class="popup-info-value">${properties.nombre_cliente}</span>
                    </div>
                    <div class="popup-info-row">
                        <span class="popup-info-label">Área:</span>
                        <span class="popup-info-value">${properties.nombre_area}</span>
                    </div>
                    <div class="popup-info-row">
                        <span class="popup-info-label">Ubicación:</span>
                        <span class="popup-info-value">${properties.direccion_completa}</span>
                    </div>
                </div>

                <button onclick="window.showIncidentDetails(${JSON.stringify(properties).replace(/"/g, '&quot;')})"
                        class="popup-button">
                    Ver Detalles
                </button>
            </div>
        `;
    }
}