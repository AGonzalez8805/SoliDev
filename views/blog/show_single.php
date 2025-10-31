<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="blog-header">
    <div class="container">
        <h1><?= htmlspecialchars($blog->getTitle()) ?></h1>
    </div>
</section>

<div class="container">
    <?php if ($blog->getCoverImage()): ?>
        <img src="<?= htmlspecialchars($blog->getCoverImage()) ?>" alt="Image de couverture" class="cover-image">
    <?php endif; ?>

    <p><strong>Catégorie:</strong> <?= htmlspecialchars($blog->getCategory()) ?></p>

    <div class="article-content">
        <?= $Parsedown->text($blog->getContent()) ?>
    </div>

    <?php
    $createdAt = $blog->getCreatedAt();
    $formattedDate = $createdAt ? (new \DateTime($createdAt))->format('d/m/Y H:i') : '';
    ?>
    <p class="article-meta">
        Par <strong><?= htmlspecialchars($blog->getAuthorName()) ?></strong>
        • <?= htmlspecialchars($blog->getCategory()) ?>
        • <?= $formattedDate ?>
    </p>

    <div class="article-actions" style="margin: 20px;">
        <a href="/?controller=blog&action=list" class="btn btn-secondary">Retour aux articles</a>
    </div>

    <!-- ==================== SECTION COMMENTAIRES ==================== -->
    <section class="comments-section" id="comments">
        <div class="comments-header">
            <h2 class="comments-title">
                <i class="fas fa-comments"></i>
                Commentaires
                <span class="comments-count">(<?= $commentsCount ?>)</span>
            </h2>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <span><?= htmlspecialchars($_SESSION['success_message']) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <span><?= htmlspecialchars($_SESSION['error_message']) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Formulaire de commentaire -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form class="comment-form" method="POST" action="/?controller=blog&action=addComment">
                <input type="hidden" name="blog_id" value="<?= $blog->getId() ?>">

                <label for="comment-content" class="comment-form-label">
                    <i class="fas fa-pen me-2"></i>Ajouter un commentaire
                </label>

                <textarea
                    id="comment-content"
                    name="content"
                    class="comment-textarea"
                    placeholder="Partagez votre avis, vos questions ou vos remarques..."
                    required
                    minlength="3"
                    maxlength="2000"></textarea>

                <div class="comment-form-footer">
                    <span class="comment-hint">
                        <i class="fas fa-info-circle"></i>
                        Minimum 3 caractères, maximum 2000
                    </span>
                    <button type="submit" class="comment-submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        Publier
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="comment-login-prompt">
                <p>
                    <i class="fas fa-lock"></i>
                    Vous devez être connecté pour laisser un commentaire
                </p>
                <a href="/?controller=auth&action=login" class="comment-login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </a>
            </div>
        <?php endif; ?>

        <!-- Liste des commentaires -->
        <?php if (empty($comments)): ?>
            <div class="comments-empty">
                <div class="comments-empty-icon">
                    <i class="far fa-comment-dots"></i>
                </div>
                <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            </div>
        <?php else: ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <div class="comment-header">
                            <?php if ($comment->getUserPhoto()): ?>
                                <img
                                    src="<?= htmlspecialchars($comment->getUserPhoto()) ?>"
                                    alt="<?= htmlspecialchars($comment->getUserFullName()) ?>"
                                    class="comment-avatar">
                            <?php else: ?>
                                <div class="comment-avatar-placeholder">
                                    <?= strtoupper(substr($comment->getUserFullName(), 0, 1)) ?>
                                </div>
                            <?php endif; ?>

                            <div class="comment-author-info">
                                <div class="comment-author-name">
                                    <?= htmlspecialchars($comment->getUserFullName()) ?>
                                </div>
                                <div class="comment-date">
                                    <?php
                                    $commentDate = $comment->getCreatedAt();
                                    if ($commentDate) {
                                        $date = new \DateTime($commentDate);
                                        $now = new \DateTime();
                                        $diff = $now->diff($date);

                                        if ($diff->days == 0) {
                                            if ($diff->h == 0) {
                                                echo "Il y a " . $diff->i . " minute" . ($diff->i > 1 ? 's' : '');
                                            } else {
                                                echo "Il y a " . $diff->h . " heure" . ($diff->h > 1 ? 's' : '');
                                            }
                                        } elseif ($diff->days == 1) {
                                            echo "Hier à " . $date->format('H:i');
                                        } elseif ($diff->days < 7) {
                                            echo "Il y a " . $diff->days . " jours";
                                        } else {
                                            echo $date->format('d/m/Y à H:i');
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="comment-content">
                            <?= nl2br(htmlspecialchars($comment->getContent())) ?>
                        </div>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment->getUserId()): ?>
                            <div class="comment-actions">
                                <button class="comment-action-btn" onclick="alert('Fonctionnalité à venir')">
                                    <i class="fas fa-trash"></i>
                                    Supprimer
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>