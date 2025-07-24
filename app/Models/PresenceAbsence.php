<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenceAbsence extends Model
{
    protected $table = 'presences_absences';

    protected $fillable = [
        'etudiant_id',
        'seance_id',
        'statuts_presence_id'
    ];

    /**
     * Relation avec le modèle Seance
     */
    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    /**
     * Relation avec le modèle Etudiant
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }


}
