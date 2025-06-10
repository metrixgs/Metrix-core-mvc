import { ErrorHandler } from '../core/ErrorHandler.js';

export const API_CONFIG = {
    baseUrl: 'https://espacialhn.com/slim4/api',
    endpoints: {
        states: {
            get: '/states',
            getById: (id) => `/states/${id}`,
            create: '/states',
            delete: (id) => `/states/${id}`
        },
        points: {
            get: '/points',
            getByState: (stateId) => `/states/${stateId}/points`
        }
    },
    handleResponse: async (response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    },
    handleError: (error, context) => {
        console.error(`API Error (${context}):`, error);
        return ErrorHandler.handleAPIError(error, context);
    }
};