<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="container py-5 text-center">
    <div class="notLogin">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <h1 class="error-title">Connexion requise</h1>

            <p class="error-message">
                Vous devez être connecté pour accéder à cette page.
            </p>

            <a href="/?controller=auth&action=login" class="btn-login-error">
                Se connecter
            </a>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>