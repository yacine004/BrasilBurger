# Déploiement Symfony sur Render

## Prérequis

- Compte Render.com
- Repository GitHub avec branche `symfony`
- Variable DATABASE_URL configurée dans l'environnement Render

## Étapes de Déploiement

### 1. Créer un service Web sur Render

1. Aller sur [https://render.com](https://render.com)
2. Cliquer sur "New +" → "Web Service"
3. Connecter votre repository GitHub
4. Sélectionner la branche `symfony`
5. Configurer le service :
   - **Name** : brasil-burger-symfony
   - **Root Directory** : symfony
   - **Environment** : PHP
   - **Build Command** : `composer install --no-dev --optimize-autoloader`
   - **Start Command** : `APP_ENV=prod php -S 0.0.0.0:${PORT:-8080} -t public`

### 2. Configurer les Variables d'Environnement

Dans le dashboard Render, aller dans les variables d'environnement et ajouter :

```
APP_ENV=prod
APP_SECRET=<générer une clé sécurisée>
DATABASE_URL=postgresql://<user>:<password>@<host>:5432/<database>
TRUSTED_HOSTS=^localhost|127\.0\.0\.1|brasil-burger-symfony\.onrender\.com$
```

### 3. Connecter la Base de Données PostgreSQL

Vous avez deux options :

#### Option A : PostgreSQL sur Render
1. Créer une nouvelle PostgreSQL Database sur Render
2. Récupérer la connection string
3. Ajouter la DATABASE_URL dans les variables d'environnement

#### Option B : PostgreSQL Existante
1. Utiliser la base de données partagée avec les autres modules
2. Configurer DATABASE_URL avec les identifiants appropriés

### 4. Déployer

- Le déploiement se fera automatiquement lors d'un push sur la branche `symfony`
- Vérifier les logs dans l'onglet "Logs" du service Render

## URL d'Accès

Une fois déployé, votre API sera accessible sur :
```
https://brasil-burger-symfony.onrender.com
```

## Endpoints Disponibles

```
GET  /api/statistiques/jour
GET  /api/statistiques/periode?dateDebut=2024-01-01&dateFin=2024-01-31
GET  /api/commandes
POST /api/commandes
GET  /api/commandes/{id}
POST /api/commandes/{id}/confirmer
POST /api/commandes/{id}/paiement
GET  /api/livraisons/en-attente
POST /api/livraisons/{id}/assigner-livreur
```

## Troubleshooting

### Erreur de base de données
- Vérifier que DATABASE_URL est correcte
- S'assurer que les tables existent dans la base de données
- Utiliser le script SQL fourni si nécessaire

### Erreur 500
- Consulter les logs Render
- Vérifier que APP_SECRET est défini
- Vérifier les permissions de fichiers

### CORS
- Les CORS sont configurés pour accepter les origines
- Ajuster dans `config/packages/framework.yaml` si nécessaire

## Maintenance

- Surveiller les logs régulièrement
- Mettre à jour les dépendances Composer
- Sauvegarder la base de données régulièrement
- Monitorer la performance via Render Dashboard

## Support

Consulter la documentation Render pour plus d'informations :
https://render.com/docs
