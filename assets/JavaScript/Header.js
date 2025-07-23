class Header {
    constructor() {
        document.addEventListener('DOMContentLoaded', async () => {
            await this.init();
        });
    }

    async init() {
        this.navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        // Normalisation du chemin actuel
        this.currentPath = this.normalizePath(window.location.pathname + window.location.search);

        this.navLinks.forEach(link => {
            link.classList.remove('active');
            link.removeAttribute('aria-current');

            // Normalisation du lien cible
            const linkUrl = this.normalizePath(new URL(link.href, window.location.origin).pathname + new URL(link.href).search);

            if (linkUrl === this.currentPath) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            }
        });
    }

    normalizePath(path) {
        // Supprime trailing slash (sauf "/") et force une casse uniforme
        return path.replace(/\/+$/, '').toLowerCase();
    }
}

export { Header };
