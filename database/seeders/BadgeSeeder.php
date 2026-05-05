<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // ── Primer escaneo ──────────────────────────────────────────
            [
                'nombre'              => 'Primer Tiro',
                'descripcion'         => 'Realizaste tu primer escaneo. ¡El comienzo de algo grande!',
                'icono_sf'            => 'star.fill',
                'color_hex'           => '#FFD700',
                'criterio_tipo'       => 'primer_escaneo',
                'criterio_valor'      => 1,
                'categoria_ml_label'  => null,
                'rareza'              => 'comun',
            ],

            // ── Escaneos totales ────────────────────────────────────────
            [
                'nombre'              => 'Reciclador en Ciernes',
                'descripcion'         => '10 residuos escaneados. ¡El hábito está naciendo!',
                'icono_sf'            => 'leaf.fill',
                'color_hex'           => '#4CAF50',
                'criterio_tipo'       => 'escaneos_total',
                'criterio_valor'      => 10,
                'categoria_ml_label'  => null,
                'rareza'              => 'comun',
            ],
            [
                'nombre'              => 'Guardián Verde',
                'descripcion'         => '50 residuos escaneados. ¡Eres un guardián del planeta!',
                'icono_sf'            => 'shield.fill',
                'color_hex'           => '#2196F3',
                'criterio_tipo'       => 'escaneos_total',
                'criterio_valor'      => 50,
                'categoria_ml_label'  => null,
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Héroe del Planeta',
                'descripcion'         => '200 residuos escaneados. ¡Eres una leyenda verde!',
                'icono_sf'            => 'flame.fill',
                'color_hex'           => '#FF5722',
                'criterio_tipo'       => 'escaneos_total',
                'criterio_valor'      => 200,
                'categoria_ml_label'  => null,
                'rareza'              => 'legendario',
            ],

            // ── Por categoría ────────────────────────────────────────────
            [
                'nombre'              => 'Rey del PET',
                'descripcion'         => '20 plásticos reciclados. ¡Eres el rey del PET!',
                'icono_sf'            => 'drop.fill',
                'color_hex'           => '#2196F3',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 20,
                'categoria_ml_label'  => 'plastic',
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Guardián del Vidrio',
                'descripcion'         => '15 vidrios reciclados. El vidrio brilla en tus manos.',
                'icono_sf'            => 'wineglass',
                'color_hex'           => '#8BC34A',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 15,
                'categoria_ml_label'  => 'glass',
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Héroe del Cartón',
                'descripcion'         => '10 cartones reciclados. ¡Los árboles te lo agradecen!',
                'icono_sf'            => 'shippingbox.fill',
                'color_hex'           => '#FF9800',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 10,
                'categoria_ml_label'  => 'cardboard',
                'rareza'              => 'comun',
            ],
            [
                'nombre'              => 'Alquimista del Metal',
                'descripcion'         => '10 metales reciclados. El aluminio es tuyo.',
                'icono_sf'            => 'cylinder.fill',
                'color_hex'           => '#9E9E9E',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 10,
                'categoria_ml_label'  => 'metal',
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Maestro de la Composta',
                'descripcion'         => '10 orgánicos llevados a composta. ¡La tierra te lo agradece!',
                'icono_sf'            => 'leaf.circle.fill',
                'color_hex'           => '#558B2F',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 10,
                'categoria_ml_label'  => 'food_organics',
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Modista Verde',
                'descripcion'         => '5 textiles reutilizados. La moda circular es real.',
                'icono_sf'            => 'tshirt.fill',
                'color_hex'           => '#9C27B0',
                'criterio_tipo'       => 'categoria_count',
                'criterio_valor'      => 5,
                'categoria_ml_label'  => 'textile',
                'rareza'              => 'epico',
            ],

            // ── Racha de días ────────────────────────────────────────────
            [
                'nombre'              => 'Semana Perfecta',
                'descripcion'         => '7 días seguidos reciclando. ¡Imparable!',
                'icono_sf'            => 'calendar.badge.checkmark',
                'color_hex'           => '#E91E63',
                'criterio_tipo'       => 'racha_dias',
                'criterio_valor'      => 7,
                'categoria_ml_label'  => null,
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Mes de Impacto',
                'descripcion'         => '30 días consecutivos reciclando. ¡Eres una máquina verde!',
                'icono_sf'            => 'bolt.fill',
                'color_hex'           => '#FF1744',
                'criterio_tipo'       => 'racha_dias',
                'criterio_valor'      => 30,
                'categoria_ml_label'  => null,
                'rareza'              => 'legendario',
            ],

            // ── Impacto de CO2 ────────────────────────────────────────────
            [
                'nombre'              => 'Capturador de Carbono',
                'descripcion'         => 'Evitaste 5 kg de CO₂. ¡El planeta respira mejor!',
                'icono_sf'            => 'wind',
                'color_hex'           => '#00BCD4',
                'criterio_tipo'       => 'co2_total_kg',
                'criterio_valor'      => 5,
                'categoria_ml_label'  => null,
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Sembrador de Futuro',
                'descripcion'         => 'Evitaste 50 kg de CO₂. Equivale a plantar 2 árboles.',
                'icono_sf'            => 'tree.fill',
                'color_hex'           => '#1B5E20',
                'criterio_tipo'       => 'co2_total_kg',
                'criterio_valor'      => 50,
                'categoria_ml_label'  => null,
                'rareza'              => 'epico',
            ],

            // ── Puntos ────────────────────────────────────────────────────
            [
                'nombre'              => 'Reciclador Plata',
                'descripcion'         => '500 puntos acumulados. ¡Nivel Guardián desbloqueado!',
                'icono_sf'            => 'medal.fill',
                'color_hex'           => '#B0BEC5',
                'criterio_tipo'       => 'puntos_total',
                'criterio_valor'      => 500,
                'categoria_ml_label'  => null,
                'rareza'              => 'raro',
            ],
            [
                'nombre'              => 'Reciclador Oro',
                'descripcion'         => '2000 puntos. ¡Héroe del Planeta confirmado!',
                'icono_sf'            => 'trophy.fill',
                'color_hex'           => '#FFD700',
                'criterio_tipo'       => 'puntos_total',
                'criterio_valor'      => 2000,
                'categoria_ml_label'  => null,
                'rareza'              => 'legendario',
            ],

            // ── Validaciones ─────────────────────────────────────────────
            [
                'nombre'              => 'Verificador',
                'descripcion'         => '5 escaneos validados con evidencia. ¡Comprometido de verdad!',
                'icono_sf'            => 'checkmark.seal.fill',
                'color_hex'           => '#00E676',
                'criterio_tipo'       => 'validaciones_total',
                'criterio_valor'      => 5,
                'categoria_ml_label'  => null,
                'rareza'              => 'comun',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['nombre' => $badge['nombre']],
                $badge
            );
        }
    }
}
