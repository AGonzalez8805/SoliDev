<?php require_once APP_ROOT . '/views/header.php'; ?>
<div class="container my-5">
    <div id="previewContainer">
        <h2 id="previewTitle" aria-live="polite">Titre de l'article</h2>
        <div class="text-muted mb-3">
            Par <strong>Utilisateur</strong> •
            <span id="previewDate"></span> •
            <span id="previewCategory"></span>
        </div>
        <div id="previewContent"></div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/footer.php'; ?>