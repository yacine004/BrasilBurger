# Documentation API - Brasil Burger Symfony

## Vue d'ensemble

API REST pour la gestion des commandes, livraisons et statistiques du restaurant Brasil Burger.

**Base URL** : `http://localhost:8000/api` (dev) ou `https://brasil-burger-symfony.onrender.com/api` (prod)

## Authentification

Actuellement, l'authentification est basique HTTP. À implémenter avec JWT/OAuth2 pour la production.

```
Authorization: Bearer <token>
```

## Codes de Statut HTTP

- `200 OK` : Requête réussie
- `201 Created` : Ressource créée
- `400 Bad Request` : Erreur de validation
- `404 Not Found` : Ressource non trouvée
- `500 Internal Server Error` : Erreur serveur

## Endpoints API

### 1. Gestion des Commandes

#### Lister les commandes
```
GET /commandes?etat=CONFIRMEE&clientId=1&page=1&limit=20
```

**Réponse** :
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "client": {
        "id": 1,
        "nom": "Dupont",
        "prenom": "Jean",
        "telephone": "774445566",
        "quartier": "Plateau"
      },
      "dateCommande": "2024-12-25 10:30:00",
      "etat": "CONFIRMEE",
      "mode": "LIVRAISON",
      "montantTotal": 15500,
      "payee": true,
      "notes": null,
      "burgers": [...],
      "menus": [...],
      "complements": [...],
      "paiement": {...}
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 50,
    "pages": 3
  }
}
```

#### Récupérer une commande
```
GET /commandes/1
```

#### Créer une commande
```
POST /commandes
Content-Type: application/json

{
  "clientId": 1,
  "mode": "LIVRAISON",
  "burgers": [
    {"id": 1, "quantite": 2},
    {"id": 3, "quantite": 1}
  ],
  "menus": [
    {"id": 2, "quantite": 1}
  ],
  "complements": [
    {"id": 5, "quantite": 2}
  ]
}
```

#### Confirmer une commande
```
POST /commandes/1/confirmer
```

#### Terminer une commande
```
POST /commandes/1/terminer
```

#### Annuler une commande
```
POST /commandes/1/annuler
```

#### Enregistrer un paiement
```
POST /commandes/1/paiement
Content-Type: application/json

{
  "montant": 15500,
  "methode": "WAVE"
}
```

**Méthodes de paiement** : `WAVE`, `OM`, `ESPECE`

### 2. Statistiques

#### Statistiques du jour
```
GET /statistiques/jour
```

**Réponse** :
```json
{
  "success": true,
  "date": "2024-12-25",
  "data": {
    "commandes_en_cours": 5,
    "commandes_validees": 12,
    "commandes_annulees": 1,
    "recettes_journalieres": 185000,
    "commandes_en_attente_paiement": 3,
    "burgers_plus_vendus": [...]
  }
}
```

#### Statistiques sur période
```
GET /statistiques/periode?dateDebut=2024-12-01&dateFin=2024-12-31
```

#### Commandes en cours
```
GET /statistiques/commandes-en-cours
```

#### Commandes validées
```
GET /statistiques/commandes-validees
```

#### Commandes annulées
```
GET /statistiques/commandes-annulees
```

#### Burgers les plus vendus
```
GET /statistiques/burgers-plus-vendus
```

#### Recettes
```
GET /statistiques/recettes
```

### 3. Livraisons

#### Livraisons en attente
```
GET /livraisons/en-attente
```

#### Regrouper par zone
```
GET /livraisons/regrouper-par-zone
```

**Réponse** :
```json
{
  "success": true,
  "count": 3,
  "data": [
    {
      "zone_id": 1,
      "zone_nom": "Plateau",
      "prix_livraison": 2000,
      "nombre_commandes": 5,
      "commandes": [...]
    }
  ]
}
```

#### Assigner un livreur
```
POST /livraisons/1/assigner-livreur
Content-Type: application/json

