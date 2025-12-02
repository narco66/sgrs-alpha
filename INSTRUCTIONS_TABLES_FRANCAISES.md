# Instructions pour les Tables en Français

## Migration Créée

Une migration a été créée pour renommer toutes les tables en français : `2025_11_22_000000_rename_tables_to_french.php`

## Mapping des Tables

| Table Anglaise | Table Française |
|----------------|----------------|
| `users` | `utilisateurs` |
| `meetings` | `reunions` |
| `meeting_types` | `types_reunions` |
| `committees` | `comites` |
| `rooms` | `salles` |
| `documents` | `documents` |
| `document_types` | `types_documents` |
| `document_versions` | `versions_documents` |
| `document_validations` | `validations_documents` |
| `participants` | `participants` |
| `meeting_participants` | `participants_reunions` |
| `delegations` | `delegations` |
| `notifications` | `notifications` |
| `room_reservations` | `reservations_salles` |
| `status_histories` | `historiques_statuts` |
| `meeting_status_histories` | `historiques_statuts_reunions` |
| `organization_committees` | `comites_organisation` |
| `organization_committee_members` | `membres_comites_organisation` |
| `meeting_requests` | `demandes_reunions` |
| `participant_requests` | `demandes_participants` |
| `audit_logs` | `journaux_audit` |

**Note:** Les tables de Spatie Permission (`roles`, `permissions`, `model_has_roles`, etc.) restent en anglais car le package les utilise directement.

## Modèles Mis à Jour

Tous les modèles ont été mis à jour avec `protected $table = 'nom_francais';` :

- ✅ `User` → `utilisateurs`
- ✅ `Meeting` → `reunions`
- ✅ `MeetingType` → `types_reunions`
- ✅ `Committee` → `comites`
- ✅ `Room` → `salles`
- ✅ `Document` → `documents`
- ✅ `DocumentType` → `types_documents`
- ✅ `DocumentVersion` → `versions_documents`
- ✅ `DocumentValidation` → `validations_documents`
- ✅ `Participant` → `participants`
- ✅ `MeetingParticipant` → `participants_reunions`
- ✅ `Delegation` → `delegations`
- ✅ `AuditLog` → `journaux_audit`
- ✅ `OrganizationCommittee` → `comites_organisation`
- ✅ `OrganizationCommitteeMember` → `membres_comites_organisation`
- ✅ `MeetingRequest` → `demandes_reunions`
- ✅ `ParticipantRequest` → `demandes_participants`
- ✅ `RoomReservation` → `reservations_salles`
- ✅ `StatusHistory` → `historiques_statuts`
- ✅ `MeetingStatusHistory` → `historiques_statuts_reunions`

## Relations Mises à Jour

Les relations `belongsToMany` ont été mises à jour :
- ✅ `User::meetings()` → utilise `participants_reunions`
- ✅ `Meeting::participantsUsers()` → utilise `participants_reunions`

## Commandes à Exécuter

### Option 1 : Migration Fresh (RECOMMANDÉ pour développement)
```bash
# ⚠️ ATTENTION : Cela supprimera toutes les données existantes
php artisan migrate:fresh --seed
```

### Option 2 : Migration Progressive (pour production)
```bash
# 1. Exécuter la migration de renommage
php artisan migrate

# 2. Exécuter la migration de mise à jour des clés étrangères
php artisan migrate

# 3. Vérifier que tout fonctionne
php artisan migrate:status
```

## Vérifications

Après avoir exécuté les migrations :

1. **Vérifier les tables :**
   ```sql
   SHOW TABLES;
   ```
   Vous devriez voir les tables avec leurs noms français.

2. **Vérifier les relations :**
   ```bash
   php artisan tinker
   ```
   Puis tester :
   ```php
   $user = App\Models\User::first();
   $user->meetings; // Devrait fonctionner
   ```

3. **Vérifier les clés étrangères :**
   ```sql
   SELECT 
     TABLE_NAME,
     COLUMN_NAME,
     CONSTRAINT_NAME,
     REFERENCED_TABLE_NAME,
     REFERENCED_COLUMN_NAME
   FROM information_schema.KEY_COLUMN_USAGE
   WHERE TABLE_SCHEMA = DATABASE()
   AND REFERENCED_TABLE_NAME IS NOT NULL;
   ```

## Problèmes Potentiels

### Si les migrations échouent :
1. Vérifiez que toutes les tables existent
2. Vérifiez que les clés étrangères peuvent être supprimées
3. Videz le cache : `php artisan cache:clear`

### Si les relations ne fonctionnent pas :
1. Vérifiez que `protected $table` est bien défini dans tous les modèles
2. Vérifiez que les noms de tables dans les relations `belongsToMany` sont corrects
3. Videz le cache : `php artisan cache:clear`

## Rollback

Pour annuler les changements :
```bash
php artisan migrate:rollback --step=2
```

Cela restaurera les noms de tables en anglais.

