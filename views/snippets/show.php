<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="snippet-detail container">
    <h1><?= htmlspecialchars($snippet->getTitle()) ?></h1>
    <p class="snippet-meta">
        Langage : <strong><?= htmlspecialchars($snippet->getLanguage()) ?></strong> |
        Catégorie : <strong><?= htmlspecialchars($snippet->getCategory()) ?></strong>
    </p>

    <p class="snippet-description"><?= nl2br(htmlspecialchars($snippet->getDescription())) ?></p>

    <h3>Code :</h3>
    <pre><code class="language-<?= htmlspecialchars($snippet->getLanguage()) ?>">
<?= htmlspecialchars($snippet->getCode()) ?>
    </code></pre>

    <?php if (!empty($snippet->getUsageExample())): ?>
        <h3>Exemple d'utilisation :</h3>
        <pre><code><?= htmlspecialchars($snippet->getUsageExample()) ?></code></pre>
    <?php endif; ?>

    <?php if (!empty($snippet->getTags())): ?>
        <p class="snippet-tags">
            <?php foreach (explode(',', $snippet->getTags()) as $tag): ?>
                <span class="snippet-tag">#<?= htmlspecialchars(trim($tag)) ?></span>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>

    <a href="/?controller=snippets&action=snippets" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>