<?php

namespace Database\Seeders;

use App\Models\Type_seance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type_seance::create([
            'nom' => 'Présentiel',
            'description' => 'Séance de cours en présentiel',
        ]);

        Type_seance::create([
            'nom' => 'E-Learning',
            'description' => 'Séance de cours en ligne',
        ]);

        Type_seance::create([
            'nom' => 'Workshop',
            'description' => 'Séance d\'atelier pratique',
        ]);

        Type_seance::create([
            'nom' => 'Examen',
            'description' => 'Séance d\'évaluation et examen',
        ]);
    }
}
