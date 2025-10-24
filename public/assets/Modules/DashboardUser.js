export class DashboardUser {
    constructor() {
        console.log("DashboardUser initialisé");

        this.photoForm = document.getElementById("photoForm");
        this.photoInput = document.getElementById("photoInput");
        this.photoImg = document.querySelector(".profile-photo-header");
        this.profileForm = document.getElementById("profileForm");
        this.userFullName = document.getElementById("userFullName");
        this.toastEl = document.getElementById("profileToast");
        this.toastMessage = document.getElementById("toastMessage");

        // Nouveaux éléments pour le changement de mot de passe
        this.passwordForm = document.getElementById("passwordForm");
        this.newPasswordInput = document.getElementById("newPassword");
        this.confirmPasswordInput = document.getElementById("confirmPassword");
        this.strengthBar = document.getElementById("passwordStrengthBar");
        this.strengthText = document.getElementById("passwordStrengthText");

        this.init();
    }

    init() {
        if (this.photoInput) {
            this.photoInput.addEventListener("change", () => this.handlePhoto());
        }

        if (this.profileForm) {
            this.profileForm.addEventListener("submit", e => this.handleProfileSubmit(e));
        }

        // Initialisation du formulaire de mot de passe
        if (this.passwordForm) {
            this.initPasswordForm();
        }

        // Initialisation des boutons toggle pour afficher/masquer les mots de passe
        this.initPasswordToggles();
    }

    initPasswordForm() {
        // Indicateur de force du mot de passe
        if (this.newPasswordInput) {
            this.newPasswordInput.addEventListener("input", () => {
                const strength = this.calculatePasswordStrength(this.newPasswordInput.value);
                this.updateStrengthBar(strength);
            });
        }

        // Validation de la confirmation du mot de passe
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

        // Soumission du formulaire
        this.passwordForm.addEventListener("submit", e => this.handlePasswordSubmit(e));
    }

    initPasswordToggles() {
        const toggleButtons = [
            { btn: 'toggleCurrentPassword', input: 'currentPassword' },
            { btn: 'toggleNewPassword', input: 'newPassword' },
            { btn: 'toggleConfirmPassword', input: 'confirmPassword' }
        ];

        toggleButtons.forEach(item => {
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
        });
    }

    calculatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[0-9]/.test(password)) strength += 10;
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
                this.showToast(data.message, true);
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
                document.querySelectorAll('#passwordForm input[type="text"]').forEach(input => {
                    input.type = 'password';
                });
                document.querySelectorAll('#passwordForm .fa-eye-slash').forEach(icon => {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                });
            } else {
                this.showToast(data.message, false);
            }
        } catch (err) {
            console.error('Erreur:', err);
            this.showToast('Une erreur est survenue', false);
        }
    }

    showToast(message, success = true) {
        if (!this.toastEl) return;
        this.toastEl.classList.remove("text-bg-success", "text-bg-danger");
        this.toastEl.classList.add(success ? "text-bg-success" : "text-bg-danger");
        this.toastMessage.textContent = message;

        const toast = new bootstrap.Toast(this.toastEl);
        toast.show();
    }

    async handlePhoto() {
        if (!this.photoInput.files.length) return;

        const file = this.photoInput.files[0];
        if (!file.type.startsWith("image/")) return this.showToast("Veuillez sélectionner une image valide.", false);
        if (file.size > 5 * 1024 * 1024) return this.showToast("Image trop lourde (max 5 Mo).", false);

        const formData = new FormData(this.photoForm);
        try {
            const res = await fetch(this.photoForm.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const data = await res.json();
            if (data.success && data.photo) {
                this.photoImg.src = data.photo + "?t=" + new Date().getTime();
                this.photoImg.style.display = "block";
                this.showToast("Photo mise à jour !");
            } else {
                this.showToast(data.message || "Erreur photo", false);
            }
        } catch (err) {
            console.error(err);
            this.showToast("Erreur réseau", false);
        }
    }

    async handleProfileSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.profileForm);
        const email = formData.get("email");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) return this.showToast("Email invalide", false);

        try {
            const res = await fetch(this.profileForm.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const data = await res.json();

            if (data.success) {
                this.showToast("Profil mis à jour !");
                if (this.userFullName) {
                    this.userFullName.textContent = `${data.newFirstName} ${data.newName}`;
                }
            } else {
                this.showToast(data.message || "Erreur profil", false);
            }
        } catch (err) {
            console.error(err);
            this.showToast("Erreur réseau", false);
        }
    }
}