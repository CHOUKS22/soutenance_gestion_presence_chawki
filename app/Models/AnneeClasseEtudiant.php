<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeClasseEtudiant extends Model
{
    protected $table = 'annee_classe_etudiant';

    protected $fillable = [
        'annee_classe_id',
        'etudiant_id'
    ];

    /**
     * Relation avec le modèle AnneeClasse
     */
    public function anneeClasse()
    {
        return $this->belongsTo(AnneeClasse::class);
    }

    /**
     * Relation avec le modèle Etudiant
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
