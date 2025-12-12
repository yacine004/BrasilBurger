# Guide de déploiement Render.com - Brasil Burger

## Prérequis
- ✅ Compte GitHub avec repo BrasilBurger
- ✅ Base de données Neon PostgreSQL créée
- ✅ Compte Cloudinary avec credentials

## Étape 1 : Créer compte Render

1. Allez sur https://render.com
2. **Sign Up** avec votre compte GitHub
3. Autorisez Render à accéder à vos repositories

## Étape 2 : Déployer l'API Java Spring Boot

### A. Créer le service

1. Dashboard Render → **New +** → **Web Service**
2. **Connect repository** : Sélectionnez `yacine004/BrasilBurger`
3. Configurez :
   - **Name** : `brasilburger-api-java`
   - **Region** : `Frankfurt (EU Central)` ou proche
   - **Branch** : `master`
   - **Root Directory** : `java`
   - **Runtime** : `Docker` (ou `Java` si disponible)
   - **Build Command** : 
     ```bash
     mvn clean package -DskipTests
     ```
   - **Start Command** :
     ```bash
     java -jar target/brasibturger-api-1.0.0.jar
     ```
   - **Instance Type** : `Free`

### B. Variables d'environnement

Cliquez sur **Environment** et ajoutez :

```
SPRING_PROFILES_ACTIVE=prod

SPRING_DATASOURCE_URL=jdbc:postgresql://ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require
SPRING_DATASOURCE_USERNAME=neondb_owner
SPRING_DATASOURCE_PASSWORD=npg_Zwmhr46vDLKy

CLOUDINARY_CLOUD_NAME=votre_cloud_name
CLOUDINARY_API_KEY=votre_api_key
CLOUDINARY_API_SECRET=votre_api_secret

SERVER_PORT=10000
```

### C. Créer le profil production

Créez `java/src/main/resources/application-prod.yml` :

```yaml
spring:
  datasource:
    url: ${SPRING_DATASOURCE_URL}
    username: ${SPRING_DATASOURCE_USERNAME}
    password: ${SPRING_DATASOURCE_PASSWORD}
  jpa:
    show-sql: false
    hibernate:
      ddl-auto: validate
  sql:
    init:
      mode: never

server:
  port: ${SERVER_PORT:10000}

cloudinary:
  cloud-name: ${CLOUDINARY_CLOUD_NAME}
  api-key: ${CLOUDINARY_API_KEY}
  api-secret: ${CLOUDINARY_API_SECRET}
```

### D. Créer Dockerfile (optionnel mais recommandé)

Créez `java/Dockerfile` :

```dockerfile
FROM eclipse-temurin:21-jdk-alpine as build
WORKDIR /workspace/app

COPY mvnw .
COPY .mvn .mvn
COPY pom.xml .
COPY src src

RUN ./mvnw package -DskipTests
RUN mkdir -p target/dependency && (cd target/dependency; jar -xf ../*.jar)

FROM eclipse-temurin:21-jre-alpine
VOLUME /tmp
ARG DEPENDENCY=/workspace/app/target/dependency
COPY --from=build ${DEPENDENCY}/BOOT-INF/lib /app/lib
COPY --from=build ${DEPENDENCY}/META-INF /app/META-INF
COPY --from=build ${DEPENDENCY}/BOOT-INF/classes /app
ENTRYPOINT ["java","-cp","app:app/lib/*","com.brasibturger.BrasilBurgerApplication"]
```

### E. Déployer

1. Cliquez **Create Web Service**
2. Attendez le build (5-10 min)
3. Une fois déployé, vous aurez une URL : `https://brasilburger-api-java.onrender.com`

### F. Tester

```bash
curl https://brasilburger-api-java.onrender.com/api/burgers
```

## Étape 3 : Déployer API C# (si nécessaire)

### Configuration similaire :
- **Root Directory** : `csharp`
- **Build Command** : `dotnet publish -c Release -o out`
- **Start Command** : `dotnet out/BrasilBurger.Web.dll`

Variables d'environnement :
```
ConnectionStrings__DefaultConnection=Host=ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech;Port=5432;Database=neondb;Username=neondb_owner;Password=npg_Zwmhr46vDLKy;SSL Mode=Require

CLOUDINARY__CloudName=votre_cloud_name
CLOUDINARY__ApiKey=votre_api_key
CLOUDINARY__ApiSecret=votre_api_secret
```

## Étape 4 : Déployer Symfony (si nécessaire)

- **Root Directory** : `symfony`
- **Build Command** : 
  ```bash
  composer install --no-dev --optimize-autoloader
  php bin/console doctrine:migrations:migrate --no-interaction || true
  php bin/console cache:clear --env=prod
  ```
- **Start Command** : `php -S 0.0.0.0:10000 -t public`

Variables d'environnement :
```
DATABASE_URL=postgresql://neondb_owner:npg_Zwmhr46vDLKy@ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require

APP_ENV=prod
APP_SECRET=générer_secret_symfony
```

## Étape 5 : Configuration DNS (optionnel)

Si vous avez un domaine :
1. Render → Service → Settings → **Custom Domain**
2. Ajoutez votre domaine
3. Configurez les DNS selon instructions Render

## Troubleshooting

### Erreur "Port already in use"
- Assurez-vous que `SERVER_PORT=10000` est défini

### Erreur de connexion DB
- Vérifiez que l'URL Neon contient `?sslmode=require`
- Testez la connexion depuis Render Shell

### Build timeout
- Le plan gratuit a une limite de 15 min de build
- Optimisez le build avec cache Maven/Docker

## Liens utiles

- 📚 Render Docs : https://render.com/docs
- 🐘 Neon Docs : https://neon.tech/docs
- ☁️ Cloudinary Docs : https://cloudinary.com/documentation

## URLs finales attendues

- **API Java** : https://brasilburger-api-java.onrender.com
- **API C#** : https://brasilburger-api-csharp.onrender.com  
- **Symfony** : https://brasilburger-symfony.onrender.com
- **GitHub** : https://github.com/yacine004/BrasilBurger
- **Neon DB** : ep-empty-fire-adg2yddb-pooler.c-2.us-east-1.aws.neon.tech
