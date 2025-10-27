<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête de création -->
<section class="create-header">
    <div class="container">
        <div class="back-link">
            <a href="/?controller=project&action=project">
                <i class="fas fa-arrow-left me-2"></i>Retour aux projets
            </a>
        </div>
        <h1 class="mb-3">
            <i class="fas fa-plus-circle me-3"></i>
            Créer un nouveau projet
        </h1>
        <p class="lead mb-0">
            Partagez votre vision, décrivez votre projet et trouvez des collaborateurs passionnés
            pour donner vie à vos idées innovantes.
        </p>
    </div>
</section>

<!-- Formulaire de création -->
<section class="create-project-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="create-project-card">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert-box alert-error">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <strong>Erreurs dans le formulaire :</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/?controller=project&action=store" enctype="multipart/form-data" class="project-form">

                        <!-- Informations principales -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Informations principales
                            </h3>

                            <!-- Titre -->
                            <div class="form-group">
                                <label for="title" class="form-label required">Titre du projet</label>
                                <input type="text"
                                    id="title"
                                    name="title"
                                    class="form-input"
                                    placeholder="Ex: Plateforme de gestion de tâches collaborative"
                                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                                    required>
                                <small class="form-help">Un titre clair et accrocheur pour votre projet</small>
                            </div>

                            <!-- Statut -->
                            <div class="form-group">
                                <label for="status" class="form-label required">Statut du projet</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="">-- Sélectionnez un statut --</option>
                                    <option value="planning" <?= (($_POST['status'] ?? '') === 'planning') ? 'selected' : '' ?>>
                                        📋 En planification
                                    </option>
                                    <option value="active" <?= (($_POST['status'] ?? '') === 'active') ? 'selected' : '' ?>>
                                        🚀 En cours de développement
                                    </option>
                                    <option value="seeking" <?= (($_POST['status'] ?? '') === 'seeking') ? 'selected' : '' ?>>
                                        👥 Recherche de collaborateurs
                                    </option>
                                    <option value="completed" <?= (($_POST['status'] ?? '') === 'completed') ? 'selected' : '' ?>>
                                        ✅ Terminé
                                    </option>
                                </select>
                            </div>

                            <!-- Description courte -->
                            <div class="form-group">
                                <label for="short_description" class="form-label required">Description courte</label>
                                <textarea id="short_description"
                                    name="short_description"
                                    class="form-textarea"
                                    rows="3"
                                    placeholder="Résumez votre projet en quelques lignes (200 caractères max)"
                                    maxlength="200"
                                    required><?= htmlspecialchars($_POST['short_description'] ?? '') ?></textarea>
                                <small class="form-help">Cette description apparaîtra sur la carte du projet</small>
                            </div>
                        </div>

                        <!-- Description détaillée -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-file-alt me-2"></i>
                                Description détaillée
                            </h3>

                            <div class="form-group">
                                <label for="description" class="form-label required">Description complète</label>
                                <div class="editor-toolbar">
                                    <button type="button" class="toolbar-btn" data-action="bold" title="Gras">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="italic" title="Italique">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="list" title="Liste">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="link" title="Lien">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="code" title="Code">
                                        <i class="fas fa-code"></i>
                                    </button>
                                </div>
                                <textarea id="description"
                                    name="description"
                                    class="form-textarea editor-textarea"
                                    rows="12"
                                    placeholder="Décrivez en détail votre projet : objectifs, fonctionnalités prévues, état d'avancement, besoins en collaboration..."
                                    required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                                <small class="form-help">Format Markdown supporté</small>
                            </div>
                        </div>

                        <!-- Technologies -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-code me-2"></i>
                                Technologies utilisées
                            </h3>

                            <div class="form-group">
                                <label class="form-label required">Sélectionnez les technologies</label>
                                <div class="tech-selector">
                                    <?php
                                    $technologies = [
                                        'JavaScript',
                                        'TypeScript',
                                        'Python',
                                        'PHP',
                                        'Java',
                                        'C#',
                                        'React',
                                        'Vue.js',
                                        'Angular',
                                        'Next.js',
                                        'Node.js',
                                        'Django',
                                        'Laravel',
                                        'MongoDB',
                                        'PostgreSQL',
                                        'MySQL',
                                        'Docker',
                                        'AWS'
                                    ];
                                    $selectedTechs = $_POST['technologies'] ?? [];
                                    foreach ($technologies as $tech):
                                    ?>
                                        <label class="tech-checkbox">
                                            <input type="checkbox"
                                                name="technologies[]"
                                                value="<?= htmlspecialchars($tech) ?>"
                                                <?= in_array($tech, $selectedTechs) ? 'checked' : '' ?>>
                                            <span class="tech-label"><?= htmlspecialchars($tech) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <small class="form-help">Sélectionnez au moins une technologie</small>
                            </div>

                            <!-- Autres technologies -->
                            <div class="form-group">
                                <label for="other_technologies" class="form-label">Autres technologies</label>
                                <input type="text"
                                    id="other_technologies"
                                    name="other_technologies"
                                    class="form-input"
                                    placeholder="Ex: TailwindCSS, Bootstrap, Symfony (séparées par des virgules)"
                                    value="<?= htmlspecialchars($_POST['other_technologies'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Collaboration -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-users me-2"></i>
                                Collaboration
                            </h3>

                            <div class="form-group">
                                <label for="team_size" class="form-label">Taille de l'équipe souhaitée</label>
                                <select id="team_size" name="team_size" class="form-select">
                                    <option value="">Non spécifié</option>
                                    <option value="solo" <?= (($_POST['team_size'] ?? '') === 'solo') ? 'selected' : '' ?>>
                                        Solo (pas de collaboration recherchée)
                                    </option>
                                    <option value="small" <?= (($_POST['team_size'] ?? '') === 'small') ? 'selected' : '' ?>>
                                        Petite équipe (2-4 personnes)
                                    </option>
                                    <option value="medium" <?= (($_POST['team_size'] ?? '') === 'medium') ? 'selected' : '' ?>>
                                        Équipe moyenne (5-10 personnes)
                                    </option>
                                    <option value="large" <?= (($_POST['team_size'] ?? '') === 'large') ? 'selected' : '' ?>>
                                        Grande équipe (10+ personnes)
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="looking_for" class="form-label">Profils recherchés</label>
                                <textarea id="looking_for"
                                    name="looking_for"
                                    class="form-textarea"
                                    rows="4"
                                    placeholder="Décrivez les compétences ou profils que vous recherchez pour votre projet..."><?= htmlspecialchars($_POST['looking_for'] ?? '') ?></textarea>
                                <small class="form-help">Ex: Développeur React, Designer UI/UX, Expert DevOps</small>
                            </div>
                        </div>

                        <!-- Liens et ressources -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-link me-2"></i>
                                Liens et ressources
                            </h3>

                            <div class="form-group">
                                <label for="repository_url" class="form-label">Dépôt Git (GitHub, GitLab...)</label>
                                <input type="url"
                                    id="repository_url"
                                    name="repository_url"
                                    class="form-input"
                                    placeholder="https://github.com/username/project"
                                    value="<?= htmlspecialchars($_POST['repository_url'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="demo_url" class="form-label">URL de démonstration</label>
                                <input type="url"
                                    id="demo_url"
                                    name="demo_url"
                                    class="form-input"
                                    placeholder="https://demo.monprojet.com"
                                    value="<?= htmlspecialchars($_POST['demo_url'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="documentation_url" class="form-label">Documentation</label>
                                <input type="url"
                                    id="documentation_url"
                                    name="documentation_url"
                                    class="form-input"
                                    placeholder="https://docs.monprojet.com"
                                    value="<?= htmlspecialchars($_POST['documentation_url'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Image de couverture -->
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-image me-2"></i>
                                Image de couverture
                            </h3>

                            <div class="form-group">
                                <label for="cover_image" class="form-label">Télécharger une image</label>
                                <div class="file-upload-wrapper">
                                    <input type="file"
                                        id="cover_image"
                                        name="cover_image"
                                        class="file-input"
                                        accept="image/*">
                                    <label for="cover_image" class="file-label">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                        Choisir une image
                                    </label>
                                    <span class="file-name">Aucun fichier sélectionné</span>
                                </div>
                                <small class="form-help">Format recommandé: 1200x630px, max 2MB (JPG, PNG, WebP)</small>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="form-actions">
                            <a href="/?controller=project&action=project" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-rocket me-2"></i>
                                Publier le projet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>