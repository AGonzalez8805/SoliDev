// En haut de DashboardUser.js
import * as bootstrap from 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.esm.min.js';

export class DashboardUser {
    constructor() {
        console.log("DashboardUser initialisé");

        // Éléments de profil
        this.photoForm = document.getElementById("photoForm");
        this.photoInput = document.getElementById("photoInput");
        this.photoImg = document.querySelector(".profile-photo-header");
        this.profileForm = document.getElementById("profileForm");
        this.userFullName = document.getElementById("userFullName");
        this.toastEl = document.getElementById("profileToast");
        this.toastMessage = document.getElementById("toastMessage");

        // Éléments pour le changement de mot de passe
        this.passwordForm = document.getElementById("passwordForm");
        this.newPasswordInput = document.getElementById("newPassword");
        this.confirmPasswordInput = document.getElementById("confirmPassword");
        this.strengthBar = document.getElementById("passwordStrengthBar");
        this.strengthText = document.getElementById("passwordStrengthText");

        // Éléments pour les notifications
        this.markAllAsReadBtn = document.getElementById("markAllAsRead");
        this.notificationsList = document.getElementById("notificationsList");

        // Éléments pour les préférences
        this.notificationSettings = document.querySelectorAll(".notification-setting");
        this.privacySettings = document.querySelectorAll(".privacy-setting");
        this.preferenceSettings = document.querySelectorAll(".preference-setting");

        // Éléments pour les favoris
        this.favoriteButtons = document.querySelectorAll(".favorite-btn");

        // Actions dangereuses
        this.deactivateBtn = document.getElementById("deactivateAccount");
        this.deleteBtn = document.getElementById("deleteAccount");

        // Liens sociaux dans le header
        this.socialLinks = {
            github: document.querySelector('.github-link'),
            linkedin: document.querySelector('.linkedin-link'),
            website: document.querySelector('.website-link')
        };

        this.init();
    }

    init() {
        // Photo de profil
        if (this.photoInput) {
            this.photoInput.addEventListener("change", () => this.handlePhoto());
        }

        // Formulaire de profil
        if (this.profileForm) {
            this.profileForm.addEventListener("submit", e => this.handleProfileSubmit(e));
        }

        // Formulaire de mot de passe
        if (this.passwordForm) {
            this.initPasswordForm();
        }

        // Toggle password visibility
        this.initPasswordToggles();

        // Notifications
        this.initNotifications();

        // Préférences
        this.initPreferences();

        // Favoris
        this.initFavorites();

        // Actions dangereuses
        this.initDangerActions();

        // Onglets
        this.initTabListeners();

        // Appliquer le thème au chargement
        this.initTheme();
    }

    // ==================== PHOTO DE PROFIL ====================
    async handlePhoto() {
        if (!this.photoInput.files.length) return;

        const file = this.photoInput.files[0];

        // Validation du fichier
        if (!file.type.startsWith("image/")) {
            return this.showToast("Veuillez sélectionner une image valide.", false);
        }

        if (file.size > 5 * 1024 * 1024) {
            return this.showToast("Image trop lourde (max 5 Mo).", false);
        }

        const formData = new FormData(this.photoForm);

        try {
            const res = await fetch(this.photoForm.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await res.json();

            if (data.success && data.photo) {
                // Mettre à jour l'image avec un timestamp pour éviter le cache
                this.photoImg.src = data.photo + "?t=" + Date.now();
                this.photoImg.style.display = "block";
                this.showToast("Photo de profil mise à jour avec succès !");
            } else {
                this.showToast(data.message || "Erreur lors de la mise à jour de la photo", false);
            }
        } catch (err) {
            console.error('Erreur photo:', err);
            this.showToast("Erreur réseau lors de l'upload", false);
        }
    }

    // ==================== FORMULAIRE DE PROFIL ====================
    async handleProfileSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this.profileForm);

