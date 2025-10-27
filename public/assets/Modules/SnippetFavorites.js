export class SnippetFavorites {
    constructor(buttons) {
        console.log("SnippetFavorites initialisé");
        this.buttons = buttons || document.querySelectorAll(".favorite-btn");
        this.init();
    }

    init() {
        this.buttons.forEach(button => {
            const snippetId = button.dataset.snippetId || button.closest('.snippet-card')?.querySelector('a[href*="id="]')?.href.match(/id=(\d+)/)?.[1];

            if (!snippetId) {
                console.warn("Bouton favoris sans ID :", button);
                return;
            }

            const icon = button.querySelector("i");
            if (!icon) return;

            button.addEventListener("click", async (e) => {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const res = await fetch(`/?controller=snippets&action=toggleFavorite`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ snippetId: parseInt(snippetId) })
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}`);
                    }

                    const data = await res.json();

                    if (data.success) {
                        // Mise à jour visuelle
                        if (data.favorited) {
                            icon.classList.remove("far");
                            icon.classList.add("fas");
                            button.classList.add("active");
                        } else {
                            icon.classList.remove("fas");
                            icon.classList.add("far");
                            button.classList.remove("active");
                        }

                        // Si on est sur la page favoris et qu'on retire le favori, on peut supprimer la card
                        if (!data.favorited && window.location.href.includes('dashboard')) {
                            const card = button.closest('.col-lg-6, .col-xl-4');
                            if (card) {
                                card.style.transition = 'opacity 0.3s';
                                card.style.opacity = '0';
                                setTimeout(() => card.remove(), 300);
                            }
                        }

                        console.log(`Favori ${data.favorited ? 'ajouté' : 'retiré'} pour snippet ${snippetId}`);
                    } else {
                        console.error("Erreur serveur:", data);
                    }
                } catch (err) {
                    console.error("Erreur SnippetFavorites :", err);
                    alert("Erreur lors de la mise à jour des favoris. Veuillez réessayer.");
                }
            });
        });

        console.log(`${this.buttons.length} boutons favoris initialisés`);
    }
}