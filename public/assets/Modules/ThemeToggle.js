export class ThemeToggle {
    constructor() {
        this.themeToggleBtn = document.getElementById('themeToggle');
        this.themeIcon = document.getElementById('themeIcon');

        this.init();
    }

    init() {
        if (!this.themeToggleBtn) return;

        // Charger le thème sauvegardé au démarrage
        this.loadSavedTheme();

        // Écouter les clics sur le bouton
        this.themeToggleBtn.addEventListener('click', () => {
            this.toggleTheme();
        });
    }

    loadSavedTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.applyTheme(savedTheme);
    }

    toggleTheme() {
        const currentTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        this.applyTheme(newTheme);
        this.saveThemeToServer(newTheme);
    }

    applyTheme(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
            if (this.themeIcon) {
                this.themeIcon.classList.remove('fa-moon');
                this.themeIcon.classList.add('fa-sun');
            }
        } else {
            document.body.classList.remove('dark-theme');
            if (this.themeIcon) {
                this.themeIcon.classList.remove('fa-sun');
                this.themeIcon.classList.add('fa-moon');
            }
        }

        // Sauvegarder dans localStorage
        localStorage.setItem('theme', theme);

        // Mettre à jour le toggle dans le dashboard si présent
        const darkModeToggle = document.getElementById('darkMode');
        if (darkModeToggle) {
            darkModeToggle.checked = theme === 'dark';
        }
    }

    async saveThemeToServer(theme) {
        // Si l'utilisateur est connecté, sauvegarder en base de données
        try {
            const res = await fetch('/?controller=user&action=updatePreferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ theme: theme })
            });

            const data = await res.json();
            if (!data.success) {
                console.log('Thème sauvegardé localement uniquement');
            }
        } catch (err) {
            // L'utilisateur n'est probablement pas connecté, on garde juste en localStorage
            console.error('Erreur lors de la sauvegarde du thème sur le serveur :', err);
        }
    }
}