<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="container-fluid">
    <div class="dashboard-container">
        <!-- En-tête du dashboard -->
        <div class="dashboard-header">
            <form action="/?controller=user&action=uploadPhoto" method="post" enctype="multipart/form-data" id="photoForm">
                <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;">
                <label for="photoInput" class="profile-photo-trigger">
                    <div class="profile-avatar">
                        <?php if (!empty($user->getPhoto())): ?>
                            <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" id="avatarImg" class="profile-photo-header">
                        <?php else: ?>
                            <img src="" alt="Profil" id="avatarImg" class="profile-photo-header" style="display:none;">
                        <?php endif; ?>
                    </div>
                </label>
            </form>
            <h1><strong><?= htmlspecialchars(ucwords($user->getName())) ?> <?= htmlspecialchars(ucwords($user->getFirstName())) ?></strong></h1>

            <div class="social-links">
                <a href="<?= htmlspecialchars($user->getGithubUrl() ?? '#') ?>" class="github-link <?= $user->getGithubUrl() ? '' : 'disabled' ?>" target="_blank">
                    <i class="fab fa-github"></i> GitHub
                </a>
                <a href="<?= htmlspecialchars($user->getLinkedinUrl() ?? '#') ?>" class="linkedin-link <?= $user->getLinkedinUrl() ? '' : 'disabled' ?>" target="_blank">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
                <a href="<?= htmlspecialchars($user->getWebsiteUrl() ?? '#') ?>" class="website-link <?= $user->getWebsiteUrl() ? '' : 'disabled' ?>" target="_blank">
                    <i class="fas fa-globe"></i> Portfolio
                </a>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card text-center">
                        <div class="stats-number"><?= $stats['forum_posts'] ?></div>
                        <div><i class="fas fa-comments me-2"></i>Messages Forum</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card text-center">
                        <div class="stats-number"><?= $stats['blog_posts'] ?></div>
                        <div><i class="fas fa-blog me-2"></i>Posts Blog</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card text-center">
                        <div class="stats-number"><?= $stats['projects'] ?></div>
                        <div><i class="fas fa-project-diagram me-2"></i>Projets Partagés</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card text-center">
                        <div class="stats-number"><?= $stats['snippets'] ?></div>
                        <div><i class="fas fa-code me-2"></i>Snippets</div>
                    </div>
                </div>
            </div>

            <!-- Onglets de navigation -->
            <div class="dashboard-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-tab" type="button">
                            <i class="fas fa-user me-2"></i>Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#drafts-tab" type="button">
                            <i class="fas fa-file-alt me-2"></i>Brouillons
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security-tab" type="button">
                            <i class="fas fa-shield-alt me-2"></i>Sécurité
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notifications-tab" type="button">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#preferences-tab" type="button">
                            <i class="fas fa-cog me-2"></i>Préférences
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Contenu des onglets -->
            <div class="tab-content">
                <!-- Onglet Profil -->
                <div class="tab-pane fade show active" id="profile-tab">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Modifier mon profil
                                </h3>
                                <form id="profileForm" action="/?controller=user&action=updateProfile" method="post">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="firstName" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($user->getName()) ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="githubUrl" class="form-label"><i class="fab fa-github me-2"></i>GitHub</label>
                                            <input type="url" class="form-control" id="githubUrl" name="githubUrl" value="<?= htmlspecialchars($user->getGithubUrl() ?? '') ?>" placeholder="https://github.com/username">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="linkedinUrl" class="form-label"><i class="fab fa-linkedin me-2"></i>LinkedIn</label>
                                            <input type="url" class="form-control" id="linkedinUrl" name="linkedinUrl" value="<?= htmlspecialchars($user->getLinkedinUrl() ?? '') ?>" placeholder="https://linkedin.com/in/username">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="websiteUrl" class="form-label"><i class="fas fa-globe me-2"></i>Site web</label>
                                            <input type="url" class="form-control" id="websiteUrl" name="websiteUrl" value="<?= htmlspecialchars($user->getWebsiteUrl() ?? '') ?>" placeholder="https://monsite.com">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user->getBio() ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="skills" class="form-label">Compétences (séparées par des virgules)</label>
                                        <input type="text" class="form-control" id="skills" name="skills" value="<?= htmlspecialchars($user->getSkills() ?? '') ?>" placeholder="JavaScript, PHP, React...">
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-history me-2"></i>
                                    Activité récente
                                </h3>
                                <?php foreach ($activities as $activity): ?>
                                    <div class="activity-item">
                                        <div>
                                            <?php
                                            $icon = match ($activity['type']) {
                                                'snippet' => 'fas fa-code',
                                                'comment' => 'fas fa-comment',
                                                'project' => 'fas fa-project-diagram',
                                                'like' => 'fas fa-heart',
                                                default => 'fas fa-info-circle',
                                            };
                                            ?>
                                            <i class="<?= $icon ?> me-2"></i>
                                            <?= htmlspecialchars($activity['message']) ?>
                                        </div>
                                        <div class="activity-time">
                                            <?= (new DateTime($activity['created_at']))->diff(new DateTime())->format('%a jours, %h heures') ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Actions rapides -->
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-bolt me-2"></i>
                                    Actions rapides
                                </h3>
                                <div class="d-grid gap-2">
                                    <a href="/?controller=forum&action=createPost" class="btn btn-primary-custom">
                                        <i class="fas fa-pen me-2"></i>
                                        Nouveau poste pour le forum
                                    </a>
                                    <a href="/?controller=blog&action=createBlog" class="btn btn-primary-custom">
                                        <i class="fas fa-plus me-2"></i>
                                        Nouvel article pour le blog
                                    </a>
                                    <a href="/project" class="btn btn-primary-custom">
                                        <i class="fas fa-project-diagram me-2"></i>
                                        Nouveau projet
                                    </a>
                                    <a href="/snippets" class="btn btn-primary-custom">
                                        <i class="fas fa-code me-2"></i>
                                        Nouveau snippet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Brouillons -->
                <div class="tab-pane fade" id="drafts-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Mes brouillons
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Sécurité -->
                <div class="tab-pane fade" id="security-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-key me-2"></i>
                                    Changer le mot de passe
                                </h3>
                                <form id="passwordForm">
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Mot de passe actuel</label>
                                        <input type="password" class="form-control" id="currentPassword" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="newPassword" required>
                                        <div class="password-strength">
                                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                        </div>
                                        <small class="text-muted" id="passwordStrengthText">Entrez un nouveau mot de passe</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="confirmPassword" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        Changer le mot de passe
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    Authentification à deux facteurs
                                </h3>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Authentification par SMS</h6>
                                            <small class="text-muted">Recevez un code par SMS pour sécuriser votre compte</small>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="smsAuth">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Application d'authentification</h6>
                                            <small class="text-muted">Utilisez Google Authenticator ou similaire</small>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="appAuth">
                                        </div>
                                    </div>
                                </div>

                                <hr style="border-color: rgba(255,255,255,0.2);">

                                <h6>Sessions actives</h6>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-desktop me-2"></i>
                                            Windows - Chrome
                                            <small class="d-block text-muted">Paris, France - Actuellement</small>
                                        </div>
                                        <span class="badge-custom">Actuelle</span>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-mobile me-2"></i>
                                            iPhone - Safari
                                            <small class="d-block text-muted">Paris, France - Il y a 2 heures</small>
                                        </div>
                                        <button class="btn btn-outline-custom btn-sm">Déconnecter</button>
                                    </div>
                                </div>

                                <button class="btn btn-danger-custom btn-sm mt-3">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Déconnecter toutes les sessions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Notifications -->
                <div class="tab-pane fade" id="notifications-tab">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-bell me-2"></i>
                                    Notifications récentes
                                </h3>

                                <div class="notification-item unread">
                                    <div class="notification-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div><strong>Marie Dupont</strong> a aimé votre snippet "Validation de formulaire"</div>
                                        <small class="text-muted">Il y a 30 minutes</small>
                                    </div>
                                </div>

                                <div class="notification-item unread">
                                    <div class="notification-icon">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div><strong>Jean Martin</strong> a commenté votre projet "API REST"</div>
                                        <small class="text-muted">Il y a 1 heure</small>
                                    </div>
                                </div>

                                <div class="notification-item">
                                    <div class="notification-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div>Votre article a été mis en avant sur la page d'accueil</div>
                                        <small class="text-muted">Il y a 2 heures</small>
                                    </div>
                                </div>

                                <div class="notification-item">
                                    <div class="notification-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div><strong>Sophie Leroy</strong> a commencé à vous suivre</div>
                                        <small class="text-muted">Il y a 1 jour</small>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-custom">
                                        <i class="fas fa-check-double me-2"></i>
                                        Marquer tout comme lu
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-cog me-2"></i>
                                    Paramètres de notification
                                </h3>

                                <div class="preference-item">
                                    <div>
                                        <h6>Nouveaux commentaires</h6>
                                        <small class="text-muted">Sur vos publications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Nouveaux likes</h6>
                                        <small class="text-muted">Sur vos publications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Nouveaux followers</h6>
                                        <small class="text-muted">Quand quelqu'un vous suit</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Newsletter hebdomadaire</h6>
                                        <small class="text-muted">Résumé de la semaine</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Préférences -->
                <div class="tab-pane fade" id="preferences-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-palette me-2"></i>
                                    Apparence
                                </h3>

                                <div class="preference-item">
                                    <div>
                                        <h6>Mode sombre</h6>
                                        <small class="text-muted">Interface en mode sombre</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="darkMode">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="language" class="form-label">Langue</label>
                                    <select class="form-control" id="language">
                                        <option value="fr" selected>Français</option>
                                        <option value="en">English</option>
                                        <option value="es">Español</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Fuseau horaire</label>
                                    <select class="form-control" id="timezone">
                                        <option value="Europe/Paris" selected>Europe/Paris (GMT+1)</option>
                                        <option value="America/New_York">America/New_York (GMT-5)</option>
                                        <option value="Asia/Tokyo">Asia/Tokyo (GMT+9)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-card">
                                <h3 class="section-title">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Confidentialité
                                </h3>

                                <div class="preference-item">
                                    <div>
                                        <h6>Profil public</h6>
                                        <small class="text-muted">Votre profil est visible par tous</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Afficher l'activité en ligne</h6>
                                        <small class="text-muted">Les autres peuvent voir quand vous êtes en ligne</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Indexation par les moteurs de recherche</h6>
                                        <small class="text-muted">Permettre l'indexation de votre profil</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>

                                <hr style="border-color: rgba(255,255,255,0.2);">

                                <h6 class="text-danger">Zone de danger</h6>
                                <div class="mt-3">
                                    <button class="btn btn-danger-custom">
                                        <i class="fas fa-user-times me-2"></i>
                                        Désactiver le compte
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-danger-custom">
                                        <i class="fas fa-trash me-2"></i>
                                        Supprimer le compte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>