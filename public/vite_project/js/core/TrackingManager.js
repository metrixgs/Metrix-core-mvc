import { ErrorHandler } from './ErrorHandler.js';

class TrackingManager {
    constructor() {
        this.STORAGE_KEY = 'metrix_tracking_log';
        this.isEnabled = true;
        this.currentSession = null;
        this.events = [];
        this.init();
    }

    init() {
        // Iniciar nueva sesión
        this.startSession();

        // Registrar eventos de cierre
        window.addEventListener('beforeunload', () => this.endSession());
        
        // Registrar eventos principales
        this.setupEventListeners();
    }

    startSession() {
        this.currentSession = {
            id: `SESSION_${Date.now()}`,
            startTime: new Date().toISOString(),
            endTime: null,
            events: []
        };
        this.saveToStorage();
    }

    endSession() {
        if (this.currentSession) {
            this.currentSession.endTime = new Date().toISOString();
            this.saveToStorage();
        }
    }

    setupEventListeners() {
        // Eventos de capas
        document.addEventListener('layerToggled', (e) => {
            this.trackEvent('LAYER', {
                action: e.detail.active ? 'ACTIVATED' : 'DEACTIVATED',
                layerId: e.detail.layerId,
                layerType: e.detail.layerType
            });
        });

        // Eventos de estilo
        document.addEventListener('styleChanged', (e) => {
            this.trackEvent('STYLE', {
                action: 'MODIFIED',
                targetType: e.detail.type,
                targetId: e.detail.id,
                styles: e.detail.styles
            });
        });

        // Eventos de filtrado
        document.addEventListener('filterApplied', (e) => {
            this.trackEvent('FILTER', {
                action: 'APPLIED',
                criteria: e.detail?.filters || 'spatial'
            });
        });

        document.addEventListener('filterCleared', () => {
            this.trackEvent('FILTER', {
                action: 'CLEARED'
            });
        });

        // Eventos de estado
        document.addEventListener('stateSaved', (e) => {
            this.trackEvent('STATE', {
                action: 'SAVED',
                stateId: e.detail.stateId
            });
        });

        document.addEventListener('stateLoaded', (e) => {
            this.trackEvent('STATE', {
                action: 'LOADED',
                stateId: e.detail.stateId
            });
        });

        // Eventos de navegación
        const map = document.getElementById('map');
        if (map) {
            map.addEventListener('moveend', () => {
                this.trackEvent('NAVIGATION', {
                    action: 'MAP_MOVED',
                    center: map.getCenter(),
                    zoom: map.getZoom()
                });
            });
        }
    }

    trackEvent(type, data) {
        if (!this.isEnabled || !this.currentSession) return;

        const event = {
            type,
            timestamp: new Date().toISOString(),
            data
        };

        this.currentSession.events.push(event);
        this.saveToStorage();

        // Disparar evento para listeners externos
        window.dispatchEvent(new CustomEvent('trackingEvent', { 
            detail: event 
        }));
    }

    saveToStorage() {
        try {
            const sessions = this.getSessions();
            const sessionIndex = sessions.findIndex(s => s.id === this.currentSession.id);

            if (sessionIndex >= 0) {
                sessions[sessionIndex] = this.currentSession;
            } else {
                sessions.push(this.currentSession);
            }

            // Mantener solo las últimas 10 sesiones
            while (sessions.length > 10) {
                sessions.shift();
            }

            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(sessions));
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'TrackingManager.saveToStorage');
        }
    }

    getSessions() {
        try {
            const stored = localStorage.getItem(this.STORAGE_KEY);
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'TrackingManager.getSessions');
            return [];
        }
    }

    getCurrentSession() {
        return this.currentSession;
    }

    getSessionById(sessionId) {
        const sessions = this.getSessions();
        return sessions.find(s => s.id === sessionId);
    }

    enable() {
        this.isEnabled = true;
    }

    disable() {
        this.isEnabled = false;
    }

    clearHistory() {
        try {
            localStorage.removeItem(this.STORAGE_KEY);
            this.startSession();
            return true;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'TrackingManager.clearHistory');
            return false;
        }
    }

    exportHistory() {
        try {
            const sessions = this.getSessions();
            const blob = new Blob(
                [JSON.stringify(sessions, null, 2)], 
                { type: 'application/json' }
            );
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `metrix_tracking_${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
            return true;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'TrackingManager.exportHistory');
            return false;
        }
    }

    getEventsByType(type) {
        if (!this.currentSession) return [];
        return this.currentSession.events.filter(e => e.type === type);
    }

    getEventsSummary() {
        if (!this.currentSession) return {};
        
        return this.currentSession.events.reduce((summary, event) => {
            if (!summary[event.type]) {
                summary[event.type] = 0;
            }
            summary[event.type]++;
            return summary;
        }, {});
    }
}

export const trackingManager = new TrackingManager();