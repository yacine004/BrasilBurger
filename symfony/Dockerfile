# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Installer les extensions PHP nécessaires
RUN apk add --no-cache postgresql-client postgresql-libs libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet Symfony
COPY symfony/ .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Nettoyer le cache
RUN php bin/console cache:clear --env=prod || true

# Runtime stage
FROM php:8.4-apache

WORKDIR /app

# Installer les extensions PHP et autres dépendances
RUN apt-get update && apt-get install -y --no-install-recommends postgresql-client libpq-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_pgsql

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Copier le code depuis le builder
COPY --from=builder /app .

# Copier la configuration PHP personnalisée si elle existe
RUN if [ -f symfony/php.ini ]; then cp symfony/php.ini /usr/local/etc/php/conf.d/custom.ini; fi

# Créer et définir les permissions - Rendre TOUT accessible
RUN mkdir -p /app/var/cache /app/var/log && \
    chown -R www-data:www-data /app && \
    chmod -R 777 /app/var && \
    chmod -R 755 /app && \
    chmod -R 755 /app/public

# Exposer le port sur 8080 pour Render
EXPOSE 8080

# Configuration pour Render - Écouter sur le port 8080
RUN sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/DocumentRoot.*$/DocumentRoot \/app\/public/' /etc/apache2/sites-available/000-default.conf

# Activer les modules requis
RUN a2enmod headers && a2enmod env

# Commande de démarrage
CMD ["apache2-foreground"]
