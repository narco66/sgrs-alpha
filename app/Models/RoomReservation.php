<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class RoomReservation extends Model
{
    use LogsActivity;
    /**
     * Nom de la table en français
     */
    protected $table = 'reservations_salles';
}
