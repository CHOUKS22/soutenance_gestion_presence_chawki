<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $table = 'presences';

    protected $fillable = [
        'etudiant_id',
        'seance_id',
        'statuts_presence_id',
        'created_by'

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

     /**
     * Relation avec le modèle StatutPresence
     */
    public function statutPresence()
    {
        return $this->belongsTo(Statut_presence::class, 'statuts_presence_id');
    }
}
