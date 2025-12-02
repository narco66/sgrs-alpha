# Debug - Problème avec les boutons Voir et Modifier

## Problème
Les boutons "Voir" et "Modifier" ne s'affichent pas dans le tableau des rôles.

## Solution appliquée

### 1. Vérification directe des rôles
Au lieu d'utiliser `hasRole()` qui peut avoir des problèmes de cache, j'utilise maintenant une vérification directe avec `in_array()` sur les noms de rôles.

### 2. Chargement explicite des rôles
Les rôles de l'utilisateur sont maintenant chargés explicitement avec `$user->load('roles')` avant la vérification.

### 3. Code dans la vue
```php
@php
    $user = auth()->user();
    $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
    $isSystem = in_array($role->name, $systemRoles);
    
    // Charger explicitement les rôles de l'utilisateur
    if ($user) {
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $canEdit = in_array('super-admin', $userRoles);
        $canDelete = in_array('super-admin', $userRoles) && !$isSystem;
    } else {
        $canEdit = false;
        $canDelete = false;
    }
@endphp
```

## Vérification

1. **Vérifiez que vous êtes connecté en tant que super-admin :**
   - Allez sur `/debug/my-roles` pour voir vos rôles
   - Vous devriez voir `"has_super_admin": true`

2. **Videz le cache :**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

3. **Rechargez la page** des rôles

4. **Si les boutons n'apparaissent toujours pas :**
   - Vérifiez dans la console du navigateur s'il y a des erreurs JavaScript
   - Vérifiez que vous êtes bien connecté avec le compte `super.admin@sgrs-ceeac.org`
   - Déconnectez-vous et reconnectez-vous

## Test rapide

Ajoutez temporairement ce code dans la vue pour debug :

```php
<!-- DEBUG -->
<div class="alert alert-info">
    User: {{ auth()->user()->email ?? 'Non connecté' }}<br>
    Roles: {{ auth()->user()->roles->pluck('name')->join(', ') ?? 'Aucun' }}<br>
    Can Edit: {{ $canEdit ? 'Oui' : 'Non' }}
</div>
```

Cela vous permettra de voir exactement ce qui se passe.

