<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $table = 'etudiants';

    protected $fillable = [
        'user_id',
        'date_naissance',
        'lieu_naissance',
        'telephone'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Relation avec le modèle User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function anneesClasses()
    {
        return $this->belongsToMany(AnneeClasse::class, 'annee_classe_etudiant');
    }
    public function parents()
    {
        return $this->belongsToMany(Parent_model::class, 'etudiants_parents', 'etudiant_id', 'parent_id');
    }
    
}
