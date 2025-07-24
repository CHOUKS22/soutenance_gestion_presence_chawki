<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statut_presence extends Model
{
    protected $table = 'statuts_presences';

    protected $fillable = [
        'libelle',
        'description'
    ];

    /**
     * Relation avec le modèle Presence
     */
    public function presences()
    {
        return $this->hasMany(Presence::class, 'statuts_presence_id');
    }
}
