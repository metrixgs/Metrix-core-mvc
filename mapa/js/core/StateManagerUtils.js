// Utility functions for state management
export const StateManagerUtils = {
    debounceTimeout: null,

    debounce(func, wait = 1000) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(func, wait);
    },

    showLoadingIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'stateLoadingIndicator';
        indicator.className = 'state-loading-indicator';
        indicator.innerHTML = '<span>Cargando estado...</span>';
        document.body.appendChild(indicator);
    },

    hideLoadingIndicator() {
        const indicator = document.getElementById('stateLoadingIndicator');
        if (indicator) {
            indicator.remove();
        }
    },

    handleApiError(error, fallback) {
        console.error('API Error:', error);
        if (fallback && typeof fallback === 'function') {
            return fallback();
        }
        return null;
    },

    validateStateResponse(state) {
        if (!state) return false;
        
        const requiredFields = ['sessionId', 'timestamp', 'points', 'viewport'];
        return requiredFields.every(field => field in state);
    }
};