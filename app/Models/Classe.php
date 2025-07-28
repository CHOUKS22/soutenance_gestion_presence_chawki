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
        return $this->belongsToMany(Etudiant::class, 'annee_classe_etudiant')
            ->withTimestamps();
    }

    public function anneesClasses()
    {
        return $this->hasMany(AnneeClasse::class);
    }
    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
    public function annees()
    {
        return $this->hasMany(AnneeClasse::class, 'classe_id');
    }
    public function anneeClasseEtudiants()
    {
        return $this->hasMany(AnneeClasseEtudiant::class, 'classe_id');
    }
}
