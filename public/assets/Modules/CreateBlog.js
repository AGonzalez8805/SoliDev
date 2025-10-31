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

        // Éléments pour l'aperçu
        this.previewContainer = document.getElementById("previewContainer");
        this.previewDate = document.getElementById("previewDate");
        this.previewCategory = document.getElementById("previewCategory");
        this.previewExcerpt = document.getElementById("previewExcerpt");
        this.previewMeta = document.getElementById("previewMeta");
        this.categorySelect = document.getElementById("category");
        this.excerptInput = document.getElementById("excerpt");
        this.previewBtn = document.getElementById("previewBtn");

        // Gestion de l'image
        this.imageUpload = document.getElementById("imageUpload");
        this.coverInput = document.getElementById("coverImage");
        this.imagePreview = document.getElementById("imagePreview");

        // Récupérer le textarea caché pour le contenu (doit exister dans le HTML)
        this.hiddenContent = document.getElementById("content");
        if (!this.hiddenContent) {
            console.error("Le textarea #content est introuvable dans le formulaire !");
            return;
        }

        this.buttons = document.querySelectorAll(".editor-btn");

        this.init();
    }

    init() {
        this.initEditorButtons();
        this.initLivePreview();
        this.initFormSubmit();
        this.initImageUpload();
        this.initPreviewButton();
    }

    // Gestion des boutons de l'éditeur
    initEditorButtons() {
        this.buttons.forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                const command = btn.dataset.command;
                const value = btn.dataset.value || null;

                if (command === "createLink") {
                    const url = prompt("Entrez l'URL du lien :");
                    if (url) document.execCommand(command, false, url);
                } else if (command === "insertImage") {
                    const url = prompt("Entrez l'URL de l'image :");
                    if (url) document.execCommand(command, false, url);
                } else if (command === "formatBlock" && value) {
                    document.execCommand(command, false, value);
                } else {
                    document.execCommand(command, false, null);
                }

                // Remettre le focus sur l'éditeur
                this.editor.focus();
            });
        });
    }

    // Aperçu en temps réel (titre + contenu uniquement)
    initLivePreview() {
        if (this.editor && this.previewContent) {
            this.editor.addEventListener("input", () => {
                this.previewContent.innerHTML = this.editor.innerHTML;
            });
        }

        if (this.titleInput && this.previewTitle) {
            this.titleInput.addEventListener("input", () => {
                this.previewTitle.textContent = this.titleInput.value || "Titre de l'article";
            });
        }
    }

    // Bouton Aperçu (rend complet avec date, catégorie)
    initPreviewButton() {
        if (!this.previewBtn) return;

        this.previewBtn.addEventListener("click", (e) => {
            e.preventDefault();

            // Récupérer les valeurs
            const title = this.titleInput.value.trim() || "Titre de l'article";
            const category = this.categorySelect.options[this.categorySelect.selectedIndex]?.text || "Catégorie";
            const content = this.editor.innerHTML.trim() || "<p>Contenu de l'article...</p>";

            const today = new Date();
            const date = today.toLocaleDateString("fr-FR", {
                day: "numeric",
                month: "long",
                year: "numeric"
            });

            // Mise à jour de la prévisualisation
            this.previewTitle.textContent = title;
            this.previewContent.innerHTML = content;
            this.previewDate.textContent = date;
            this.previewCategory.textContent = category;

            // Afficher le conteneur de prévisualisation
            if (this.previewContainer) {
                this.previewContainer.classList.add("show");

                // Scroll vers la prévisualisation
                this.previewContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    // Copier le contenu dans le textarea caché avant l'envoi
    initFormSubmit() {
        this.form.addEventListener("submit", (e) => {
            // Récupérer le contenu de l'éditeur
            let content = this.editor.innerHTML.trim();

            // Nettoyer le placeholder par défaut
            if (content === "<p>Rédigez votre article ici...</p>") {
                content = "";
            }

            // Vérifier si le contenu est vide
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const textContent = tempDiv.textContent || tempDiv.innerText || '';

            if (!textContent.trim()) {
                e.preventDefault();
                alert("Veuillez rédiger le contenu de votre article.");
                this.editor.focus();
                return false;
            }

            // IMPORTANT : Copier le contenu dans le textarea caché
            console.log("Contenu copié vers le textarea:", content);
            this.hiddenContent.value = content;

            // Vérifier que la valeur a bien été assignée
            if (!this.hiddenContent.value) {
                e.preventDefault();
                alert("Erreur: le contenu n'a pas pu être sauvegardé. Veuillez réessayer.");
                return false;
            }

            // Le formulaire peut être soumis
            return true;
        });
    }

    // Gestion de l'image de couverture
    initImageUpload() {
        if (!this.imageUpload || !this.coverInput) return;

        // Clic sur la zone d'upload
        this.imageUpload.addEventListener("click", () => {
            this.coverInput.click();
        });

        // Changement de fichier
        this.coverInput.addEventListener("change", () => {
            this.handleImageFile();
        });

        // Drag & Drop
        this.imageUpload.addEventListener("dragover", (e) => {
            e.preventDefault();
            this.imageUpload.classList.add("dragover");
        });

        this.imageUpload.addEventListener("dragleave", () => {
            this.imageUpload.classList.remove("dragover");
        });

        this.imageUpload.addEventListener("drop", (e) => {
            e.preventDefault();
            this.imageUpload.classList.remove("dragover");

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.coverInput.files = files;
                this.handleImageFile();
            }
        });
    }

    handleImageFile() {
        const file = this.coverInput.files[0];
        if (!file) return;

        // Vérification du type
        if (!file.type.startsWith("image/")) {
            alert("Veuillez sélectionner une image valide.");
            this.coverInput.value = "";
            return;
        }

        // Vérification de la taille (2MB)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert("Le fichier est trop volumineux (max 2MB).");
            this.coverInput.value = "";
            this.imagePreview.innerHTML = "";
            return;
        }

        // Afficher l'aperçu
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Aperçu de l'image de couverture" 
                     style="max-width:100%; height:auto; max-height:300px; border-radius:8px; display:block; margin:0 auto;">
            `;
        };
        reader.readAsDataURL(file);
    }
}