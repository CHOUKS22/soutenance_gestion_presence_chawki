<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    protected $fillable = [
        'motif',
        'document',
        'saisie_par',
    ];

    public function absences()
    {
        return $this->belongsToMany(
            Absence::class,
            'absence_justifie',
            'justification_id',
            'absence_id'
        );
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'saisie_par');
    }
}
