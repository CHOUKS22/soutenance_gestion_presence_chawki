<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $table = 'seances';

    protected $fillable = [
        'date',
        'professeur_id',
        'matiere_id',
        'date_debut',
        'date_fin',
        'type_seance_id',
        'statut_seance_id',
        'annee_academique_id',
        'classe_id',
        'semestre_id',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function typeSeance()
    {
        return $this->belongsTo(Type_seance::class);
    }

    public function statutSeance()
    {
        return $this->belongsTo(Statut_seance::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
