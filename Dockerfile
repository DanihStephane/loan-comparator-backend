FROM php:8.2-fpm

# Mettre à jour les dépendances du système et installer les extensions PHP requises
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    make \
    libpcre2-dev \
    autoconf \
    libxslt-dev \
    bash \
    default-mysql-client \
    dos2unix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances PHP avec Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --optimize-autoloader

# Générer l'autoloader de Composer
RUN composer dump-autoload

# Définir les permissions pour le dossier var
RUN chmod 777 -R /var/www/html/var

# Exposer le port PHP-FPM
EXPOSE 9000

# Commande de démarrage par défaut
CMD ["php-fpm"]

