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
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher des articles...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="filter-select">
                    <option value="">Toutes les catégories</option>
                    <option value="javascript">JavaScript</option>
                    <option value="php">PHP</option>
                    <option value="css">CSS</option>
                    <option value="framework">Framework</option>
                    <option value="react">React</option>
                    <option value="tutorial">Tutoriels</option>
                    <option value="devops">DevOps</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="filter-select">
                    <option value="recent">Plus récents</option>
                    <option value="popular">Plus populaires</option>
                    <option value="commented">Plus commentés</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <a href="/?controller=blog&action=createBlog" class="btn btn-write">
                    <i class="fas fa-pen me-2"></i>Écrire un article
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Article vedette -->
<section class="featured-section">
    <div class="container">
        <h3 class="mb-4">
            <i class="fas fa-star text-warning me-2"></i>
            Article vedette de la semaine
        </h3>
        <div class="featured-article">
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="featured-image">
                        <div class="featured-badge">TENDANCE</div>
                        <i class="fab fa-js-square"></i>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="featured-content">
                        <h2 class="featured-title">Les nouveautés JavaScript 2024 qui vont changer votre code</h2>
                        <p class="featured-excerpt">
                            Découvrez les nouvelles fonctionnalités de JavaScript qui révolutionnent le développement web.
                            Pattern matching, nouvelles méthodes d'arrays, améliorations des modules et bien plus encore.
                            Un guide complet avec des exemples pratiques pour maîtriser ES2024.
                        </p>
                        <div class="featured-meta">
                            <div class="author-info">
                                <div class="author-avatar">JD</div>
                                <span>John Doe • 18 sept. 2024</span>
                            </div>
                            <div>
                                <i class="fas fa-eye me-1"></i> 3.2k vues
                                <i class="fas fa-heart ms-3 me-1"></i> 147 likes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Liste des articles -->
<section class="blog-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php foreach ($blogs as $blog): ?>
                    <article class="article-list-item">
                        <h2 class="article-title">
                            <a href="/?controller=blog&action=show&id=<?= $blog->getId() ?>">
                                <?= htmlspecialchars($blog->getTitle()) ?>
                            </a>
                        </h2>
                        <p class="article-excerpt">
                            <?= nl2br(htmlspecialchars($blog->getDescription())) ?>
                        </p>
                        <a href="/?controller=blog&action=show&id=<?= $blog->getId() ?>" class="read-more">
                            Lire l'article
                        </a>
                    </article>
                <?php endforeach; ?>

                <!-- Pagination -->
                <div class="pagination-container">
                    <ul class="pagination-list">
                        <li><a href="#" class="page-btn disabled"><i class="fas fa-chevron-left"></i></a></li>
                        <li><a href="#" class="page-btn active">1</a></li>
                        <li><a href="#" class="page-btn">2</a></li>
                        <li><a href="#" class="page-btn">3</a></li>
                        <li><a href="#" class="page-btn">4</a></li>
                        <li><span class="page-btn disabled">...</span></li>
                        <li><a href="#" class="page-btn">12</a></li>
                        <li><a href="#" class="page-btn"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>


<?php require_once APP_ROOT . '/views/footer.php'; ?>