#!/bin/bash
# Script de déploiement pour Render
# À exécuter sur Render lors du build

set -e

echo "🚀 Début du déploiement Brasil Burger sur Render"

# Aller au répertoire C#
cd csharp/BrasilBurger.Web

echo "📦 Restauration des dépendances NuGet..."
dotnet restore

echo "🔨 Compilation du projet en Release..."
dotnet build -c Release

echo "✅ Déploiement terminé avec succès!"
echo "L'application va démarrer sur le port ${PORT}"
