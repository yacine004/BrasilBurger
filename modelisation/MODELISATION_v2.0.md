# Brasil Burger - Modélisation Complète v2.0

## 📋 Table des Matières
1. [Vue d'Ensemble](#vue-densemble)
2. [Diagrammes UML](#diagrammes-uml)
3. [Modèle Logique des Données (MLD)](#modèle-logique-des-données)
4. [Architecture Technique](#architecture-technique)
5. [Spécifications Fonctionnelles](#spécifications-fonctionnelles)
6. [Normes et Standards](#normes-et-standards)

---

## 🎯 Vue d'Ensemble

**Brasil Burger** est une plateforme complète de gestion de restaurant avec commandes en ligne, livrées par des livreurs indépendants. Le système comprend trois composants majeurs:

### Architecture 3-Tiers

```
┌─────────────────────────────────────────────────────┐
│  PRESENTATION TIER (Frontend)                       │
├─────────────────────────────────────────────────────┤
│  • Mobile App (React Native/Flutter)                │
│  • Web App Client (React/Angular)                   │
│  • Admin Dashboard (C# ASP.NET MVC)                 │
└─────────────────────────────────────────────────────┘
                        ↓ HTTP/REST
┌─────────────────────────────────────────────────────┐
│  BUSINESS LOGIC TIER (APIs)                         │
├─────────────────────────────────────────────────────┤
│  • Java Spring Boot API (REST - Port 8080)          │
│  • C# ASP.NET MVC API (Port 3000)                   │
│  • Symfony API (Gestionnaire - Port 8000)           │
│  • API Gateway (Nginx)                              │
└─────────────────────────────────────────────────────┘
                        ↓ JDBC/ORM
┌─────────────────────────────────────────────────────┐
│  DATA TIER                                          │
├─────────────────────────────────────────────────────┤
│  • SQL Server 2019+ (DESKTOP-VSKNDSS\SQLEXPRESS)   │
│  • Redis Cache                                      │
│  • Backup/Archiving                                 │
└─────────────────────────────────────────────────────┘
```

### Acteurs du Système

| Acteur | Rôle | Responsabilités |
|--------|------|-----------------|
| **Client** | Consommateur | Consulter catalogue, passer commande, suivre livraison, payer |
| **Gestionnaire** | Admin | Gérer menu, valider commandes, gérer livreurs, statistiques |
| **Livreur** | Transporteur | Accepter livraison, actualiser position, finaliser livraison |
| **Système** | Automatisation | Notifications, paiements, archivage, statistiques |

---

## 📊 Diagrammes UML

### 1. Use Case Diagram
**Fichier:** `diagrammes/use_case_diagram.puml`

Décrit tous les cas d'usage du système:

**Client:**
- Consulter Catalogue
- Passer Commande (avec gestion panier)
- Gérer Compte
- Suivre Commande
- Effectuer Paiement

**Gestionnaire:**
- Gérer Burgers
- Gérer Menus
- Gérer Compléments
- Gérer Commandes (valider/préparer)
- Gérer Livraisons
- Gérer Zones de Livraison
- Gérer Livreurs
- Consulter Statistiques
- Assigner Livraisons

**Livreur:**
- Accepter Livraison
- Actualiser Statut
- Fournir Localisation GPS

**Système:**
- Traiter Paiements
- Envoyer Notifications
- Calculer Statistiques

### 2. Class Diagram
**Fichier:** `diagrammes/class_diagram.puml`

Architecture orientée objet avec 10 classes principales:

```
Client --(1..*)--> Commande
  ├── email: String
  ├── mot_de_passe: String
  ├── nom, prenom: String
  └── statut: ACTIF|INACTIF

Commande --(1..*)--> Ligne_Commande
  ├── montant_total: BigDecimal
  ├── etat: VALIDE|PRETE|LIVREE|ANNULEE
  ├── type_livraison: SUR_PLACE|RETRAIT|LIVRAISON
  └── id_livreur → Livreur

Ligne_Commande --> (Burger | Menu)
  ├── quantite: Integer
  ├── prix_unitaire: BigDecimal
  └── sous_total: BigDecimal

Menu --(1..*)--> Burger
Menu --(*..*)--> Complement

Burger
  ├── nom: String
  ├── prix: BigDecimal
  ├── image: String
  └── statut: ACTIF|ARCHIVE

Complement
  ├── type_complement: FRITES|BOISSON|AUTRE
  ├── prix: BigDecimal
  └── statut: ACTIF|ARCHIVE

Commande --(1..*)--> Paiement
  ├── methode: WAVE|OM
  ├── statut: PENDING|VALIDE|REJETE
  └── montant: BigDecimal

Commande --> Zone
Zone --(1..*)--> Zone_Quartier
Zone --(1..*)--> Livreur

Livreur
  ├── nom, prenom: String
  ├── telephone: String
  └── statut: ACTIF|INACTIF

Gestionnaire
  ├── role: String
  ├── email: String
  └── supervise: Livreur, Burger, Menu
```

### 3. Entity Relationship Diagram
**Fichier:** `diagrammes/entity_relationship_diagram.puml`

Modèle logique avec toutes les entités et relations (voir section MLD ci-dessous).

### 4. Sequence Diagram - Flux de Commande
**Fichier:** `diagrammes/sequence_order_flow.puml`

**Acteurs:**
- Client
- Application Mobile
- API Java
- Base de Données
- Gestionnaire
- Livreur

**Étapes principales:**
1. Client consulte catalogue → API GET /api/burgers
2. Client ajoute articles au panier
3. Client valide commande → API POST /api/commandes
4. Système crée Commande (VALIDE) et Lignes_Commande
5. Client effectue paiement (WAVE/OM)
6. API valide paiement → Commande devient PRETE
7. Gestionnaire prépare commande
8. Gestionnaire assigne livreur
9. Livreur accepte et actualise position (GPS en temps réel)
10. Client suit position sur carte
11. Livreur finalise livraison → Commande LIVREE
12. Gestionnaire consulte statistiques

### 5. Activity Diagram - Validation de Paiement
**Fichier:** `diagrammes/activity_payment_validation.puml`

**Flux:**
```
Client initie paiement
    ↓
Sélectionne méthode (WAVE/OM)
    ↓
Valide montant > 0
    ├─ Non → ERROR
    └─ Oui → Crée Paiement (PENDING)
         ↓
         Envoie au fournisseur
         ↓
         Fournisseur confirme?
         ├─ Non → REJETE + Affiche erreur + Permet retry
         └─ Oui → VALIDE
              ↓
              Update Commande.etat = PRETE
              ↓
              Notifie Client + Gestionnaire
              ↓
              SUCCESS
```

### 6. Deployment Diagram
**Fichier:** `diagrammes/deployment_diagram.puml`

**Infrastructure Render.com:**

```
FRONTEND TIER
  ├── Mobile App (React Native/Flutter)
  └── Web App (React/Angular)
           ↓
       API Gateway (Nginx - LB)
           ↓
BUSINESS LOGIC TIER
  ├── Java Spring Boot (Port 8080)
  ├── C# ASP.NET MVC (Port 3000)
  └── Symfony (Port 8000)
           ↓
DATA TIER
  ├── SQL Server 2019+
  └── Redis Cache
           ↓
EXTERNAL SERVICES
  ├── Payment Gateway (WAVE/OM)
  ├── Notification Service (SMS/Email)
  └── Mapping Service (OpenMap)

CI/CD: GitHub Actions → Deploy sur Render
```

---

## 📚 Modèle Logique des Données

### MLD - 12 Tables Principales

#### 1. **CLIENT**
```sql
id_client (PK, BIGINT IDENTITY)
email (UNIQUE, VARCHAR 255)
mot_de_passe (VARCHAR 255, hashed)
nom (VARCHAR 100)
prenom (VARCHAR 100)
telephone (VARCHAR 20)
adresse (TEXT)
statut (ACTIF | INACTIF)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: email, statut
```

#### 2. **BURGER**
```sql
id_burger (PK, BIGINT IDENTITY)
nom (VARCHAR 150, NOT NULL)
description (TEXT)
prix (DECIMAL 10,2)
image (VARCHAR 500)
statut (ACTIF | ARCHIVE, DEFAULT ACTIF)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: nom, statut
```

#### 3. **MENU**
```sql
id_menu (PK, BIGINT IDENTITY)
nom (VARCHAR 150)
description (TEXT)
image (VARCHAR 500)
statut (ACTIF | ARCHIVE)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: nom, statut
```

#### 4. **MENU_BURGER** (Association)
```sql
id_menu_burger (PK, BIGINT IDENTITY)
id_menu (FK → MENU)
id_burger (FK → BURGER)

UNIQUE(id_menu, id_burger)
CASCADE ON DELETE
```

#### 5. **COMPLEMENT**
```sql
id_complement (PK, BIGINT IDENTITY)
nom (VARCHAR 150)
prix (DECIMAL 10,2)
image (VARCHAR 500)
type_complement (FRITES | BOISSON | AUTRE)
statut (ACTIF | ARCHIVE)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: nom, type_complement, statut
```

#### 6. **MENU_COMPLEMENT** (Association)
```sql
id_menu_complement (PK, BIGINT IDENTITY)
id_menu (FK → MENU)
id_complement (FK → COMPLEMENT)

UNIQUE(id_menu, id_complement)
CASCADE ON DELETE
```

#### 7. **COMMANDE**
```sql
id_commande (PK, BIGINT IDENTITY)
id_client (FK → CLIENT, NOT NULL)
date_creation (DATETIME, DEFAULT GETDATE())
date_modification (DATETIME)
montant_total (DECIMAL 12,2)
etat (VALIDE | PRETE | LIVREE | ANNULEE)
type_livraison (SUR_PLACE | RETRAIT | LIVRAISON)
id_zone (FK → ZONE, NULLABLE)
id_livreur (FK → LIVREUR, NULLABLE)
notes (TEXT)

Indexes:
  - idx_client (id_client)
  - idx_etat (etat)
  - idx_date (date_creation)
  - idx_type_livraison (type_livraison)
  - idx_commande_date_etat (date_creation, etat)
  - idx_commande_client_date (id_client, date_creation)
```

#### 8. **LIGNE_COMMANDE**
```sql
id_ligne (PK, BIGINT IDENTITY)
id_commande (FK → COMMANDE, NOT NULL)
id_burger (FK → BURGER, NULLABLE)
id_menu (FK → MENU, NULLABLE)
quantite (INT, DEFAULT 1)
prix_unitaire (DECIMAL 10,2)
sous_total (DECIMAL 12,2)
date_creation (DATETIME)

Constraints:
  - (id_burger IS NOT NULL) OR (id_menu IS NOT NULL)
  - sous_total = quantite * prix_unitaire

Indexes: id_commande
```

#### 9. **LIGNE_COMMANDE_COMPLEMENT** (Association)
```sql
id_lcc (PK, BIGINT IDENTITY)
id_ligne (FK → LIGNE_COMMANDE)
id_complement (FK → COMPLEMENT)

UNIQUE(id_ligne, id_complement)
CASCADE ON DELETE
```

#### 10. **PAIEMENT**
```sql
id_paiement (PK, BIGINT IDENTITY)
id_commande (FK → COMMANDE, NOT NULL)
montant (DECIMAL 12,2)
methode (WAVE | OM)
statut (PENDING | VALIDE | REJETE)
reference (VARCHAR 255)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: id_commande, statut, methode
```

#### 11. **ZONE** (Zones de Livraison)
```sql
id_zone (PK, BIGINT IDENTITY)
nom_zone (VARCHAR 150)
prix_livraison (DECIMAL 10,2)
description (TEXT)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: nom_zone
```

#### 12. **ZONE_QUARTIER** (Quartiers dans une Zone)
```sql
id_zone_quartier (PK, BIGINT IDENTITY)
id_zone (FK → ZONE)
quartier (VARCHAR 150)

UNIQUE(id_zone, quartier)
CASCADE ON DELETE
```

#### 13. **LIVREUR**
```sql
id_livreur (PK, BIGINT IDENTITY)
nom (VARCHAR 100)
prenom (VARCHAR 100)
telephone (VARCHAR 20)
id_zone (FK → ZONE)
statut (ACTIF | INACTIF)
date_embauche (DATE)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: id_zone, statut, telephone
```

#### 14. **GESTIONNAIRE**
```sql
id_gestionnaire (PK, BIGINT IDENTITY)
email (UNIQUE, VARCHAR 255)
mot_de_passe (VARCHAR 255, hashed)
nom (VARCHAR 100)
prenom (VARCHAR 100)
role (ADMIN | MANAGER | ACCOUNTANT)
statut (ACTIF | INACTIF)
date_embauche (DATE)
date_creation (DATETIME)
date_modification (DATETIME)

Indexes: email, role, statut
```

### Relations d'Intégrité Référentielle

```
CLIENT (1) ──→ (N) COMMANDE
COMMANDE (1) ──→ (N) LIGNE_COMMANDE
BURGER (1) ──← (N) LIGNE_COMMANDE
MENU (1) ──← (N) LIGNE_COMMANDE
MENU (1) ──→ (N) MENU_BURGER ←── (N) BURGER
MENU (1) ──→ (N) MENU_COMPLEMENT ←── (N) COMPLEMENT
LIGNE_COMMANDE (1) ──→ (N) LIGNE_COMMANDE_COMPLEMENT ←── (N) COMPLEMENT
COMMANDE (1) ──→ (N) PAIEMENT
ZONE (1) ──→ (N) ZONE_QUARTIER
ZONE (1) ──→ (N) LIVREUR
ZONE (1) ──← (N) COMMANDE
LIVREUR (1) ──← (N) COMMANDE
```

### Règles de Gestion

| Règle | Description |
|-------|-------------|
| **RG1** | Un Burger ne peut être supprimé s'il est référencé dans un Ligne_Commande |
| **RG2** | Un Menu ne peut contenir QU'UN Burger uniquement |
| **RG3** | Un Menu DOIT contenir au moins 1 Complement (boisson + frites) |
| **RG4** | Une Commande ne peut passer à l'état LIVREE que si le paiement est VALIDE |
| **RG5** | Un Livreur ne peut être assigné qu'à une seule Zone |
| **RG6** | Le montant_total d'une Commande = SUM(sous_total) de toutes ses Lignes + prix_livraison (Zone) |
| **RG7** | Une Commande peut être ANNULEE que si son etat est VALIDE ou PRETE |
| **RG8** | Un Paiement REJETE permet à l'utilisateur de réessayer |
| **RG9** | Les données archivées (Client.statut=INACTIF) ne sont jamais supprimées mais masquées |
| **RG10** | Les Commandes sont conservées pour historique (5 ans minimum) |

---

## 🏗️ Architecture Technique

### Stack Technologique

#### Backend - Java Spring Boot 3.3.6 (REST API)
```
Framework: Spring Boot 3.3.6 LTS
Language: Java 21 LTS
Build Tool: Maven
Database: SQL Server / H2 (demo)

Dependencies:
  ├── spring-boot-starter-web (REST Controllers)
  ├── spring-boot-starter-data-jpa (ORM)
  ├── spring-boot-starter-security (Authentication)
  ├── mssql-jdbc (SQL Server Driver)
  ├── h2database (Demo Database)
  ├── lombok (1.18.38 - Java 21 compatible)
  ├── spring-boot-starter-validation (Validations)
  └── spring-boot-starter-test (Testing)

Ports:
  - Development: 8080
  - Production: 8080 (via Render)
```

#### Frontend - Clients (3 variantes)

**Mobile App:**
- React Native ou Flutter
- Gestion offline-first (cache local)
- GPS en temps réel pour suivi livraison
- Notifications push (FCM/APNs)

**Web Client:**
- React / Angular / Vue.js
- Responsive Design (Mobile-first)
- Progressive Web App (PWA)

**Admin Dashboard:**
- C# ASP.NET MVC (Port 3000)
- Gestionnaire backend
- Statistiques et rapports
- Gestion utilisateurs

#### Middleware - Symfony (Gestionnaire)
```
Framework: Symfony 6.4+
Language: PHP 8.2+
Port: 8000
Purpose: Admin backend, Gestionnaire operations
```

#### Database

**Primary (Production):**
- SQL Server 2019+ Express
- Connection String: DESKTOP-VSKNDSS\SQLEXPRESS
- Database: brasil_burger
- Charset: UTF-8
- Collation: SQL_Latin1_General_CP1_CI_AS

**Secondary (Demo/Testing):**
- H2 In-memory
- Connection: jdbc:h2:mem:brasil_burger
- Auto-reset on restart

#### External Services

| Service | Purpose | Integration |
|---------|---------|-------------|
| **WAVE** | Mobile Payment | REST API |
| **Orange Money (OM)** | Mobile Payment | REST API |
| **Twilio** | SMS Notifications | REST API |
| **SendGrid** | Email Notifications | REST API |
| **OpenStreetMap** | Maps & Routing | REST API |
| **Firebase** | Push Notifications | SDKs |

#### Infrastructure - Deployment

**Hosting:** Render.com
```
Services:
  ├── Web Service 1: Java Spring Boot API
  ├── Web Service 2: C# ASP.NET MVC
  ├── Web Service 3: Symfony API
  ├── PostgreSQL/MySQL (optional, if not using SQL Server)
  └── Redis (caching)

Environment Variables:
  ├── DATABASE_URL
  ├── API_KEYS (WAVE, OM)
  ├── JWT_SECRET
  ├── CORS_ORIGINS
  └── PAYMENT_WEBHOOK_SECRET
```

**CI/CD Pipeline - GitHub Actions**
```yaml
Triggers: Push to branches (modelisation, java, csharp, symfony)

Jobs:
  1. Checkout Code
  2. Setup Environment (JDK 21, .NET 8, PHP 8.2)
  3. Build & Compile
  4. Run Unit Tests
  5. Run Integration Tests
  6. SonarQube Code Quality
  7. Build Docker Image
  8. Push to Registry
  9. Deploy to Render
  10. Run Smoke Tests
```

---

## 🎯 Spécifications Fonctionnelles

### Actor: CLIENT

#### UC1: Consulter Catalogue
```
Préconditions: Client conecté
Scénario principal:
  1. Client accède à la page "Catalogue"
  2. Système récupère tous les Burgers (statut=ACTIF)
  3. Système récupère tous les Menus (statut=ACTIF)
  4. Client peut filtrer par prix, type, rating
  5. Affiche détails + images + prix
Postconditions: Catalogue affiché
```

#### UC2: Passer Commande
```
Préconditions: Client connecté, Catalogue consulté
Scénario principal:
  1. Client ajoute Burger/Menu au panier
  2. Client sélectionne compléments (Frites, Boisson, etc.)
  3. Client définit type_livraison (SUR_PLACE, RETRAIT, LIVRAISON)
  4. Si LIVRAISON: Client sélectionne Zone → prix_livraison ajouté
  5. Système calcule montant_total
  6. Client valide panier → POST /api/commandes
  7. Système crée Commande (etat=VALIDE)
  8. Système crée Lignes_Commande + Lignes_Commandes_Complements
  9. Système crée Paiement (statut=PENDING)
  10. Affiche écran paiement
Postconditions: Commande créée, en attente paiement
```

#### UC3: Effectuer Paiement
```
Préconditions: Commande créée (VALIDE)
Scénario principal:
  1. Client choisit méthode: WAVE ou OM
  2. Client entre montant + infos (numéro téléphone, code secret)
  3. Système envoie à fournisseur WAVE/OM
  4. Si succès: Paiement.statut = VALIDE
  5. Update Commande.etat = PRETE
  6. Notifie Client: "Commande préparée"
  7. Notifie Gestionnaire: "Nouvelle commande PRETE"
  Scénario alternatif (Échec paiement):
  8. Si erreur: Paiement.statut = REJETE
  9. Affiche message d'erreur
  10. Permet retry
Postconditions: Paiement validé ou rejeté, Commande PRETE
```

#### UC4: Suivre Commande
```
Préconditions: Commande en cours
Scénario principal:
  1. Client ouvre "Mes Commandes"
  2. Système récupère toutes les Commandes du Client
  3. Pour chaque Commande: Affiche etat (VALIDE, PRETE, LIVREE)
  4. Si etat=LIVREE: Affiche position Livreur en temps réel (GPS)
  5. Si etat=LIVREE: Affiche ETA (Estimated Time of Arrival)
  6. Client peut cliquer pour voir détails (items, adresse, notes)
Postconditions: Statut et tracking affichés
```

### Actor: GESTIONNAIRE

#### UC5: Gérer Commandes
```
Préconditions: Gestionnaire connecté
Scénario principal:
  1. Gestionnaire ouvre "Commandes"
  2. Système récupère toutes Commandes (etat=PRETE)
  3. Affiche liste: Client, items, montant, type_livraison
  4. Gestionnaire sélectionne Commande
  5. Marque comme "Prête" → etat change à PRETE
  6. System notifie Client
  7. Gestionnaire assigne Livreur → Commande.id_livreur = X
  8. Système notifie Livreur
  9. Livreur reçoit notification → accepte livraison
  10. Commande.etat = LIVREE (auto-update quand Client confirme réception)
Postconditions: Commande prête, Livreur assigné, Notifications envoyées
```

#### UC6: Gérer Statistiques
```
Préconditions: Gestionnaire connecté
Scénario principal:
  1. Gestionnaire ouvre "Dashboard"
  2. Système calcule KPIs:
     - Revenu du jour (SUM montant_total WHERE date = TODAY)
     - Revenu du mois
     - Nombre de commandes (TODAY, WEEK, MONTH)
     - Top Burgers (most sold)
     - Top Clients (most orders)
     - Performance Livreurs (avg delivery time)
  3. Affiche graphiques, tableaux, rapports
  4. Permet export PDF/Excel
Postconditions: Statistiques affichées et exportables
```

### Actor: LIVREUR

#### UC7: Accepter Livraison
```
Préconditions: Livreur connecté, Commande assignée
Scénario principal:
  1. Livreur reçoit notification de nouvelle Commande assignée
  2. Livreur ouvre app → voit Commandes assignées
  3. Livreur clique "Accepter"
  4. Système démarre GPS tracking
  5. Livreur se dirige vers restaurant/point de retrait
  6. Récupère commande physique
  7. Livreur clique "En route vers client"
  8. GPS tracking continue
Postconditions: Livraison acceptée, GPS actif
```

#### UC8: Mettre à Jour Position
```
Préconditions: Livreur en route (GPS actif)
Scénario principal:
  1. App Livreur envoie position GPS toutes les 10 secondes
  2. API reçoit et update Commande.localisation (latitude, longitude)
  3. Client peut voir position Livreur en temps réel sur carte
  4. Calcul ETA automatique via OpenStreetMap
  5. Livreur arrive chez client
  6. Client confirme réception physique
  7. Livreur clique "Livraison effectuée"
  8. Commande.etat = LIVREE
Postconditions: Position mise à jour, Client notifié, Commande LIVREE
```

---

## 📋 Normes et Standards

### Conventions de Code

#### Java (Spring Boot)
```java
// Class naming: PascalCase
public class BurgerService { }

// Method naming: camelCase
public Burger getBurgerById(Long id) { }

// Constants: UPPER_SNAKE_CASE
public static final String DB_URL = "jdbc:...";

// Variables: camelCase
private String burgerName;

// Enums: PascalCase
public enum Statut { ACTIF, INACTIF }
```

#### Database (SQL)
```sql
-- Table naming: UPPER_SNAKE_CASE
CREATE TABLE CLIENT { }

-- Column naming: LOWER_snake_case
id_client BIGINT PRIMARY KEY

-- Index naming: idx_table_column
CREATE INDEX idx_client_email ON CLIENT(email);

-- Constraints: CK_table_column
ALTER TABLE BURGER ADD CONSTRAINT CK_statut CHECK (statut IN ('ACTIF', 'ARCHIVE'));
```

#### API Endpoints (REST)
```
GET    /api/clients              # List all clients
GET    /api/clients/{id}         # Get client by ID
POST   /api/clients              # Create client
PUT    /api/clients/{id}         # Update client
DELETE /api/clients/{id}         # Delete client

GET    /api/burgers              # List burgers
GET    /api/burgers/{id}         # Get burger
POST   /api/burgers              # Create burger (admin)

GET    /api/commandes            # List orders (auth)
POST   /api/commandes            # Create order
GET    /api/commandes/{id}       # Get order details
PUT    /api/commandes/{id}/etat  # Update order status

POST   /api/paiements            # Process payment
GET    /api/paiements/{id}       # Get payment status

GET    /api/zones                # List delivery zones
GET    /api/zones/{id}/quartiers # Get neighborhoods

GET    /api/livreurs             # List deliverers (admin)
PUT    /api/livreurs/{id}/localisation  # Update GPS
```

### HTTP Status Codes

| Code | Usage |
|------|-------|
| 200 | OK - Successful request |
| 201 | Created - Resource created |
| 204 | No Content - Success, no response body |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Missing/invalid auth |
| 403 | Forbidden - Permission denied |
| 404 | Not Found - Resource not found |
| 409 | Conflict - Duplicate email, etc. |
| 500 | Internal Server Error - Server error |

### Response Format (JSON)

```json
{
  "success": true,
  "status": 200,
  "message": "Opération réussie",
  "data": {
    "id": 1,
    "nom": "Burger Classique",
    "prix": 15.99
  },
  "timestamp": "2024-12-10T14:30:00Z",
  "path": "/api/burgers/1"
}
```

### Error Response

```json
{
  "success": false,
  "status": 400,
  "message": "Validation échouée",
  "errors": [
    {
      "field": "email",
      "message": "Email invalide"
    }
  ],
  "timestamp": "2024-12-10T14:30:00Z",
  "path": "/api/clients"
}
```

### Authentication & Authorization

- **Mechanism:** JWT (JSON Web Token)
- **Header:** `Authorization: Bearer <token>`
- **Token TTL:** 24 hours (production)
- **Refresh Token:** Available (7 days)
- **Roles:** CLIENT, GESTIONNAIRE, LIVREUR
- **Password:** bcrypt hashing (strength 10+)

### Security Best Practices

1. ✅ HTTPS/TLS for all communications
2. ✅ SQL Injection prevention (Parameterized queries)
3. ✅ XSS protection (Input sanitization)
4. ✅ CSRF tokens for state-changing operations
5. ✅ Rate limiting (100 req/min per IP)
6. ✅ Input validation (Length, type, regex)
7. ✅ Sensitive data masking in logs
8. ✅ CORS whitelist (specific origins)
9. ✅ API versioning (/api/v1/, /api/v2/)
10. ✅ Audit logging for critical operations

---

## 📅 Calendrier de Livraison

| Livrable | Description | Date | Status |
|----------|-------------|------|--------|
| **Livrable 1** | Modélisation + Java API (Render) | 14/12/2025 | 🟡 En cours |
| **Livrable 2** | C# ASP.NET MVC Client | 20/12/2025 | ⏳ À commencer |
| **Livrable 3** | Symfony Gestionnaire | 30/12/2025 | ⏳ À commencer |

---

## 📞 Contacts & Support

- **Project Manager:** [À remplir]
- **Lead Developer (Java):** [À remplir]
- **Database Admin:** [À remplir]
- **DevOps:** [À remplir]

---

**Version:** 2.0  
**Last Updated:** 10 December 2024  
**Status:** ✅ Modélisation Complète  
**Next Step:** GitHub Setup + Java Deployment to Render
