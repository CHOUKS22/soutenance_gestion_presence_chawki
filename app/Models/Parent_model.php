<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parent_model extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'telephone',
        'type_relation'
    ];

    /**
     * Relation avec le modèle User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les étudiants
     */
    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'etudiants_parents', 'parent_id', 'etudiant_id');
    }
}
