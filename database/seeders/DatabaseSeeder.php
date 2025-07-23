<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            StatutSeanceSeeder::class,
            TypeSeanceSeeder::class,
        ]);
    }
}
