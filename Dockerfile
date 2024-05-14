# Définition de l'image de base
FROM php:8.3.4-fpm

# Utilisation d'un ID d'utilisateur par défaut
ARG uid=1000
ARG user=lina

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxslt-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Obtention de la dernière version de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Création d'un utilisateur système pour exécuter les commandes Composer et Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Définition du répertoire de travail
WORKDIR /var/www

# Copie du fichier composer.json pour installer les dépendances PHP
COPY composer.json composer.lock ./

# Installation des dépendances PHP
RUN composer install --no-scripts --no-autoloader

# Copie du contenu du répertoire de l'application existante
COPY . .

# Génération de la clé d'application Laravel
RUN php artisan key:generate

# Définition de l'utilisateur à utiliser dans le conteneur
USER $user
