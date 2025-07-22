<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classe::create([
            'nom' => 'B2 dev',
        ]);
        Classe::create([
            'nom' => 'B2 Crea',
        ]);
        Classe::create([
            'nom' => 'B2 comm',
        ]);
    }
}
