<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Administrateur',
                'description' => 'Accès complet au système'
            ],
            [
                'nom' => 'Professeur',
                'description' => 'Accès limité aux fonctionnalités de base'
            ],
            [
                'nom' => 'Coordinateur',
                'description' => 'Accès aux fonctionnalités de modération'
            ],
            [
                'nom' => 'Parent',
                'description' => 'Accès aux fonctionnalités de modération'
            ],
            [
                'nom' => 'Etudiant',
                'description' => 'Accès aux fonctionnalités de modération'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
