# Solution pour l'erreur 403 sur la page Rôles et Permissions

## Problème
Vous recevez une erreur **403 Forbidden** lorsque vous essayez d'accéder à `/roles`.

## Causes possibles
1. L'utilisateur connecté n'a pas le rôle `dsi` ou `super-admin`
2. Le cache des rôles/permissions n'est pas à jour
3. Les rôles n'ont pas été correctement assignés lors du seeding

## Solutions

### Solution 1 : Vérifier votre utilisateur et corriger les rôles

1. **Vérifiez quel utilisateur est connecté :**
   - Regardez l'email affiché dans le menu (en haut à droite)
   - Notez l'email de votre compte

2. **Exécutez la commande pour corriger les rôles :**
   ```bash
   php artisan sgrs:fix-dsi-roles
   ```
   Cette commande réassigne automatiquement les rôles `dsi` et `super-admin` aux comptes par défaut.

3. **Vérifiez les rôles d'un utilisateur spécifique :**
   ```bash
   php artisan sgrs:check-user-roles dsi.admin@sgrs-ceeac.org
   ```
   ou
   ```bash
   php artisan sgrs:check-user-roles super.admin@sgrs-ceeac.org
   ```

4. **Videz tous les caches :**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

5. **Déconnectez-vous et reconnectez-vous** avec un compte ayant le bon rôle :
   - **Super-Admin :** `super.admin@sgrs-ceeac.org` / `Password@2025`
   - **DSI :** `dsi.admin@sgrs-ceeac.org` / `Password@2025`

### Solution 2 : Réinitialiser complètement les rôles et permissions

Si la solution 1 ne fonctionne pas :

1. **Réexécutez le seeder des rôles et permissions :**
   ```bash
   php artisan db:seed --class=RoleAndPermissionSeeder
   ```

2. **Réexécutez le seeder des utilisateurs :**
   ```bash
   php artisan db:seed --class=UserSeeder
   ```

3. **Videz tous les caches :**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Déconnectez-vous et reconnectez-vous**

### Solution 3 : Assigner manuellement le rôle DSI

Si vous êtes connecté avec un autre compte et voulez lui donner le rôle DSI :

1. **Connectez-vous en tant que super-admin :**
   - Email : `super.admin@sgrs-ceeac.org`
   - Mot de passe : `Password@2025`

2. **Allez dans la gestion des utilisateurs** (`/users`)

3. **Trouvez votre utilisateur** et assignez-lui le rôle `dsi`

### Message d'erreur amélioré

Le contrôleur affiche maintenant un message d'erreur plus détaillé qui indique :
- Que seuls le Super-Administrateur et le DSI peuvent accéder
- Quels rôles votre utilisateur actuel possède

Si vous voyez ce message, cela signifie que votre utilisateur n'a pas les rôles requis.

## Comptes par défaut

| Email | Mot de passe | Rôles |
|-------|-------------|-------|
| `super.admin@sgrs-ceeac.org` | `Password@2025` | super-admin |
| `dsi.admin@sgrs-ceeac.org` | `Password@2025` | admin, dsi |
| `sg.admin@sgrs-ceeac.org` | `Password@2025` | admin, sg |
| `staff@sgrs-ceeac.org` | `Password@2025` | staff |

## Vérification rapide

Pour vérifier rapidement si vous avez le bon rôle, exécutez :
```bash
php artisan sgrs:check-user-roles
```

Cela affichera tous les utilisateurs et leurs rôles.

