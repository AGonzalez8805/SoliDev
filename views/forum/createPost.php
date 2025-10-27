<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="form-header">
    <div class="container">
        <div class="back-link">
            <a href="/?controller=forum&action=forum">
                <i class="fas fa-arrow-left me-2"></i>Retour au forum
            </a>
        </div>
        <h1 class="mb-2">
            <i class="fas fa-plus-circle me-3"></i>
            Créer un nouveau sujet
        </h1>
        <p class="lead mb-0">Partagez vos questions, idées ou discussions avec la communauté</p>
    </div>
</section>

<section class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Formulaire principal -->
            <div class="form-container fade-in">
                <form id="newTopicForm" action="/?controller=forum&action=save_post" method="POST">
                    <!-- Catégorie -->
                    <div class="mb-4">
                        <label for="category" class="form-label">
                            <i class="fas fa-folder me-2"></i>Catégorie *
                        </label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Choisissez une catégorie</option>
                            <option value="web">Développement Web</option>
                            <option value="mobile">Développement Mobile</option>
                            <option value="backend">Backend & Bases de données</option>
                            <option value="devops">DevOps & Cloud</option>
                            <option value="help">Aide & Support</option>
                            <option value="general">Discussion générale</option>
                        </select>
                        <div class="category-info" id="categoryInfo" style="display: none;">
                            <h6 id="categoryTitle"></h6>
                            <p id="categoryDescription"></p>
                        </div>
                    </div>

                    <!-- Titre du sujet -->
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-2"></i>Titre du sujet *
                        </label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Soyez précis et descriptif..." maxlength="100" required>
                        <div class="character-count">
                            <span id="titleCount">0</span>/100 caractères
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mb-4">
                        <label for="tags" class="form-label">
                            <i class="fas fa-tags me-2"></i>Tags (optionnel)
                        </label>
                        <input type="text" class="form-control" id="tags" name="tags"
                            placeholder="Ex: javascript, react, api (séparés par des virgules)">
                        <small class="text-muted">Ajoutez des mots-clés pour aider les autres à trouver votre sujet</small>
                    </div>

                    <!-- Priorité/Type -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-flag me-2"></i>Type de sujet
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="topic_type" id="question" value="question" checked>
                                    <label class="form-check-label" for="question">
                                        <i class="fas fa-question-circle text-info me-2"></i>Question
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="topic_type" id="discussion" value="discussion">
                                    <label class="form-check-label" for="discussion">
                                        <i class="fas fa-comments text-success me-2"></i>Discussion
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="topic_type" id="help" value="help">
                                    <label class="form-check-label" for="help">
                                        <i class="fas fa-hands-helping text-warning me-2"></i>Demande d'aide
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="topic_type" id="share" value="share">
                                    <label class="form-check-label" for="share">
                                        <i class="fas fa-share-alt text-primary me-2"></i>Partage
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message/Contenu -->
                    <div class="mb-4">
                        <label for="message" class="form-label">
                            <i class="fas fa-edit me-2"></i>Message *
                        </label>

                        <!-- Barre d'outils de l'éditeur -->
                        <div class="editor-toolbar">
                            <button type="button" class="editor-btn" title="Gras" onclick="formatText('bold')">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" class="editor-btn" title="Italique" onclick="formatText('italic')">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" class="editor-btn" title="Lien" onclick="addLink()">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" class="editor-btn" title="Code" onclick="formatText('code')">
                                <i class="fas fa-code"></i>
                            </button>
                            <button type="button" class="editor-btn" title="Liste" onclick="formatText('list')">
                                <i class="fas fa-list"></i>
                            </button>
                            <button type="button" class="editor-btn" title="Aperçu" onclick="togglePreview()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <textarea class="form-control" id="message" name="message"
                            placeholder="Décrivez votre sujet en détail..." required></textarea>
                        <div class="character-count">
                            <span id="messageCount">0</span> caractères
                        </div>

                        <!-- Zone de prévisualisation -->
                        <div class="preview-container" id="previewContainer">
                            <h6><i class="fas fa-eye me-2"></i>Aperçu :</h6>
                            <div class="preview-content" id="previewContent"></div>
                        </div>
                    </div>

                    <!-- Options avancées -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-cog me-2"></i>Options
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications" name="notifications" checked>
                                    <label class="form-check-label" for="notifications">
                                        Recevoir les notifications par email
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pin" name="pin">
                                    <label class="form-check-label" for="pin">
                                        Épingler ce sujet (modérateurs)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="/?controller=forum&action=forum" class="btn btn-cancel">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="saveDraft()">
                                <i class="fas fa-save me-2"></i>Sauvegarder en brouillon
                            </button>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>Publier le sujet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar avec conseils -->
        <section class="col-lg-4">
            <div class="form-tips fade-in">
                <h6><i class="fas fa-lightbulb me-2"></i>Conseils pour un bon sujet</h6>
                <ul class="mb-0">
                    <li>Choisissez un titre clair et descriptif</li>
                    <li>Sélectionnez la bonne catégorie</li>
                    <li>Détaillez votre problème ou question</li>
                    <li>Ajoutez du code si nécessaire</li>
                    <li>Utilisez des tags pertinents</li>
                    <li>Soyez poli et respectueux</li>
                </ul>
            </div>

            <div class="form-tips fade-in mt-3">
                <h6><i class="fas fa-keyboard me-2"></i>Formatage du texte</h6>
                <ul class="mb-0">
                    <li><strong>**texte**</strong> pour du gras</li>
                    <li><em>*texte*</em> pour de l'italique</li>
                    <li><code>`code`</code> pour du code inline</li>
                    <li>```code``` pour un bloc de code</li>
                    <li>[lien](url) pour un lien</li>
                </ul>
            </div>

            <div class="form-tips fade-in mt-3">
                <h6><i class="fas fa-users me-2"></i>Règles de la communauté</h6>
                <ul class="mb-0">
                    <li>Respectez les autres membres</li>
                    <li>Pas de spam ou publicité</li>
                    <li>Recherchez avant de poster</li>
                    <li>Restez dans le sujet</li>
                    <li>Aidez les autres quand possible</li>
                </ul>
            </div>
        </section>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>