# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Installer les extensions PHP nécessaires
RUN apk add --no-cache postgresql-client postgresql-libs libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet Symfony
COPY symfony/ .

# Installer les dépendances et nettoyer le cache
RUN composer install --no-dev --optimize-autoloader && \
    php bin/console cache:clear --env=prod || true

# Runtime stage
FROM php:8.4-apache

WORKDIR /app

# Installer les extensions PHP et autres dépendances
RUN apt-get update && \
    apt-get install -y --no-install-recommends postgresql-client libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    rm -rf /var/lib/apt/lists/*

# Activer les modules Apache
RUN a2enmod rewrite headers env

# Copier le code depuis le builder
COPY --from=builder /app .

# Copier la configuration PHP personnalisée si elle existe
RUN if [ -f symfony/php.ini ]; then \
        cp symfony/php.ini /usr/local/etc/php/conf.d/custom.ini; \
    fi

# Changer le port Apache de 80 à 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# Supprimer la configuration par défaut et en créer une nouvelle pour Symfony
RUN rm -f /etc/apache2/sites-available/000-default.conf && \
    echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf && \
    echo '  ServerName brasil-burger.onrender.com' >> /etc/apache2/sites-available/000-default.conf && \
    echo '  DocumentRoot /app/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '  <Directory /app/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '  </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# S'assurer qu'aucun autre site n'est activé
RUN a2dissite 000-default.conf 2>/dev/null || true && \
    a2dissite default-ssl 2>/dev/null || true && \
    a2ensite 000-default.conf

# Créer le répertoire var et définir les permissions
RUN mkdir -p /app/var/cache /app/var/log && \
    chown -R www-data:www-data /app && \
    chmod -R 755 /app/public && \
    chmod -R 777 /app/var

# Exposer le port 8080 pour Render
EXPOSE 8080

# Commande de démarrage
CMD ["apache2-foreground"]
