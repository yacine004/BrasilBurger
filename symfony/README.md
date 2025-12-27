# Brasil Burger - Module Symfony (Gestionnaire)

Partie backend Symfony pour la gestion des commandes, du suivi et des statistiques du restaurant Brasil Burger.

## Fonctionnalités

### Gestion des Commandes
- **Créer une commande** : Création de nouvelles commandes avec burgers, menus et compléments
- **Lister les commandes** : Visualisation de toutes les commandes avec filtres (état, client, date)
- **Détails d'une commande** : Affichage complet d'une commande avec tous ses détails
- **Confirmer une commande** : Passage d'une commande au statut "confirmée"
- **Terminer une commande** : Marquage d'une commande comme terminée
- **Annuler une commande** : Annulation d'une commande avant livraison
- **Enregistrer un paiement** : Traitement des paiements (WAVE, OM, ESPECE)

### Suivi des Commandes
- **État des commandes** : CREEE, CONFIRMEE, EN_COURS, TERMINER, ANNULEE
- **Mode de livraison** : SUR_PLACE, RECUPERATION, LIVRAISON
- **Status de paiement** : Payée / Non payée

### Gestion des Livraisons
- **Créer une livraison** : Association d'une commande à une zone de livraison
- **Assigner un livreur** : Attribution d'un livreur à une livraison
- **Regrouper par zone** : Groupement des commandes à livrer par zone
- **Marquer comme livrée** : Confirmation de la livraison
- **Signaler un échec** : Documentation des livraisons échouées

### Statistiques
- **Commandes en cours du jour** : Nombre et détails des commandes en cours
- **Commandes validées du jour** : Total des commandes payées et terminées
- **Recettes journalières** : Somme des paiements du jour
- **Burgers les plus vendus** : Top des burgers par nombre de ventes
- **Commandes annulées** : Suivi des annulations
- **Statistiques par période** : Analyse sur une période spécifique

## Structure du Projet

```
src/
├── Controller/
│   ├── CommandeController.php       # API des commandes
│   ├── StatistiquesController.php   # API des statistiques
│   └── LivraisonController.php      # API des livraisons
├── Service/
│   ├── CommandeService.php          # Logique métier des commandes
│   ├── StatistiquesService.php      # Logique métier des statistiques
│   └── LivraisonService.php         # Logique métier des livraisons
├── Entity/
│   ├── Commande.php
│   ├── Burger.php
│   ├── Menu.php
│   ├── Complement.php
│   ├── Client.php
│   ├── Paiement.php
│   ├── Livraison.php
│   ├── Livreur.php
│   ├── Zone.php
│   ├── Quartier.php
│   ├── Configuration.php
│   └── ...
├── Repository/
│   ├── CommandeRepository.php
│   ├── ClientRepository.php
│   ├── LivraisonRepository.php
│   └── ...
└── Kernel.php

config/
├── services.yaml
├── packages/
│   ├── doctrine.yaml
│   └── framework.yaml
└── routes.yaml

public/
└── index.php

.env              # Configuration locale
.env.prod         # Configuration production
```

## Installation

### Prérequis
- PHP 8.2+
- Composer
- PostgreSQL 12+

### Étapes

1. **Cloner et accéder au répertoire**
```bash
cd symfony/
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
# Copier et adapter le fichier .env
cp .env .env.local

# Éditer DATABASE_URL pour votre base de données
```

4. **Créer la base de données**
```bash
# La BD doit être créée manuellement avec le script SQL fourni
# SQL situé dans: ../modelisation/mld.sql
```

5. **Démarrer le serveur de développement**
```bash
symfony serve
```

Le serveur est accessible sur `http://localhost:8000`

## Endpoints API

### Commandes
- `GET /api/commandes` - Lister les commandes (avec filtres)
- `GET /api/commandes/{id}` - Détails d'une commande
- `POST /api/commandes` - Créer une commande
- `POST /api/commandes/{id}/confirmer` - Confirmer
- `POST /api/commandes/{id}/terminer` - Terminer
- `POST /api/commandes/{id}/annuler` - Annuler
- `POST /api/commandes/{id}/paiement` - Enregistrer paiement

### Statistiques
- `GET /api/statistiques/jour` - Stats du jour
- `GET /api/statistiques/periode` - Stats sur période (avec paramètres dateDebut, dateFin)
- `GET /api/statistiques/commandes-en-cours` - Commandes en cours
- `GET /api/statistiques/commandes-validees` - Commandes validées
- `GET /api/statistiques/commandes-annulees` - Commandes annulées
- `GET /api/statistiques/burgers-plus-vendus` - Top burgers
- `GET /api/statistiques/recettes` - Recettes du jour

### Livraisons
- `GET /api/livraisons/en-attente` - Livraisons en attente
- `GET /api/livraisons/regrouper-par-zone` - Groupement par zone
- `POST /api/livraisons/{id}/assigner-livreur` - Assigner livreur
- `POST /api/livraisons/{id}/livrer` - Marquer comme livrée
- `POST /api/livraisons/{id}/echouee` - Signaler échouée
- `GET /api/livraisons/livreur/{livreurId}` - Livraisons d'un livreur

## Configuration Base de Données

### Connection String
```
DATABASE_URL=postgresql://brasilburger:brasilburger_pass@localhost:5432/brasilburger_db
```

### Tables Principales
- `commande` : Commandes du client
- `commande_burger` : Association commande-burger
- `commande_menu` : Association commande-menu
- `commande_complement` : Association commande-complément
- `paiement` : Paiements des commandes
- `livraison` : Livraisons en cours
- `client` : Clients du restaurant
- `burger` : Catalogue des burgers
- `menu` : Catalogue des menus
- `complement` : Catalogue des compléments
- `livreur` : Équipe de livraison
- `zone` : Zones de livraison
- `quartier` : Quartiers de livraison
- `configuration` : Paramètres d'application

## Technologies

- **Framework** : Symfony 7.0
- **ORM** : Doctrine 3.0
- **Base de données** : PostgreSQL
- **PHP** : 8.2+
- **Architecture** : API REST + Service Layer

## Déploiement

Voir le fichier [DEPLOYMENT_RENDER.md](../DEPLOYMENT_RENDER.md) pour les instructions de déploiement sur Render.

## Notes

- La base de données est partagée avec les projets C# et Java
- L'authentification des managers doit être implémentée
- Les CORS sont configurés pour permettre les requêtes cross-origin
- Les modifications se font via les Services pour respecter le pattern métier

## Support

Pour les issues ou questions, consulter la documentation du projet principal.
