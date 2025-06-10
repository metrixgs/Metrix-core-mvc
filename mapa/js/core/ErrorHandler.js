export class ErrorHandler {
    static handleAPIError(error, context = '') {
        console.error(`Error en API ${context}:`, error);
        return {
            success: false,
            error: error.message,
            data: []
        };
    }

    static validatePoint(point) {
        try {
            if (!point) return false;
            
            // Validación básica de estructura
            if (typeof point !== 'object') return false;
            
            // Validación de coordenadas
            const lat = parseFloat(point.latitud);
            const lng = parseFloat(point.longitud);
            
            if (isNaN(lat) || isNaN(lng)) return false;
            if (lat < -90 || lat > 90) return false;
            if (lng < -180 || lng > 180) return false;
            
            // Validación de campos requeridos
            const requiredFields = ['estado', 'identificador', 'titulo'];
            if (!requiredFields.every(field => field in point)) return false;
            
            // Sanitización de campos de texto
            const textFields = ['estado', 'titulo', 'descripcion', 'nombre_cliente', 'nombre_area'];
            textFields.forEach(field => {
                if (point[field]) {
                    point[field] = this.sanitizeText(point[field]);
                }
            });
            
            return true;
        } catch (error) {
            console.error('Error validando punto:', error);
            return false;
        }
    }

    static sanitizeText(text) {
        if (!text) return '';
        
        // Convertir a string si no lo es
        text = String(text);
        
        // Eliminar caracteres especiales y HTML
        text = text.replace(/<[^>]*>/g, '');
        
        // Eliminar caracteres no imprimibles
        text = text.replace(/[\x00-\x1F\x7F-\x9F]/g, '');
        
        // Trim espacios
        text = text.trim();
        
        return text;
    }

    static getDefaultPoint() {
        return {
            latitud: 0,
            longitud: 0,
            estado: 'Sin estado',
            identificador: 'N/A',
            titulo: 'Sin título',
            descripcion: 'Sin descripción',
            nombre_cliente: 'Sin cliente',
            nombre_area: 'Sin área',
            fecha_creacion: new Date().toISOString(),
            prioridad: 'Baja',
            color_sla: '#cccccc'
        };
    }
}