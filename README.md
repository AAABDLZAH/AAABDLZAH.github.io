# 🌤️ Weather App - PHP & AJAX

## 📄 Description
Cette application météo simple, développée en PHP avec AJAX, permet à l'utilisateur de rechercher la météo actuelle et la prévision sur 5 jours pour n'importe quelle ville via l'API OpenWeatherMap. Les données se mettent à jour sans recharger la page.

## ✨ Fonctionnalités
- 🔍 Recherche météo par nom de ville.
- 🌡️ Affiche la météo actuelle : température, humidité, vitesse du vent, conditions météo et icône correspondante.
- 📅 Prévisions météo quotidiennes à midi pour les jours à venir.
- ⚠️ Gestion des erreurs pour ville non trouvée ou erreurs API.
- 🔄 Soumission du formulaire via AJAX pour une expérience fluide.
- ⏰ Actualisation automatique des données météo toutes les 30 minutes.

## 📋 Prérequis
- PHP 7.x ou supérieur
- Connexion internet (pour accéder à l'API OpenWeatherMap)
- jQuery (inclus via CDN)

## ⚙️ Installation
1. Obtenez une clé API sur [OpenWeatherMap](https://openweathermap.org/).
2. Remplacez la clé API dans le fichier PHP :
   ```php
   $apikey = 'votre_clef_api_ici';
