export class CreateSnippet {
    constructor() {
        console.log("CreateSnippet initialisé");

        this.form = document.querySelector(".snippet-form");
        if (!this.form) return;

        this.codeEditor = document.getElementById("code");
        this.usageEditor = document.getElementById("usage");
        this.lineCountEl = document.getElementById("lineCount");
        this.formatBtn = document.getElementById("formatCode");
        this.copyBtn = document.getElementById("copyCode");
        this.previewBtn = document.getElementById("previewBtn");
        this.previewContent = document.getElementById("previewContent");
        this.tagsInput = document.getElementById("tags");
        this.tagsList = document.getElementById("tagsList");

        this.hiddenCode = document.createElement("textarea");
        this.hiddenCode.name = "code";
        this.hiddenCode.style.display = "none";
        this.form.appendChild(this.hiddenCode);

        this.hiddenUsage = document.createElement("textarea");
        this.hiddenUsage.name = "usage";
        this.hiddenUsage.style.display = "none";
        this.form.appendChild(this.hiddenUsage);

        this.init();
    }

    init() {
        this.updateLineCount();
        this.initCodeEditor();
        this.initTags();
        this.initPreview();
        this.initFormSubmit();
    }

    updateLineCount() {
        if (!this.codeEditor || !this.lineCountEl) return;
        this.codeEditor.addEventListener("input", () => {
            const lines = this.codeEditor.value.split("\n").length;
            this.lineCountEl.textContent = lines;
        });
    }

    initCodeEditor() {
        if (!this.codeEditor) return;

        if (this.formatBtn) {
            this.formatBtn.addEventListener("click", () => {
                this.codeEditor.value = this.codeEditor.value
                    .split("\n")
                    .map(line => line.trim())
                    .join("\n");
                this.updateLineCount();
            });
        }

        if (this.copyBtn) {
            this.copyBtn.addEventListener("click", () => {
                navigator.clipboard.writeText(this.codeEditor.value)
                    .then(() => alert("Code copié !"))
                    .catch(err => console.error(err));
            });
        }
    }

    initTags() {
        if (!this.tagsInput || !this.tagsList) return;

        this.tagsInput.addEventListener("input", () => {
            const tags = this.tagsInput.value
                .split(",")
                .map(tag => tag.trim())
                .filter(tag => tag !== "")
                .slice(0, 10);

            this.tagsList.innerHTML = tags.map(tag => `<span class="badge bg-primary me-1">${tag}</span>`).join("");
        });
    }

    initPreview() {
        if (!this.previewBtn || !this.previewContent) return;

        this.previewBtn.addEventListener("click", () => {
            const title = this.form.querySelector("#title")?.value || "Titre du snippet";
            const description = this.form.querySelector("#description")?.value || "";
            const language = this.form.querySelector("#language")?.value || "";
            const category = this.form.querySelector("#category")?.value || "";
            const code = this.codeEditor.value.trim();
            const usage = this.usageEditor.value.trim();
            const tags = this.tagsInput.value;

            const html = `
                <h4>${title}</h4>
                <p><strong>Description :</strong> ${description}</p>
                <p><strong>Langage :</strong> ${language} | <strong>Catégorie :</strong> ${category}</p>
                <pre><code class="language-${language}">${Prism.util.encode(code)}</code></pre>
                ${usage ? `<pre><code class="language-${language}">${Prism.util.encode(usage)}</code></pre>` : ""}
                ${tags ? `<p><strong>Tags :</strong> ${tags}</p>` : ""}
            `;
            this.previewContent.innerHTML = html;

            Prism.highlightAllUnder(this.previewContent);

            const modal = new bootstrap.Modal(document.getElementById("previewModal"));
            modal.show();
        });
    }

    initFormSubmit() {
        this.form.addEventListener("submit", () => {
            this.hiddenCode.value = this.codeEditor.value.trim();
            this.hiddenUsage.value = this.usageEditor.value.trim();
        });
    }
}
