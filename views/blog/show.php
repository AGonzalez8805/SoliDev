<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="blog-header">
    <div class="container">
        <h1>
            <i class="fas fa-blog me-3"></i>
            Blog
        </h1>
        <p class="lead">
            Partagez vos connaissances, découvertes et expériences de développement.
            Rédigez des articles techniques, des tutoriels détaillés, des retours d'expérience sur vos projets,
            des analyses de nouvelles technologies ou des guides pratiques pour enrichir notre communauté.
        </p>
    </div>
</section>

<!-- Contrôles du blog -->
<section class="blog-controls">
    <div class="container">
        <form method="GET" action="">
            <input type="hidden" name="controller" value="blog">
            <input type="hidden" name="action" value="list">
            <div class="controls-wrapper">
                <!-- Section recherche et filtres -->
                <div class="search-filters">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" name="q"
                            placeholder="Rechercher des articles..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                    <!-- Filtres -->
                    <div class="filter-group">
                        <select name="category" class="filter-select">
                            <option value="">Toutes les catégories</option>
                            <option value="javascript" <?= (($_GET['category'] ?? '') === 'javascript') ? 'selected' : '' ?>>JavaScript</option>
                            <option value="php" <?= (($_GET['category'] ?? '') === 'php') ? 'selected' : '' ?>>PHP</option>
                            <option value="css" <?= (($_GET['category'] ?? '') === 'css') ? 'selected' : '' ?>>CSS</option>
                            <option value="framework" <?= (($_GET['category'] ?? '') === 'framework') ? 'selected' : '' ?>>Framework</option>
                            <option value="react" <?= (($_GET['category'] ?? '') === 'react') ? 'selected' : '' ?>>React</option>
                            <option value="tutorial" <?= (($_GET['category'] ?? '') === 'tutorial') ? 'selected' : '' ?>>Tutoriels</option>
                            <option value="devops" <?= (($_GET['category'] ?? '') === 'devops') ? 'selected' : '' ?>>DevOps</option>
                        </select>

                        <select name="sort" class="filter-select">
                            <option value="recent" <?= (($_GET['sort'] ?? '') === 'recent') ? 'selected' : '' ?>>Plus récents</option>
                            <option value="popular" <?= (($_GET['sort'] ?? '') === 'popular') ? 'selected' : '' ?>>Plus populaires</option>
                            <option value="commented" <?= (($_GET['sort'] ?? '') === 'commented') ? 'selected' : '' ?>>Plus commentés</option>
                        </select>

                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
                <!-- Section actions -->
                <div class="action-section">
                    <a href="/?controller=blog&action=createBlog" class="btn-write">
                        <i class="fas fa-pen me-2"></i>Écrire un article
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Liste des articles -->
<section class="blog-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php
                if (isset($blogs) && is_array($blogs)) :
                    foreach ($blogs as $blog) : ?>
                        <article class="article-list-item">
                            <h2 class="article-title">
                                <a href="/?controller=blog&action=show&id=<?= $blog->getId() ?>">
                                    <?= htmlspecialchars($blog->getTitle()) ?>
                                </a>
                            </h2>
                            <p class="article-excerpt">
                                <?= nl2br(htmlspecialchars($blog->getExcerpt())) ?>
                            </p>
                            <a href="/?controller=blog&action=show&id=<?= $blog->getId() ?>" class="read-more">
                                Lire l'article
                            </a>
                        </article>
                    <?php endforeach;
                elseif (isset($blog)) : ?>
                    <article class="article-list-item">
                        <h2 class="article-title">
                            <?= htmlspecialchars($blog->getTitle()) ?>
                        </h2>
                        <p class="article-excerpt">
                            <?= nl2br(htmlspecialchars($blog->getExcerpt())) ?>
                        </p>
                        <h1><?= htmlspecialchars($blog->getTitle()) ?></h1>
                        <?php if ($blog->getCoverImage()): ?>
                            <img src="<?= htmlspecialchars($blog->getCoverImage()) ?>" alt="Image de couverture" class="cover-image">
                        <?php endif; ?>
                        <p><strong>Catégorie:</strong> <?= htmlspecialchars($blog->getCategory()) ?></p>
                        <div class="preview-content">
                            <?= $Parsedown->text($blog->getContent()) ?>
                        </div>
                        <a href="/?controller=blog&action=comment" class="read-more">
                            Commentaire
                        </a>
                    </article>
                <?php else : ?>
                    <p>Aucun article trouvé.</p>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if (isset($totalPages) && $totalPages >= 1): ?>
                    <div class="pagination-container">
                        <ul class="pagination-list">
                            <!-- Page précédente -->
                            <li>
                                <a href="/?controller=blog&action=list&page=<?= max(1, $page - 1) ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>"
                                    class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Pages numérotées -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li>
                                    <a href="/?controller=blog&action=list&page=<?= $i ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>"
                                        class="page-btn <?= $i == $page ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Page suivante -->
                            <li>
                                <a href="/?controller=blog&action=list&page=<?= min($totalPages, $page + 1) ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>"
                                    class="page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>


            </div>
        </div>
    </div>
</section>


<?php require_once APP_ROOT . '/views/footer.php'; ?>