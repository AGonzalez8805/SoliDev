<?php require_once APP_ROOT . '/views/header.php'; ?>

<main class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="solidev-card p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <img src="/assets/images/logo-png.png" alt="" class="form-logo">
        </div>
        <h2 class="form-title text-center mb-4">Inscription à SoliDev</h2>
        <form>
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" placeholder="Bois" aria-label="First name">
                </div>
                <div class="col">
                    <label for="firstName" class="form-label">Prenom</label>
                    <input type="text" class="form-control" id="firstName" placeholder="Julie" aria-label="Last name">
                </div>
            </div>
            <div class="mb-3 ">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" placeholder="exemple@solidev.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
            </div>
            <div class="mb-3">
                <label for="validatePassword" class="form-label">Confirmer votre mot de passe</label>
                <input type="password" class="form-control" id="validatePassword" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn solidev-btn w-100">S'inscrire</button>
        </form>
    </div>
</main>



<?php require_once APP_ROOT . '/views/footer.php'; ?>