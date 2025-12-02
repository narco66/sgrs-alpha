<?php

namespace App\Enums;

enum MeetingStatus: string
{
    case DRAFT        = 'brouillon';
    case PLANNED      = 'planifiee';
    case PREPARATION  = 'en_preparation';
    case ONGOING      = 'en_cours';
    case COMPLETED    = 'terminee';
    case CANCELLED    = 'annulee';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT       => 'Brouillon',
            self::PLANNED     => 'Planifiée',
            self::PREPARATION => 'En préparation',
            self::ONGOING     => 'En cours',
            self::COMPLETED   => 'Terminée',
            self::CANCELLED   => 'Annulée',
        };
    }
}
