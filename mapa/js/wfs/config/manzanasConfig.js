export const manzanasConfig = {
  enabledStateId: '22',
  baseUrl:
    'https://espacialhn.com/slim4/api/api/sinit/limite_mxmanzabasqueretaro',
  style: {
    color: '#22C55E', // Verde brillante y moderno
    weight: 1.0,
    opacity: 0.8,
    fillColor: '#86EFAC', // Verde pastel vivo
    fillOpacity: 0.6,
    className: 'manzana-layer',
  },
  hoverStyle: {
    color: '#16A34A', // Verde más fuerte para borde en hover
    weight: 2.5,
    opacity: 1,
    fillColor: '#4ADE80', // Más saturado para destacar
    fillOpacity: 0.7,
  },
  darkStyle: {
    color: '#4ADE80', // Verde claro neón sobre fondo oscuro
    weight: 1.2,
    opacity: 0.9,
    fillColor: '#86EFAC',
    fillOpacity: 0.4,
    className: 'manzana-layer',
  },
  darkHoverStyle: {
    color: '#22C55E',
    weight: 2,
    opacity: 1,
    fillColor: '#BBF7D0', // Verde claro vibrante
    fillOpacity: 0.6,
  },
  availableForAllMunicipalities: true,
};
