# Build stage - Compile l'app ASP.NET Core
FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build

WORKDIR /src

# Copier le projet C#
COPY csharp/BrasilBurger.Web/BrasilBurger.Web.csproj ./

# Restaurer les dépendances
RUN dotnet restore

# Copier tout le code source
COPY csharp/BrasilBurger.Web/ ./

# Compiler et publier
RUN dotnet publish -c Release -o /app/publish --no-restore

# Runtime stage - Image minimale pour l'exécution
FROM mcr.microsoft.com/dotnet/aspnet:10.0

WORKDIR /app

# Copier l'application compilée
COPY --from=build /app/publish .

# Port (Render utilise ce port)
EXPOSE 8080

# Variables d'environnement
ENV ASPNETCORE_URLS=http://+:8080
ENV ASPNETCORE_ENVIRONMENT=Production
ENV ASPNETCORE_CONTENTROOT=/app

# Lancer l'application
ENTRYPOINT ["dotnet", "BrasilBurger.Web.dll"]
