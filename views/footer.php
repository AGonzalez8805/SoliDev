</main>
<!-- === Pied de page (footer) du site === -->
<footer class="footer">
    <!-- Menu de navigation secondaire centré dans le footer -->
    <ul class="justify-content-center border-bottom border-black pb-3 mb-3">
        <li class="nav-item">
            <!-- Lien vers la page Mentions légales -->
            <a href="#" class="nav-link px-2 text-body-secondary">Mentions Légales</a>
        </li>
        <li class="nav-item">
            <!-- Lien vers la page À propos -->
            <a href="#" class="nav-link px-2 text-body-secondary">A propos</a>
        </li>
        <li class="nav-item">
            <!-- Lien vers la page de FAQ -->
            <a href="#" class="nav-link px-2 text-body-secondary">FAQs</a>
        </li>
        <li class="nav-item">
            <!-- Lien vers la page de contact, avec activation dynamique de la classe si la page est active -->
            <a class="nav-link <?= $currentPage === 'contact' ? 'active-link' : '' ?>" href="/?controller=page&action=contact">Contact</a>
        </li>
    </ul>
    <!-- Texte de copyright centré -->
    <p class="footer-copyright">© 2025 SoliDev. Tous droits réservés.</p>
</footer>
<!-- === Scripts JavaScript === -->
<!-- Script Bootstrap (fonctionnalités comme les menus déroulants, le responsive, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.chartData = {
        distribution: {
            blog: <?= $stats['blogs'] ?>,
            projects: <?= $stats['projects'] ?>,
            snippets: <?= $stats['snippets'] ?>
        },
        monthlyUsers: <?= json_encode($monthlyUsers) ?>,
        monthlyLabels: <?= json_encode($monthlyLabels) ?>
    };
</script>
<!-- Script principal de l'application (JavaScript personnalisé) -->
<script type="module" src="/assets/app.js"></script>

<script src="https://kit.fontawesome.com/f5ae6a01b5.js" crossorigin="anonymous"></script>
</body>

</html>