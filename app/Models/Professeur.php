<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    
    protected $fillable = [
        'user_id',
        'filliere_id',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le modèle Filiere
    public function filliere()
    {
        return $this->belongsTo(Filliere::class); // Attention : assure-toi du bon nom de classe ici
    }
}
