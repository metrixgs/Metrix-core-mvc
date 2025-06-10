export class PulseLayer {
    constructor() {
        this.map = null;
        this.pulseMarker = null;
        this.init();
    }

    init() {
        if (!document.getElementById('pulse-layer-style')) {
            const style = document.createElement('style');
            style.id = 'pulse-layer-style';
            style.textContent = `
                .pulse-marker {
                    border-radius: 50%;
                    position: relative;
                    background: rgba(0, 166, 80, 0.4);
                }

                .pulse-marker::before,
                .pulse-marker::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border-radius: 50%;
                    background: rgba(0, 166, 80, 0.4);
                    animation: pulse 2s ease-out infinite;
                }

                .pulse-marker::after {
                    animation-delay: 0.5s;
                }

                @keyframes pulse {
                    0% {
                        transform: scale(1);
                        opacity: 1;
                    }
                    100% {
                        transform: scale(3);
                        opacity: 0;
                    }
                }

                .pulse-marker-inner {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 10px;
                    height: 10px;
                    background: #00A650;
                    border-radius: 50%;
                    border: 2px solid white;
                }
            `;
            document.head.appendChild(style);
        }
    }

    setMap(map) {
        this.map = map;
    }

    showPulse(lat, lng) {
        if (!this.map) return;

        this.clearPulse();

        const pulseIcon = L.divIcon({
            className: 'pulse-marker',
            html: '<div class="pulse-marker-inner"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        this.pulseMarker = L.marker([lat, lng], {
            icon: pulseIcon,
            zIndexOffset: 1000
        }).addTo(this.map);
    }

    clearPulse() {
        if (this.pulseMarker && this.map) {
            this.map.removeLayer(this.pulseMarker);
            this.pulseMarker = null;
        }
    }
}

export const pulseLayer = new PulseLayer();