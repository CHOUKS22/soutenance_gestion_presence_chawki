<?php

namespace Database\Seeders;

use App\Models\Filliere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fillieres = [
            'Développement Web',
            'Création Digitale',
            'Communication Digitale'
        ];

        foreach ($fillieres as $filliere) {
            Filliere::firstOrCreate([
                'nom' => $filliere
            ]);
        }
    }
}
