# API Integration Guide

## Endpoints

### States

- `GET /api/states` - Get all saved states
- `GET /api/states/{id}` - Get specific state by ID
- `POST /api/states` - Create new state
- `DELETE /api/states/{id}` - Delete state

### Points

- `GET /api/points` - Get all points
- `GET /api/states/{stateId}/points` - Get points for specific state

## State Object Structure

```json
{
    "sessionId": "MAP_20240327_123456",
    "timestamp": "2024-03-27T12:34:56.789Z",
    "filterCriteria": {
        "type": "polygon",
        "geometry": {...}
    },
    "points": ["TICK-001", "TICK-002"],
    "viewport": {
        "center": {
            "lat": 14.0906,
            "lng": -87.2054
        },
        "zoom": 12
    }
}
```

## Points Response Structure

```json
{
    "points": [
        {
            "identificador": "TICK-001",
            "latitud": 14.0906,
            "longitud": -87.2054,
            "estado": "Abierto",
            "titulo": "Incidencia 1",
            "descripcion": "...",
            "fecha_creacion": "2024-03-27T12:34:56.789Z"
        }
    ]
}
```

## Usage Example

```javascript
import { stateAPI } from './api/StateAPI.js';
import { pointsAPI } from './api/PointsAPI.js';

// Get all states
const states = await stateAPI.getAllStates();

// Get points for a specific state
const points = await pointsAPI.getPointsByState('MAP_20240327_123456');
```