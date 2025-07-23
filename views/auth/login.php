<?php require_once APP_ROOT . '/views/header.php'; ?>
<main class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="solidev-card p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <img src="/assets/images/logo-png.png" alt="" class="form-logo">
        </div>
        <h2 class="form-title text-center mb-4">Connexion à SoliDev</h2>
        <form>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" placeholder="exemple@solidev.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn solidev-btn w-100">Se connecter</button>
        </form>
        <div class="text-center mt-3">
            <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>
    </div>
</main>



<?php require_once APP_ROOT . '/views/footer.php'; ?>