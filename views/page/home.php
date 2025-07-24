<?php require_once  APP_ROOT . '/views/header.php'; ?>
    <!-- Bloc 1 -->
    <section class="hero-section">
        <div class="container-fluid hero-content text-center">
            <h1 class="hero-section__title">Bonjour et bienvenue sur SoliDev !</h1>
            <p class="hero-section__welcome-text"> La plateforme pensée par et pour les développeurs: <br> 
                    Partage d'astuces, entraide, actus tech et projet open source. <br>
                    Que vous soyez débutant ou expert, ici on code solidaire</p>
        </div>
    </section>
    <!-- Carte 1 la grande -->
    <section class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <a href="#!"><img class="card-img-top" src="https://dummyimage.com/850x350/dee2e6/6c757d.jpg" alt="..." /></a>
                    <div class="card-body">
                        <div class="small text-muted">January 1, 2023</div>
                            <h2 class="card-title">Featured Post Title</h2>
                            <p class="card-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam.
                            </p>
                        <a class="btn btn-primary" href="#!">Read more →</a>
                    </div>
                </div>
    <!-- Carte 2 petite 1 -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
    <!-- Carte 2 petite 2 -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    <!-- Partie recherche-->
            <div class="col-lg-4">
                <div class="search-home mb-4">
                    <div class="text-center mb-2">Recherche</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Entrer votre recherche..." aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">
                            <img src="/assets/icones/search.png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php require_once  APP_ROOT . '/views/footer.php';?>
