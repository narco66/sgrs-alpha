# Guide de Résolution de l'Erreur 403 sur /roles

## Étape 1 : Vérifier votre compte connecté

1. **Allez sur cette URL de debug :**
   ```
   http://127.0.0.1:2020/debug/my-roles
   ```
   
   Cette page vous montrera :
   - Votre email
   - Vos rôles actuels
   - Si vous avez le rôle `super-admin` ou `dsi`
   - Si vous pouvez accéder à la gestion des rôles

## Étape 2 : Si vous n'avez pas les bons rôles

### Option A : Utiliser un compte avec les bons rôles

1. **Déconnectez-vous** de l'application
2. **Connectez-vous** avec un des comptes suivants :

   **Super-Administrateur :**
   - Email : `super.admin@sgrs-ceeac.org`
   - Mot de passe : `Password@2025`

   **DSI :**
   - Email : `dsi.admin@sgrs-ceeac.org`
   - Mot de passe : `Password@2025`

### Option B : Assigner le rôle DSI à votre compte

Si vous êtes connecté en tant que **super-admin**, vous pouvez :

1. Allez dans **Utilisateurs** (`/users`)
2. Trouvez votre compte
3. Assignez-lui le rôle `dsi`

## Étape 3 : Corriger les rôles dans la base de données

Exécutez ces commandes dans le terminal :

```bash
# 1. Corriger les rôles automatiquement
php artisan sgrs:fix-dsi-roles

# 2. Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Vérifier les rôles d'un utilisateur
php artisan sgrs:check-user-roles dsi.admin@sgrs-ceeac.org
```

## Étape 4 : Réinitialiser complètement (si nécessaire)

Si rien ne fonctionne, réinitialisez les rôles et utilisateurs :

```bash
# 1. Réinitialiser les rôles et permissions
php artisan db:seed --class=RoleAndPermissionSeeder

# 2. Réinitialiser les utilisateurs
php artisan db:seed --class=UserSeeder

# 3. Vider les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Étape 5 : Se reconnecter

Après avoir corrigé les rôles :

1. **Déconnectez-vous** complètement
2. **Fermez le navigateur** (ou utilisez la navigation privée)
3. **Reconnectez-vous** avec un compte Super-Admin ou DSI

## Message d'erreur amélioré

Si vous recevez toujours une erreur 403, le message vous indiquera maintenant :
- Votre email
- Vos rôles actuels
- Les comptes à utiliser pour accéder à la page

## Vérification rapide

Pour vérifier rapidement tous les utilisateurs et leurs rôles :

```bash
php artisan sgrs:check-user-roles
```

## Comptes par défaut

| Email | Mot de passe | Rôles | Accès /roles |
|-------|-------------|-------|--------------|
| `super.admin@sgrs-ceeac.org` | `Password@2025` | super-admin | ✅ Oui |
| `dsi.admin@sgrs-ceeac.org` | `Password@2025` | admin, dsi | ✅ Oui |
| `sg.admin@sgrs-ceeac.org` | `Password@2025` | admin, sg | ❌ Non |
| `staff@sgrs-ceeac.org` | `Password@2025` | staff | ❌ Non |

## Notes importantes

- Le cache des permissions Spatie peut causer des problèmes. Toujours vider le cache après avoir modifié les rôles.
- Si vous modifiez les rôles d'un utilisateur, il doit se déconnecter et se reconnecter pour que les changements prennent effet.
- La route `/debug/my-roles` est temporaire et devrait être supprimée en production.

