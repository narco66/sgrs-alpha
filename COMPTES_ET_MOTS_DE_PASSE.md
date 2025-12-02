# 🔐 Comptes et Mots de Passe - SGRS-CEEAC

## 📋 Comptes Administrateurs par Défaut

Les comptes suivants sont créés automatiquement lors de l'exécution du seeder.

### 1. Super Administrateur
- **Email:** `super.admin@sgrs-ceeac.org`
- **Mot de passe:** `Password@2025`
- **Rôle:** `super-admin`
- **Permissions:** Toutes les permissions du système
- **Accès:** Complet à toutes les fonctionnalités

### 2. Administrateur Secrétariat Général
- **Email:** `sg.admin@sgrs-ceeac.org`
- **Mot de passe:** `Password@2025`
- **Rôles:** `admin`, `sg`
- **Permissions:** Gestion complète des réunions, documents, validation institutionnelle
- **Accès:** Administration et validation au niveau du Secrétariat Général

### 3. Administrateur DSI
- **Email:** `dsi.admin@sgrs-ceeac.org`
- **Mot de passe:** `Password@2025`
- **Rôles:** `admin`, `dsi`
- **Permissions:** Gestion technique, paramétrage, utilisateurs, délégations
- **Accès:** Administration technique de la Direction des Systèmes d'Information

---

## 👤 Comptes Utilisateurs

### 4. Utilisateur Staff
- **Email:** `staff@sgrs-ceeac.org`
- **Mot de passe:** `Password@2025`
- **Rôle:** `staff`
- **Permissions:** Lecture, création limitée de réunions
- **Accès:** Utilisateur standard avec accès de base

### 5. Utilisateur de Test
- **Email:** `test@example.com`
- **Mot de passe:** `password`
- **Rôle:** `staff`
- **Permissions:** Lecture, création limitée
- **Accès:** Compte de test pour développement

---

## 🚀 Création des Comptes

Pour créer ces comptes, exécutez :

```bash
php artisan db:seed
```

Ou spécifiquement pour les utilisateurs :

```bash
php artisan db:seed --class=UserSeeder
```

---

## 🔑 Création d'un Nouveau Compte Administrateur

### Via Tinker (Ligne de commande)

```bash
php artisan tinker
```

Puis dans tinker :

```php
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

// Créer l'utilisateur
$user = User::create([
    'name' => 'Votre Nom',
    'email' => 'votre.email@sgrs-ceeac.org',
    'password' => Hash::make('VotreMotDePasse'),
    'email_verified_at' => now(),
    'is_active' => true,
]);

// Assigner le rôle super-admin
$role = Role::where('name', 'super-admin')->first();
$user->assignRole($role);
```

### Via l'Interface Web

1. Connectez-vous avec un compte administrateur
2. Allez dans **Administration > Utilisateurs**
3. Cliquez sur **Nouvel utilisateur**
4. Remplissez le formulaire et assignez les rôles appropriés

---

## 🔒 Réinitialisation de Mot de Passe

### Via Tinker

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'super.admin@sgrs-ceeac.org')->first();
$user->password = Hash::make('NouveauMotDePasse');
$user->save();
```

### Via l'Interface Web

1. Sur la page de connexion, cliquez sur **"Mot de passe oublié ?"**
2. Entrez votre adresse email
3. Suivez les instructions dans l'email de réinitialisation

---

## ⚠️ Sécurité

**IMPORTANT pour la production :**

1. **Changez les mots de passe par défaut** immédiatement après la première connexion
2. **Utilisez des mots de passe forts** :
   - Minimum 8 caractères
   - Majuscules et minuscules
   - Chiffres
   - Caractères spéciaux
3. **Activez la vérification d'email** en production
4. **Désactivez les comptes inactifs** régulièrement
5. **Ne partagez jamais** les identifiants par email non sécurisé

---

## 📝 Notes Techniques

- Tous les comptes créés par le seeder ont `email_verified_at` défini (emails vérifiés)
- Les mots de passe sont hashés avec bcrypt/argon2 (Laravel 11)
- Les comptes générés par la factory (10 utilisateurs) ont le rôle `staff` par défaut
- Le champ `is_active` contrôle l'accès au système

---

## 🔄 Commandes Utiles

```bash
# Créer tous les seeders
php artisan db:seed

# Créer uniquement les utilisateurs
php artisan db:seed --class=UserSeeder

# Réinitialiser la base de données et recréer tout
php artisan migrate:fresh --seed

# Voir les utilisateurs dans la base
php artisan tinker
>>> User::all(['name', 'email'])->toArray();
```

---

**Dernière mise à jour:** 21 Novembre 2025
