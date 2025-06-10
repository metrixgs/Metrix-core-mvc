# Componentes Core

## MapManager

El `MapManager` es el componente central que gestiona toda la funcionalidad del mapa:

```javascript
class MapManager {
    constructor() {
        this.map = null;
        this.layerControl = null;
        this.isInitialized = false;
        this.baseLayerInstances = new Map();
    }
}
```

### Responsabilidades:
- Gestión del ciclo de vida del mapa
- Control de capas base
- Manejo de eventos del mapa
- Gestión de controles y plugins

## DataCore

`DataCore` maneja toda la lógica de datos:

```javascript
class DataCore {
    constructor() {
        this.points = [];
        this.pointsLayer = null;
        this.clusterLayer = null;
        this.visiblePointsCount = 0;
    }
}
```

### Responsabilidades:
- Validación de datos
- Transformación de formatos
- Gestión de clusters
- Control de visibilidad

## HeatmapCore y HexagonCore

Componentes de visualización especializados:

```javascript
class HeatmapCore {
    constructor(map) {
        this.map = map;
        this.heatmapLayer = null;
        this.points = [];
        this.isVisible = false;
    }
}

class HexagonCore {
    constructor() {
        this.map = null;
        this.hexLayer = null;
        this.points = [];
        this.isVisible = false;
    }
}
```

### Responsabilidades:
- Visualización de datos
- Gestión de capas especializadas
- Control de estados visuales

## Flujo de Datos

```
API → DataCore → DataLoader → Visualizaciones
                           ├→ Clusters
                           ├→ Heatmap
                           └→ Hexágonos
```

## Gestión de Estado

El sistema utiliza un patrón de estado distribuido donde:
- `MapManager`: Estado del mapa
- `DataCore`: Estado de datos
- Componentes visuales: Estado de visualización

## Extensibilidad

El sistema está diseñado para ser extensible a través de:
1. Nuevos tipos de visualización
2. Plugins adicionales
3. Capas personalizadas
4. Nuevos controles