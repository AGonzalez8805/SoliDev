<?php require_once APP_ROOT . '/views/header.php'; ?>

<h1>Bienvenue sur ton espace <?= $_SESSION['role']; ?></h1>

 <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="forum-stats">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4>1,248</h4>
                                    <small>Membres</small>
                                </div>
                                <div class="col-md-3">
                                    <h4>3,567</h4>
                                    <small>Sujets</small>
                                </div>
                                <div class="col-md-3">
                                    <h4>15,432</h4>
                                    <small>Messages</small>
                                </div>
                                <div class="col-md-3">
                                    <h4>42</h4>
                                    <small>En ligne</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php require_once APP_ROOT . '/views/footer.php'; ?>