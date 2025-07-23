<?php

namespace Database\Seeders;

use App\Models\AnneeClasse;
use App\Models\Annee_academique;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Coordinateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnneeClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $annees = [
            'Année académique 2023-2024',
            'Année académique 2024-2025',
            'Année académique 2025-2026',
        ];

        $classe = Classe::first();
        $coord = Coordinateur::first();

        if ($classe && $coord) {
            foreach ($annees as $libelleaAnnee) {
                $annee = AnneeAcademique::where('libelle', $libelleaAnnee)->first();
                if ($annee) {
                    AnneeClasse::FirstOrCreate(
                        [
                            'annee_academique_id' => $annee->id,
                            'classe_id' => $classe->id,
                        ],
                        [
                            'coordinateur_id' => $coord->id,
                        ]
                    );
                }
            }
        }
    }
}
