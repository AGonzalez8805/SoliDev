// assets/js/DashboardAdmin.js

export class DashboardAdmin {
    constructor() {
        console.log("DashboardAdmin initialisé");
        this.usersTableBody = document.getElementById('usersTableBody');
        this.initEvents();
    }

    initEvents() {
        if (!this.usersTableBody) return;

        this.usersTableBody.addEventListener('click', (e) => {
            const target = e.target;

            if (target.classList.contains('btn-delete')) {
                this.handleDelete(target.dataset.id, target);
            }

            if (target.classList.contains('btn-edit')) {
                this.handleEdit(target.dataset.id, target);
            }
        });
    }

    async handleDelete(userId, button) {
        if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;

        try {
            const response = await fetch(`index.php?controller=user&action=delete&id=${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Supprime la ligne du tableau
                button.closest('tr').remove();
            } else {
                alert(result.message || "Erreur lors de la suppression.");
            }
        } catch (error) {
            console.error(error);
            alert("Une erreur est survenue lors de la suppression.");
        }
    }

    async handleEdit(userId, button) {
        const row = button.closest('tr');
        const oldName = row.querySelector('td:nth-child(2)').textContent.trim();
        const newName = prompt("Entrez le nouveau nom :", oldName);

        if (!newName || newName === oldName) return;

        try {
            const response = await fetch(`index.php?controller=user&action=update&id=${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name: newName })
            });

            const result = await response.json();

            if (result.success) {
                row.querySelector('td:nth-child(2)').textContent = newName;
            } else {
                alert(result.message || "Erreur lors de la mise à jour.");
            }
        } catch (error) {
            console.error(error);
            alert("Une erreur est survenue lors de la modification.");
        }
    }
}
