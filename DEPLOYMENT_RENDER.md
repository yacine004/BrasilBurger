# Déploiement sur Render - Brasil Burger

## Instructions de déploiement

### 1. Pré-requis
- Compte GitHub (déjà configuré)
- Compte Render (https://render.com)
- PostgreSQL Neon (déjà configuré)
- Cloudinary (déjà configuré)

### 2. Variables d'environnement requises pour Render

Configurez ces variables dans Render Dashboard:

```
ASPNETCORE_ENVIRONMENT = Production
DOTNET_ENV = production
ConnectionStrings__DefaultConnection = Host=ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech;Database=neondb;Username=neondb_owner;Password=<votre_password>;SSL Mode=Require;Trust Server Certificate=true
Cloudinary__CloudName = dd8kegetk
Cloudinary__ApiKey = 427329567874199
Cloudinary__ApiSecret = 27B9-zdx3cUBKSnIHYCxNWiH96s
```

### 3. Étapes de déploiement

#### A. Pousser le code sur GitHub
```powershell
cd C:\Users\HP\Desktop\BrasilBurger\csharp
git add .
git commit -m "feat: Initial deployment setup for Brasil Burger on Render"
git push origin livrable2-csharp
```

#### B. Créer un nouveau Web Service sur Render
1. Allez sur https://dashboard.render.com
2. Cliquez sur "New +" → "Web Service"
3. Sélectionnez votre repository GitHub
4. Configurez:
   - **Name**: brasil-burger-app
   - **Environment**: Docker ou .NET (automatique)
   - **Build Command**: `cd BrasilBurger.Web && dotnet restore && dotnet build -c Release`
   - **Start Command**: `cd BrasilBurger.Web && dotnet BrasilBurger.Web.dll`
   - **Plan**: Free (ou Starter si vous voulez un domaine)

#### C. Ajouter les variables d'environnement
1. Dans Render Dashboard → Settings → Environment
2. Ajouter toutes les variables listées ci-dessus
3. Cliquer "Deploy"

### 4. Fichiers importants
- `.render.yaml` - Configuration de déploiement
- `appsettings.Production.json` - Paramètres production (stocke les clés env)
- `.gitignore` - Ne pas versionner les secrets

### 5. Vérification après déploiement
- Vérifier les logs sur Render Dashboard
- Tester l'application sur `https://brasil-burger-app.onrender.com`
- Vérifier la connexion à PostgreSQL Neon
- Tester l'upload d'images vers Cloudinary

### 6. Dépannage
- Si `dotnet restore` échoue: Vérifier `.csproj` et dépendances NuGet
- Si connexion BD échoue: Vérifier chaîne de connexion et credentials Neon
- Si images ne s'affichent: Vérifier Cloudinary credentials
- Vérifier les logs: Render Dashboard → Logs

### 7. Prochaines étapes
1. Confirmer déploiement réussi
2. Tester les fonctionnalités (listing, cart)
3. Ajouter les images des burgers (une fois déploiement stable)
4. Migrer les autres livrables
