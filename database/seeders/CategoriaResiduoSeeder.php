<?php

namespace Database\Seeders;

use App\Models\CategoriaResiduo;
use Illuminate\Database\Seeder;

class CategoriaResiduoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre'           => 'Plástico',
                'descripcion'      => 'Botellas PET, envases, bolsas y empaques plásticos',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'plastic',
                'icono_sf'         => 'drop.fill',
                'color_hex'        => '#2196F3',
                'puntos_base'      => 30,
                'peso_promedio_kg' => 0.05,
                'co2_por_kg'       => 1.5,
                'agua_por_kg'      => 17.0,
                'instrucciones'    => 'Enjuaga el envase, aplástalo para reducir volumen y llévalo a un punto verde o centro de acopio. Separa por tipo: PET1 (botellas transparentes), HDPE2 (leche, detergente).',
            ],
            [
                'nombre'           => 'Vidrio',
                'descripcion'      => 'Botellas, frascos y envases de vidrio',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'glass',
                'icono_sf'         => 'wineglass',
                'color_hex'        => '#4CAF50',
                'puntos_base'      => 25,
                'peso_promedio_kg' => 0.30,
                'co2_por_kg'       => 0.3,
                'agua_por_kg'      => 1.0,
                'instrucciones'    => 'Enjuaga el envase y retira la tapa (va en otro contenedor). No mezcles con espejos o vidrio roto. Llévalo al centro de acopio más cercano.',
            ],
            [
                'nombre'           => 'Metal / Aluminio',
                'descripcion'      => 'Latas de refresco, conservas y envases de aluminio',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'metal',
                'icono_sf'         => 'cylinder.fill',
                'color_hex'        => '#9E9E9E',
                'puntos_base'      => 40,
                'peso_promedio_kg' => 0.015,
                'co2_por_kg'       => 9.0,
                'agua_por_kg'      => 0.0,
                'instrucciones'    => 'El aluminio es el material más valioso para reciclar: ahorra hasta 95% de energía vs producción nueva. Aplásta la lata y llévala al centro de acopio o reciclador cercano.',
            ],
            [
                'nombre'           => 'Cartón',
                'descripcion'      => 'Cajas, empaques y cartón corrugado',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'cardboard',
                'icono_sf'         => 'shippingbox.fill',
                'color_hex'        => '#FF9800',
                'puntos_base'      => 20,
                'peso_promedio_kg' => 0.50,
                'co2_por_kg'       => 0.9,
                'agua_por_kg'      => 7.0,
                'instrucciones'    => 'Dobla las cajas para reducir volumen. Asegúrate de que estén secas y sin restos de comida. Lleva el cartón limpio al centro de acopio o pepenador de tu zona.',
            ],
            [
                'nombre'           => 'Papel',
                'descripcion'      => 'Periódicos, revistas, papel de oficina e impresiones',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'paper',
                'icono_sf'         => 'doc.fill',
                'color_hex'        => '#FFEB3B',
                'puntos_base'      => 15,
                'peso_promedio_kg' => 0.10,
                'co2_por_kg'       => 1.0,
                'agua_por_kg'      => 10.0,
                'instrucciones'    => 'Agrupa el papel limpio y seco. No incluyas papel encerado, papel carbón o servilletas usadas. Llévalo al centro de acopio o entrégalo al cartonero de tu colonia.',
            ],
            [
                'nombre'           => 'Residuo General',
                'descripcion'      => 'Residuos no clasificables o de composición mixta',
                'tipo_destino'     => 'reciclaje',
                'ml_label'         => 'trash',
                'icono_sf'         => 'trash.fill',
                'color_hex'        => '#607D8B',
                'puntos_base'      => 10,
                'peso_promedio_kg' => 0.20,
                'co2_por_kg'       => 0.1,
                'agua_por_kg'      => 0.0,
                'instrucciones'    => 'Aunque este residuo no es fácilmente reciclable, clasificarlo correctamente evita contaminar otros materiales. Desecha en el contenedor de residuos no valorizables.',
            ],
            [
                'nombre'           => 'Orgánicos / Alimentos',
                'descripcion'      => 'Restos de comida, frutas, verduras y alimentos en buen estado',
                'tipo_destino'     => 'composta',
                'ml_label'         => 'food_organics',
                'icono_sf'         => 'leaf.fill',
                'color_hex'        => '#8BC34A',
                'puntos_base'      => 25,
                'peso_promedio_kg' => 0.20,
                'co2_por_kg'       => 0.5,
                'agua_por_kg'      => 0.0,
                'instrucciones'    => 'Si el alimento está en buen estado y cerrado, considera donarlo al banco de alimentos más cercano. Si son restos, lleva a un centro de composta municipal o inicia composta en casa.',
            ],
            [
                'nombre'           => 'Vegetación',
                'descripcion'      => 'Hojas, ramas, pasto y residuos de jardín',
                'tipo_destino'     => 'composta',
                'ml_label'         => 'vegetation',
                'icono_sf'         => 'tree.fill',
                'color_hex'        => '#33691E',
                'puntos_base'      => 20,
                'peso_promedio_kg' => 0.30,
                'co2_por_kg'       => 0.4,
                'agua_por_kg'      => 0.0,
                'instrucciones'    => 'Los residuos de jardín son ideales para composta. Lleva a los puntos de recepción de orgánicos en mercados o alcaldías. También puedes hacer composta casera en macetas.',
            ],
            [
                'nombre'           => 'Textil / Ropa',
                'descripcion'      => 'Ropa, telas, zapatos y accesorios en desuso',
                'tipo_destino'     => 'reutilizacion',
                'ml_label'         => 'textile',
                'icono_sf'         => 'tshirt.fill',
                'color_hex'        => '#9C27B0',
                'puntos_base'      => 35,
                'peso_promedio_kg' => 0.50,
                'co2_por_kg'       => 2.1,
                'agua_por_kg'      => 120.0,
                'instrucciones'    => 'Si la ropa está en buen estado, llévala a una tienda de segunda mano, intercámbiala en redes vecinales o dónala a albergues. Si está deteriorada, hay centros de reciclaje textil en CDMX.',
            ],
        ];

        foreach ($categorias as $categoria) {
            CategoriaResiduo::updateOrCreate(
                ['ml_label' => $categoria['ml_label']],
                $categoria
            );
        }
    }
}
