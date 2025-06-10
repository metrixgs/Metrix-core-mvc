class ThemeManager {
    constructor() {
        this.init();
    }

    init() {
        // Inicializar tema
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (prefersDark ? 'dark' : 'light');
        
        this.setTheme(initialTheme);

        // Configurar botón de tema
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
            this.updateThemeButton(initialTheme);
        }

        // Escuchar cambios del sistema
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    setTheme(theme) {
        document.body.dataset.theme = theme;
        localStorage.setItem('theme', theme);
        this.updateThemeButton(theme);
    }

    toggleTheme() {
        const currentTheme = document.body.dataset.theme;
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    updateThemeButton(theme) {
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const icon = themeToggle.querySelector('.icon');
            if (icon) {
                icon.textContent = theme === 'dark' ? '☀️' : '🌙';
            }
        }
    }

    getCurrentTheme() {
        return document.body.dataset.theme || 'light';
    }
}

export const themeManager = new ThemeManager();