        // Validation de l'email
        const email = formData.get("email");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            return this.showToast("Adresse email invalide", false);
        }

        try {
            const res = await fetch(this.profileForm.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await res.json();

            if (data.success) {
                this.showToast("Profil mis à jour avec succès !");

                // Mettre à jour le nom affiché
                if (this.userFullName && data.newFirstName && data.newName) {
                    const firstName = data.newFirstName.charAt(0).toUpperCase() + data.newFirstName.slice(1);
                    const name = data.newName.charAt(0).toUpperCase() + data.newName.slice(1);
                    this.userFullName.innerHTML = `<strong>${firstName} ${name}</strong>`;
                }

                // Mettre à jour les liens sociaux si nécessaire
                this.updateSocialLinks(formData);
            } else {
                this.showToast(data.message || "Erreur lors de la mise à jour du profil", false);
            }
        } catch (err) {
            console.error('Erreur profil:', err);
            this.showToast("Erreur réseau", false);
        }
    }

    updateSocialLinks(formData) {
        const github = formData.get('github_url');
        const linkedin = formData.get('linkedin_url');
        const website = formData.get('website_url');

        if (this.socialLinks.github) {
            if (github) {
                this.socialLinks.github.href = github;
                this.socialLinks.github.classList.remove('disabled');
            } else {
                this.socialLinks.github.href = '#';
                this.socialLinks.github.classList.add('disabled');
            }
        }

        if (this.socialLinks.linkedin) {
            if (linkedin) {
                this.socialLinks.linkedin.href = linkedin;
                this.socialLinks.linkedin.classList.remove('disabled');
            } else {
                this.socialLinks.linkedin.href = '#';
                this.socialLinks.linkedin.classList.add('disabled');
            }
        }

        if (this.socialLinks.website) {
            if (website) {
                this.socialLinks.website.href = website;
                this.socialLinks.website.classList.remove('disabled');
            } else {
                this.socialLinks.website.href = '#';
                this.socialLinks.website.classList.add('disabled');
            }
        }
    }

    // ==================== MOT DE PASSE ====================
    initPasswordForm() {
        // Indicateur de force du mot de passe
        if (this.newPasswordInput) {
            this.newPasswordInput.addEventListener("input", () => {
                const strength = this.calculatePasswordStrength(this.newPasswordInput.value);
                this.updateStrengthBar(strength);
            });
        }

        // Validation de la confirmation
        if (this.confirmPasswordInput) {
            this.confirmPasswordInput.addEventListener("input", () => {
                if (this.confirmPasswordInput.value &&
                    this.confirmPasswordInput.value !== this.newPasswordInput.value) {
                    this.confirmPasswordInput.classList.add("is-invalid");
                } else {
                    this.confirmPasswordInput.classList.remove("is-invalid");
                }
            });
        }

        // Soumission
        this.passwordForm.addEventListener("submit", e => this.handlePasswordSubmit(e));
    }

    initPasswordToggles() {
        const toggleButtons = [
            { btn: 'toggleCurrentPassword', input: 'currentPassword' },
            { btn: 'toggleNewPassword', input: 'newPassword' },
            { btn: 'toggleConfirmPassword', input: 'confirmPassword' }
        ];

        for (const item of toggleButtons) {
            const btn = document.getElementById(item.btn);
            if (btn) {
                btn.addEventListener('click', () => {
                    const input = document.getElementById(item.input);
                    const icon = btn.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
        }
    }

    calculatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[A-Z]/.test(password)) strength += 20;
        if (/\d/.test(password)) strength += 10;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 10;

        return strength;
    }

    updateStrengthBar(strength) {
        if (!this.strengthBar || !this.strengthText) return;

        this.strengthBar.style.width = strength + '%';

        if (strength < 40) {
            this.strengthBar.className = 'password-strength-bar weak';
            this.strengthText.textContent = 'Mot de passe faible';
            this.strengthText.style.color = '#dc3545';
        } else if (strength < 70) {
            this.strengthBar.className = 'password-strength-bar medium';
            this.strengthText.textContent = 'Mot de passe moyen';
            this.strengthText.style.color = '#ffc107';
        } else {
            this.strengthBar.className = 'password-strength-bar strong';
            this.strengthText.textContent = 'Mot de passe fort';
            this.strengthText.style.color = '#28a745';
        }
    }

    async handlePasswordSubmit(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = this.newPasswordInput.value;
        const confirmPassword = this.confirmPasswordInput.value;

        // Validation côté client
        if (newPassword !== confirmPassword) {
            this.showToast('Les mots de passe ne correspondent pas', false);
            return;
        }

        if (newPassword.length < 8) {
            this.showToast('Le mot de passe doit contenir au moins 8 caractères', false);
            return;
        }

        try {
            const res = await fetch('/?controller=user&action=changePassword', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    currentPassword: currentPassword,
                    newPassword: newPassword,
                    confirmPassword: confirmPassword
                })
            });

            const data = await res.json();

            if (data.success) {
                this.showToast(data.message || 'Mot de passe modifié avec succès', true);
                this.passwordForm.reset();

                // Réinitialiser la barre de force
                if (this.strengthBar) {
                    this.strengthBar.style.width = '0%';
                    this.strengthBar.className = 'password-strength-bar';
                }
                if (this.strengthText) {
                    this.strengthText.textContent = 'Entrez un nouveau mot de passe (min. 8 caractères)';
                    this.strengthText.style.color = '';
                }

                // Réinitialiser les toggles
                for (const input of document.querySelectorAll('#passwordForm input[type="text"]')) {
                    input.type = 'password';
                }
                for (const icon of document.querySelectorAll('#passwordForm .fa-eye-slash')) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            } else {
                this.showToast(data.message || 'Erreur lors du changement de mot de passe', false);
            }
        } catch (err) {
            console.error('Erreur:', err);
            this.showToast('Une erreur est survenue', false);
        }
    }

    // ==================== NOTIFICATIONS ====================
    initTabListeners() {
        const notifTab = document.querySelector('[data-bs-target="#notifications-tab"]');
        if (notifTab) {
            notifTab.addEventListener('shown.bs.tab', () => {
                this.loadNotifications();
            });
        }
    }

    initNotifications() {
        // Marquer toutes comme lues
        if (this.markAllAsReadBtn) {
            this.markAllAsReadBtn.addEventListener('click', () => this.markAllNotificationsAsRead());
        }

        // Clic sur notification individuelle
        const notifItems = document.querySelectorAll('.notification-item');
        notifItems.forEach(item => {
            item.addEventListener('click', () => {
                const notifId = item.dataset.notificationId;
                if (item.classList.contains('unread')) {
                    this.markNotificationAsRead(notifId, item);
                }
            });
        });

        // Changement des paramètres de notification
        this.notificationSettings.forEach(input => {
            input.addEventListener('change', (e) => {
                const preference = e.target.dataset.preference;
                const value = e.target.checked;
                this.updatePreference({ [preference]: value });
            });
        });
    }

    async loadNotifications() {
        try {
            const res = await fetch('/?controller=user&action=getNotifications', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.success && data.notifications) {
                this.renderNotifications(data.notifications);
            }
        } catch (err) {
            console.error('Erreur chargement notifications:', err);
        }
    }

    renderNotifications(notifications) {
        if (!this.notificationsList) return;

        if (notifications.length === 0) {
            this.notificationsList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                    <p>Aucune notification pour le moment</p>
                </div>
            `;
            return;
        }

        this.notificationsList.innerHTML = notifications.map(notif => {
            const icon = this.getNotificationIcon(notif.type);
            const timeAgo = this.getTimeAgo(notif.created_at);
            const unreadClass = notif.is_read ? '' : 'unread';

            return `
                <div class="notification-item ${unreadClass}" data-notification-id="${notif.id}">
                    <div class="notification-icon">
                        <i class="${icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div>${this.escapeHtml(notif.message)}</div>
                        <small class="text-muted">${timeAgo}</small>
                    </div>
                </div>
            `;
        }).join('');

        // Réattacher les événements
        this.notificationsList.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', () => {
                const notifId = item.dataset.notificationId;
                if (item.classList.contains('unread')) {
                    this.markNotificationAsRead(notifId, item);
                }
            });
        });
    }

    getNotificationIcon(type) {
        const icons = {
            'like': 'fas fa-heart',
            'comment': 'fas fa-comment',
            'featured': 'fas fa-star',
            'follow': 'fas fa-user-plus',
            'forum': 'fas fa-comments',
            'blog': 'fas fa-blog',
            'project': 'fas fa-project-diagram',
            'snippet': 'fas fa-code'
        };
        return icons[type] || 'fas fa-bell';
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffDays > 0) return `Il y a ${diffDays} jour${diffDays > 1 ? 's' : ''}`;
        if (diffHours > 0) return `Il y a ${diffHours} heure${diffHours > 1 ? 's' : ''}`;
        if (diffMins > 0) return `Il y a ${diffMins} minute${diffMins > 1 ? 's' : ''}`;
        return "À l'instant";
    }

    async markNotificationAsRead(notifId, element) {
        try {
            const res = await fetch('/?controller=user&action=markNotificationAsRead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ notificationId: notifId })
            });

            const data = await res.json();
            if (data.success && element) {
                element.classList.remove('unread');
            }
        } catch (err) {
            console.error('Erreur:', err);
        }
    }

    async markAllNotificationsAsRead() {
        try {
            const res = await fetch('/?controller=user&action=markAllNotificationsAsRead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();
            if (data.success) {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                this.showToast(data.message || 'Toutes les notifications sont marquées comme lues', true);
            }
        } catch (err) {
            console.error('Erreur:', err);
            this.showToast('Erreur lors de la mise à jour', false);
        }
    }

    // ==================== PRÉFÉRENCES ====================
    initPreferences() {
        // Paramètres de confidentialité
        this.privacySettings.forEach(input => {
            input.addEventListener('change', (e) => {
                const preference = e.target.dataset.preference;
                const value = e.target.checked;
                this.updatePreference({ [preference]: value });
            });
        });

        // Paramètres d'apparence (mode sombre, etc.)
        this.preferenceSettings.forEach(input => {
            input.addEventListener('change', (e) => {
                const preference = e.target.dataset.preference;
                const valueOn = e.target.dataset.valueOn;
                const valueOff = e.target.dataset.valueOff;
                const value = e.target.checked ? valueOn : valueOff;

                // Application immédiate du thème
                if (preference === 'theme') {
                    this.applyTheme(value);
                }

                this.updatePreference({ [preference]: value });
            });
        });
    }

    initTheme() {
        const darkModeToggle = document.getElementById('darkMode');
        if (darkModeToggle) {
            // Appliquer le thème enregistré
            const savedTheme = darkModeToggle.checked ? 'dark' : 'light';
            this.applyTheme(savedTheme);
        }
    }

    async updatePreference(preferenceData) {
        // Convertir les booléens en entiers pour MySQL
        const convertedData = {};
        for (const [key, value] of Object.entries(preferenceData)) {
            if (typeof value === 'boolean') {
                convertedData[key] = value ? 1 : 0;
            } else {
                convertedData[key] = value;
            }
        }

        try {
            const res = await fetch('/?controller=user&action=updatePreferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(convertedData)
            });

            const data = await res.json();
            if (data.success) {
                this.showToast('Préférence mise à jour avec succès', true);
            } else {
                this.showToast(data.message || 'Erreur lors de la mise à jour', false);
            }
        } catch (err) {
            console.error('Erreur:', err);
            this.showToast('Erreur réseau', false);
        }
    }

    applyTheme(theme) {
        const html = document.documentElement;
        const body = document.body;

        if (theme === 'dark') {
            html.classList.add('dark-theme');
            body.classList.add('dark-theme');
        } else {
            html.classList.remove('dark-theme');
            body.classList.remove('dark-theme');
        }

        // Sauvegarder localement
        localStorage.setItem('theme', theme);

        // Mettre à jour l'icône dans la navbar si elle existe
        const themeIcon = document.getElementById('themeIcon');
        if (themeIcon) {
            themeIcon.classList.remove('fa-moon', 'fa-sun');
            themeIcon.classList.add(theme === 'dark' ? 'fa-sun' : 'fa-moon');
        }
    }

    // ==================== FAVORIS ====================
    initFavorites() {
        this.favoriteButtons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                const snippetId = btn.dataset.snippetId;
                const isActive = btn.classList.contains('active');

                try {
                    const res = await fetch('/?controller=user&action=toggleFavorite', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ snippetId })
                    });

                    const data = await res.json();
                    if (data.success) {
                        if (isActive) {
                            // Retirer des favoris - supprimer la carte
                            const card = btn.closest('.col-lg-6, .col-xl-4');
                            if (card) {
                                card.remove();

                                // Vérifier s'il reste des favoris
                                const remainingCards = document.querySelectorAll('#favorites-tab .snippet-card').length;
                                if (remainingCards === 0) {
                                    this.showNoFavoritesMessage();
                                }
                            }
                            this.showToast(data.message || "Retiré des favoris", true);
                        } else {
                            btn.classList.add('active');
                            this.showToast(data.message || "Ajouté aux favoris", true);
                        }
                    } else {
                        this.showToast(data.message || "Erreur favoris", false);
                    }
                } catch (err) {
                    console.error(err);
                    this.showToast("Erreur réseau", false);
                }
            });
        });
    }

    showNoFavoritesMessage() {
        const favoritesTab = document.querySelector('#favorites-tab .row');
        if (favoritesTab) {
            favoritesTab.innerHTML = `
                <div class="col-12">
                    <div class="no-snippets">
                        <i class="fas fa-heart-broken"></i>
                        <h3>Vous n'avez aucun favori</h3>
                        <p>Ajoutez des snippets à vos favoris pour les retrouver ici !</p>
                        <a href="/?controller=snippets&action=snippets" class="btn-add-snippet">
                            <i class="fas fa-code me-2"></i>Voir tous les snippets
                        </a>
                    </div>
                </div>
            `;
        }
    }

    // ==================== ACTIONS DANGEREUSES ====================
    initDangerActions() {
        if (this.deactivateBtn) {
            this.deactivateBtn.addEventListener('click', async () => {
                if (!confirm("Êtes-vous sûr de vouloir désactiver votre compte ? Vous pourrez le réactiver plus tard.")) {
                    return;
                }

                try {
                    const res = await fetch('/?controller=user&action=deactivateAccount', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    if (data.success) {
                        this.showToast("Compte désactivé avec succès", true);
                        setTimeout(() => window.location.href = '/', 1500);
                    } else {
                        this.showToast(data.message || "Erreur lors de la désactivation", false);
                    }
                } catch (err) {
                    console.error(err);
                    this.showToast("Erreur réseau", false);
                }
            });
        }

        if (this.deleteBtn) {
            this.deleteBtn.addEventListener('click', async () => {
                const confirmation = confirm(
                    "⚠️ ATTENTION ⚠️\n\n" +
                    "Êtes-vous absolument sûr de vouloir supprimer définitivement votre compte ?\n\n" +
                    "Cette action est IRRÉVERSIBLE et supprimera :\n" +
                    "- Votre profil\n" +
                    "- Tous vos posts\n" +
                    "- Tous vos commentaires\n" +
                    "- Tous vos snippets\n" +
                    "- Tous vos projets\n\n" +
                    "Tapez OUI dans le prochain message pour confirmer."
                );

                if (!confirmation) return;

                const finalConfirmation = prompt("Tapez 'OUI' en majuscules pour confirmer la suppression définitive :");

                if (finalConfirmation !== 'OUI') {
                    this.showToast("Suppression annulée", false);
                    return;
                }

                try {
                    const res = await fetch('/?controller=user&action=deleteAccount', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();

                    if (data.success) {
                        this.showToast("Compte supprimé définitivement", true);
                        setTimeout(() => window.location.href = '/', 1500);
                    } else {
                        this.showToast(data.message || "Erreur lors de la suppression", false);
                    }
                } catch (err) {
                    console.error(err);
                    this.showToast("Erreur réseau", false);
                }
            });
        }
    }

    // ==================== UTILITAIRES ====================
    showToast(message, success = true) {
        if (!this.toastEl || !this.toastMessage) return;

        this.toastEl.classList.remove("text-bg-success", "text-bg-danger");
        this.toastEl.classList.add(success ? "text-bg-success" : "text-bg-danger");
        this.toastMessage.textContent = message;

        const toast = new bootstrap.Toast(this.toastEl);
        toast.show();
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}