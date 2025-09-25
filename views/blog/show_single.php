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
    <p class="article-meta">
        Par <strong><?= htmlspecialchars($blog->getAuthorName()) ?></strong>
        • <?= htmlspecialchars($blog->getCategory()) ?>
        <?php
        $createdAt = $blog->getCreatedAt();
        $formattedDate = $createdAt ? (new \DateTime($createdAt))->format('d/m/Y H:i') : '';
        ?>
    <p class="article-meta">
        Par <strong><?= htmlspecialchars($blog->getAuthorName()) ?></strong>
        • <?= htmlspecialchars($blog->getCategory()) ?>
        • <?= $formattedDate ?>
    </p>

    </p>

    <div class="article-actions" style="margin: 20px;">
        <a href="/?controller=blog&action=comment&id=<?= $blog->getId() ?>" class="btn btn-primary">Commentaire</a>
        <a href="/?controller=blog&action=list" class="btn btn-secondary">Retour aux articles</a>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>