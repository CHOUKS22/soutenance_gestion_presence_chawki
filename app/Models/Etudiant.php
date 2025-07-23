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
}
