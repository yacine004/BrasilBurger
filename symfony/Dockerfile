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
FROM php:8.4-apache-alpine

WORKDIR /app

# Installer les extensions PHP et autres dépendances
RUN apk add --no-cache postgresql-client postgresql-libs libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Copier le code depuis le builder
COPY --from=builder /app .

# Copier la configuration PHP personnalisée
COPY symfony/php.ini /usr/local/etc/php/conf.d/custom.ini

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
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Désactiver les sites par défaut
RUN a2dissite 000-default.conf
RUN a2ensite 000-default.conf

# Définir les permissions
RUN chown -R www-data:www-data /app/var
RUN chmod -R 755 /app/var

# Créer le répertoire cache s'il n'existe pas
RUN mkdir -p /app/var/cache /app/var/log
RUN chown -R www-data:www-data /app/var/cache /app/var/log

# Exposer le port
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
