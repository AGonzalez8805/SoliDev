<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête du forum -->
<section class="forum-header">
    <div class="container">
        <h1 class="mb-3">
            <i class="fas fa-comments me-3"></i>
            Forum SoliDev
        </h1>
        <p class="lead mb-4">
            Posez vos questions techniques, partagez vos solutions, demandez des avis sur vos projets ou discutez des dernières tendances du développement.
            Notre communauté bienveillante est là pour vous accompagner dans vos défis quotidiens de développeur.
        </p>
    </div>
</section>

<section class="container py-4">
    <!-- Bouton nouveau sujet -->
    <div class="text-end mb-4">
        <a href="/?controller=forum&action=createPost" class="btn btn-new-topic">
            <i class="fas fa-plus me-2"></i>Nouveau sujet
        </a>
    </div>

    <div class="row">
        <!-- Filtre par catégorie -->
        <form method="get" action="/" class="mb-4">
            <input type="hidden" name="controller" value="forum">
            <input type="hidden" name="action" value="forum">
            <label for="categorySelect" class="form-label">Filtrer par catégorie :</label>
            <select id="categorySelect" name="category" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $slug => $category): ?>
                    <option value="<?= htmlspecialchars($slug) ?>"
                        <?= ($selectedCategory === $slug) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['title']) ?> (<?= intval($category['topics'] ?? 0) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Liste des sujets récents -->
        <div class="">
            <div class="recent-topics">
                <h5 class="mb-4">
                    <i class="fas fa-fire me-2 text-warning"></i>
                    Sujets récents
                </h5>
                <?php foreach ($recentTopics as $topic): ?>
                    <div class="topic-item mb-3 p-3 border rounded shadow-sm">
                        <div class="d-flex">
                            <!-- Avatar de l'auteur -->
                            <div class="user-avatar me-3">
                                <?= strtoupper(substr($topic->getAuthorName(), 0, 2)) ?>
                            </div>

                            <div class="flex-grow-1">
                                <!-- Titre cliquable -->
                                <a href="/?controller=forum&action=topic&id=<?= $topic->getId() ?>"
                                    class="topic-title fw-bold text-decoration-none d-block">
                                    <?= htmlspecialchars($topic->getTitle()) ?>
                                </a>

                                <!-- Métadonnées -->
                                <div class="topic-meta small text-muted mt-1">
                                    Par <strong><?= htmlspecialchars($topic->getAuthorName()) ?></strong>
                                    • <?= $topic->getCreatedAt()->format('d/m/Y H:i') ?>
                                </div>

                                <!-- Contenu du topic -->
                                <div class="topic-content mt-2">
                                    <?= nl2br(html_entity_decode($topic->getContent())) ?>
                                </div>

                                <!-- Barre d'actions avec compteur de commentaires -->
                                <div class="topic-actions mt-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-3">
                                        <span class="comment-count text-muted small">
                                            <i class="fas fa-comments me-1"></i>
                                            <?= $topic->getCommentCount() ?> commentaire<?= $topic->getCommentCount() > 1 ? 's' : '' ?>
                                        </span>
                                    </div>
                                    <a href="/?controller=forum&action=topic&id=<?= $topic->getId() ?>"
                                        class="btn btn-sm btn-reply">
                                        <i class="fas fa-reply me-1"></i>Répondre
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($recentTopics)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun sujet trouvé dans cette catégorie.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>