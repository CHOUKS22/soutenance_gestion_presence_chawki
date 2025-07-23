<?php

namespace Database\Seeders;

use App\Models\Professeur;
use App\Models\Filliere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupere filliere existante
        $filliere = Filliere::where('nom', 'Développement Web')->first();

        if ($filliere) {
            Professeur::create([
                'user_id' => 2,
                'filliere_id' => $filliere->id,
            ]);
        }
    }
}
