import { API_CONFIG } from './config.js';

class PointsAPI {
    async getAllPoints() {
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.points.get}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching points:', error);
            throw error;
        }
    }

    async getPointsByState(stateId) {
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.points.getByState(stateId)}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching points for state:', error);
            throw error;
        }
    }
}

export const pointsAPI = new PointsAPI();