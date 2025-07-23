<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'nom',
    ];

    /**
     * Relation avec le modèle AnneeAcademique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    /**
     * Relation avec le modèle Etudiant
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
}
