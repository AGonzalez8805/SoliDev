<!-- Inclusion du fichier d'en-tête de la page -->
<?php require_once  APP_ROOT . '/views/header.php'; ?>

<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Bienvenue sur SoliDev</h1>
            <p class="hero-subtitle">
                La plateforme collaborative qui réunit les développeurs pour partager, apprendre et grandir ensemble.
                Forum d'entraide, blog technique, showcase de projets et bibliothèque de snippets.
            </p>
            <div class="hero-buttons d-flex flex-wrap justify-content-center">
                <a href="/?controller=auth&action=registration" class="btn-hero-primary">
                    <i class="fas fa-rocket me-2"></i>Rejoindre la communauté
                </a>
                <a href="#features" class="btn-hero-secondary">
                    <i class="fas fa-info-circle me-2"></i>Découvrir les fonctionnalités
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Tout ce dont vous avez besoin</h2>
            <p class="section-subtitle">
                SoliDev vous offre tous les outils pour développer vos compétences et collaborer efficacement
            </p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Forum d'entraide</h3>
                    <p class="feature-description">
                        Posez vos questions, partagez vos solutions et participez aux discussions techniques avec une communauté bienveillante.
                    </p>
                    <a href="/?controller=forum&action=forum" class="feature-link">
                        Accéder au forum <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-blog"></i>
                    </div>
                    <h3 class="feature-title">Blog technique</h3>
                    <p class="feature-description">
                        Rédigez et découvrez des articles techniques, tutoriels et analyses approfondies sur les dernières technologies.
                    </p>
                    <a href="/blog?action=show" class="feature-link">
                        Lire le blog <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="feature-title">Showcase projets</h3>
                    <p class="feature-description">
                        Présentez vos créations, découvrez des projets inspirants et collaborez avec d'autres développeurs.
                    </p>
                    <a href="/project?action=" class="feature-link">
                        Voir les projets <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="feature-title">Snippets utiles</h3>
                    <p class="feature-description">
                        Partagez et trouvez des extraits de code réutilisables pour optimiser votre productivité au quotidien.
                    </p>
                    <a href="/snippets?action=" class="feature-link">
                        Parcourir les snippets <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">1,247</span>
                    <span class="stat-label">Membres actifs</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">3,891</span>
                    <span class="stat-label">Messages postés</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">156</span>
                    <span class="stat-label">Articles publiés</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">892</span>
                    <span class="stat-label">Snippets partagés</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Activity -->
<section class="activity-section">
    <div class="container">
        <div class="section-title">
            <h2>Activité récente</h2>
            <p class="section-subtitle">Restez au courant des dernières contributions de la communauté</p>
        </div>

        <div class="activity-tabs">
            <button class="activity-tab active" data-tab="forum">Forum</button>
            <button class="activity-tab" data-tab="blog">Blog</button>
            <button class="activity-tab" data-tab="projects">Projets</button>
        </div>

        <div id="forum-content" class="tab-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Comment optimiser les requêtes SQL ?</h4>
                            <span class="activity-meta">il y a 2h</span>
                        </div>
                        <p class="activity-excerpt">
                            Je cherche des conseils pour améliorer les performances de mes requêtes sur une base de données MySQL avec plusieurs millions d'enregistrements...
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Problème avec React useEffect</h4>
                            <span class="activity-meta">il y a 4h</span>
                        </div>
                        <p class="activity-excerpt">
                            Mon useEffect se déclenche en boucle infinie malgré mes dépendances. Quelqu'un peut-il m'aider à identifier le problème ?
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Meilleure architecture pour une API REST</h4>
                            <span class="activity-meta">il y a 6h</span>
                        </div>
                        <p class="activity-excerpt">
                            Je développe une API avec Express.js et je m'interroge sur la meilleure façon d'organiser mon code pour un projet qui va grandir...
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Git : annuler un commit poussé</h4>
                            <span class="activity-meta">il y a 1j</span>
                        </div>
                        <p class="activity-excerpt">
                            J'ai poussé un commit avec des erreurs sur la branche principale. Quelle est la meilleure méthode pour l'annuler proprement ?
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="blog-content" class="tab-content" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Les nouveautés JavaScript 2024</h4>
                            <span class="activity-meta">il y a 1j</span>
                        </div>
                        <p class="activity-excerpt">
                            Découvrez les dernières fonctionnalités de JavaScript qui vont révolutionner votre façon de coder. Pattern matching, nouvelles méthodes d'arrays...
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Guide complet Docker pour développeurs</h4>
                            <span class="activity-meta">il y a 2j</span>
                        </div>
                        <p class="activity-excerpt">
                            Un tutoriel step-by-step pour maîtriser Docker, depuis les concepts de base jusqu'au déploiement en production avec des exemples concrets.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="projects-content" class="tab-content" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">App de gestion de tâches React/Node</h4>
                            <span class="activity-meta">il y a 3h</span>
                        </div>
                        <p class="activity-excerpt">
                            Une application complète de to-do avec authentification, temps réel et API REST. Stack : React, Node.js, MongoDB, Socket.io.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-item">
                        <div class="activity-header">
                            <h4 class="activity-title">Extension VS Code pour snippets</h4>
                            <span class="activity-meta">il y a 1j</span>
                        </div>
                        <p class="activity-excerpt">
                            Extension personnalisée qui synchronise vos snippets favoris avec votre compte GitHub. TypeScript, API VS Code, GitHub API.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Prêt à rejoindre SoliDev ?</h2>
        <p class="cta-text">
            Rejoignez une communauté de développeurs passionnés et faites évoluer vos compétences ensemble
        </p>
        <a href="/?controller=auth&action=registration" class="btn-cta">
            <i class="fas fa-user-plus me-2"></i>S'inscrire maintenant
        </a>
    </div>
</section>

<!-- Inclusion du pied de page -->
<?php require_once  APP_ROOT . '/views/footer.php'; ?>