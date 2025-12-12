<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Service centralisé de journalisation d'audit.
 *
 * Utilisé pour consigner les actions importantes :
 * - CRUD sur les modèles critiques (via LogsActivity)
 * - Authentification (login, logout, échecs, reset)
 * - Téléchargements, validations, changements de statut, etc.
 * - Actions publiques / anonymes (user_id nul mais IP / User-Agent présents)
 */
class AuditLogger
{
    /**
     * Journaliser un événement d'audit.
     *
     * @param  string               $event
     * @param  \Illuminate\Database\Eloquent\Model|null $target
     * @param  array<string,mixed>|null $old
     * @param  array<string,mixed>|null $new
     * @param  array<string,mixed>      $meta
     * @return void
     */
    public static function log(
        string $event,
        ?Model $target = null,
        ?array $old = null,
        ?array $new = null,
        array $meta = []
    ): void {
        try {
            AuditLog::create([
                'event'          => $event,
                'auditable_type' => $target ? $target::class : null,
                'auditable_id'   => $target?->getKey(),
                'user_id'        => Auth::id(),
                'ip_address'     => Request::ip(),
                'user_agent'     => Request::header('User-Agent'),
                'old_values'     => $old,
                'new_values'     => $new,
                'meta'           => $meta,
            ]);
        } catch (\Throwable $e) {
            // En production, on évite de faire échouer la requête pour un problème d'audit.
            // Un log technique peut être ajouté ici si nécessaire.
        }
    }
}





