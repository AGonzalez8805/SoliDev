<?php require_once APP_ROOT . '/views/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="card-title">Confirmation de compte</h3>
                    <p class="card-text">
                        <?= htmlspecialchars($message ?? "Statut inconnu.") ?>
                    </p>
                    <a href="/?controller=auth&action=login" class="btn btn-primary mt-3">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/footer.php'; ?>