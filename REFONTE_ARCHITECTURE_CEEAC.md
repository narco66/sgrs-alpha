# Refonte de l'Architecture SGRS-CEEAC

## Résumé de la Refonte

Cette refonte majeure de l'application SGRS-CEEAC aligne l'architecture et la logique métier sur le modèle institutionnel réel de gestion des réunions statutaires de la CEEAC.

## Changements Principaux

### 1. Comité d'Organisation de la Réunion

**Améliorations apportées :**
- Distinction entre fonctionnaires CEEAC et fonctionnaires du pays hôte
- Ajout de champs pour historiser les interventions (joined_at, left_at)
- Gestion des responsabilités spécifiques par membre
- Support pour les services/départements d'origine

**Fichiers modifiés :**
- `app/Models/OrganizationCommittee.php`
- `app/Models/OrganizationCommitteeMember.php`
- `database/migrations/2025_01_25_000004_enhance_organization_committees_table.php`

### 2. Cahier des Charges entre la CEEAC et le Pays Hôte

**Nouveau module créé :**
- Modèle `TermsOfReference` pour gérer les cahiers des charges
- Gestion des versions avec historique
- Workflow de validation interne CEEAC
- Signature par la CEEAC et le pays hôte
- Génération de PDF
- Partage des responsabilités, charges financières et logistiques

**Fichiers créés :**
- `app/Models/TermsOfReference.php`
- `app/Http/Controllers/TermsOfReferenceController.php`
- `database/migrations/2025_01_25_000001_create_terms_of_reference_table.php`

### 3. Participation par Délégations (Refonte Majeure)

**Changement fondamental :**
- ❌ **SUPPRIMÉ** : Gestion des participants individuels comme unité principale
- ✅ **NOUVEAU** : Participation uniquement par délégations institutionnelles

**Structure métier :**
```
Réunion → Délégations → Membres de la délégation (facultatif)
```

**Types d'entités supportés :**
- États membres
- Organisations internationales
- Partenaires techniques
- Partenaires financiers
- Autres organismes invités

**Fichiers créés/modifiés :**
- `app/Models/Delegation.php` (refondu)
- `app/Models/DelegationMember.php` (nouveau)
- `app/Http/Controllers/DelegationController.php` (refondu)
- `app/Http/Controllers/DelegationMemberController.php` (nouveau)
- `database/migrations/2025_01_25_000002_refactor_delegations_table.php`
- `database/migrations/2025_01_25_000003_create_delegation_members_table.php`

### 4. Révision Complète de la Logique de Réunion

**Modifications dans MeetingController :**
- Utilisation de `delegations()` au lieu de `participants()`
- Relations dépréciées conservées pour compatibilité
- Notifications adaptées pour les délégations

**Modifications dans Meeting Model :**
- Relation principale : `delegations()` (hasMany)
- Relations dépréciées : `participants()`, `participantsUsers()` (marquées @deprecated)
- Nouvelle relation : `termsOfReference()` pour le cahier des charges

## Migrations à Exécuter

```bash
php artisan migrate
```

Les migrations suivantes seront exécutées dans l'ordre :
1. `2025_01_25_000001_create_terms_of_reference_table.php`
2. `2025_01_25_000002_refactor_delegations_table.php`
3. `2025_01_25_000003_create_delegation_members_table.php`
4. `2025_01_25_000004_enhance_organization_committees_table.php`
5. `2025_01_25_000005_deprecate_participants_tables.php`

## Routes Ajoutées

### Cahier des Charges
- `GET /meetings/{meeting}/terms-of-reference` - Afficher
- `GET /meetings/{meeting}/terms-of-reference/create` - Créer
- `POST /meetings/{meeting}/terms-of-reference` - Enregistrer
- `GET /meetings/{meeting}/terms-of-reference/{termsOfReference}/edit` - Éditer
- `PUT /meetings/{meeting}/terms-of-reference/{termsOfReference}` - Mettre à jour
- `POST /meetings/{meeting}/terms-of-reference/{termsOfReference}/validate` - Valider
- `POST /meetings/{meeting}/terms-of-reference/{termsOfReference}/sign` - Signer
- `GET /meetings/{meeting}/terms-of-reference/{termsOfReference}/pdf` - Export PDF
- `POST /meetings/{meeting}/terms-of-reference/{termsOfReference}/version` - Nouvelle version

### Membres de Délégation
- `GET /delegations/{delegation}/members` - Liste
- `GET /delegations/{delegation}/members/create` - Créer
- `POST /delegations/{delegation}/members` - Enregistrer
- `GET /delegations/{delegation}/members/{member}/edit` - Éditer
- `PUT /delegations/{delegation}/members/{member}` - Mettre à jour
- `DELETE /delegations/{delegation}/members/{member}` - Supprimer
- `PATCH /delegations/{delegation}/members/{member}/status` - Mettre à jour le statut

### Délégations
- `POST /delegations/{delegation}/confirm` - Confirmer la participation

## Compatibilité et Migration des Données

### Tables Participants (Dépréciées)

Les tables suivantes sont marquées comme obsolètes mais conservées pour migration progressive :
- `participants_reunions` - Colonne `is_deprecated` ajoutée
- `participants` - Conservée pour référence historique

**Note importante :** Les données existantes ne sont pas supprimées. Une migration manuelle des données vers le nouveau modèle de délégations est recommandée.

### Relations Dépréciées

Dans le code, les relations suivantes sont marquées `@deprecated` :
- `Meeting::participants()`
- `Meeting::participantsUsers()`
- `Delegation::users()`
- `Delegation::participants()`

Ces relations restent fonctionnelles pour la compatibilité mais ne doivent plus être utilisées dans le nouveau code.

## Prochaines Étapes

### À Faire

1. **Vues Blade** - Mettre à jour les vues pour afficher les délégations au lieu des participants
   - `resources/views/meetings/show.blade.php`
   - `resources/views/meetings/index.blade.php`
   - `resources/views/delegations/*.blade.php`
   - Créer les vues pour `terms-of-reference/*.blade.php`
   - Créer les vues pour `delegation-members/*.blade.php`

2. **Notifications** - Adapter les notifications pour les délégations
   - Créer `DelegationInvitationNotification`
   - Adapter `MeetingInvitationNotification` pour les délégations

3. **Policies** - Mettre à jour les policies pour les nouveaux modèles
   - `TermsOfReferencePolicy`
   - `DelegationMemberPolicy`

4. **Tests** - Créer des tests pour les nouveaux modèles et contrôleurs

5. **Migration des Données** - Script pour migrer les participants existants vers les délégations

## Conformité Institutionnelle

Cette refonte garantit que l'application respecte fidèlement :

✅ Le cycle institutionnel réel de la CEEAC
✅ La préparation par le Comité d'organisation
✅ La signature du cahier des charges avec le pays hôte
✅ La participation par délégations institutionnelles
✅ La gestion des ordres du jour, documents, PV
✅ Le suivi des engagements

## Support

Pour toute question sur cette refonte, consulter :
- Les modèles dans `app/Models/`
- Les contrôleurs dans `app/Http/Controllers/`
- Les migrations dans `database/migrations/`























