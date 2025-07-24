<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    protected $table = 'semestres';

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'annee_academique_id'
    ];

    // Conversion des dates pour éviter la conversion manuelle
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation avec le modèle AnneeAcademique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }
}
