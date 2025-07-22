<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les IDs des rôles
        $adminRole = Role::where('nom', 'Administrateur')->first();
        $professeurRole = Role::where('nom', 'Professeur')->first();
        $coordinateurRole = Role::where('nom', 'Coordinateur')->first();
        $parentRole = Role::where('nom', 'Parent')->first();
        $etudiantRole = Role::where('nom', 'Etudiant')->first();

        // Créer un utilisateur Administrateur
        if ($adminRole) {
            User::create([
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]);
        }

        // Créer un utilisateur Professeur
        if ($professeurRole) {
            User::create([
                'nom' => 'Kouame',
                'prenom' => 'Jean',
                'email' => 'professeur@example.com',
                'password' => Hash::make('professeur123'),
                'role_id' => $professeurRole->id,
                'email_verified_at' => now(),
            ]);
        }

        // Créer un utilisateur Coordinateur
        if ($coordinateurRole) {
            User::create([
                'nom' => 'Traore',
                'prenom' => 'Marie',
                'email' => 'coordinateur@example.com',
                'password' => Hash::make('coordinateur123'),
                'role_id' => $coordinateurRole->id,
                'email_verified_at' => now(),
            ]);
        }

        // Créer un utilisateur Parent
        if ($parentRole) {
            User::create([
                'nom' => 'Koffi',
                'prenom' => 'Paul',
                'email' => 'parent@example.com',
                'password' => Hash::make('parent123'),
                'role_id' => $parentRole->id,
                'email_verified_at' => now(),
            ]);
        }

        // Créer un utilisateur Etudiant
        if ($etudiantRole) {
            User::create([
                'nom' => 'Yao',
                'prenom' => 'Aya',
                'email' => 'etudiant@example.com',
                'password' => Hash::make('etudiant123'),
                'role_id' => $etudiantRole->id,
                'email_verified_at' => now(),
            ]);
        }
    }
}
