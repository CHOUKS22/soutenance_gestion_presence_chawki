<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('nom', 'Administrateur')->first();
        $prof = Role::where('nom', 'Professeur')->first();
        $coord = Role::where('nom', 'Coordinateur')->first();
        $parent = Role::where('nom', 'Parent')->first();
        $etu = Role::where('nom', 'Etudiant')->first();

        if ($admin) {
            User::create([
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'), // On hash le mot de passe pour la securite
                'role_id' => $admin->id,
                'email_verified_at' => now(),
            ]);
        }

        // Creation d'un prof
        if ($prof) {
            User::create([
                'nom' => 'Kouame',
                'prenom' => 'Jean',
                'email' => 'professeur@example.com',
                'password' => Hash::make('professeur123'),
                'role_id' => $prof->id,
                'email_verified_at' => now(),
            ]);
        }

        // Un coordinateur,
        if ($coord) {
            User::create([
                'nom' => 'Traore',
                'prenom' => 'Marie',
                'email' => 'coordinateur@example.com',
                'password' => Hash::make('coordinateur123'),
                'role_id' => $coord->id,
                'email_verified_at' => now(),
            ]);
        }

        // Creation d'un parent
        if ($parent) {
            User::create([
                'nom' => 'Koffi',
                'prenom' => 'Paul',
                'email' => 'parent@example.com',
                'password' => Hash::make('parent123'),
                'role_id' => $parent->id,
                'email_verified_at' => now(),
            ]);
        }

        // Creation d'un etudiant
        if ($etu) {
            User::create([
                'nom' => 'Yao',
                'prenom' => 'Aya',
                'email' => 'etudiant@example.com',
                'password' => Hash::make('etudiant123'),
                'role_id' => $etu->id,
                'email_verified_at' => now(),
            ]);
        }
    }
}
