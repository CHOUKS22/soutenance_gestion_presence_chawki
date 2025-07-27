<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeClasse extends Model
{
    protected $table = 'annees_classes';

    protected $fillable = [
        'annee_academique_id',
        'classe_id',
        'coordinateur_id',
        'date_debut',
        'date_fin'
    ];

    // Conversion des dates pour eviter la conversion manuelle
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

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class, 'coordinateur_id');
    }
    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'annee_classe_etudiant')
            ->withTimestamps();
    }
}
