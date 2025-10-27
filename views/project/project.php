<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête des projets -->
<section class="projects-header">
    <div class="container">
        <h1 class="mb-3">
            <i class="fas fa-rocket me-3"></i>
            Projets SoliDev
        </h1>
        <p class="lead mb-4">
            Découvrez les projets innovants de notre communauté, partagez vos créations,
            trouvez des collaborateurs ou rejoignez des équipes pour donner vie à des idées ambitieuses.
            Ensemble, construisons l'avenir du développement.
        </p>
    </div>
</section>

<!-- Contrôles des projets -->
<section class="projects-controls">
    <div class="container">
        <form method="GET" action="/">
            <input type="hidden" name="controller" value="projects">
            <input type="hidden" name="action" value="list">
            <div class="controls-wrapper">
                <!-- Section recherche et filtres -->
                <div class="search-filters">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" name="q"
                            placeholder="Rechercher des projets..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                    <!-- Filtres -->
                    <div class="filter-group">
                        <select name="status" class="filter-select">
                            <option value="">Tous les statuts</option>
                            <option value="planning" <?= (($_GET['status'] ?? '') === 'planning') ? 'selected' : '' ?>>En planification</option>
                            <option value="active" <?= (($_GET['status'] ?? '') === 'active') ? 'selected' : '' ?>>En cours</option>
                            <option value="completed" <?= (($_GET['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Terminé</option>
                            <option value="seeking" <?= (($_GET['status'] ?? '') === 'seeking') ? 'selected' : '' ?>>Recherche collaborateurs</option>
                        </select>

                        <select name="tech" class="filter-select">
                            <option value="">Toutes les technologies</option>
                            <option value="javascript" <?= (($_GET['tech'] ?? '') === 'javascript') ? 'selected' : '' ?>>JavaScript</option>
                            <option value="php" <?= (($_GET['tech'] ?? '') === 'php') ? 'selected' : '' ?>>PHP</option>
                            <option value="python" <?= (($_GET['tech'] ?? '') === 'python') ? 'selected' : '' ?>>Python</option>
                            <option value="react" <?= (($_GET['tech'] ?? '') === 'react') ? 'selected' : '' ?>>React</option>
                            <option value="vue" <?= (($_GET['tech'] ?? '') === 'vue') ? 'selected' : '' ?>>Vue.js</option>
                            <option value="node" <?= (($_GET['tech'] ?? '') === 'node') ? 'selected' : '' ?>>Node.js</option>
                        </select>

                        <select name="sort" class="filter-select">
                            <option value="recent" <?= (($_GET['sort'] ?? '') === 'recent') ? 'selected' : '' ?>>Plus récents</option>
                            <option value="popular" <?= (($_GET['sort'] ?? '') === 'popular') ? 'selected' : '' ?>>Plus populaires</option>
                            <option value="contributors" <?= (($_GET['sort'] ?? '') === 'contributors') ? 'selected' : '' ?>>Plus de collaborateurs</option>
                        </select>

                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
                <!-- Section actions -->
                <div class="action-section">
                    <a href="/?controller=project&action=create" class="btn-create-project">
                        <i class="fas fa-plus me-2"></i>Nouveau projet
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Liste des projets -->
<section class="projects-content">
    <div class="container">
        <div class="row">
            <?php
            if (isset($projects) && is_array($projects) && count($projects) > 0) :
                foreach ($projects as $project) :
                    $createdAt = $project->getCreatedAt();
                    $formattedDate = $createdAt ? (new \DateTime($createdAt))->format('d/m/Y') : '';
            ?>
                    <div class="col-lg-6 mb-4">
                        <article class="project-card">
                            <!-- Badge de statut -->
                            <div class="project-status-badge status-<?= htmlspecialchars($project->getStatus()) ?>">
                                <?php
                                $statusLabels = [
                                    'planning' => 'En planification',
                                    'active' => 'En cours',
                                    'completed' => 'Terminé',
                                    'seeking' => 'Recherche collaborateurs'
                                ];
                                echo $statusLabels[$project->getStatus()] ?? $project->getStatus();
                                ?>
                            </div>

                            <!-- Image du projet -->
                            <?php if ($project->getCoverImage()): ?>
                                <div class="project-image">
                                    <img src="<?= htmlspecialchars($project->getCoverImage()) ?>" alt="<?= htmlspecialchars($project->getTitle()) ?>">
                                </div>
                            <?php endif; ?>

                            <div class="project-content">
                                <!-- Titre -->
                                <h2 class="project-title">
                                    <a href="/?controller=project&action=show&id=<?= $project->getId() ?>">
                                        <?= htmlspecialchars($project->getTitle()) ?>
                                    </a>
                                </h2>

                                <!-- Meta informations -->
                                <div class="project-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <strong><?= htmlspecialchars($project->getOwnerName()) ?></strong>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <?= $formattedDate ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <?= $project->getCollaboratorsCount() ?? 0 ?> collaborateurs
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="project-description">
                                    <?= nl2br(htmlspecialchars($project->getDescription())) ?>
                                </p>

                                <!-- Technologies -->
                                <div class="project-technologies">
                                    <?php
                                    $technologies = $project->getTechnologies() ?? [];
                                    foreach ($technologies as $tech):
                                    ?>
                                        <span class="tech-badge"><?= htmlspecialchars($tech) ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Actions -->
                                <div class="project-actions">
                                    <a href="/?controller=project&action=view&id=<?= $project->getId() ?>" class="btn-view-project">
                                        Voir le projet
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                    <?php if ($project->getStatus() === 'seeking'): ?>
                                        <a href="/?controller=project&action=join&id=<?= $project->getId() ?>" class="btn-join-project">
                                            <i class="fas fa-handshake me-1"></i>
                                            Rejoindre
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php
                endforeach;
            else :
                ?>
                <div class="col-12">
                    <div class="no-projects">
                        <i class="fas fa-folder-open"></i>
                        <h3>Aucun projet trouvé</h3>
                        <p>Soyez le premier à partager votre projet avec la communauté !</p>
                        <a href="/?controller=project&action=create" class="btn-create-project">
                            <i class="fas fa-plus me-2"></i>Créer un projet
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination-container">
                <ul class="pagination-list">
                    <!-- Page précédente -->
                    <li>
                        <a href="/?controller=&action=list&page=<?= max(1, $page - 1) ?>&status=<?= urlencode($status ?? '') ?>&tech=<?= urlencode($tech ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
                            class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Pages numérotées -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="/?controller=project&action=list&page=<?= $i ?>&status=<?= urlencode($status ?? '') ?>&tech=<?= urlencode($tech ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
                                class="page-btn <?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Page suivante -->
                    <li>
                        <a href="/?controller=project&action=list&page=<?= min($totalPages, $page + 1) ?>&status=<?= urlencode($status ?? '') ?>&tech=<?= urlencode($tech ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
                            class="page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>