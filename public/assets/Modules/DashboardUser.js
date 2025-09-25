export class DashboardUser {
    constructor() {
        console.log("DashboardUser initialisé");
        this.photoForm = document.getElementById("photoForm");
        this.photoInput = document.getElementById("photoInput");

        // Sélectionne l’image si elle existe déjà
        this.photoImg = document.querySelector(".profile-photo-header");

        // Si pas d’image (nouvel utilisateur), on en crée une
        if (!this.photoImg) {
            const img = document.createElement("img");
            img.className = "profile-photo-header";
            img.alt = "Profil";
            img.style.display = "none"; // invisible tant qu’aucune photo
            document.querySelector(".profile-avatar").appendChild(img);
            this.photoImg = img;
        }

        this.init();
    }

    init() {
        if (this.photoInput) {
            this.photoInput.addEventListener("change", () => this.handlePhoto());
        }
    }

    async handlePhoto() {
        if (!this.photoInput.files.length) return;

        const file = this.photoInput.files[0];
        if (!file.type.startsWith("image/")) return alert("Veuillez sélectionner une image.");
        if (file.size > 5 * 1024 * 1024) return alert("Image trop lourde (max 5 Mo).");

        // Aperçu immédiat
        const reader = new FileReader();
        reader.onload = (e) => {
            this.photoImg.style.display = "block";
            this.photoImg.src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Upload via AJAX
        const formData = new FormData(this.photoForm);
        formData.append("field", "photo");

        try {
            const response = await fetch(this.photoForm.action, {
                method: this.photoForm.method,
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await response.json();
            if (data.success && data.photo) {
                // Forcer le refresh pour éviter le cache navigateur
                this.photoImg.src = data.photo + "?t=" + new Date().getTime();
            } else {
                alert("Impossible de mettre à jour la photo.");
            }
        } catch (err) {
            console.error(err);
            alert("Erreur réseau lors de l’envoi de la photo.");
        }
    }
}
