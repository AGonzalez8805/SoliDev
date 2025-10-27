<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="project-view">
    <div class="container">
        <?php if (isset($project) && $project): ?>
            <div class="project-header">
                <h1 class="project-title">
                    <i class="fas fa-rocket me-2"></i>
                    <?= htmlspecialchars($project->getTitle()) ?>
                </h1>
                <p class="project-shortdesc"><?= htmlspecialchars($project->getShortDescription()) ?></p>
            </div>

            <!-- Image du projet -->
            <?php if ($project->getCoverImage()): ?>
                <div class="project-cover">
                    <img src="<?= htmlspecialchars($project->getCoverImage()) ?>"
                        alt="<?= htmlspecialchars($project->getTitle()) ?>"
                        class="img-fluid rounded shadow">
                </div>
            <?php endif; ?>

            <div class="project-meta mt-4 mb-3">
                <span class="meta-item me-4">
                    <i class="fas fa-user"></i>
                    Propriétaire ID #<?= htmlspecialchars($project->getOwnerId()) ?>
                </span>
                <span class="meta-item me-4">
                    <i class="fas fa-calendar"></i>
                    Créé le <?= (new \DateTime($project->getCreatedAt()))->format('d/m/Y') ?>
                </span>
                <span class="meta-item me-4">
                    <i class="fas fa-flag"></i>
                    Statut :
                    <span class="badge bg-primary text-white">
                        <?= ucfirst(htmlspecialchars($project->getStatus())) ?>
                    </span>
                </span>
            </div>

            <!-- Description -->
            <article class="project-description mb-5">
                <h3><i class="fas fa-info-circle me-2"></i>Description complète</h3>
                <p><?= nl2br(htmlspecialchars($project->getDescription())) ?></p>
            </article>

            <!-- Technologies -->
            <?php if ($project->getTechnologies()): ?>
                <div class="project-technologies mb-5">
                    <h3><i class="fas fa-code me-2"></i>Technologies utilisées</h3>
                    <?php foreach ($project->getTechnologies() as $tech): ?>
                        <span class="tech-badge"><?= htmlspecialchars($tech) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Infos complémentaires -->
            <div class="project-details mb-5">
                <?php if ($project->getTeamSize()): ?>
                    <p><strong>Taille de l’équipe :</strong> <?= htmlspecialchars($project->getTeamSize()) ?></p>
                <?php endif; ?>

                <?php if ($project->getLookingFor()): ?>
                    <p><strong>Recherche :</strong> <?= nl2br(htmlspecialchars($project->getLookingFor())) ?></p>
                <?php endif; ?>
            </div>

            <!-- Liens externes -->
            <div class="project-links mb-5">
                <h3><i class="fas fa-link me-2"></i>Liens utiles</h3>
                <ul>
                    <?php if ($project->getRepositoryUrl()): ?>
                        <li><a href="<?= htmlspecialchars($project->getRepositoryUrl()) ?>" target="_blank"><i class="fab fa-github"></i> Dépôt du projet</a></li>
                    <?php endif; ?>

                    <?php if ($project->getDemoUrl()): ?>
                        <li><a href="<?= htmlspecialchars($project->getDemoUrl()) ?>" target="_blank"><i class="fas fa-external-link-alt"></i> Démo en ligne</a></li>
                    <?php endif; ?>

                    <?php if ($project->getDocumentationUrl()): ?>
                        <li><a href="<?= htmlspecialchars($project->getDocumentationUrl()) ?>" target="_blank"><i class="fas fa-book"></i> Documentation</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Boutons d'action -->
            <div class="project-actions mt-5">
                <a href="?controller=project&action=project" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux projets
                </a>
                <?php if ($project->getStatus() === 'seeking'): ?>
                    <a href="#" class="btn btn-success ms-2">
                        <i class="fas fa-handshake me-2"></i>Rejoindre ce projet
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Projet introuvable.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>