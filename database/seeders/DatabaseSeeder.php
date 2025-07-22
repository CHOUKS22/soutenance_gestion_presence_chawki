<?php

namespace Database\Seeders;

use App\Models\Statut_seance;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mockery\Matcher\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            FilliereSeeder::class,
            MatiereSeeder::class,
            AnneeAcademiqueSeeder::class,
            SemestreSeeder::class,
            ProfesseurSeeder::class,
            ClasseSeeder::class,
            SeanceSeeder::class,
            StatutSeanceSeeder::class,
            TypeSeanceSeeder::class,
        ]);
    }
}
