<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- Fil d'Ariane -->
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $item): ?>
                <?php if ($item['url']): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= htmlspecialchars($item['url']) ?>">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($item['title']) ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>

<!-- Contenu du Topic -->
<section class="container py-4">
    <!-- Messages de succès -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($successMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Erreurs -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Topic Principal -->
    <div class="topic-detail-card mb-4">
        <div class="d-flex">
            <!-- Avatar de l'auteur -->
            <div class="user-avatar-large me-4">
                <?= strtoupper(substr($topic->getAuthorName(), 0, 2)) ?>
            </div>

            <div class="flex-grow-1">
                <!-- Titre du topic -->
                <h2 class="topic-detail-title mb-3">
                    <?= htmlspecialchars($topic->getTitle()) ?>
                </h2>

                <!-- Métadonnées -->
                <div class="topic-detail-meta mb-3">
                    <span class="me-3">
                        <i class="fas fa-user me-1"></i>
                        <strong><?= htmlspecialchars($topic->getAuthorName()) ?></strong>
                    </span>
                    <span class="me-3">
                        <i class="fas fa-calendar me-1"></i>
                        <?= $topic->getCreatedAt()->format('d/m/Y à H:i') ?>
                    </span>
                    <span class="me-3">
                        <i class="fas fa-comments me-1"></i>
                        <?= $commentCount ?> réponse<?= $commentCount > 1 ? 's' : '' ?>
                    </span>
                </div>

                <!-- Contenu du topic -->
                <div class="topic-detail-content">
                    <?= nl2br(html_entity_decode($topic->getContent())) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des commentaires -->
    <div class="comments-section">
        <h4 class="comments-title mb-4">
            <i class="fas fa-comments me-2"></i>
            Réponses (<?= $commentCount ?>)
        </h4>

        <!-- Liste des commentaires -->
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item mb-3">
                    <div class="d-flex">
                        <!-- Avatar du commentateur -->
                        <div class="user-avatar me-3">
                            <?= strtoupper(substr($comment->getAuthorName(), 0, 2)) ?>
                        </div>

                        <div class="flex-grow-1">
                            <!-- En-tête du commentaire -->
                            <div class="comment-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="comment-author">
                                        <?= htmlspecialchars($comment->getAuthorName()) ?>
                                    </strong>
                                    <span class="comment-date text-muted small ms-2">
                                        <?= $comment->getCreatedAt()->format('d/m/Y à H:i') ?>
                                    </span>
                                    <?php if ($comment->getUpdatedAt()): ?>
                                        <span class="comment-edited text-muted small ms-2">
                                            <i>(modifié)</i>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Bouton supprimer si l'utilisateur est l'auteur -->
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment->getAuthorId()): ?>
                                    <a href="/?controller=forum&action=deleteComment&comment_id=<?= $comment->getId() ?>&topic_id=<?= $topic->getId() ?>"
                                        class="btn btn-sm btn-delete-comment"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Contenu du commentaire -->
                            <div class="comment-content">
                                <?= nl2br(html_entity_decode($comment->getContent())) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucune réponse pour le moment. Soyez le premier à répondre !
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout de commentaire -->
        <?php if ($canReply): ?>
            <div class="add-comment-form mt-4">
                <h5 class="mb-3">
                    <i class="fas fa-reply me-2"></i>
                    Ajouter une réponse
                </h5>
                <form method="post" action="/?controller=forum&action=addComment">
                    <input type="hidden" name="topic_id" value="<?= $topic->getId() ?>">

                    <div class="mb-3">
                        <textarea
                            name="content"
                            class="form-control comment-textarea <?= isset($errors['content']) ? 'is-invalid' : '' ?>"
                            rows="5"
                            placeholder="Écrivez votre réponse ici..."
                            required></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['content']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-submit-comment">
                            <i class="fas fa-paper-plane me-2"></i>
                            Publier la réponse
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4">
                <i class="fas fa-lock me-2"></i>
                Vous devez être <a href="/?controller=user&action=login">connecté</a> pour répondre à ce sujet.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>