<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Définition du jeu de caractères -->
    <meta charset="UTF-8">
    <!-- Configuration du viewport pour le responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Description de la page (utile pour le SEO) -->
    <meta name="description" content="SoliDev est une plateforme collaborative pour développeurs : forum d'entraide, présentation de projets, partage de snippets et articles de blog techniques.">
    <!-- Mots-clés (optionnel, certains moteurs de recherche ne les utilisent plus) -->
    <meta name="keywords" content="développement, code, forum, projets, snippets, blog, développeurs, SoliDev">
    <!-- Robots (indexation et suivi des liens) -->
    <meta name="robots" content="index, follow">

    <link rel="shortcut icon" href="/assets/images/logo-png.jpg" type="image/x-icon">
    <!-- Préconnexion pour améliorer le chargement des polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Importation des polices Google (Open Sans, Poppins, Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Importation de Bootstrap 5 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Lien vers la feuille de style personnalisée -->
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <title>SoliDev - Votre Solution Web</title>
    <!-- Chargement du thème utilisateur -->
    <script>
        // Charger le thème depuis la session PHP ou localStorage
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['theme'])): ?>
            localStorage.setItem('theme', '<?= $_SESSION['theme'] ?>');
        <?php endif; ?>

        // Appliquer le thème immédiatement pour éviter le flash
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark-theme');
            document.body.classList.add('dark-theme');
        }
    </script>
</head>

<body>
    <header>
        <!-- Barre de navigation Bootstrap -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <!-- Logo du site -->
                <div class="d-flex align-items-center col-md-3 mb-2 mb-md-0 ms-5">
                    <img width="70" src="/assets/images/logo-png.png" alt="Logo SoliDev">
                </div>
                <!-- Bouton pour menu burger sur mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Contenu du menu de navigation -->
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <!-- Récupère l'action de la page via l'URL, ou 'home' par défaut -->
                        <?php $currentPage = $_GET['action'] ?? 'home'; ?>
                        <!-- Liens de navigation avec surlignage de la page active -->
                        <a class="nav-link <?= $currentPage === 'home' ? 'active-link' : '' ?>" href="/?controller=page&action=home">Accueil</a>
                        <a class="nav-link <?= $currentPage === 'forum' ? 'active-link' : '' ?>" href="/?controller=forum&action=forum">Forum</a>
                        <a class="nav-link <?= $currentPage === 'show' ? 'active-link' : '' ?>" href="/?controller=blog&action=show">Blog</a>
                        <a class="nav-link <?= $currentPage === 'project' ? 'active-link' : '' ?>" href="/?controller=project&action=project">Projets</a>
                        <a class="nav-link <?= $currentPage === 'snippets' ? 'active-link' : '' ?>" href="/?controller=snippets&action=snippets">Snippets</a>
                    </div>
                    <!-- Zone à droite de la barre de navigation (connexion/inscription ou espace perso) -->
                    <div class="ms-auto">
                        <!-- Bouton toggle Dark/Light Mode -->
                        <button id="themeToggle" class="btn btn-outline-light me-3" title="Changer le thème">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Affichage conditionnel selon le rôle de l'utilisateur -->
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <!-- Lien vers le tableau de bord admin -->
                                <a href="/?controller=admin&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord</a>
                            <?php else: ?>
                                <!-- Lien vers le compte utilisateur -->
                                <a href="/?controller=user&action=dashboard" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                            <?php endif; ?>
                            <!-- Bouton de déconnexion -->
                            <a href="/?controller=auth&action=logout" class="btn btn-outline-light btn-login me-3">Déconnexion</a>
                        <?php else: ?>
                            <!-- Boutons pour s'inscrire ou se connecter -->
                            <a href="/?controller=auth&action=registration" class="btn btn-outline-light btn-registration me-3">Inscription</a>
                            <a href="/?controller=auth&action=login" class="btn btn-outline-light btn-login me-3">Connexion</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>