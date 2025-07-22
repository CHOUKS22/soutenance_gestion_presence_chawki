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
        // Récupérer les données existantes
        $anneeAcademique = AnneeAcademique::where('libelle', 'Année académique 2024-2025')->first();
        $classe = Classe::where('nom', 'Licence 1')->first();
        $coordinateur = Coordinateur::first();

        // Créer une association si les données existent
        if ($anneeAcademique && $classe && $coordinateur) {
            AnneeClasse::create([
                'annee_academique_id' => $anneeAcademique->id,
                'classe_id' => $classe->id,
                'coordinateur_id' => $coordinateur->id,
            ]);
        }

        // Créer d'autres associations si possible
        $anneeAcademique2 = AnneeAcademique::where('libelle', 'Année académique 2023-2024')->first();
        if ($anneeAcademique2 && $classe && $coordinateur) {
            // Vérifier que l'association n'existe pas déjà
            $exists = AnneeClasse::where('annee_academique_id', $anneeAcademique2->id)
                ->where('classe_id', $classe->id)
                ->exists();

            if (!$exists) {
                AnneeClasse::create([
                    'annee_academique_id' => $anneeAcademique2->id,
                    'classe_id' => $classe->id,
                    'coordinateur_id' => $coordinateur->id,
                ]);
            }
        }
    }
}
