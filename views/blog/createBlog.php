<?php require_once APP_ROOT . '/views/header.php'; ?>
<section class="create-header">
    <div class="container">
        <div class="back-link">
            <a href="/?controller=blog&action=list">
                <i class="fas fa-arrow-left me-2"></i>Retour aux blog
            </a>
        </div>
        <h1>
            <i class="fas fa-pen me-2"></i>
            Écrire un article
        </h1>
        <p>Rédigez des articles techniques, partagez vos découvertes, créez des tutoriels pas-à-pas ou racontez vos expériences de développement. Contribuez à enrichir les connaissances de notre communauté en partageant ce que vous avez appris.</p>
    </div>
</section>

<!-- Formulaire de création -->
<div class="create-container">
    <textarea name="content" id="content" style="display:none;"></textarea>

    <form class="create-form" id="articleForm" method="POST" action="/?controller=blog&action=store" enctype="multipart/form-data">
        <!-- Informations générales -->
        <div class="form-section">
            <h3><i class="fas fa-info-circle me-2"></i>Informations générales</h3>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Titre de l'article *</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Ex: Les nouveautés JavaScript 2024" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category" class="form-label">Catégorie *</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Choisir une catégorie</option>
                        <option value="javascript">JavaScript</option>
                        <option value="php">PHP</option>
                        <option value="css">CSS</option>
                        <option value="react">React</option>
                        <option value="vue">Vue.js</option>
                        <option value="node">Node.js</option>
                        <option value="python">Python</option>
                        <option value="tutorial">Tutoriels</option>
                        <option value="devops">DevOps</option>
                        <option value="database">Base de données</option>
                        <option value="tools">Outils</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
            </div>

            <!-- Résumé -->
            <div class="form-group">
                <label for="excerpt" class="form-label">
                    Résumé en quelques mots <span class="required">*</span>
                </label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                    placeholder="Décrivez en 2-3 phrases de quoi parle votre article..." required></textarea>
            </div>

            <!-- Image de couverture -->
            <div class="form-section">
                <h3><i class="fas fa-image me-2"></i>Image de couverture</h3>
                <div class="image-upload" id="imageUpload">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <h4>Glissez votre image ici ou cliquez pour parcourir</h4>
                    <p class="text-muted">Formats acceptés: JPG, PNG, WebP (max 2MB)</p>
                    <input type="file" id="coverImage" name="cover_image" accept="image/*" style="display: none;">
                </div>
                <div id="imagePreview"></div>
            </div>

            <!-- Contenu de l'article -->
            <div class="form-section">
                <h3><i class="fas fa-edit me-2"></i>Contenu de l'article</h3>

                <div class="editor-toolbar">
                    <button type="button" class="editor-btn" data-command="bold" title="Gras">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button type="button" class="editor-btn" data-command="italic" title="Italique">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button type="button" class="editor-btn" data-command="underline" title="Souligné">
                        <i class="fas fa-underline"></i>
                    </button>
                    <div style="width: 1px; height: 20px; background: #ddd; margin: 0 5px;"></div>
                    <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Liste à puces">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button type="button" class="editor-btn" data-command="insertOrderedList" title="Liste numérotée">
                        <i class="fas fa-list-ol"></i>
                    </button>
                    <button type="button" class="editor-btn" data-command="createLink" title="Lien">
                        <i class="fas fa-link"></i>
                    </button>
                    <button type="button" class="editor-btn" data-command="insertImage" title="Image">
                        <i class="fas fa-image"></i>
                    </button>
                    <div style="width: 1px; height: 20px; background: #ddd; margin: 0 5px;"></div>
                    <button type="button" class="editor-btn" data-command="formatBlock" data-value="h2" title="Titre H2">
                        H2
                    </button>
                    <button type="button" class="editor-btn" data-command="formatBlock" data-value="h3" title="Titre H3">
                        H3
                    </button>
                    <button type="button" class="editor-btn" data-command="formatBlock" data-value="pre" title="Code">
                        <i class="fas fa-code"></i>
                    </button>
                </div>

                <div class="editor-content" id="editor" contenteditable="true">
                    <p>Rédigez votre article ici...</p>
                </div>
            </div>
            <fieldset class="form-group">
                <legend class="form-label">Options</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="draft" value="draft" checked>
                    <label class="form-check-label" for="draft">
                        Sauvegarder en brouillon (vous pourrez le publier plus tard)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="publish" value="published">
                    <label class="form-check-label" for="publish">
                        Publier immédiatement
                    </label>
                </div>
            </fieldset>

            <!-- Boutons d'action -->
            <div class="action-buttons">
                <div>
                    <span class="autosave-status" id="autosaveStatus">
                        <i class="fas fa-check-circle"></i> Sauvegardé automatiquement
                    </span>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-primary" id="previewBtn">
                        <i class="fas fa-eye me-2"></i>Aperçu
                    </button>
                    <button type="button" class="btn btn-secondary" id="saveDraftBtn">
                        <i class="fas fa-save me-2"></i>Sauvegarder
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Publier l'article
                    </button>
                </div>
            </div>
    </form>

    <!-- Prévisualisation -->
    <div class="preview-container" id="previewContainer">
        <div class="preview-header">
            <h2 class="preview-title" id="previewTitle">Titre de l'article</h2>
            <div class="preview-meta" id="previewMeta">
                Par Utilisateur • <span id="previewDate"></span> • <span id="previewCategory">Catégorie</span>
            </div>
        </div>
        <div class="preview-content" id="previewContent">
            Contenu de l'article...
        </div>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>