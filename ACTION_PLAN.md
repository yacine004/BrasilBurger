# 🎯 PLAN D'ACTION IMMÉDIAT - Brasil Burger
**Deadline Livrable 1 : 14/12/2025 (2 jours restants)**

## ✅ COMPLÉTÉ (12/12/2025 03:30)

1. **✅ Base de données PostgreSQL Neon**
   - Créée et opérationnelle
   - Host: `ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech`
   - 6 burgers, 10 compléments, 3 menus, 3 clients insérés

2. **✅ API Java Spring Boot**
   - Endpoints REST fonctionnels
   - GET /api/burgers → 6 burgers ✅
   - GET /api/menus → 3 menus ✅
   - GET /api/complements → 10 compléments ✅
   - GET /api/clients → 3 clients ✅
   - Spring Security configuré (accès public)
   - Code versionné sur GitHub (commit `3e25de4`)

3. **✅ Repository GitHub**
   - https://github.com/yacine004/BrasilBurger
   - Branche `master` active
   - Derniers commits sécurisés

4. **✅ Modélisation**
   - MLD SQL complet : `modelisation/mld/brasil_burger_postgresql.sql`
   - Script d'insertion : `modelisation/mld/neon_insert_data.sql`
   - Documentation : `modelisation/MODELISATION_v2.0.md`

## 📋 À FAIRE MAINTENANT (Ordre de priorité)

### PRIORITÉ 1 : Cloudinary (30 minutes) ⏰

**Pourquoi maintenant ?** Les images sont essentielles pour le Livrable 1.

**Actions :**
1. ☐ Créer compte sur https://cloudinary.com/users/register_free
2. ☐ Récupérer Cloud Name, API Key, API Secret
3. ☐ Ajouter dépendance Maven dans `java/pom.xml` :
   ```xml
   <dependency>
       <groupId>com.cloudinary</groupId>
       <artifactId>cloudinary-http44</artifactId>
       <version>1.39.0</version>
   </dependency>
   ```
4. ☐ Créer `CloudinaryService.java` (voir CLOUDINARY_SETUP.md)
5. ☐ Ajouter credentials dans `application-dev.yml` (local, non commité)
6. ☐ Tester upload d'une image de burger
7. ☐ Commit : "feat: Intégration Cloudinary pour stockage images"

**Fichier guide** : `CLOUDINARY_SETUP.md` ✅ CRÉÉ

---

### PRIORITÉ 2 : Déploiement Render (1 heure) ⏰

**Pourquoi maintenant ?** Le prof doit pouvoir tester l'API en ligne.

**Actions :**
1. ☐ Créer compte sur https://render.com (sign up avec GitHub)
2. ☐ Créer `application-prod.yml` dans `java/src/main/resources/`
3. ☐ (Optionnel) Créer `Dockerfile` dans `java/`
4. ☐ Sur Render Dashboard :
   - New Web Service
   - Connecter repo `yacine004/BrasilBurger`
   - Branch: `master`
   - Root Directory: `java`
   - Build Command: `mvn clean package -DskipTests`
   - Start Command: `java -jar target/brasibturger-api-1.0.0.jar`
5. ☐ Configurer variables d'environnement Render :
   ```
   SPRING_PROFILES_ACTIVE=prod
   SPRING_DATASOURCE_URL=jdbc:postgresql://...
   SPRING_DATASOURCE_USERNAME=neondb_owner
   SPRING_DATASOURCE_PASSWORD=npg_Zwmhr46vDLKy
   CLOUDINARY_CLOUD_NAME=...
   CLOUDINARY_API_KEY=...
   CLOUDINARY_API_SECRET=...
   SERVER_PORT=10000
   ```
6. ☐ Déployer et tester l'URL publique
7. ☐ Commit : "deploy: Configuration production Render"

**Fichier guide** : `RENDER_DEPLOYMENT.md` ✅ CRÉÉ

**URL finale attendue** : `https://brasilburger-api-java.onrender.com/api/burgers`

---

### PRIORITÉ 3 : Diagrammes UML (2 heures) ⏰

**Requis pour Livrable 1 :**

1. ☐ **Diagramme de cas d'utilisation**
   - Acteurs : Client, Gestionnaire, Livreur
   - Use cases : Passer commande, Gérer menu, Suivre livraison
   - Fichier : `modelisation/diagrammes/use_case_diagram.puml`

2. ☐ **Diagramme de classes**
   - Classes principales : Burger, Menu, Commande, Client
   - Relations, attributs, méthodes
   - Fichier : `modelisation/diagrammes/class_diagram.puml`

