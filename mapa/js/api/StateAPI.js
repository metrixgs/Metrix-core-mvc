import { API_CONFIG } from './config.js';
import { StateManagerUtils } from '../core/StateManagerUtils.js';

class StateAPI {
    async getAllStates() {
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.states.get}`);
            return await API_CONFIG.handleResponse(response);
        } catch (error) {
            return API_CONFIG.handleError(error, 'getAllStates');
        }
    }

    async getStateById(stateId) {
        try {
            StateManagerUtils.showLoadingIndicator();
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.states.getById(stateId)}`);
            const data = await API_CONFIG.handleResponse(response);
            return StateManagerUtils.validateStateResponse(data) ? data : null;
        } catch (error) {
            return API_CONFIG.handleError(error, 'getStateById');
        } finally {
            StateManagerUtils.hideLoadingIndicator();
        }
    }

    async createState(stateData) {
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.states.create}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(stateData)
            });
            return await API_CONFIG.handleResponse(response);
        } catch (error) {
            return API_CONFIG.handleError(error, 'createState');
        }
    }

    async deleteState(stateId) {
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.states.delete(stateId)}`, {
                method: 'DELETE'
            });
            return await API_CONFIG.handleResponse(response);
        } catch (error) {
            return API_CONFIG.handleError(error, 'deleteState');
        }
    }
}

export const stateAPI = new StateAPI();