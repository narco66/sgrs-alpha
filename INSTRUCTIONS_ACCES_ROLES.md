# Instructions d'Accès à la Gestion des Rôles et Permissions

## Accès au Menu

Le menu **"Rôles et Permissions"** est visible uniquement pour :
- **Super-Administrateur** (super-admin)
- **DSI** (dsi)

## Vérification de votre Rôle

Pour vérifier si vous avez accès :

1. **Connectez-vous** avec un compte ayant le rôle `super-admin` ou `dsi`
2. Le menu devrait apparaître dans la section **Administration** du sidebar

## Comptes par Défaut

### Super-Administrateur
- **Email :** `super.admin@sgrs-ceeac.org`
- **Mot de passe :** `Password@2025`
- **Rôle :** super-admin

### DSI
- **Email :** `dsi.admin@sgrs-ceeac.org`
- **Mot de passe :** `Password@2025`
- **Rôles :** admin, dsi

## Si le Menu N'Apparaît Pas

1. **Vérifiez votre rôle :**
   - Connectez-vous avec un compte super-admin ou dsi
   - Vérifiez dans la base de données que votre utilisateur a bien le rôle

2. **Videz le cache :**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Réinitialisez les permissions :**
   ```bash
   php artisan db:seed --class=RoleAndPermissionSeeder
   ```

4. **Vérifiez que votre utilisateur a le bon rôle :**
   - Allez dans la gestion des utilisateurs
   - Vérifiez que votre compte a bien le rôle `dsi` ou `super-admin`

## Permissions du DSI

Le rôle DSI a les permissions suivantes pour la gestion des rôles :
- ✅ `roles.view` - Voir les rôles
- ✅ `roles.create` - Créer des rôles
- ✅ `roles.update` - Modifier les rôles (sauf rôles système)
- ✅ `roles.delete` - Supprimer les rôles (sauf rôles système)
- ✅ `roles.manage` - Gestion complète

## Restrictions

- **Rôles système protégés :** Les rôles `super-admin`, `admin`, `sg`, `dsi`, `staff` ne peuvent pas être supprimés
- **Modification des rôles système :** Seul le super-admin peut modifier les rôles système
- **Suppression :** Seul le super-admin peut supprimer des rôles (et seulement les rôles personnalisés)

