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
            <h1 id="userFullName"><strong><?= htmlspecialchars(ucwords($user->getName())) ?> <?= htmlspecialchars(ucwords($user->getFirstName())) ?></strong></h1>

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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#favorites-tab" type="button">
                            <i class="fas fa-heart me-2"></i>Favoris
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
                                            <input type="text" class="form-control" id="firstName" name="firstName"
                                                value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="<?= htmlspecialchars($user->getName()) ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?= htmlspecialchars($user->getEmail()) ?>">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="githubUrl" class="form-label"><i class="fab fa-github me-2"></i>GitHub</label>
                                            <input type="url" class="form-control" id="githubUrl" name="github_url"
                                                value="<?= htmlspecialchars($user->getGithubUrl() ?? '') ?>" placeholder="https://github.com/username">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="linkedinUrl" class="form-label"><i class="fab fa-linkedin me-2"></i>LinkedIn</label>
                                            <input type="url" class="form-control" id="linkedinUrl" name="linkedin_url"
                                                value="<?= htmlspecialchars($user->getLinkedinUrl() ?? '') ?>" placeholder="https://linkedin.com/in/username">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="websiteUrl" class="form-label"><i class="fas fa-globe me-2"></i>Site web</label>
                                            <input type="url" class="form-control" id="websiteUrl" name="website_url"
                                                value="<?= htmlspecialchars($user->getWebsiteUrl() ?? '') ?>" placeholder="https://monsite.com">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4"
                                            placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user->getBio() ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="skills" class="form-label">Compétences (séparées par des virgules)</label>
                                        <input type="text" class="form-control" id="skills" name="skills"
                                            value="<?= htmlspecialchars($user->getSkills() ?? '') ?>" placeholder="JavaScript, PHP, React...">
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
                                                'blog' => 'fas fa-blog',
                                                'forum' => 'fas fa-comments',
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
                                    <a href="/?controller=project&action=create" class="btn btn-primary-custom">
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
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                        </div>
                                        <small class="text-muted" id="passwordStrengthText">Entrez un nouveau mot de passe (min. 8 caractères)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        Changer le mot de passe
                                    </button>
                                </form>
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

                                <div id="notificationsList">
                                    <?php if (!empty($notifications)): ?>
                                        <?php foreach ($notifications as $notif): ?>
                                            <div class="notification-item <?= !$notif['is_read'] ? 'unread' : '' ?>" data-notification-id="<?= $notif['id'] ?>">
                                                <div class="notification-icon">
                                                    <?php
                                                    $icon = match ($notif['type']) {
                                                        'like' => 'fas fa-heart',
                                                        'comment' => 'fas fa-comment',
                                                        'featured' => 'fas fa-star',
                                                        'follow' => 'fas fa-user-plus',
                                                        default => 'fas fa-bell',
                                                    };
                                                    ?>
                                                    <i class="<?= $icon ?>"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div><?= htmlspecialchars($notif['message']) ?></div>
                                                    <small class="text-muted">
                                                        <?php
                                                        $date = new DateTime($notif['created_at']);
                                                        $now = new DateTime();
                                                        $diff = $now->diff($date);

                                                        if ($diff->days > 0) {
                                                            echo "Il y a " . $diff->days . " jour" . ($diff->days > 1 ? 's' : '');
                                                        } elseif ($diff->h > 0) {
                                                            echo "Il y a " . $diff->h . " heure" . ($diff->h > 1 ? 's' : '');
                                                        } elseif ($diff->i > 0) {
                                                            echo "Il y a " . $diff->i . " minute" . ($diff->i > 1 ? 's' : '');
                                                        } else {
                                                            echo "À l'instant";
                                                        }
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                            <p>Aucune notification pour le moment</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($notifications)): ?>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-outline-custom" id="markAllAsRead">
                                            <i class="fas fa-check-double me-2"></i>
                                            Marquer tout comme lu
                                        </button>
                                    </div>
                                <?php endif; ?>
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
                                        <input class="form-check-input notification-setting"
                                            type="checkbox"
                                            id="notifyComments"
                                            data-preference="notify_comments"
                                            <?= $preferences['notify_comments'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Nouveaux likes</h6>
                                        <small class="text-muted">Sur vos publications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input notification-setting"
                                            type="checkbox"
                                            id="notifyLikes"
                                            data-preference="notify_likes"
                                            <?= $preferences['notify_likes'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Nouveaux followers</h6>
                                        <small class="text-muted">Quand quelqu'un vous suit</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input notification-setting"
                                            type="checkbox"
                                            id="notifyFollowers"
                                            data-preference="notify_followers"
                                            <?= $preferences['notify_followers'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Newsletter hebdomadaire</h6>
                                        <small class="text-muted">Résumé de la semaine</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input notification-setting"
                                            type="checkbox"
                                            id="notifyNewsletter"
                                            data-preference="notify_newsletter"
                                            <?= $preferences['notify_newsletter'] ? 'checked' : '' ?>>
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
                                        <input class="form-check-input preference-setting"
                                            type="checkbox"
                                            id="darkMode"
                                            data-preference="theme"
                                            data-value-on="dark"
                                            data-value-off="light"
                                            <?= $preferences['theme'] === 'dark' ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="language" class="form-label">Langue</label>
                                    <select class="form-control preference-setting"
                                        id="language"
                                        data-preference="language">
                                        <option value="fr" <?= $preferences['language'] === 'fr' ? 'selected' : '' ?>>Français</option>
                                        <option value="en" <?= $preferences['language'] === 'en' ? 'selected' : '' ?>>English</option>
                                        <option value="es" <?= $preferences['language'] === 'es' ? 'selected' : '' ?>>Español</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Fuseau horaire</label>
                                    <select class="form-control preference-setting"
                                        id="timezone"
                                        data-preference="timezone">
                                        <option value="Europe/Paris" <?= $preferences['timezone'] === 'Europe/Paris' ? 'selected' : '' ?>>Europe/Paris (GMT+1)</option>
                                        <option value="America/New_York" <?= $preferences['timezone'] === 'America/New_York' ? 'selected' : '' ?>>America/New_York (GMT-5)</option>
                                        <option value="Asia/Tokyo" <?= $preferences['timezone'] === 'Asia/Tokyo' ? 'selected' : '' ?>>Asia/Tokyo (GMT+9)</option>
                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary-custom" id="saveAppearanceSettings">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les modifications
                                </button>
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
                                        <input class="form-check-input privacy-setting"
                                            type="checkbox"
                                            id="profilePublic"
                                            data-preference="profile_public"
                                            <?= $preferences['profile_public'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Afficher l'activité en ligne</h6>
                                        <small class="text-muted">Les autres peuvent voir quand vous êtes en ligne</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input privacy-setting"
                                            type="checkbox"
                                            id="showOnlineStatus"
                                            data-preference="show_online_status"
                                            <?= $preferences['show_online_status'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <div class="preference-item">
                                    <div>
                                        <h6>Indexation par les moteurs de recherche</h6>
                                        <small class="text-muted">Permettre l'indexation de votre profil</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input privacy-setting"
                                            type="checkbox"
                                            id="allowSearchIndexing"
                                            data-preference="allow_search_indexing"
                                            <?= $preferences['allow_search_indexing'] ? 'checked' : '' ?>>
                                    </div>
                                </div>

                                <hr>

                                <h6 class="text-danger">Zone de danger</h6>
                                <div class="mt-3">
                                    <button class="btn btn-danger-custom" id="deactivateAccount">
                                        <i class="fas fa-user-times me-2"></i>
                                        Désactiver le compte
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-danger-custom" id="deleteAccount">
                                        <i class="fas fa-trash me-2"></i>
                                        Supprimer le compte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Onglet Favoris -->
                <div class="tab-pane fade" id="favorites-tab">
                    <div class="row">
                        <?php if (!empty($favorites) && is_array($favorites)): ?>
                            <?php foreach ($favorites as $snippet): ?>
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <article class="snippet-card">
                                        <!-- En-tête du snippet -->
                                        <div class="snippet-header">
                                            <div class="snippet-language">
                                                <i class="fas fa-code"></i>
                                                <?= htmlspecialchars($snippet->getLanguage()) ?>
                                            </div>
                                            <div class="snippet-actions">
                                                <button class="action-btn" title="Copier">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button class="action-btn favorite-btn active" title="Retirer des favoris" data-snippet-id="<?= $snippet->getId() ?>">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Titre et description -->
                                        <div class="snippet-content">
                                            <h3 class="snippet-title">
                                                <a href="/?controller=snippets&action=show&id=<?= $snippet->getId() ?>">
                                                    <?= htmlspecialchars($snippet->getTitle()) ?>
                                                </a>
                                            </h3>
                                            <p class="snippet-description">
                                                <?= htmlspecialchars($snippet->getDescription()) ?>
                                            </p>
                                        </div>

                                        <!-- Footer -->
                                        <div class="snippet-footer">
                                            <div class="snippet-author">
                                                <div class="author-avatar">
                                                    <?= strtoupper(substr($snippet->getAuthorName(), 0, 2)) ?>
                                                </div>
                                                <span class="author-name"><?= htmlspecialchars($snippet->getAuthorName()) ?></span>
                                            </div>
                                            <div class="snippet-stats">
                                                <span class="stat-item" title="Vues">
                                                    <i class="fas fa-eye"></i>
                                                    <?= $snippet->getViews() ?? 0 ?>
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="no-snippets">
                                    <i class="fas fa-heart-broken"></i>
                                    <h3>Vous n'avez aucun favori</h3>
                                    <p>Ajoutez des snippets à vos favoris pour les retrouver ici !</p>
                                    <a href="/?controller=snippets&action=snippets" class="btn-add-snippet">
                                        <i class="fas fa-code me-2"></i>Voir tous les snippets
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
</section>
<!-- Toast container Bootstrap -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="profileToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Profil mis à jour avec succès !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>