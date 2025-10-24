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

        this.init();
    }

    init() {
        if (this.photoInput) {
            this.photoInput.addEventListener("change", () => this.handlePhoto());
        }

        if (this.profileForm) {
            this.profileForm.addEventListener("submit", e => this.handleProfileSubmit(e));
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
