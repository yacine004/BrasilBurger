# Brasil Burger - Livrable 1

## Projet Semestre 1 L3 ISM - Gestion Commande Restaurant

**Date de livraison :** 14/12/2025

---

## Contenu du Livrable 1

### 1. Modelisation (UML)
Diagrammes realises avec PlantUML :
- Diagramme de cas d'utilisation
- Diagramme de classe
- Diagramme de sequence (Commande, Paiement, Livraison)
- MCD/MLD (SQL)
- Maquettes Figma (Client + Gestionnaire)

Localisation : `modelisation/`

### 2. Application Java Console
Application console Java pour la creation des ressources :
- **Burgers** : CRUD complet (Create, Read, Update, Delete)
- **Menus** : En cours de developpement
- **Complements** : En cours de developpement

**Technologies :**
- Java 21
- Maven 3.9.6
- PostgreSQL (Neon.tech)
- Cloudinary (upload images)

Localisation : `java/`

---

## Base de Donnees

**PostgreSQL heberge sur Neon.tech**

- Endpoint : `ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech`
- Database : `neondb`
- Schema : Voir `modelisation/mld/brasil_burger.sql`

Tables :
- `users`
- `clients`
- `burgers` ✅
- `menus`
- `complements`
- `orders`
- `order_lines`
- `burger_complements`

---

## Installation et Execution

### Prerequis
- Java 21+
- Maven 3.9.6+
- PostgreSQL (Neon.tech)
- Cloudinary account

### Configuration
1. Configurer les variables d'environnement dans `java/src/main/resources/application.yml`
2. Verifier la connexion PostgreSQL
3. Configurer les credentials Cloudinary

### Lancer l'application
```bash
cd java
mvn clean compile
mvn exec:java
```

---

## Prochaines Etapes (Livrables 2 & 3)

- **Livrable 2 (20/12/2025)** : Application C# ASP MVC (Client)
- **Livrable 3 (30/12/2025)** : Application Symfony (Gestionnaire + Statistiques)

---

## Equipe

**L3 ISM - Semestre 1**

---

## License

Projet academique - Brasil Burger Restaurant
