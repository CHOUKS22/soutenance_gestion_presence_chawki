<?php

namespace Database\Seeders;

use App\Models\Statut_seance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatutSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statut_seance::create([
            'libelle' => 'Planifiée',
            'description' => 'Séance planifiée mais pas encore commencée',
        ]);

        Statut_seance::create([
            'libelle' => 'En cours',
            'description' => 'Séance en cours de déroulement',
        ]);

        Statut_seance::create([
            'libelle' => 'Terminée',
            'description' => 'Séance terminée avec succès',
        ]);

        Statut_seance::create([
            'libelle' => 'Annulée',
            'description' => 'Séance annulée pour une raison quelconque',
        ]);

        Statut_seance::create([
            'libelle' => 'Reportée',
            'description' => 'Séance reportée à une date ultérieure',
        ]);
    }
}
