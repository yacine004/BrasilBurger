# Guide de Deploiement - Brasil Burger

## Livrable 1 - Application Java Console

---

## Prerequis

- **Java 21** ou superieur
- **Maven 3.9+** (pour compilation depuis les sources)
- **PostgreSQL** - Base de donnees Neon.tech (deja configuree)
- **Cloudinary** - Compte pour upload d'images (optionnel)

---

## Methode 1 : Execution du JAR (Recommande)

### Etape 1 : Cloner le repository

```bash
git clone https://github.com/yacine004/BrasilBurger.git
cd BrasilBurger/java
```

### Etape 2 : Executer le JAR

```bash
java -jar target/brasilburger-console-1.0.0.jar
```

**Note** : Le JAR contient toutes les dependances necessaires (PostgreSQL, Cloudinary).

---

## Methode 2 : Compilation et Execution depuis les sources

### Etape 1 : Cloner le repository

```bash
git clone https://github.com/yacine004/BrasilBurger.git
cd BrasilBurger/java
```

### Etape 2 : Compiler le projet

```bash
mvn clean compile
```

### Etape 3 : Executer l'application

```bash
mvn exec:java
```

---

## Configuration

### Base de donnees PostgreSQL (Neon.tech)

La configuration est incluse dans le code :

- **Host** : `ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech`
- **Database** : `neondb`
- **User** : `neondb_owner`
- **SSL** : Requis

**La base de donnees est deja configuree et accessible.**

### Cloudinary (Upload d'images)

Configuration dans `CloudinaryConfig.java` :
- Cloud name
- API Key
- API Secret

**Note** : Les credentials Cloudinary sont integres dans l'application.

---

## Fonctionnalites disponibles

### Menu principal

```
=============== MENU PRINCIPAL ===============
1. Gerer les Burgers
2. Gerer les Menus
3. Gerer les Complements
4. Quitter
==============================================
```

### Gestion des Burgers (Fonctionnel)

- **Afficher tous les burgers** : Liste complete depuis la base de donnees
- **Ajouter un burger** : Creation avec nom, description, prix, disponibilite
- **Modifier un burger** : Mise a jour des informations
- **Supprimer un burger** : Suppression definitive
- **Archiver un burger** : Desactivation (is_available = false)
- **Upload image** : Integration Cloudinary pour les photos

### Menus et Complements

En cours de developpement (placeholders actifs).

---

## Structure du projet

```
java/
├── src/main/java/com/brasilburger/
│   ├── Main.java                    # Point d'entree
│   ├── config/
│   │   ├── DatabaseConfig.java      # Configuration PostgreSQL
│   │   └── CloudinaryConfig.java    # Configuration Cloudinary
│   ├── model/
│   │   ├── Burger.java
│   │   ├── Menu.java
│   │   └── Complement.java
│   ├── dao/
│   │   └── BurgerDAO.java          # CRUD Burgers
│   └── service/
│       └── CloudinaryService.java   # Upload/Delete images
├── target/
│   └── brasilburger-console-1.0.0.jar  # JAR executable
├── pom.xml                              # Configuration Maven
└── README.md                            # Documentation
```

---

## Tests et Validation

### Test 1 : Connexion a la base de donnees

Au demarrage, l'application affiche :
```
[OK] Connexion a la base de donnees etablie.
[OK] Cloudinary configure.
```

### Test 2 : Afficher les burgers

Selectionnez l'option 1 → 1 pour voir les burgers existants.

### Test 3 : Ajouter un burger

Selectionnez l'option 1 → 2 et suivez les instructions.

---

## Deploiement GitHub

**Repository** : https://github.com/yacine004/BrasilBurger

### Contenu du Livrable 1

```
BrasilBurger/
├── modelisation/              # UML + MLD + Maquettes
│   ├── diagrammes/           # PNG des diagrammes
│   ├── maquettes/            # 15 captures d'ecran Figma
│   │   ├── client/          # 7 maquettes application client
│   │   └── gestionnaire/    # 8 maquettes dashboard
│   ├── mld.sql              # Script SQL PostgreSQL
│   └── README.md
├── java/                     # Application Console
│   ├── src/
│   ├── target/
│   │   └── brasilburger-console-1.0.0.jar
│   ├── pom.xml
│   └── README.md
├── README.md                 # Documentation principale
└── DEPLOIEMENT.md           # Ce fichier
```

---

## Verification du deploiement

### 1. Cloner le repository

```bash
git clone https://github.com/yacine004/BrasilBurger.git
```

### 2. Verifier la structure

```bash
cd BrasilBurger
ls
# Doit afficher : java, modelisation, README.md, DEPLOIEMENT.md, .gitignore
```

### 3. Executer l'application

```bash
cd java
java -jar target/brasilburger-console-1.0.0.jar
```

### 4. Tester les fonctionnalites

- Afficher les burgers
- Ajouter un burger de test
- Modifier/Supprimer

---

## Problemes courants

### Erreur : "Driver PostgreSQL non trouve"

**Solution** : Recompiler avec Maven pour inclure les dependances
```bash
mvn clean package
java -jar target/brasilburger-console-1.0.0.jar
```

### Erreur : "Connexion a la base de donnees refusee"

**Solution** : Verifier la connectivite Internet (base Neon.tech en ligne)

### Caracteres mal affiches (emojis)

**Solution** : L'application utilise uniquement des caracteres ASCII pour compatibilite PowerShell Windows

---

## Prochaines etapes (Livrables 2 & 3)

- **Livrable 2 (20/12/2025)** : Application C# ASP.NET MVC (Client)
- **Livrable 3 (30/12/2025)** : Application Symfony (Gestionnaire + Statistiques)

---

## Contact

**Repository GitHub** : https://github.com/yacine004/BrasilBurger

**Date de livraison** : 14/12/2025

---

## Checklist de validation Livrable 1

- [x] Modelisation UML complete (diagrammes)
- [x] MLD/SQL (script PostgreSQL)
- [x] Maquettes Figma (15 captures PNG)
- [x] Base de donnees PostgreSQL deployee (Neon.tech)
- [x] Application Java Console fonctionnelle
- [x] CRUD Burgers operationnel
- [x] Integration Cloudinary pour images
- [x] Code source sur GitHub
- [x] JAR executable disponible
- [x] Documentation complete (README + DEPLOIEMENT)

**Statut** : ✅ LIVRABLE 1 COMPLET
