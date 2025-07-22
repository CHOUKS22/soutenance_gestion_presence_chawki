<?php

namespace Database\Seeders;

use App\Models\StatutPresence;
use Illuminate\Database\Seeder;

class StatutPresenceSeeder extends Seeder
{
    public function run()
    {
        $statuts = [
            [
                'libelle' => 'Présent',
                'description' => 'L\'étudiant est présent à la séance'
            ],
            [
                'libelle' => 'Absent',
                'description' => 'L\'étudiant est absent de la séance'
            ],
            [
                'libelle' => 'En retard',
                'description' => 'L\'étudiant est arrivé en retard'
            ],
            [
                'libelle' => 'Absent justifié',
                'description' => 'L\'étudiant est absent avec justification'
            ]
        ];

        foreach ($statuts as $statut) {
            StatutPresence::firstOrCreate(
                ['libelle' => $statut['libelle']],
                $statut
            );
        }
    }
}
