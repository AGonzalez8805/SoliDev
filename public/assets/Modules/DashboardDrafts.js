export class DashboardDrafts {
    constructor() {
        console.log("DashboardDrafts initialisé");

        this.draftsContainer = document.querySelector('#drafts-tab .form-card');
        if (!this.draftsContainer) return;

        this.init();
    }

    init() {
        // Charger les brouillons au démarrage
        this.loadDrafts();

        // Écoute d'un événement personnalisé pour recharger après sauvegarde
        window.addEventListener('draftSaved', () => this.loadDrafts());
    }

    async loadDrafts() {
        try {
            const res = await fetch('/?controller=blog&action=getDrafts');
            if (!res.ok) throw new Error("Erreur réseau : " + res.status);

            const data = await res.json();
            if (!data.success) {
                this.draftsContainer.innerHTML = "<p>Erreur : " + (data.error || "Impossible de charger les brouillons") + "</p>";
                return;
            }

            this.renderDrafts(data.drafts);
        } catch (err) {
            console.error("Erreur lors du chargement des brouillons :", err);
            this.draftsContainer.innerHTML = "<p>Impossible de charger les brouillons.</p>";
        }
    }


    renderDrafts(drafts) {
        if (!drafts.length) {
            this.draftsContainer.innerHTML = "<p>Aucun brouillon trouvé.</p>";
            return;
        }

        this.draftsContainer.innerHTML = ''; // reset

        drafts.forEach(d => {
            this.draftsContainer.innerHTML += `
            <div class="draft-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5>${d.title}</h5>
                        <p>${d.excerpt}</p>
                        <div class="draft-meta">
                            <span class="badge-custom">${d.type || "Article"}</span>
                            <span class="ms-2"><i class="fas fa-clock me-1"></i>${d.updated_at}</span>
                        </div>
                    </div>
                    <div class="ms-3">
                        <button class="btn btn-outline-custom btn-sm me-2 edit-draft" data-id="${d.id}">✏️</button>
                        <button class="btn btn-primary-custom btn-sm publish-draft" data-id="${d.id}">📤</button>
                    </div>
                </div>
            </div>`;
        });

        // Ajouter les événements des boutons après le rendu
        this.bindActions();
    }

    bindActions() {
        const editButtons = this.draftsContainer.querySelectorAll('.edit-draft');
        const publishButtons = this.draftsContainer.querySelectorAll('.publish-draft');

        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                window.location.href = `/?controller=blog&action=edit&id=${id}`;
            });
        });

        publishButtons.forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                try {
                    const res = await fetch(`/?controller=blog&action=publishDraft&id=${id}`, {
                        method: "POST"
                    });
                    const result = await res.json();
                    if (result.success) {
                        // Recharger les brouillons
                        this.loadDrafts();
                        alert("Brouillon publié !");
                    } else {
                        alert("Erreur lors de la publication.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("Erreur réseau.");
                }
            });
        });
    }
}
