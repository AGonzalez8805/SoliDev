<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="form-header">
    <div class="container">
        <h1 class="mb-2">
            <i class="fas fa-plus-circle me-3"></i>
            Créer un nouveau article
        </h1>
        <p class="lead mb-0"></p>
    </div>
</section>

<form action="/?controller=blog&action=store" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Titre de l’article</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Contenu</label>
        <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i>Publier
    </button>
</form>

<?php require_once APP_ROOT . '/views/footer.php'; ?>