// assets/js/DashboardAdmin.js

export class DashboardAdmin {
    constructor() {
        console.log("DashboardAdmin initialisé");

        // DOM Elements
        this.usersTableBody = document.getElementById('usersTableBody');
        this.searchInput = document.getElementById('searchUsers');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.pageNumbers = document.getElementById('pageNumbers');

        // Rows
        this.rows = Array.from(this.usersTableBody.querySelectorAll('tr'));
        this.filteredRows = [...this.rows];

        // Pagination
        this.currentPage = 1;
        this.rowsPerPage = 5;

        // Initialisation
        this.initEvents();
        this.initFilters();
        this.renderPagination();
    }

    initEvents() {
        if (!this.usersTableBody) return;

        // Événements sur les actions
        this.usersTableBody.addEventListener('click', (e) => {
            const target = e.target;

            if (target.classList.contains('btn-delete')) {
                this.handleDelete(target.dataset.id, target);
            }

            if (target.classList.contains('btn-edit')) {
                this.handleEdit(target.dataset.id, target);
            }
        });

        // Pagination boutons
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.renderPagination();
                }
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(this.filteredRows.length / this.rowsPerPage);
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.renderPagination();
                }
            });
        }
    }

    initFilters() {
        if (!this.searchInput) return;

        // Recherche en temps réel
        this.searchInput.addEventListener('input', () => this.filterTable());
    }

    filterTable() {
        const searchTerm = this.searchInput.value.toLowerCase();

        this.filteredRows = this.rows.filter(row => {
            const name = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();

            return name.includes(searchTerm) || email.includes(searchTerm);
        });

        this.currentPage = 1; // Revenir à la première page après un filtre
        this.renderPagination();
    }

    renderPagination() {
        const totalPages = Math.ceil(this.filteredRows.length / this.rowsPerPage);

        // Masquer toutes les lignes
        this.rows.forEach(row => row.style.display = 'none');

        // Afficher les lignes de la page courante
        const start = (this.currentPage - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;
        this.filteredRows.slice(start, end).forEach(row => row.style.display = '');

        // Mettre à jour les boutons Précédent / Suivant
        this.prevBtn.disabled = this.currentPage === 1;
        this.nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

        // Générer les boutons numérotés
        this.pageNumbers.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.classList.add('pagination-number-btn');
            if (i === this.currentPage) btn.classList.add('active');
            btn.addEventListener('click', () => {
                this.currentPage = i;
                this.renderPagination();
            });
            this.pageNumbers.appendChild(btn);
        }
    }

    async handleDelete(userId, button) {
        if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;

        try {
            const response = await fetch(`index.php?controller=user&action=delete&id=${userId}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (result.success) {
                const row = button.closest('tr');
                row.remove();
                this.rows = this.rows.filter(r => r !== row);
                this.filterTable(); // Met à jour pagination et filtre
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
