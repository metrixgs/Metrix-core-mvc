export const wfsLayers = {
  adminBoundaries: {
    name: 'Límites Administrativos',
    layers: [
      {
        id: 'limite_mxestados',
        url: 'https://espacialhn.com/slim4/api/api/sinit/limite_mxestados/',
        name: 'Estados de México',
        style: {
          color: '#E53935',
          weight: 2.0,
          opacity: 1,
          fillColor: '#FFFFFF',
          fillOpacity: 0.05,
          dashArray: null,
          lineCap: 'round',
          lineJoin: 'round'
        },
        darkStyle: {
          color: '#FF5252',
          weight: 2.0,
          opacity: 1,
          fillColor: '#424242',
          fillOpacity: 0.1,
          dashArray: null,
          lineCap: 'round',
          lineJoin: 'round'
        }
      }
    ]
  },
  incidents: {
    name: 'Incidencias',
    layers: [
      {
        id: 'incidencias',
        url: 'https://lightsteelblue-spoonbill-227005.hostingersite.com/api/incidencias',
        name: 'Incidencias',
        style: {
          radius: 8,
          fillColor: '#ef4444',
          color: '#ffffff',
          weight: 1,
          opacity: 1,
          fillOpacity: 0.8
        }
      }
    ]
  }
};

export const zoomBehaviors = {
  limite_mxestados: {
    minZoom: 5,
    maxZoom: 9,
    fadeZoom: {
      in: 5,
      out: 9
    }
  }
};