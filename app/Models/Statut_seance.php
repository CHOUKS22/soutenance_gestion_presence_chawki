<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statut_seance extends Model
{
    protected $table = 'statuts_seances';

    protected $fillable = [
        'libelle',
        'description'
    ];
}
