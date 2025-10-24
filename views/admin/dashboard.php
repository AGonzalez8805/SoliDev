<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="dashboard-container">
    <!-- En-tête du dashboard -->
    <div class="dashboard-header">
        <h1>Bienvenue sur ton espace <?= $_SESSION['role']; ?></h1>
    </div>

    <section class="stats-grid">
        <div class="stat-card users">
            <div class="icon">👥</div>
            <h3>Utilisateurs</h3>
            <div class="value"><?= $stats['users'] ?></div>
        </div>

        <div class="stat-card blog">
            <div class="icon">📝</div>
            <h3>Articles Blog</h3>
            <div class="value"><?= $stats['blogs'] ?></div>
        </div>

        <div class="stat-card projects">
            <div class="icon">🚀</div>
            <h3>Projets</h3>
            <div class="value"><?= $stats['projects'] ?></div>
        </div>

        <div class="stat-card snippets">
            <div class="icon">💻</div>
            <h3>Snippets</h3>
            <div class="value"><?= $stats['snippets'] ?></div>
        </div>
    </section>



    <!-- Chart.js -->
    <section class="charts-grid">
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
    </section>

    <!-- Gestion Utilisateurs Section -->
    <section class="users-management-section">
        <div class="section-header">
            <h2>Gestion des Utilisateurs</h2>
        </div>

        <div class="filters-bar">
            <div class="search-box">
                <input type="text" id="searchUsers" placeholder="Rechercher par nom ou email..." class="search-input">
                <span class="search-icon">🔍</span>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name'] . ' ' . $user['firstName']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="status-badge <?= $user['role'] === 'admin' ? 'admin' : 'utilisateur' ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['registrationDate'])) ?></td>
                            <td class="actions-cell">
                                <button class="btn-action btn-edit" data-id="<?= $user['users_id'] ?>" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete" data-id="<?= $user['users_id'] ?>" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <div class="pagination">
            <button class="pagination-btn" id="prevBtn">← Précédent</button>
            <div class="page-numbers" id="pageNumbers"></div>
            <button class="pagination-btn" id="nextBtn">Suivant →</button>
        </div>
    </section>

    <?php require_once APP_ROOT . '/views/footer.php'; ?>