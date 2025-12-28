# Guide de Déploiement - Brasil Burger (Symfony)

## 🎯 Objectif
Déployer l'application Symfony Brasil Burger sur Render avec PostgreSQL Neon.

## 📋 Pré-requis
- ✅ Compte GitHub (déjà configuré)
- ✅ Compte Render (https://render.com)
- ✅ PostgreSQL Neon (déjà configuré: ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech)
- ✅ Accès au repository GitHub

## 🚀 Étapes de Déploiement

### 1. Préparer le Code

#### a) Mettre à jour `.env` pour production
```bash
# .env.production
APP_ENV=prod
APP_DEBUG=false
DATABASE_URL="postgresql://neondb_owner:npg_Zwmhr46vDLKy@ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require"
APP_SECRET=your-production-secret-key-here
```

#### b) Configurer Symfony pour production
```bash
cd symfony
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod
php bin/console assets:install public
```

### 2. Créer un Dockerfile pour Symfony

Créez `symfony/Dockerfile`:

```dockerfile
# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Installer les extensions PHP nécessaires
RUN apk add --no-cache postgresql-client postgresql-libs
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
COPY symfony/ .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Runtime stage
FROM php:8.4-apache-alpine

WORKDIR /app

# Installer les extensions PHP
RUN apk add --no-cache postgresql-client postgresql-libs
RUN docker-php-ext-install pdo pdo_pgsql

# Activer les modules Apache nécessaires
RUN a2enmod rewrite
RUN a2enmod ssl

# Copier le code depuis le builder
COPY --from=builder /app .

# Configuration Apache
RUN echo '<VirtualHost *:80>\n\
    ServerName brasiburguer.com\n\
    DocumentRoot /app/public\n\
    <Directory /app/public>\n\
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

# Exposer le port
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
```

### 3. Pousser le Code sur GitHub

```powershell
cd C:\Users\HP\Desktop\BrasilBurger

# Initialiser git si nécessaire
git init
git add .
git commit -m "feat: Application Symfony Brasil Burger avec design moderne"
git branch -M main
git remote add origin https://github.com/yacine004/BrasilBurger.git
git push -u origin main
```

### 4. Déployer sur Render

#### Étape A: Créer un Web Service
1. Aller sur https://dashboard.render.com
2. Cliquer "New +" → "Web Service"
3. Sélectionner le repository GitHub
4. Configurer:
   - **Name**: brasil-burger-symfony
   - **Environment**: Docker
   - **Region**: Choisir la plus proche
   - **Branch**: main
   - **Dockerfile path**: ./symfony/Dockerfile
   - **Plan**: Free (ou Starter pour plus de performance)

#### Étape B: Ajouter les Variables d'Environnement
Dans Render Dashboard → Web Service → Environment:

```
APP_ENV=prod
APP_DEBUG=false
DATABASE_URL=postgresql://neondb_owner:npg_Zwmhr46vDLKy@ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require
APP_SECRET=change-me-to-random-secret-in-production
```

#### Étape C: Déployer
1. Cliquer "Create Web Service"
2. Attendre le build (5-10 minutes)
3. Vérifier les logs pour erreurs

### 5. Vérification Après Déploiement

✅ **Accéder à l'application**
```
https://brasil-burger-symfony.onrender.com
```

✅ **Tester les pages principales**
- Accueil: https://brasil-burger-symfony.onrender.com/
- Dashboard: https://brasil-burger-symfony.onrender.com/dashboard
- Commandes: https://brasil-burger-symfony.onrender.com/commandes
- Livreurs: https://brasil-burger-symfony.onrender.com/livreurs

✅ **Vérifier la base de données**
La connexion PostgreSQL Neon doit fonctionner automatiquement.

✅ **Consulter les logs**
Render Dashboard → Logs pour vérifier:
- Pas d'erreurs PHP
- Connexion PostgreSQL OK
- Assets chargés correctement

## 🔧 Dépannage

### Erreur: "Connection refused" PostgreSQL
- Vérifier DATABASE_URL dans Environment Variables
- Vérifier que Neon whitelist les IPs de Render

### Erreur: "Class not found" (Composer)
```bash
composer install --no-dev
composer dump-autoload -o
```

### Erreur: "Apache 403 Forbidden"
- Vérifier les permissions sur /app
- Vérifier .htaccess dans public/

### Erreur: Assets CSS/JS ne chargent pas
```bash
php bin/console assets:install public --env=prod
```

## 📈 Optimisation Production

### 1. Augmenter les Performances
- Activer le cache: `cache:warmup --env=prod`
- Activer OPcache dans php.ini
- Minifier les assets CSS/JS

### 2. Sécurité
- Changer APP_SECRET aléatoire
- Activer HTTPS (Render le fait automatiquement)
- Configurer CORS si nécessaire

### 3. Monitoring
- Activer les logs: `tail -f var/log/prod.log`
- Ajouter New Relic ou autre APM
- Configurer alertes Render

## 🔄 Mise à Jour Continue

Chaque push sur GitHub:
```powershell
git add .
git commit -m "feat: Description du changement"
git push origin main
```

Render redéploiera automatiquement en 2-5 minutes.

## 📞 Support

En cas de problème:
1. Vérifier les logs Render
2. Vérifier la connexion PostgreSQL
3. Tester localement d'abord
4. Consulter la documentation Render

---

**Statut du Déploiement**: Prêt pour production ✅
