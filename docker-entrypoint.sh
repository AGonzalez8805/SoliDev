#!/bin/bash
set -e

# Créer le dossier photos s'il n'existe pas
mkdir -p /var/www/html/public/photos

# Donner les permissions appropriées
chown -R www-data:www-data /var/www/html/public/photos
chmod -R 775 /var/www/html/public/photos

# Démarrer Apache
exec apache2-foreground