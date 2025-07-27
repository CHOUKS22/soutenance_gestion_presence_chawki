<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'etudiant_id',
        'seance_id',
        'created_by',
    ];

    public function justifications()
    {
        return $this->belongsToMany(
            Justification::class,
            'absence_justifie',
            'absence_id',
            'justification_id'
        );
    }
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id')->with('user');
    }
    public function seance()
    {
        return $this->belongsTo(Seance::class)->with(['classe', 'matiere']);
    }
}
