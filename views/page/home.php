<?php require_once  APP_ROOT . '/views/header.php'; ?>

    <section class="hero-section">
        <div class="container-fluid hero-content text-center">
            <h1 class="hero-section__title">Bonjour et bienvenue sur SoliDev !</h1>
            <p class="hero-section__welcome-text"> La plateforme pensée par et pour les développeurs: <br> 
                    Partage d'astuces, entraide, actus tech et projet open source. <br>
                    Que vous soyez débutant ou expert, ici on code solidaire</p>
        </div>
    </section>
    <section>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <div class="row text-center">
            <div class="col-md-4 my-2 d-flex">
                <div class="card">
                        <img src=" uploads/books/1-1984.jpg" class="card-img-top" alt="Desc">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2 d-flex">
                <div class="card">
                    <img src=" uploads/books/2-histoires-courtes.jpg" class="card-img-top" alt="Desc">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2 d-flex">
                <div class="card">
                    <img src=" uploads/books/3-zai-zai-zai-zai.jpg" class="card-img-top" alt="Desc">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                </div>
            </div>
        </div>
    </section>

<?php require_once  APP_ROOT . '/views/footer.php';?>
