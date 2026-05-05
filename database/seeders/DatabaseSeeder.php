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
            UserSeeder::class,           // 6 usuarios con hábitos variados + mascotas
            CategoriaResiduoSeeder::class,
            CentroDestinoSeeder::class,
            BadgeSeeder::class,
            RetoComunidadSeeder::class,
            EscaneoSeeder::class,        // historial de escaneos por usuario
        ]);
    }
}
