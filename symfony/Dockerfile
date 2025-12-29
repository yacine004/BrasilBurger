# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Installer les extensions PHP nécessaires
RUN apk add --no-cache postgresql-client postgresql-libs libpq-dev curl && \
    docker-php-ext-install pdo pdo_pgsql

# Télécharger Composer directement
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers du projet Symfony
COPY symfony/ .

# Valider composer.json
RUN echo "=== Validating composer.json ===" && \
    composer validate --no-check-lock --strict 2>&1

# Installer les dépendances
RUN echo "=== Installing Dependencies ===" && \
    composer install --no-dev --no-interaction --no-scripts --no-plugins 2>&1 || true

RUN echo "=== Checking vendor ===" && \
    ls -la vendor/ 2>&1 || echo "vendor not found"

# Générer le cache Symfony pour la production
RUN php bin/console cache:clear --env=prod && \
    php bin/console cache:warmup --env=prod || true

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

# Rediriger les logs Apache vers stdout/stderr pour Render
RUN ln -sf /dev/stdout /var/log/apache2/access.log && \
    ln -sf /dev/stderr /var/log/apache2/error.log

# Créer le répertoire var et définir les permissions
RUN mkdir -p /app/var/cache /app/var/log && \
    chown -R www-data:www-data /app && \
    chmod -R 755 /app/public && \
    chmod -R 755 /app/vendor && \
    chmod -R 777 /app/var && \
    echo "Verifying installation..." && \
    test -f /app/vendor/autoload.php && echo "✅ vendor/autoload.php exists in runtime" || (echo "❌ ERROR: vendor/autoload.php missing!" && exit 1)

# Exposer le port 8080 pour Render
EXPOSE 8080

# Commande de démarrage
CMD ["apache2-foreground"]
