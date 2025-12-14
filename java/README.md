# Brasil Burger - Application Console Java

Application console Java pour la creation des ressources du restaurant Brasil Burger.

## 🎯 Fonctionnalites (Livrable 1)

### 1. Gestion des Burgers ✅
- Afficher tous les burgers
- Ajouter un nouveau burger
- Modifier un burger existant
- Supprimer un burger
- Upload d'image vers Cloudinary

### 2. Gestion des Menus 🔄
- En cours de developpement

### 3. Gestion des Complements 🔄
- En cours de developpement

## 🛠️ Technologies

- **Java 21**
- **Maven 3.9.6**
- **PostgreSQL** (Neon Database)
- **Cloudinary** (Stockage d'images)

## 📦 Dépendances

```xml
<dependencies>
    <!-- PostgreSQL Driver -->
    <dependency>
        <groupId>org.postgresql</groupId>
        <artifactId>postgresql</artifactId>
        <version>42.7.1</version>
    </dependency>

    <!-- Cloudinary SDK -->
    <dependency>
        <groupId>com.cloudinary</groupId>
        <artifactId>cloudinary-http44</artifactId>
        <version>1.39.0</version>
    </dependency>

    <!-- Lombok -->
    <dependency>
        <groupId>org.projectlombok</groupId>
        <artifactId>lombok</artifactId>
        <version>1.18.30</version>
        <scope>provided</scope>
    </dependency>
</dependencies>
```

## 📁 Structure du Projet

```
java/
├── pom.xml
└── src/
    └── main/
        └── java/
            └── com/
                └── brasilburger/
                    ├── Main.java                    # Point d'entrée
                    ├── config/
                    │   ├── DatabaseConfig.java      # Configuration PostgreSQL
                    │   └── CloudinaryConfig.java    # Configuration Cloudinary
                    ├── model/
                    │   ├── Burger.java
                    │   ├── Complement.java
                    │   └── Menu.java
                    ├── dao/
                    │   └── BurgerDAO.java          # CRUD Burgers
                    └── service/
                        └── CloudinaryService.java   # Upload/Delete images
```

## 🚀 Compilation

```bash
cd C:\Users\HP\Desktop\BrasilBurger\java
mvn clean compile
```

## ▶️ Exécution

### Méthode 1 : Avec Maven
```bash
cd C:\Users\HP\Desktop\BrasilBurger\java
mvn exec:java '-Dexec.mainClass=com.brasilburger.Main'
```

### Méthode 2 : JAR Exécutable
```bash
# Créer le JAR
mvn clean package

# Exécuter
java -jar target/brasilburger-console-1.0.0.jar
```

## 🗄️ Configuration Base de Données

**PostgreSQL Neon Database**
```
URL: jdbc:postgresql://ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require
User: neondb_owner
Password: npg_Zwmhr46vDLKy
```

**Tables utilisées :**
- `burgers` (id, name, description, price, image, is_available)
- `menus` (id, name, description, price, burger_id, is_available)
- `complements` (id, name, description, price, category, is_available)

## ☁️ Configuration Cloudinary

```
Cloud Name: dd8kegetk
API Key: 427329567874199
API Secret: 27B9-zdx3cUBKSnIHYCxNWiH96s
```

**Dossiers Cloudinary :**
- `burgers/` - Images des burgers
- `menus/` - Images des menus
- `complements/` - Images des compléments
- `modelisation/` - Diagrammes UML/MLD

## 📋 Utilisation

### Menu Principal
```
╔══════════════════════════════════════════╗
║   BRASIL BURGER - Application Console   ║
╚══════════════════════════════════════════╝

✅ Connexion à la base de données établie.
✅ Cloudinary configuré.

═══════════════ MENU PRINCIPAL ═══════════════
1. Gérer les Burgers
2. Upload images modélisation vers Cloudinary
3. Quitter
```

### Option 1 : Gérer les Burgers
```
📋 GESTION DES BURGERS
1. Afficher tous les burgers
2. Ajouter un burger
3. Modifier un burger
4. Supprimer un burger
5. Upload image pour un burger
6. Retour au menu principal
```

### Option 2 : Upload Images Modélisation
Upload automatique des 6 fichiers PNG :
- `usecase.png`
- `classdiagram.png`
- `sequence_commande.png`
- `sequence_paiement.png`
- `sequence_livraison.png`
- `mld.png`

Les images sont uploadées vers le dossier `modelisation/` sur Cloudinary.

## 🔧 Développement

### Ajouter un nouveau DAO
1. Créer la classe dans `com.brasilburger.dao`
2. Implémenter les méthodes CRUD
3. Utiliser `DatabaseConfig.getConnection()`

### Ajouter un nouveau Model
1. Créer la classe dans `com.brasilburger.model`
2. Ajouter les champs avec getters/setters
3. Implémenter `toString()` pour l'affichage console

### Patterns utilisés
- **Singleton** : DatabaseConfig, CloudinaryConfig
- **DAO** : BurgerDAO
- **Service Layer** : CloudinaryService

## ⚠️ Notes Importantes

- L'ancien package `com.brasibturger` (avec typo) contient des fichiers JDK verrouillés mais n'affecte pas la compilation
- Le nouveau code est dans `com.brasilburger` (orthographe correcte)
- La connexion utilise le endpoint direct Neon (pas le pooler)
- **Tous les caractères accentués et emojis ont été supprimés** pour garantir un affichage correct dans PowerShell Windows
- Les messages utilisent des codes ASCII simples : `[OK]`, `[X]`, `[^]`, `[+]`, `[-]`, `[!]`, `[#]`, `[*]`

## 📝 TODO

- [ ] Implémenter MenuDAO pour la gestion des menus
- [ ] Implémenter ComplementDAO pour la gestion des compléments
- [ ] Ajouter des tests unitaires
- [ ] Améliorer la gestion des erreurs
- [ ] Ajouter un logger (SLF4J + Logback)

## 👤 Auteur

Projet Brasil Burger - Livrable 1
Date : 14 décembre 2025

## 📄 Licence

Projet académique
