<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="dashboard-container">
    <!-- En-tête du dashboard -->
    <div class="dashboard-header">
        <h1>Bienvenue sur ton espace <?= $_SESSION['role']; ?></h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card users">
            <div class="icon">👥</div>
            <h3>Utilisateurs</h3>
            <div class="value" id="usersCount">125</div>
            <div class="trend">↗ +12 ce mois</div>
        </div>

        <div class="stat-card blog">
            <div class="icon">📝</div>
            <h3>Articles Blog</h3>
            <div class="value" id="blogCount">48</div>
            <div class="trend">↗ +5 ce mois</div>
        </div>

        <div class="stat-card projects">
            <div class="icon">🚀</div>
            <h3>Projets</h3>
            <div class="value" id="projectsCount">23</div>
            <div class="trend">↗ +3 ce mois</div>
        </div>

        <div class="stat-card snippets">
            <div class="icon">💻</div>
            <h3>Snippets</h3>
            <div class="value" id="snippetsCount">67</div>
            <div class="trend">↗ +8 ce mois</div>
        </div>
    </div>
    <!-- Chart.js -->
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-container square">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-container">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Gestion Utilisateurs Section -->
    <div class="users-management-section">
        <div class="section-header">
            <h2>Gestion des Utilisateurs</h2>
        </div>

        <div class="filters-bar">
            <div class="search-box">
                <input type="text" id="searchUsers" placeholder="Rechercher par nom ou email..." class="search-input">
                <span class="search-icon">🔍</span>
            </div>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
            </select>
        </div>

        <div class="table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="checkbox-header">
                        </th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr>
                        <td><input type="checkbox" class="checkbox-row"></td>
                        <td>Jean Dupont</td>
                        <td>jean.dupont@example.com</td>
                        <td><span class="status-badge active">Actif</span></td>
                        <td>15/01/2025</td>
                        <td class="actions-cell">
                            <button class="btn-action btn-edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-delete" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="checkbox-row"></td>
                        <td>Marie Martin</td>
                        <td>marie.martin@example.com</td>
                        <td><span class="status-badge active">Actif</span></td>
                        <td>20/02/2025</td>
                        <td class="actions-cell">
                            <button class="btn-action btn-edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-delete" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="checkbox-row"></td>
                        <td>Pierre Bernard</td>
                        <td>pierre.bernard@example.com</td>
                        <td><span class="status-badge inactive">Inactif</span></td>
                        <td>10/03/2025</td>
                        <td class="actions-cell">
                            <button class="btn-action btn-edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-delete" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button class="pagination-btn" id="prevBtn">← Précédent</button>
            <div class="page-numbers" id="pageNumbers"></div>
            <button class="pagination-btn" id="nextBtn">Suivant →</button>
        </div>
    </div>
    <?php require_once APP_ROOT . '/views/footer.php'; ?>