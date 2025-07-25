<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/style.css">

    <title>SoliDev - Votre Solution Web</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <div class="d-flex align-items-center col-md-3 mb-2 mb-md-0 ms-5">
                    <img width="70" src="../assets/images/logo-png.png" alt="Logo SoliDev">
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <?php $currentPage = $_GET['action'] ?? 'home'; ?>
                        <a class="nav-link <?= $currentPage === 'home' ? 'active-link' : '' ?>" href="/page?action=home">Accueil</a>
                        <a class="nav-link <?= $currentPage === 'forum' ? 'active-link' : '' ?>" href="/page?action=forum">Forum</a>
                        <a class="nav-link <?= $currentPage === 'show' ? 'active-link' : '' ?>" href="/blog?action=show">Blog</a>
                        <a class="nav-link <?= $currentPage === 'project' ? 'active-link' : '' ?>" href="/project?action=">Projets</a>
                        <a class="nav-link <?= $currentPage === 'snippets' ? 'active-link' : '' ?>" href="/snippets?action=">Snippets</a>
                    </div>
                    <div class="ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="/?controller=admin&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord</a>
                            <?php else: ?>
                                <a href="/?controller=user&action=dashboard" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                            <?php endif; ?>
                            <a href="/?controller=auth&action=logout" class="btn btn-outline-light btn-login me-3">Déconnexion</a> <?php else: ?>
                            <a href="/?controller=auth&action=registration" class="btn btn-outline-light btn-registration me-3">Inscription</a>
                            <a href="/?controller=auth&action=login" class="btn btn-outline-light btn-login me-3">Connexion</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>