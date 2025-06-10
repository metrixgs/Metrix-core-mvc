# Integración de API

## Endpoints Principales

### Incidencias
```javascript
const API_URL = 'https://lightsteelblue-spoonbill-227005.hostingersite.com/api/incidencias';
```

### Estructura de Respuesta
```typescript
interface APIResponse {
    data: {
        latitud: number;
        longitud: number;
        estado: string;
        identificador: string;
        titulo: string;
        descripcion: string;
        fecha_creacion: string;
        fecha_vencimiento?: string;
        prioridad: 'Alta' | 'Media' | 'Baja';
        color_sla: string;
    }[];
}
```

## Manejo de Datos

### Validación
```javascript
validatePoint(point) {
    const requiredFields = ['latitud', 'longitud', 'estado', 'identificador'];
    return requiredFields.every(field => field in point);
}
```

### Transformación
```javascript
createGeoJSON(points) {
    return {
        type: 'FeatureCollection',
        features: points.map(point => ({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [point.longitud, point.latitud]
            },
            properties: point
        }))
    };
}
```

## Gestión de Errores

### Tipos de Errores
1. Error de Red
2. Datos Inválidos
3. Timeout
4. Errores de Formato

### Manejo
```javascript
async fetchData() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
}
```

## Actualización de Datos

### Frecuencia
- Carga inicial al montar la aplicación
- Actualizaciones bajo demanda
- No hay polling automático

### Caché
```javascript
class DataCore {
    constructor() {
        this.lastUpdate = null;
        this.cache = new Map();
    }

    isDataStale() {
        if (!this.lastUpdate) return true;
        const staleThreshold = 5 * 60 * 1000; // 5 minutos
        return (new Date() - this.lastUpdate) > staleThreshold;
    }
}
```