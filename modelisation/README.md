# 📊 Modélisation - Brasil Burger

> Documentation complète de la modélisation du système Brasil Burger

---

## 📁 Structure des Fichiers

```
modelisation/
├── usecase.puml              ✅ Diagramme Use Case
├── classdiagram.puml         ✅ Diagramme de Classe
├── sequence_commande.puml    ✅ Diagramme de Séquence - Commande
├── sequence_paiement.puml    ✅ Diagramme de Séquence - Paiement
├── sequence_livraison.puml   ✅ Diagramme de Séquence - Livraison
├── mld.png                   ⏳ MLD (à générer via StarUML/PlantUML)
├── mld.puml                  ✅ MLD en PlantUML
├── mld.sql                   ✅ Script SQL PostgreSQL
└── README.md                 ✅ Ce fichier
```

---

## 🎯 Diagrammes UML

### 1. Diagramme Use Case (`usecase.puml`)
**Description**: Représente les différents acteurs et leurs interactions avec le système.

**Acteurs principaux**:
- **Client**: Consulter menu, passer commande, effectuer paiement
- **Gestionnaire**: Gérer produits, gérer commandes, consulter statistiques
- **Livreur**: Consulter livraisons, mettre à jour statut
- **Système de Paiement**: Valider paiement (externe)

**Cas d'utilisation**:
- Authentification
- Gestion du catalogue (burgers, menus, compléments)
- Gestion des commandes
- Traitement des paiements
- Gestion des livraisons
- Notifications

**Visualiser**: Ouvrir avec PlantUML extension dans VS Code ou exporter en PNG

---

### 2. Diagramme de Classe (`classdiagram.puml`)
**Description**: Modèle objet complet avec toutes les classes métier et leurs relations.

**Classes principales**:
- `User`, `Client` (héritage)
- `Burger`, `Complement`, `Menu`
- `Order`, `OrderLine`
- `Payment`, `Delivery`

**Relations**:
- Client 1 --- * Order (composition)
- Order 1 --- * OrderLine (composition)
- Burger * --- * Complement (association)
- Menu * --- 1 Burger (agrégation)

**Visualiser**: PlantUML ou StarUML

---

### 3. Diagrammes de Séquence

#### a) `sequence_commande.puml` - Flux de Commande
**Scénario**: Client passe une commande

**Flux**:
1. Client consulte le menu
2. Client sélectionne produits (burgers, compléments)
3. Client ajoute au panier
4. Client valide la commande
5. Système calcule le total
6. Système enregistre la commande
7. Confirmation envoyée au client

**Participants**: Client, Interface Web, Contrôleur, Service Commande, Base de Données

---

#### b) `sequence_paiement.puml` - Validation Paiement
**Scénario**: Traitement du paiement d'une commande

**Flux**:
1. Client choisit mode de paiement (CB, PayPal, Espèces)
2. Système initie transaction
3. Passerelle de paiement valide
4. Système met à jour statut commande
5. Notification envoyée

**Participants**: Client, Système, Passerelle Paiement, Service Commande, Base de Données

---

#### c) `sequence_livraison.puml` - Gestion Livraison
**Scénario**: Livreur traite une livraison

**Flux**:
1. Livreur se connecte
2. Livreur consulte commandes en attente
3. Livreur prend en charge une commande
4. Livreur livre au client
5. Livreur confirme livraison
6. Client reçoit notification

**Participants**: Livreur, Système, Base de Données, Client

---

## 🗄️ Modèle Logique de Données (MLD)

### Fichiers MLD

#### `mld.puml` - MLD PlantUML
Représentation visuelle des tables et relations en notation UML.

**Tables principales**:
- `users` (utilisateurs authentifiés)
- `clients` (informations clients)
- `burgers`, `complements`, `menus` (catalogue)
- `burger_complements` (table associative)
- `orders`, `order_lines` (commandes)

**Cardinalités**:
- users (1,1) --- (0,1) clients
- clients (1,1) --- (0,N) orders
- burgers (1,N) --- (0,N) complements
- orders (1,1) --- (1,N) order_lines

#### `mld.sql` - Script PostgreSQL
Script complet pour créer la base de données sur **Neon.tech**.

**Contenu**:
- Création de toutes les tables avec contraintes
- Index pour optimisation
- Contraintes de clés étrangères
- Valeurs par défaut
- Triggers (updated_at)

**Exécution**:
```bash
# Connexion à Neon
psql 'postgresql://neondb_owner:PASSWORD@ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require'

# Exécuter le script
\i mld.sql

# Vérifier les tables
\dt
```

#### `mld.png` - Image du MLD
**À générer** via:
- **StarUML**: Importer `mld.puml` ou créer manuellement
- **PlantUML**: Exporter `mld.puml` en PNG
  ```bash
  # Avec PlantUML CLI
  java -jar plantuml.jar mld.puml
  
  # Ou avec VS Code extension PlantUML
  Ctrl+Shift+P → PlantUML: Export Current Diagram
  ```

---

## 🎨 Maquettes Figma

> **Note**: Les maquettes doivent être créées sur Figma séparément

**Pages à maquetter**:
1. **Accueil** - Landing page avec menu
2. **Menu** - Catalogue burgers/menus/compléments
3. **Détail Produit** - Fiche burger avec options
4. **Panier** - Résumé commande
5. **Paiement** - Formulaire paiement
6. **Confirmation** - Récapitulatif commande validée
7. **Tableau de bord Gestionnaire** - CRUD produits
8. **Espace Livreur** - Liste livraisons

**Lien Figma**: [À ajouter après création]

---

## ✅ Checklist Modélisation

### Diagrammes UML
- [x] ✅ Diagramme Use Case (`usecase.puml`)
- [x] ✅ Diagramme de Classe (`classdiagram.puml`)
- [x] ✅ Diagramme de Séquence - Commande (`sequence_commande.puml`)
- [x] ✅ Diagramme de Séquence - Paiement (`sequence_paiement.puml`)
- [x] ✅ Diagramme de Séquence - Livraison (`sequence_livraison.puml`)

### Modèle de Données
- [x] ✅ MLD PlantUML (`mld.puml`)
- [x] ✅ Script SQL PostgreSQL (`mld.sql`)
- [ ] ⏳ Image MLD (`mld.png`) - À exporter

### Maquettes
- [ ] ⏳ Maquettes Figma - À créer

### Base de Données
- [x] ✅ Base PostgreSQL créée sur Neon
- [x] ✅ Tables créées et populées
- [x] ✅ Données de test insérées (6 burgers, 10 compléments, 3 menus, 3 clients)

---

## 🔗 Liens Utiles

- **Neon Database**: https://console.neon.tech
- **PlantUML Documentation**: https://plantuml.com
- **StarUML**: https://staruml.io
- **Figma**: https://www.figma.com
- **Repository GitHub**: https://github.com/yacine004/BrasilBurger

---

## 📝 Notes

- Tous les fichiers `.puml` sont éditables avec PlantUML
- Le MLD correspond exactement à la base PostgreSQL déployée sur Neon
- Les diagrammes de séquence couvrent les 3 principaux flux métier
- Les maquettes Figma doivent être créées manuellement et le lien ajouté ici

---

**Date de création**: 12 décembre 2025  
**Version**: 1.0  
**Projet**: Brasil Burger - Livrable 1
