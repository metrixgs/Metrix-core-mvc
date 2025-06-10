# Arquitectura del Sistema

## Archivos Críticos y Flujo de Carga

### 1. js/map.js
El punto de entrada principal del sistema. Responsable de:
- Inicialización del mapa base
- Configuración inicial de componentes core
- Gestión de eventos principales
- Orquestación de la carga de datos

### 2. js/core/MapManager.js
Singleton que gestiona la instancia principal del mapa:
- Manejo del estado global del mapa
- Gestión de capas base
- Control de eventos del mapa
- Gestión de controles y plugins

### 3. js/core/DataCore.js
Motor central de datos:
- Gestión de puntos y clusters
- Validación de datos
- Transformación de GeoJSON
- Manejo de errores de datos

### 4. js/dataLoader.js
Responsable de la carga y gestión de datos:
- Carga inicial de puntos
- Gestión de clusters
- Integración con heatmap y hexágonos
- Actualización de contadores

## Flujo de Inicialización

1. `map.js` inicia la aplicación:
   ```javascript
   // Inicializa el mapa
   const map = mapManager.initialize('map', config);
   
   // Configura componentes core
   heatmapCore.map = map;
   hexagonCore.setMap(map);
   ```

2. `MapManager` configura el mapa base:
   ```javascript
   initialize(containerId, config) {
     this.map = L.map(containerId, config);
     this.initializeBaseLayers();
     this.initializeControls();
   }
   ```

3. `DataLoader` carga los datos iniciales:
   ```javascript
   async loadPoints() {
     const data = await fetch('api/incidencias');
     this.handlePoints(data);
   }
   ```

## Jerarquía de Componentes

```
map.js
├── MapManager (Singleton)
│   ├── Gestión de capas base
│   └── Controles del mapa
├── DataCore
│   ├── Validación de datos
│   └── Transformación GeoJSON
└── DataLoader
    ├── Gestión de clusters
    ├── HeatmapCore
    └── HexagonCore
```

## Eventos Principales

1. Carga Inicial:
   - Inicialización del mapa
   - Carga de capas base
   - Configuración de controles

2. Carga de Datos:
   - Fetch de puntos
   - Creación de clusters
   - Actualización de visualizaciones

3. Interacción Usuario:
   - Zoom/Pan del mapa
   - Filtrado de datos
   - Cambio de visualizaciones

## Patrones de Diseño Utilizados

1. Singleton
   - MapManager
   - DataCore
   - ThemeManager

2. Observer
   - Eventos del mapa
   - Actualizaciones de datos

3. Factory
   - Creación de capas
   - Generación de marcadores