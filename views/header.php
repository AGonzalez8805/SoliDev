<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>SoliDev - Votre Solution Web</title>
</head>
<body>
<header>
    <nav style="background-color: #D95D30;" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark ms-5">
                <img width="70" src="../assets/images/logo-png.png" alt="Logo SoliDev">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="/page&action=home">Accueil</a>
                    <a class="nav-link" href="#">Forum</a>
                    <a class="nav-link" href="#">Blog</a>
                    <a class="nav-link" href="#">Contact</a>
                </div>
                <div class="ms-auto">
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <a href="#" class="mr-5 hover:text-stone-200">Tableau de bord</a>
                        <a href="#" class="mr-5 hover:text-stone-200">Déconnexion</a>
                    <?php elseif (isset($_SESSION['user_id'])): ?>
                        <a href="#" class="mr-5 hover:text-stone-200">Mon compte</a>
                        <a href="#" class="mr-5 hover:text-stone-200">Déconnexion</a>
                    <?php else: ?>
                        <a href="/auth&action=login" class="">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>