3. ☐ **Diagrammes de séquence (3 scénarios)**
   - Scénario 1 : Passer une commande
   - Scénario 2 : Gérer les burgers (CRUD)
   - Scénario 3 : Traiter un paiement
   - Fichiers : `modelisation/diagrammes/sequences/`

**Outils recommandés :**
- PlantUML (https://plantuml.com)
- VS Code extension PlantUML
- Ou StarUML / Draw.io

**Commit** : "docs: Ajout diagrammes UML complets (use case, class, sequences)"

---

### PRIORITÉ 4 : Documentation Livrable 1 (1 heure) ⏰

**Créer fichier `LIVRABLE_1.md` :**

```markdown
# Livrable 1 - Brasil Burger

## 📅 Date de rendu : 14/12/2025

## 🔗 Liens importants

- **Repository GitHub** : https://github.com/yacine004/BrasilBurger
- **API Production** : https://brasilburger-api-java.onrender.com
- **Database Neon** : ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech
- **Cloudinary** : [Cloud Name]

## 📋 Contenu du Livrable

### 1. Modélisation (modelisation/)
- ✅ Diagramme de cas d'utilisation
- ✅ Diagramme de classes
- ✅ Diagrammes de séquence (3)
- ✅ MLD (Modèle Logique de Données)
- ✅ Script SQL PostgreSQL complet

### 2. API REST Java (java/)
- ✅ Spring Boot 3.3.6
- ✅ Endpoints CRUD : burgers, menus, compléments, clients
- ✅ Connecté à PostgreSQL Neon
- ✅ Stockage images Cloudinary
- ✅ Déployé sur Render

### 3. Tests API

**Base URL** : `https://brasilburger-api-java.onrender.com`

Endpoints disponibles :
- GET /api/burgers → Liste des burgers
- GET /api/menus → Liste des menus
- GET /api/complements → Liste des compléments
- POST /api/burgers → Créer burger
- PUT /api/burgers/{id} → Modifier burger
- DELETE /api/burgers/{id} → Supprimer burger

## 🚀 Instructions de déploiement

[Voir RENDER_DEPLOYMENT.md]

## 📸 Captures d'écran

[À ajouter]
```

---

## ⏱️ PLANNING (12/12 - 14/12)

### Jeudi 12/12 (Aujourd'hui) - Après-midi/Soir
- ✅ 14:00 - 14:30 : Cloudinary setup
- ✅ 14:30 - 15:30 : Déploiement Render
- ✅ 15:30 - 16:00 : Tests API production

### Vendredi 13/12 - Matin
- 09:00 - 11:00 : Diagrammes UML
- 11:00 - 12:00 : Documentation Livrable 1

### Vendredi 13/12 - Après-midi
- 14:00 - 15:00 : Captures d'écran + README final
- 15:00 - 16:00 : Vérification complète
- 16:00 - 17:00 : Buffer pour imprévus

### Samedi 14/12 - Matin (Deadline)
- 09:00 - 10:00 : Dernières retouches
- 10:00 - 11:00 : Soumission finale

---

## 📞 COMMANDES RAPIDES

### Démarrer API locale :
```bash
cd C:\Users\HP\Desktop\BrasilBurger\java
$env:SPRING_PROFILES_ACTIVE="dev"
mvn spring-boot:run
```

### Tester API locale :
```powershell
Invoke-RestMethod http://localhost:8080/api/burgers | ConvertTo-Json
```

### Git commit rapide :
```bash
git add .
git commit -m "feat: [description]"
git push origin master
```

### Vérifier connexion Neon :
```bash
psql "postgresql://neondb_owner:npg_Zwmhr46vDLKy@ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require"
```

---

## ✅ CHECKLIST FINALE AVANT SOUMISSION

- [ ] API déployée et accessible publiquement
- [ ] Base de données Neon avec données de test
- [ ] Images stockées sur Cloudinary
- [ ] 3 diagrammes UML (use case, class, sequence)
- [ ] Script SQL complet et testé
- [ ] README.md à jour avec toutes les URLs
- [ ] LIVRABLE_1.md complété
- [ ] Captures d'écran des tests
- [ ] Repository GitHub propre (pas de credentials)
- [ ] Toutes les branches à jour

---

## 🎯 OBJECTIF

**Avoir un Livrable 1 complet et professionnel avant le 14/12/2025 à 23:59**

✨ Vous êtes sur la bonne voie ! L'API fonctionne déjà parfaitement. Il reste surtout de la configuration et de la documentation.
