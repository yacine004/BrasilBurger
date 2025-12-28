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

# Configuration Apache - VirtualHost
RUN echo '<VirtualHost *:80>\n\
    ServerName brasil-burger.onrender.com\n\
    DocumentRoot /app/public\n\
    <Directory /app/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        <IfModule mod_rewrite.c>\n\
            RewriteEngine On\n\
            RewriteCond %{REQUEST_FILENAME} !-f\n\
            RewriteCond %{REQUEST_FILENAME} !-d\n\
            RewriteRule ^(.*)$ /index.php [QSA,L]\n\
        </IfModule>\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/default.conf && \
    a2ensite default.conf && \
    a2dissite 000-default.conf

# Créer et définir les permissions
RUN mkdir -p /app/var/cache /app/var/log && \
    chown -R www-data:www-data /app/var && \
    chmod -R 755 /app/var

# Exposer le port sur 8080 pour Render
EXPOSE 8080

# Configuration pour Render
ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOCK_DIR=/var/run/apache2 \
    APACHE_PID_FILE=/var/run/apache2/apache2.pid \
    APACHE_RUN_DIR=/var/run/apache2 \
    APACHE_LOG_DIR=/var/log/apache2 \
    LANG=C

# Port Render
RUN sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf

# Commande de démarrage
CMD ["apache2-foreground"]
