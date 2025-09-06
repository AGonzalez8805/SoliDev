<?php
$hash = '$2y$10$8cDNXcq8qhAamj5onoUQ3uv8dBDEV4yoHcUd44wJHu04FdrDddJp6 '; // votre hash de MySQL
$motDePasse = 'monMotDePasse'; // le mot de passe que vous voulez tester

if (password_verify($motDePasse, $hash)) {
    echo "Mot de passe correct !";
} else {
    echo "Mot de passe incorrect.";
}
