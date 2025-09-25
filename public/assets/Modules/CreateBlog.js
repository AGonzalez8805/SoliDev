// CreateBlog.js
export class CreateBlog {
    constructor() {
        console.log("CreateBlog initialisé");
        this.form = document.getElementById("articleForm");
        if (!this.form) return;

        this.editor = document.getElementById("editor");
        this.titleInput = document.getElementById("title");
        this.previewTitle = document.getElementById("previewTitle");
        this.previewContent = document.getElementById("previewContent");

        // Nouveaux éléments pour l’aperçu
        this.previewContainer = document.getElementById("previewContainer");
        this.previewDate = document.getElementById("previewDate");
        this.previewCategory = document.getElementById("previewCategory");
        this.categorySelect = document.getElementById("category");
        this.excerptInput = document.getElementById("excerpt");
        this.previewBtn = document.getElementById("previewBtn");

        // Gestion de l'image
        this.imageUpload = document.getElementById("imageUpload");
        this.coverInput = document.getElementById("coverImage");
        this.imagePreview = document.getElementById("imagePreview");

        // Créer un textarea caché pour envoyer le contenu
        this.hiddenContent = document.createElement("textarea");
        this.hiddenContent.name = "content";
        this.hiddenContent.style.display = "none";
        this.form.appendChild(this.hiddenContent);

        this.buttons = document.querySelectorAll(".editor-btn");

        this.init();
    }

    init() {
        this.initEditorButtons();
        this.initLivePreview();
        this.initFormSubmit();
        this.initImageUpload();
        this.initPreviewButton();
        this.initSaveDraft();
    }

    // Gestion des boutons de l'éditeur
    initEditorButtons() {
        this.buttons.forEach(btn => {
            btn.addEventListener("click", () => {
                const command = btn.dataset.command;
                const value = btn.dataset.value || null;

                if (command === "createLink" || command === "insertImage") {
                    const url = prompt("Entrez l'URL :");
                    if (url) document.execCommand(command, false, url);
                } else if (command === "formatBlock" && value) {
                    document.execCommand(command, false, value);
                } else {
                    document.execCommand(command, false, null);
                }
            });
        });
    }

    // Aperçu en temps réel (titre + contenu uniquement)
    initLivePreview() {
        this.editor.addEventListener("input", () => {
            this.previewContent.innerHTML = this.editor.innerHTML;
        });

        this.titleInput.addEventListener("input", () => {
            this.previewTitle.textContent = this.titleInput.value || "Titre de l'article";
        });
    }

    // Bouton Aperçu (rend complet avec date, catégorie, extrait)
    initPreviewButton() {
        if (!this.previewBtn) return;

        this.previewBtn.addEventListener("click", () => {
            // Récupérer les valeurs
            const title = this.titleInput.value.trim() || "Titre de l'article";
            const excerpt = this.excerptInput.value.trim();
            const category = this.categorySelect.value || "Catégorie";
            const content = this.editor.innerHTML.trim() || "Contenu de l'article...";

            const today = new Date();
            const date = today.toLocaleDateString("fr-FR", {
                day: "numeric",
                month: "long",
                year: "numeric"
            });

            // Stocker dans localStorage
            const previewData = { title, excerpt, category, content, date };
            localStorage.setItem("previewArticle", JSON.stringify(previewData));

            // Ouvrir la page preview
            window.open("/?controller=blog&action=preview", "_blank");
        });
    }

    // Copier le contenu dans le textarea caché avant l'envoi
    initFormSubmit() {
        this.form.addEventListener("submit", () => {
            this.hiddenContent.value = this.editor.innerHTML.trim();
        });
    }

    // Gestion de l'image de couverture
    initImageUpload() {
        this.imageUpload.addEventListener("click", () => {
            this.coverInput.click();
        });

        this.coverInput.addEventListener("change", () => {
            const file = this.coverInput.files[0];
            if (!file) return;

            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert("Le fichier est trop volumineux (max 2MB).");
                this.coverInput.value = "";
                this.imagePreview.innerHTML = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview.innerHTML = `<img src="${e.target.result}" alt="Image de couverture" style="max-width:100%; height:auto; border-radius:5px;">`;
            };
            reader.readAsDataURL(file);
        });
    }

    // Méthode pour sauvegarder le brouillon automatiquement
    initSaveDraft() {
        const saveBtn = document.getElementById("saveDraftBtn");
        if (!saveBtn) return;

        saveBtn.addEventListener("click", async () => {
            const formData = new FormData(this.form);
            // Forcer le status en draft
            formData.set("status", "draft");
            // Ajouter le contenu de l'éditeur
            formData.set("content", this.editor.innerHTML.trim());

            try {
                const response = await fetch("/?controller=blog&action=store", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    const statusEl = document.getElementById("autosaveStatus");
                    statusEl.textContent = "Brouillon sauvegardé à l'instant";
                    statusEl.classList.add("text-success");
                } else {
                    alert("Erreur lors de la sauvegarde du brouillon !");
                }
            } catch (err) {
                console.error(err);
                alert("Impossible de sauvegarder le brouillon.");
            }
        });
    }
}
