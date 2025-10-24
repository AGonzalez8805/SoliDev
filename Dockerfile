
# Utilise l'image officielle PHP 8.2 avec Apache comme base
FROM php:8.2-apache

# Met à jour les paquets et installe les dépendances nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git unzip zip libicu-dev libzip-dev libonig-dev libssl-dev pkg-config \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

# Active le module "rewrite" d'Apache (utile pour les routes en PHP/Laravel/Symfony)
RUN a2enmod rewrite

# Remplace le fichier de configuration Apache par un fichier personnalisé
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copie l'exécutable Composer depuis l'image officielle de Composer vers cette image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Change le propriétaire du dossier /var/www/html pour l'utilisateur Apache (www-data)
RUN chown -R www-data:www-data /var/www/html

# Copie tout le contenu du projet local vers le dossier public d’Apache
COPY . /var/www/html

# Copie le fichier ini dans le dossier conf.d de PHP
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Expose le port 80 (HTTP) pour que le conteneur soit accessible via ce port
EXPOSE 80
