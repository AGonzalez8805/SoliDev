<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête du forum -->
<section class="forum-header">
    <div class="container">
        <h1 class="mb-3">
            <i class="fas fa-comments me-3"></i>
            Forum SoliDev
        </h1>
        <p class="lead mb-4">Échangez, partagez et apprenez ensemble dans notre communauté de développeurs solidaires</p>
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
        <div class="col-lg-8">
            <h3 class="mb-4">Catégories</h3>

            <?php foreach ($categories as $category): ?>
                <div class="category-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <i class="<?= htmlspecialchars($category['icon']) ?> category-icon"></i>
                        </div>
                        <div class="col-md-8">
                            <h5 class="category-title"><?= htmlspecialchars($category['title']) ?></h5>
                            <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
                        </div>
                        <div class="col-md-3">
                            <div class="category-stats">
                                <div>
                                    <strong><?= $category['topics'] ?? 0 ?></strong> sujets<br>
                                    <strong><?= $category['messages'] ?? 0 ?></strong> messages
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="recent-topics">
    <h5 class="mb-3">
        <i class="fas fa-fire me-2 text-warning"></i>
        Sujets récents
    </h5>
    <?php foreach ($recentTopics as $topic): ?>
        <div class="topic-item mb-3 p-2 border rounded">
            <div class="d-flex">
                <div class="user-avatar me-3">
                    <?= strtoupper(substr($topic->getAuthorName(), 0, 2)) ?>
                </div>
                <div class="flex-grow-1">
                    <!-- titre -->
                    <span class="topic-title fw-bold">
                        <?= htmlspecialchars($topic->getTitle()) ?>
                    </span>
                    
                    <!-- meta -->
                    <div class="topic-meta small text-muted">
                        Par <strong><?= htmlspecialchars($topic->getAuthorName()) ?></strong>
                        • <?= $topic->getCreatedAt()->format('d/m/Y H:i') ?>
                    </div>

                    <!-- contenu -->
                    <div class="topic-content mt-2">
                        <?= nl2br(html_entity_decode($topic->getContent())) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


            <div class="recent-topics mt-4">
                <h5 class="mb-3">
                    <i class="fas fa-circle text-success me-2"></i>
                    Membres en ligne (<?= count($onlineUsers) ?>)
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($onlineUsers as $user): ?>
                        <span class="badge bg-light text-dark"><?= htmlspecialchars($user['username']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>