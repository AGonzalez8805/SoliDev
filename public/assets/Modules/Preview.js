// Preview.js
export class Preview {
    constructor() {
        console.log("Preview initialisé");

        // Récupération des éléments HTML
        this.previewTitle = document.getElementById("previewTitle");
        this.previewCategory = document.getElementById("previewCategory");
        this.previewDate = document.getElementById("previewDate");
        this.previewContent = document.getElementById("previewContent");

        this.loadPreview();
    }

    loadPreview() {
        const data = JSON.parse(localStorage.getItem("previewArticle"));
        if (!data) {
            console.warn("Aucune donnée d'aperçu trouvée dans localStorage.");
            return;
        }

        this.previewTitle.textContent = data.title;
        this.previewCategory.textContent = data.category;
        this.previewDate.textContent = data.date;
        this.previewContent.innerHTML = data.excerpt
            ? `<p><em>${data.excerpt}</em></p><hr>${data.content}`
            : data.content;
    }
}
