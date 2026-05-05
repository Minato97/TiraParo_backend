<?php

namespace Database\Seeders;

use App\Models\RetoComunidad;
use Illuminate\Database\Seeder;

class RetoComunidadSeeder extends Seeder
{
    public function run(): void
    {
        $retos = [
            [
                'titulo'       => 'Mayo Verde CDMX',
                'descripcion'  => 'Entre todas las colonias de CDMX, lleguemos a 500 kg de plástico reciclado en mayo.',
                'meta_kg'      => 500.0,
                'actual_kg'    => 0.0,
                'fecha_inicio' => '2026-05-01',
                'fecha_fin'    => '2026-05-31',
                'tipo_material' => 'plastic',
                'alcaldia'     => null,
                'activo'       => true,
            ],
            [
                'titulo'       => 'Reto Cartón – Cuauhtémoc',
                'descripcion'  => 'La Alcaldía Cuauhtémoc se une para reciclar 200 kg de cartón esta semana.',
                'meta_kg'      => 200.0,
                'actual_kg'    => 0.0,
                'fecha_inicio' => '2026-05-04',
                'fecha_fin'    => '2026-05-10',
                'tipo_material' => 'cardboard',
                'alcaldia'     => 'Cuauhtémoc',
                'activo'       => true,
            ],
            [
                'titulo'       => 'Orgánicos a la Composta',
                'descripcion'  => 'Toda CDMX: 300 kg de orgánicos a composta en mayo.',
                'meta_kg'      => 300.0,
                'actual_kg'    => 0.0,
                'fecha_inicio' => '2026-05-01',
                'fecha_fin'    => '2026-05-31',
                'tipo_material' => 'food_organics',
                'alcaldia'     => null,
                'activo'       => true,
            ],
        ];

        foreach ($retos as $reto) {
            RetoComunidad::updateOrCreate(
                ['titulo' => $reto['titulo']],
                $reto
            );
        }
    }
}
