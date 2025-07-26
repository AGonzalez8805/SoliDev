<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- Section principale de la page de connexion -->
<section class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <!-- Carte centrale contenant le formulaire de connexion -->
    <div class="solidev-card p-4" style="max-width: 400px; width: 100%;">
        <!-- Logo de l'application -->
        <div class="text-center mb-4">
            <img src="/assets/images/logo-png.png" alt="" class="form-logo">
        </div>
        <!-- Titre du formulaire -->
        <h2 class="form-title text-center mb-4">Connexion à SoliDev</h2>
        <!-- Formulaire de connexion -->
        <form id="loginForm" method="post">
            <!-- Champ email -->
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" placeholder="exemple@solidev.com" required>
                <div class="invalid-feedback">Adresse email invalide</div>
            </div>
            <!-- Champ mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                <div class="invalid-feedback">Mot de passe requis</div>
            </div>
            <!-- Case à cocher pour rester connecté -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
            </div>
            <!-- Bouton de soumission du formulaire -->
            <button type="submit" id="login" class="btn solidev-btn w-100">Se connecter</button>
        </form>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>