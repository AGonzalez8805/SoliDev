<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête de la page Snippets -->
<section class="snippets-header">
    <div class="container">
        <h1>
            <i class="fas fa-code me-3"></i>
            Snippets de Code
        </h1>
        <p class="lead">
            Partagez vos extraits de code réutilisables, découvrez des solutions élégantes à des problèmes courants,
            et construisez votre bibliothèque personnelle de snippets pour accélérer votre développement.
        </p>
    </div>
</section>

<!-- Contrôles et filtres -->
<section class="snippets-controls">
    <div class="container">
        <form method="GET" action="">
            <input type="hidden" name="controller" value="snippets">
            <input type="hidden" name="action" value="list">
            <div class="controls-wrapper">
                <!-- Section recherche et filtres -->
                <div class="search-filters">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" name="q"
                            placeholder="Rechercher des snippets..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>

                    <!-- Filtres -->
                    <div class="filter-group">
                        <select name="language" class="filter-select">
                            <option value="">Tous les langages</option>
                            <option value="javascript" <?= (($_GET['language'] ?? '') === 'javascript') ? 'selected' : '' ?>>JavaScript</option>
                            <option value="php" <?= (($_GET['language'] ?? '') === 'php') ? 'selected' : '' ?>>PHP</option>
                            <option value="python" <?= (($_GET['language'] ?? '') === 'python') ? 'selected' : '' ?>>Python</option>
                            <option value="css" <?= (($_GET['language'] ?? '') === 'css') ? 'selected' : '' ?>>CSS</option>
                            <option value="html" <?= (($_GET['language'] ?? '') === 'html') ? 'selected' : '' ?>>HTML</option>
                            <option value="sql" <?= (($_GET['language'] ?? '') === 'sql') ? 'selected' : '' ?>>SQL</option>
                            <option value="java" <?= (($_GET['language'] ?? '') === 'java') ? 'selected' : '' ?>>Java</option>
                            <option value="csharp" <?= (($_GET['language'] ?? '') === 'csharp') ? 'selected' : '' ?>>C#</option>
                        </select>

                        <select name="category" class="filter-select">
                            <option value="">Toutes les catégories</option>
                            <option value="fonction" <?= (($_GET['category'] ?? '') === 'fonction') ? 'selected' : '' ?>>Fonctions utiles</option>
                            <option value="algorithm" <?= (($_GET['category'] ?? '') === 'algorithm') ? 'selected' : '' ?>>Algorithmes</option>
                            <option value="ui" <?= (($_GET['category'] ?? '') === 'ui') ? 'selected' : '' ?>>Composants UI</option>
                            <option value="api" <?= (($_GET['category'] ?? '') === 'api') ? 'selected' : '' ?>>API & Requêtes</option>
                            <option value="database" <?= (($_GET['category'] ?? '') === 'database') ? 'selected' : '' ?>>Base de données</option>
                            <option value="animation" <?= (($_GET['category'] ?? '') === 'animation') ? 'selected' : '' ?>>Animations</option>
                            <option value="security" <?= (($_GET['category'] ?? '') === 'security') ? 'selected' : '' ?>>Sécurité</option>
                        </select>

                        <select name="sort" class="filter-select">
                            <option value="recent" <?= (($_GET['sort'] ?? '') === 'recent') ? 'selected' : '' ?>>Plus récents</option>
                            <option value="popular" <?= (($_GET['sort'] ?? '') === 'popular') ? 'selected' : '' ?>>Plus populaires</option>
                            <option value="favorites" <?= (($_GET['sort'] ?? '') === 'favorites') ? 'selected' : '' ?>>Plus de favoris</option>
                        </select>

                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
                </div>

                <!-- Section actions -->
                <div class="action-section">
                    <a href="/?controller=snippets&action=createSnippet" class="btn-add-snippet">
                        <i class="fas fa-plus me-2"></i>Ajouter un snippet
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Grille de snippets -->
<section class="snippets-content">
    <div class="container">
        <div class="row">
            <?php if (isset($snippets) && is_array($snippets) && count($snippets) > 0): ?>
                <?php foreach ($snippets as $snippet): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <article class="snippet-card">
                            <!-- En-tête du snippet -->
                            <div class="snippet-header">
                                <div class="snippet-language">
                                    <i class="fas fa-code"></i>
                                    <?= htmlspecialchars($snippet->getLanguage()) ?>
                                </div>
                                <div class="snippet-actions">
                                    <button class="action-btn" title="Copier">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button class="action-btn favorite-btn" title="Ajouter aux favoris">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Titre et description -->
                            <div class="snippet-content">
                                <h3 class="snippet-title">
                                    <a href="/?controller=snippets&action=show&id=<?= $snippet->getId() ?>">
                                        <?= htmlspecialchars($snippet->getTitle()) ?>
                                    </a>
                                </h3>
                                <p class="snippet-description">
                                    <?= htmlspecialchars($snippet->getDescription()) ?>
                                </p>

                                <!-- Aperçu du code -->
                                <div class="snippet-preview">
                                    <pre><code class="language-<?= htmlspecialchars($snippet->getLanguage()) ?>"><?= htmlspecialchars(substr($snippet->getCode(), 0, 150)) ?><?= strlen($snippet->getCode()) > 150 ? '...' : '' ?></code></pre>
                                </div>

                                <!-- Tags -->
                                <div class="snippet-tags">
                                    <?php
                                    $tags = explode(',', $snippet->getTags() ?? '');
                                    foreach (array_slice($tags, 0, 3) as $tag):
                                        $tag = trim($tag);
                                        if (!empty($tag)):
                                    ?>
                                            <span class="snippet-tag">#<?= htmlspecialchars($tag) ?></span>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>

                            <!-- Footer avec stats -->
                            <div class="snippet-footer">
                                <div class="snippet-author">
                                    <div class="author-avatar">
                                        <?= strtoupper(substr($snippet->getAuthorName(), 0, 2)) ?>
                                    </div>
                                    <span class="author-name"><?= htmlspecialchars($snippet->getAuthorName()) ?></span>
                                </div>
                                <div class="snippet-stats">
                                    <span class="stat-item" title="Favoris">
                                        <i class="fas fa-heart"></i>
                                        <?= $snippet->getFavorites() ?? 0 ?>
                                    </span>
                                    <span class="stat-item" title="Vues">
                                        <i class="fas fa-eye"></i>
                                        <?= $snippet->getViews() ?? 0 ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-snippets">
                        <i class="fas fa-code-branch"></i>
                        <h3>Aucun snippet trouvé</h3>
                        <p>Soyez le premier à partager un snippet de code !</p>
                        <a href="/?controller=snippets&action=create" class="btn-add-snippet">
                            <i class="fas fa-plus me-2"></i>Créer un snippet
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
                        <a href="/?controller=snippets&action=list&page=<?= max(1, $page - 1) ?>&language=<?= urlencode($language ?? '') ?>&category=<?= urlencode($category ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
                            class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Pages numérotées -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="/?controller=snippets&action=list&page=<?= $i ?>&language=<?= urlencode($language ?? '') ?>&category=<?= urlencode($category ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
                                class="page-btn <?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Page suivante -->
                    <li>
                        <a href="/?controller=snippets&action=list&page=<?= min($totalPages, $page + 1) ?>&language=<?= urlencode($language ?? '') ?>&category=<?= urlencode($category ?? '') ?>&q=<?= urlencode($search ?? '') ?>"
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