{
  "livreur_id": 2
}
```

#### Marquer comme livrée
```
POST /livraisons/1/livrer
```

#### Signaler échouée
```
POST /livraisons/1/echouee
Content-Type: application/json

{
  "raison": "Client absent"
}
```

#### Livraisons d'un livreur
```
GET /livraisons/livreur/2
```

### 4. Catalogue

#### Lister les burgers
```
GET /burgers
```

#### Lister les menus
```
GET /menus
```

#### Lister les compléments
```
GET /complements
```

#### Détail d'un burger
```
GET /burgers/1
```

### 5. Clients

#### Lister les clients
```
GET /clients
```

#### Récupérer un client
```
GET /clients/1
```

#### Chercher par téléphone
```
GET /clients/search/telephone/774445566
```

## États des Commandes

| État | Description |
|------|-------------|
| `CREEE` | Commande nouvellement créée |
| `CONFIRMEE` | Commande confirmée par le client |
| `EN_COURS` | Commande en préparation |
| `TERMINER` | Commande prête/livrée |
| `ANNULEE` | Commande annulée |

## Modes de Commande

| Mode | Description |
|------|-------------|
| `SUR_PLACE` | À consommer sur place |
| `RECUPERATION` | À récupérer par le client |
| `LIVRAISON` | À livrer à domicile |

## États des Livraisons

| État | Description |
|------|-------------|
| `ASSIGNEE` | Livraison assignée, en attente de livreur |
| `EN_ROUTE` | Livreur en route |
| `LIVREE` | Livraison complétée |
| `ECHOUEE` | Livraison échouée |

## Exemple d'Utilisation Complète

### 1. Créer un client
```bash
curl -X POST http://localhost:8000/api/clients \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Dupont",
    "prenom": "Jean",
    "telephone": "774445566",
    "email": "jean@example.com",
    "adresse": "123 Rue de la Paix",
    "quartier": "Plateau"
  }'
```

### 2. Créer une commande
```bash
curl -X POST http://localhost:8000/api/commandes \
  -H "Content-Type: application/json" \
  -d '{
    "clientId": 1,
    "mode": "LIVRAISON",
    "burgers": [
      {"id": 1, "quantite": 2}
    ],
    "menus": [
      {"id": 1, "quantite": 1}
    ]
  }'
```

### 3. Confirmer la commande
```bash
curl -X POST http://localhost:8000/api/commandes/1/confirmer
```

### 4. Enregistrer le paiement
```bash
curl -X POST http://localhost:8000/api/commandes/1/paiement \
  -H "Content-Type: application/json" \
  -d '{
    "montant": 15500,
    "methode": "WAVE"
  }'
```

### 5. Terminer la commande
```bash
curl -X POST http://localhost:8000/api/commandes/1/terminer
```

### 6. Créer une livraison
```bash
curl -X POST http://localhost:8000/api/livraisons \
  -H "Content-Type: application/json" \
  -d '{
    "commandeId": 1,
    "zoneId": 1
  }'
```

## Gestion des Erreurs

Toutes les réponses d'erreur suivent ce format :

```json
{
  "error": "Message d'erreur descriptif"
}
```

Exemples :
```json
{"error": "Client non trouvé"}
{"error": "Le montant du paiement ne correspond pas au total de la commande"}
{"error": "Cette commande n'est pas en mode livraison"}
```

## Limites et Pagination

Les listes paginées utilisent les paramètres :
- `page` : Numéro de page (défaut: 1)
- `limit` : Nombre d'éléments par page (défaut: 20)

Réponse :
```json
{
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 150,
    "pages": 8
  }
}
```

## Rate Limiting

Actuellement non implémenté. À ajouter pour la production.

## Webhooks

À implémenter pour les notifications en temps réel.

## Changelog

### v1.0 (2024-12-25)
- Endpoints API initiales
- Gestion des commandes
- Gestion des livraisons
- Statistiques de base
