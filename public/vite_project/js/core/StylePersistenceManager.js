import { ErrorHandler } from './ErrorHandler.js';

class StylePersistenceManager {
    constructor() {
        this.STORAGE_KEY = 'metrix_map_styles';
        this.cache = new Map();
        this.loadFromStorage();
    }

    async loadFromStorage() {
        try {
            const stored = localStorage.getItem(this.STORAGE_KEY);
            if (stored) {
                const data = JSON.parse(stored);
                Object.entries(data).forEach(([key, value]) => {
                    this.cache.set(key, value);
                });
            }
            return true;
        } catch (error) {
            console.error('Error loading styles from storage:', error);
            return false;
        }
    }

    saveToStorage() {
        try {
            const data = Object.fromEntries(this.cache);
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Error saving styles to storage:', error);
            return false;
        }
    }

    getStyleKey(type, id) {
        return `${type}_${id}`;
    }

    saveStyle(type, id, style) {
        try {
            if (!type || !id || !style) {
                throw new Error('Invalid parameters for saveStyle');
            }

            const key = this.getStyleKey(type, id);
            this.cache.set(key, {
                timestamp: Date.now(),
                style: style
            });
            
            this.saveToStorage();
            return true;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'saveStyle');
            return false;
        }
    }

    getStyle(type, id) {
        try {
            const key = this.getStyleKey(type, id);
            const data = this.cache.get(key);
            return data?.style || null;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'getStyle');
            return null;
        }
    }

    deleteStyle(type, id) {
        try {
            const key = this.getStyleKey(type, id);
            const deleted = this.cache.delete(key);
            if (deleted) {
                this.saveToStorage();
            }
            return deleted;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'deleteStyle');
            return false;
        }
    }

    clearAllStyles() {
        try {
            this.cache.clear();
            localStorage.removeItem(this.STORAGE_KEY);
            return true;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'clearAllStyles');
            return false;
        }
    }

    exportStyles() {
        try {
            return {
                version: '1.0',
                timestamp: Date.now(),
                styles: Object.fromEntries(this.cache)
            };
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'exportStyles');
            return null;
        }
    }

    async importStyles(data) {
        try {
            if (!data || !data.styles || data.version !== '1.0') {
                throw new Error('Invalid style data format');
            }

            // Limpiar estilos existentes
            this.cache.clear();

            // Importar nuevos estilos
            Object.entries(data.styles).forEach(([key, value]) => {
                this.cache.set(key, value);
            });
            
            // Guardar en storage
            await this.saveToStorage();

            // Disparar evento de estilos actualizados
            window.dispatchEvent(new CustomEvent('stylesUpdated'));

            return true;
        } catch (error) {
            ErrorHandler.handleAPIError(error, 'importStyles');
            return false;
        }
    }

    getBackup() {
        return {
            data: this.exportStyles(),
            timestamp: new Date().toISOString()
        };
    }

    restoreFromBackup(backup) {
        if (!backup || !backup.data) {
            return false;
        }
        return this.importStyles(backup.data);
    }
}

export const stylePersistenceManager = new StylePersistenceManager();