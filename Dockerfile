# Utilise l'image officielle PHP 8.2 avec Apache comme base
FROM php:8.2-apache

# Met à jour les paquets et installe les dépendances nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git unzip zip libicu-dev libzip-dev libonig-dev libssl-dev pkg-config \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo pdo_mysql zip gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

# Active le module "rewrite" d'Apache
RUN a2enmod rewrite

# Remplace le fichier de configuration Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copie Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie tout le contenu du projet
COPY . /var/www/html

# Copie le fichier ini
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Copie et rend exécutable le script d'entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Change le propriétaire
RUN chown -R www-data:www-data /var/www/html

# Expose le port 80
EXPOSE 80

# Utilise le script d'entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]