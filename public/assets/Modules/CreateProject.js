export class CreateProject {
    constructor() {
        console.log("CreateProject initialisé");

        this.form = document.querySelector(".project-form");
        this.coverInput = document.getElementById("cover_image");
        this.fileName = document.querySelector(".file-name");
        this.editor = document.getElementById("description");
        this.toolbarButtons = document.querySelectorAll(".toolbar-btn");
        this.shortDesc = document.getElementById("short_description");
        this.techCheckboxes = document.querySelectorAll('input[name="technologies[]"]');

        if (this.form) this.init();
    }

    init() {
        // --- Aperçu du fichier ---
        if (this.coverInput) {
            this.coverInput.addEventListener("change", () => this.previewFile());
        }

        // --- Boutons de formatage Markdown ---
        this.toolbarButtons.forEach(btn => {
            btn.addEventListener("click", (e) => this.applyMarkdown(e));
        });

        // --- Compteur de caractères description courte ---
        if (this.shortDesc) {
            const counter = document.createElement("div");
            counter.className = "char-counter";
            counter.textContent = `0 / ${this.shortDesc.maxLength}`;
            this.shortDesc.parentNode.appendChild(counter);

            this.shortDesc.addEventListener("input", () => {
                counter.textContent = `${this.shortDesc.value.length} / ${this.shortDesc.maxLength}`;
                if (this.shortDesc.value.length > this.shortDesc.maxLength) {
                    counter.style.color = "red";
                } else {
                    counter.style.color = "";
                }
            });
        }

        // --- Validation avant soumission ---
        this.form.addEventListener("submit", (e) => this.validateForm(e));
    }

    /** Aperçu image uploadée */
    previewFile() {
        const file = this.coverInput.files[0];
        if (!file) return;
        if (!file.type.startsWith("image/")) {
            alert("Veuillez sélectionner une image valide.");
            this.coverInput.value = "";
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert("L’image dépasse la taille maximale de 2 Mo.");
            this.coverInput.value = "";
            return;
        }

        this.fileName.textContent = file.name;

        const reader = new FileReader();
        reader.onload = (e) => {
            // Affiche un aperçu (optionnel : tu peux ajouter une zone <img>)
            let preview = document.querySelector(".cover-preview");
            if (!preview) {
                preview = document.createElement("img");
                preview.className = "cover-preview mt-2 rounded shadow-sm";
                preview.style.maxHeight = "200px";
                this.coverInput.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    /** Actions Markdown simples */
    applyMarkdown(e) {
        const action = e.currentTarget.dataset.action;
        const textarea = this.editor;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selected = textarea.value.substring(start, end);

        let insertText = "";
        switch (action) {
            case "bold":
                insertText = `**${selected || "texte en gras"}**`;
                break;
            case "italic":
                insertText = `*${selected || "texte en italique"}*`;
                break;
            case "list":
                insertText = selected
                    ? selected.split("\n").map(line => `- ${line}`).join("\n")
                    : "- Élément 1\n- Élément 2";
                break;
            case "link":
                insertText = `[${selected || "texte du lien"}](https://exemple.com)`;
                break;
            case "code":
                insertText = `\`${selected || "code"}\``;
                break;
            default:
                return;
        }

        textarea.setRangeText(insertText, start, end, "end");
        textarea.focus();
    }

    /** Validation basique du formulaire */
    validateForm(e) {
        const title = document.getElementById("title").value.trim();
        const checkedTechs = Array.from(this.techCheckboxes).filter(t => t.checked);

        if (!title) {
            e.preventDefault();
            alert("Veuillez renseigner un titre pour votre projet.");
            return false;
        }

        if (checkedTechs.length === 0) {
            e.preventDefault();
            alert("Veuillez sélectionner au moins une technologie.");
            return false;
        }

        // ✅ tu peux ajouter d’autres validations ici
        return true;
    }
}
