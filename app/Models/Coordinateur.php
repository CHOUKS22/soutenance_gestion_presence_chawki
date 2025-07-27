<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinateur extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'user_id'
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
        return $this->hasMany(AnneeClasse::class);
    }
}
