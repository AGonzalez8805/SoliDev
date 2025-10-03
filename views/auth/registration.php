<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- Section principale de la page d'inscription -->
<main class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <!-- Carte centrale contenant le formulaire -->
    <div class="solidev-card p-4" style="max-width: 400px; width: 100%;">
        <!-- Logo de l'application -->
        <div class="text-center mb-4">
            <img src="/assets/images/logo-png.png" alt="" class="form-logo">
        </div>
        <!-- Titre du formulaire -->
        <h2 class="form-title text-center mb-4">Inscription à SoliDev</h2>
        <!-- Formulaire d'inscription -->
        <div id="registrationMessage" class="alert alert-success d-none"></div>
        <form id="registrationForm" method="post" action="/?controller=user&action=register">
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Bois" required>
                    <div class="valid-feedback">Saisie correcte</div>
                    <div class="invalid-feedback">Veuillez renseigner votre nom.</div>
                </div>
                <div class="col">
                    <label for="firstName" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Julie" required>
                    <div class="valid-feedback">Saisie correcte</div>
                    <div class="invalid-feedback">Veuillez renseigner votre prénom.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="exemple@solidev.com" required>
                <div class="valid-feedback">Adresse email valide</div>
                <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                <small class="text-muted" style="text-decoration: underline;">Le mot de passe doit contenir :</small>
                <ul class="list-unstyled small mt-1 mb-3" id="passwordCriteria">
                    <li id="length" class="text-danger">Au moins 9 caractères</li>
                    <li id="uppercase" class="text-danger">Une majuscule</li>
                    <li id="lowercase" class="text-danger">Une minuscule</li>
                    <li id="number" class="text-danger">Un chiffre</li>
                    <li id="special" class="text-danger">Un caractère spécial</li>
                </ul>
            </div>

            <div class="mb-3">
                <label for="validatePassword" class="form-label">Confirmer votre mot de passe</label>
                <input type="password" class="form-control" id="validatePassword" name="validatePassword" placeholder="••••••••" required>
                <div class="valid-feedback">Mot de passe confirmé</div>
                <div class="invalid-feedback">Les mots de passe ne correspondent pas.</div>
            </div>

            <button type="submit" id="register" class="btn solidev-btn w-100">S'inscrire</button>
        </form>

    </div>
</main>

<?php require_once APP_ROOT . '/views/footer.php'; ?>