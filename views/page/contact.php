<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="contact flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="d-flex align-items-center justify-content-center flex-wrap">
        <div class="text-center me-5 mb-4 mb-md-0">
            <img src="/assets/images/logo-png.png" alt="SoliDev Logo" class="contact-logo img-fluid">
        </div>

        <div class="solidev-contact">
            <h2 class="form-title text-center mb-4">Contacter SoliDev</h2>

            <!-- Messages de succès ou d'erreur -->
            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="?controller=page&action=contact">
                <div class="row">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Bois" required>
                    </div>
                    <div class="col-md-6">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Julie" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@solidev.com" required>
                    </div>
                    <div class="col-md-6">
                        <label for="number" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="number" name="number" placeholder="06 01 02 03 04">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Sujet</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="blog" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn solidev-btn w-100">Envoyer</button>
            </form>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>