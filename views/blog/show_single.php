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

    <div class="article-actions">
        <a href="/?controller=blog&action=comment&id=<?= $blog->getId() ?>" class="btn btn-primary">Commentaire</a>
        <a href="/?controller=blog&action=list" class="btn btn-secondary">Retour aux articles</a>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>