<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            EstatusSeeder::class,
            UserSeeder::class,
            CategoriaResiduoSeeder::class,
            CentroDestinoSeeder::class,
            BadgeSeeder::class,
            RetoComunidadSeeder::class,
        ]);
    }
}
