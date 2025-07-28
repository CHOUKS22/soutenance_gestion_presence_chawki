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
        'annee_classe_id',
        'semestre_id',
        'date_reportee',
        'heure_debut_report',
        'heure_fin_report',
        'commentaire_report',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_reportee' => 'date',
        'heure_debut_report' => 'datetime:H:i',
        'heure_fin_report' => 'datetime:H:i',
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

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function anneeClasse()
    {
        return $this->belongsTo(AnneeClasse::class);
    }
}
