<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role_id',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec le modèle Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function coordinateur()
    {
        return $this->hasOne(Coordinateur::class);
    }
    public function professeur()
    {
        return $this->hasOne(Professeur::class);
    }
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }
    public function parent()
{
    return $this->hasOne(Parent_model::class); // ou App\Models\Parent si pas importé
}

}
