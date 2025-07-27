<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $table = 'matieres';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
