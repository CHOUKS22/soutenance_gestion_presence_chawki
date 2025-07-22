<?php

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matiere::create([
            'nom' => 'flutter',
            'description' => 'Cours de flutters(applications mobiles)',
        ]);
        Matiere::create([
            'nom' => 'laravel',
            'description' => 'Cours de laravel(Back-end)',
        ]);
        Matiere::create([
            'nom' => 'php',
            'description' => 'Cours de php(Back-end)',
        ]);
        Matiere::create([
            'nom' => 'javascript',
            'description' => 'Cours de javascript(Front-end)',
        ]);
        Matiere::create([
            'nom' => 'html',
            'description' => 'Cours de html(Front-end)',
        ]);
        Matiere::create([
            'nom' => 'css',
            'description' => 'Cours de css(Front-end)',
        ]);
    }
}
