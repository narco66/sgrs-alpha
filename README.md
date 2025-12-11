# README.md — SGRS-ALPHA
_Système de Gestion des Réunions Statutaires de la Commission de la CEEAC_

Repository : https://github.com/narco66/sgrs-alpha.git

## 📛 Badges du Projet
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel)
![MySQL](https://img.shields.io/badge/Database-MySQL-blue?logo=mysql)
![GitHub](https://img.shields.io/badge/Status-Actif-success)
![Maintenance](https://img.shields.io/badge/Maintenu-Oui-brightgreen)
![Contributions](https://img.shields.io/badge/Contributions-Interne-orange)

## 🧭 TABLE DES MATIÈRES
1. Présentation du Projet
2. Technologies et Architecture
3. Installation et Déploiement
4. Mise à jour du Projet
5. Gestion des rôles et permissions
6. Tableau de bord et Fonctionnalités
7. Documentation
8. Tests automatisés
9. Support et Assistance
10. Contribution interne
11. Licence et Confidentialité
12. FAQ

## 📘 Présentation du Projet
SGRS-ALPHA est une application institutionnelle développée sous Laravel 11, destinée à organiser, gérer et suivre l’ensemble des réunions statutaires de la Commission de la CEEAC.

## ⚙ Technologies et Architecture
- Laravel 11 (PHP 8.2+)
- MySQL / MariaDB
- Blade Templates + Bootstrap 5
- Spatie Laravel-Permission
- Chart.js / ApexCharts
- Vite + Node.js

## 📦 Installation et Déploiement
### 1. Prérequis
- PHP 8.2+
- Composer 2.x
- MySQL 8+
- Node.js 18+
- Git

### 2. Cloner le dépôt
```
git clone https://github.com/narco66/sgrs-alpha.git
cd sgrs-alpha
```

### 3. Installer les dépendances
```
composer install
npm install
npm run build
```

### 4. Préparation de l’environnement
```
cp .env.example .env
php artisan key:generate
```

Configurer la base de données dans `.env`.

### 5. Migration & seeders
```
php artisan migrate
php artisan db:seed
```

### 6. Créer le lien de stockage
```
php artisan storage:link
```

### 7. Lancer l’application
```
php artisan serve
```

## 📄 Mise à jour du Projet
```
git pull
composer install
npm install
npm run build
php artisan migrate
php artisan optimize:clear
```

## 🔐 Gestion des rôles et permissions
SGRS-ALPHA utilise Spatie Laravel-Permission.

## 📊 Tableau de bord – Fonctionnalités
Statistiques, graphiques, notifications, résumé des réunions.

## 📚 Documentation
La documentation complète est disponible dans `/docs`.

## 🧪 Tests automatisés
```
php artisan test
```

## 📬 Support et Assistance
Direction des Systèmes d’Information – Commission de la CEEAC.

## 🤝 Contribution interne
Workflow interne basé sur branches et Pull Requests.

## 📜 Licence et Confidentialité
Projet interne de la CEEAC — diffusion interdite sans autorisation.

## ❓ FAQ
Inclut : problèmes PDF, migrations, permissions, mises à jour, etc.
