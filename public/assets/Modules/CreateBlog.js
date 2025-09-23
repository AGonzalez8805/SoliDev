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

    // Aperçu en temps réel
    initLivePreview() {
        this.editor.addEventListener("input", () => {
            this.previewContent.innerHTML = this.editor.innerHTML;
        });

        this.titleInput.addEventListener("input", () => {
            this.previewTitle.textContent = this.titleInput.value || "Titre de l'article";
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
        // Click sur la div pour ouvrir le sélecteur
        this.imageUpload.addEventListener("click", () => {
            this.coverInput.click();
        });

        // Quand un fichier est sélectionné
        this.coverInput.addEventListener("change", () => {
            const file = this.coverInput.files[0];
            if (!file) return;

            // Limite côté client : 2MB maximum
            const maxSize = 2 * 1024 * 1024; // 2MB en octets
            if (file.size > maxSize) {
                alert("Le fichier est trop volumineux (max 2MB).");
                this.coverInput.value = ""; // Réinitialise le champ
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
}
