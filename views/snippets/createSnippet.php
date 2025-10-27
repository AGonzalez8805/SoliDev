<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- En-tête de création -->
<section class="create-snippet-header">
    <div class="container">
        <div class="header-content">
            <div class="back-link">
                <a href="/?controller=snippets&action=snippets">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux snippets
                </a>
            </div>
            <h1>
                <i class="fas fa-code me-3"></i>
                Créer un nouveau snippet
            </h1>
            <p class="lead">
                Partagez votre code avec la communauté SoliDev
            </p>
        </div>
    </div>
</section>

<!-- Formulaire de création -->
<section class="create-snippet-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form method="POST" action="/?controller=snippets&action=store" class="snippet-form">

                    <!-- Section Informations générales -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3><i class="fas fa-info-circle me-2"></i>Informations générales</h3>
                        </div>

                        <div class="form-group">
                            <label for="title" class="form-label required">
                                Titre du snippet
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                placeholder="Ex: Fonction de validation d'email en JavaScript"
                                required
                                maxlength="150">
                            <small class="form-text">Donnez un titre clair et descriptif</small>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label required">
                                Description
                            </label>
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="3"
                                placeholder="Décrivez brièvement ce que fait votre snippet et comment l'utiliser..."
                                required
                                maxlength="500"></textarea>
                            <small class="form-text">Maximum 500 caractères</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language" class="form-label required">
                                        Langage de programmation
                                    </label>
                                    <select class="form-select" id="language" name="language" required>
                                        <option value="">Sélectionnez un langage</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="php">PHP</option>
                                        <option value="python">Python</option>
                                        <option value="java">Java</option>
                                        <option value="csharp">C#</option>
                                        <option value="cpp">C++</option>
                                        <option value="css">CSS</option>
                                        <option value="html">HTML</option>
                                        <option value="sql">SQL</option>
                                        <option value="typescript">TypeScript</option>
                                        <option value="ruby">Ruby</option>
                                        <option value="go">Go</option>
                                        <option value="rust">Rust</option>
                                        <option value="swift">Swift</option>
                                        <option value="kotlin">Kotlin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label required">
                                        Catégorie
                                    </label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="fonction">Fonctions utiles</option>
                                        <option value="algorithm">Algorithmes</option>
                                        <option value="ui">Composants UI</option>
                                        <option value="api">API & Requêtes</option>
                                        <option value="database">Base de données</option>
                                        <option value="animation">Animations</option>
                                        <option value="security">Sécurité</option>
                                        <option value="validation">Validation</option>
                                        <option value="utility">Utilitaires</option>
                                        <option value="performance">Performance</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Code -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3><i class="fas fa-terminal me-2"></i>Votre code</h3>
                        </div>

                        <div class="form-group">
                            <label for="code" class="form-label required">
                                Code source
                            </label>
                            <div class="code-editor-wrapper">
                                <div class="editor-toolbar">
                                    <button type="button" class="toolbar-btn" id="formatCode" title="Formater le code">
                                        <i class="fas fa-magic"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" id="copyCode" title="Copier">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <span class="line-count">Lignes: <span id="lineCount">0</span></span>
                                </div>
                                <textarea
                                    class="form-control code-editor"
                                    id="code"
                                    name="code"
                                    rows="15"
                                    placeholder="// Collez votre code ici...
function exemple() {
    console.log('Hello World!');
}"
                                    required></textarea>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Assurez-vous que votre code est bien indenté et commenté
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="usage" class="form-label">
                                Exemple d'utilisation (optionnel)
                            </label>
                            <textarea
                                class="form-control code-editor"
                                id="usage"
                                name="usage"
                                rows="6"
                                placeholder="// Exemple d'utilisation
const resultat = exemple();
console.log(resultat);"></textarea>
                            <small class="form-text">Montrez comment utiliser votre snippet</small>
                        </div>
                    </div>

                    <!-- Section Tags et visibilité -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3><i class="fas fa-tags me-2"></i>Tags et paramètres</h3>
                        </div>

                        <div class="form-group">
                            <label for="tags" class="form-label">
                                Tags
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="tags"
                                name="tags"
                                placeholder="Exemple: array, sort, filter, map"
                                maxlength="200">
                            <small class="form-text">
                                Séparez les tags par des virgules. Maximum 10 tags
                            </small>
                            <div id="tagsList" class="tags-preview mt-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-eye me-1"></i>Visibilité
                                    </label>
                                    <div class="visibility-options">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="visibility"
                                                id="public"
                                                value="public"
                                                checked>
                                            <label class="form-check-label" for="public">
                                                <strong>Public</strong>
                                                <small class="d-block text-muted">Visible par tous les membres</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="visibility"
                                                id="private"
                                                value="private">
                                            <label class="form-check-label" for="private">
                                                <strong>Privé</strong>
                                                <small class="d-block text-muted">Visible uniquement par vous</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt me-1"></i>Options
                                    </label>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="allow_comments"
                                            id="allow_comments"
                                            checked>
                                        <label class="form-check-label" for="allow_comments">
                                            Autoriser les commentaires
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="allow_fork"
                                            id="allow_fork"
                                            checked>
                                        <label class="form-check-label" for="allow_fork">
                                            Autoriser les forks/modifications
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-preview" id="previewBtn">
                            <i class="fas fa-eye me-2"></i>Prévisualiser
                        </button>
                        <div class="action-buttons">
                            <a href="/?controller=snippets&action=list" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-check me-2"></i>Publier le snippet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Modal de prévisualisation -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Prévisualisation du snippet
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" class="snippet-preview-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>