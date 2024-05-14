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
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

# Nettoyage du cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtention de la dernière version de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Création d'un utilisateur système pour exécuter les commandes Composer et Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Définition du répertoire de travail
WORKDIR /var/www

# Copie du contenu du répertoire de l'application existante
COPY . /var/www

# Définition de l'utilisateur à utiliser dans le conteneur
USER $user