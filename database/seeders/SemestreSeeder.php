<?php

namespace Database\Seeders;

use App\Models\Semestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Semestre::create([
            'annee_academique_id' => 1,
            'libelle' => 'Semestre 1',
            'date_debut' => '2023-09-01',
            'date_fin' => '2024-01-31',
        ]);

        Semestre::create([
            'annee_academique_id' => 1,
            'libelle' => 'Semestre 2',
            'date_debut' => '2024-02-01',
            'date_fin' => '2024-06-30',
        ]);

    }
}
