<?php

namespace Database\Seeders;

use App\Models\Annee_academique;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnneeAcademiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Annee_academique::create([
            'libelle' => 'Année académique 2023-2024',
            'date_debut' => '2023-09-01',
            'date_fin' => '2024-06-30',
        ]);

        Annee_academique::create([
            'libelle' => 'Année académique 2024-2025',
            'date_debut' => '2024-09-01',
            'date_fin' => '2025-06-30',
        ]);

        Annee_academique::create([
            'libelle' => 'Année académique 2025-2026',
            'date_debut' => '2025-09-01',
            'date_fin' => '2026-06-30',
        ]);
    }
}
