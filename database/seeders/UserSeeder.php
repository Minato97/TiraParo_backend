<?php

namespace Database\Seeders;

use App\Models\MascotaUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [

            // ── 1. Admin / superusuario ────────────────────────────────────────
            [
                'user' => [
                    'nombres'          => 'Admin',
                    'apellido_paterno' => 'TiraParo',
                    'apellido_materno' => null,
                    'email'            => 'admin@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Centro Histórico',
                    'alcaldia'         => 'Cuauhtémoc',
                    'roles_id'         => 1,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 3200,
                    'puntos_semana'    => 180,
                    'nivel'            => 4,
                    'racha_dias'       => 21,
                    'co2_evitado_kg'   => 48.5,
                    'agua_ahorrada_litros' => 2400.0,
                    'arboles_equivalentes' => 2.23,
                    // Omnívoro, cocina diario, muchas bebidas en envase, supermercado
                    // → genera: orgánico, plástico, vidrio, cartón
                    'habitos_perfil'   => [
                        'dieta'             => 'omnivoro',
                        'frecuencia_cocina' => 'diario',
                        'bebidas_envase'    => 'mucho',
                        'habito_compras'    => 'supermercado',
                        'residuos_ropa'     => 'ocasional',
                        'papel_carton'      => 'regular',
                    ],
                ],
                'mascota' => [
                    'nombre'         => 'Árbol',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 95,
                    'escaneos_semana'=> 12,
                    'racha_semanas'  => 8,
                    'mensaje_actual' => '¡Estoy floreciendo gracias a ti! 🌳',
                ],
            ],

            // ── 2. Usuario simple (sin hábitos) ───────────────────────────────
            [
                'user' => [
                    'nombres'          => 'Carlos',
                    'apellido_paterno' => 'Mendoza',
                    'apellido_materno' => 'Ríos',
                    'email'            => 'carlos@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Del Valle',
                    'alcaldia'         => 'Benito Juárez',
                    'roles_id'         => 2,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 85,
                    'puntos_semana'    => 30,
                    'nivel'            => 1,
                    'racha_dias'       => 3,
                    'co2_evitado_kg'   => 1.2,
                    'agua_ahorrada_litros' => 60.0,
                    'arboles_equivalentes' => 0.06,
                    // Sin perfil de hábitos — para probar el flujo sin plan
                    'habitos_perfil'   => null,
                ],
                'mascota' => [
                    'nombre'         => 'Semillita',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 65,
                    'escaneos_semana'=> 3,
                    'racha_semanas'  => 1,
                    'mensaje_actual' => 'Voy bien, sigue reciclando 🌱',
                ],
            ],

            // ── 3. Ana — vegana, compra en mercado ────────────────────────────
            [
                'user' => [
                    'nombres'          => 'Ana',
                    'apellido_paterno' => 'López',
                    'apellido_materno' => 'Torres',
                    'email'            => 'ana@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Polanco',
                    'alcaldia'         => 'Miguel Hidalgo',
                    'roles_id'         => 2,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 890,
                    'puntos_semana'    => 95,
                    'nivel'            => 3,
                    'racha_dias'       => 14,
                    'co2_evitado_kg'   => 18.5,
                    'agua_ahorrada_litros' => 925.0,
                    'arboles_equivalentes' => 0.85,
                    // Vegana + mercado + reutiliza ropa
                    // → genera principalmente: orgánico, cartón (compras a granel)
                    'habitos_perfil'   => [
                        'dieta'             => 'vegano',
                        'frecuencia_cocina' => 'diario',
                        'bebidas_envase'    => 'poco',
                        'habito_compras'    => 'mercado',
                        'residuos_ropa'     => 'reutiliza',
                        'papel_carton'      => 'poco',
                    ],
                ],
                'mascota' => [
                    'nombre'         => 'Clorofila',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 88,
                    'escaneos_semana'=> 8,
                    'racha_semanas'  => 5,
                    'mensaje_actual' => '¡Tu dieta plant-based me hace muy feliz! 🥦',
                ],
            ],

            // ── 4. Luis — delivery lover, moda rápida ─────────────────────────
            [
                'user' => [
                    'nombres'          => 'Luis',
                    'apellido_paterno' => 'Herrera',
                    'apellido_materno' => 'Vega',
                    'email'            => 'luis@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Nápoles',
                    'alcaldia'         => 'Benito Juárez',
                    'roles_id'         => 2,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 420,
                    'puntos_semana'    => 60,
                    'nivel'            => 2,
                    'racha_dias'       => 7,
                    'co2_evitado_kg'   => 8.3,
                    'agua_ahorrada_litros' => 415.0,
                    'arboles_equivalentes' => 0.38,
                    // Omnívoro + delivery + moda rápida + muchas bebidas
                    // → genera: plástico, cartón (cajas), vidrio, textil
                    'habitos_perfil'   => [
                        'dieta'             => 'omnivoro',
                        'frecuencia_cocina' => 'pocas',
                        'bebidas_envase'    => 'mucho',
                        'habito_compras'    => 'delivery',
                        'residuos_ropa'     => 'seguido',
                        'papel_carton'      => 'bastante',
                    ],
                ],
                'mascota' => [
                    'nombre'         => 'Cartoncito',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 72,
                    'escaneos_semana'=> 5,
                    'racha_semanas'  => 2,
                    'mensaje_actual' => 'Cada caja de delivery que reciclas cuenta 📦',
                ],
            ],

            // ── 5. Sofía — flexitariana, cocina mucho, consciente ─────────────
            [
                'user' => [
                    'nombres'          => 'Sofía',
                    'apellido_paterno' => 'Ramírez',
                    'apellido_materno' => 'Cruz',
                    'email'            => 'sofia@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Coyoacán',
                    'alcaldia'         => 'Coyoacán',
                    'roles_id'         => 2,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 1650,
                    'puntos_semana'    => 120,
                    'nivel'            => 3,
                    'racha_dias'       => 30,
                    'co2_evitado_kg'   => 32.1,
                    'agua_ahorrada_litros' => 1605.0,
                    'arboles_equivalentes' => 1.48,
                    // Flexitariana, cocina diario, tiendita, nunca tira ropa
                    // → genera: orgánico, algo de plástico, muy poco textil
                    'habitos_perfil'   => [
                        'dieta'             => 'flexitariano',
                        'frecuencia_cocina' => 'diario',
                        'bebidas_envase'    => 'regular',
                        'habito_compras'    => 'tiendita',
                        'residuos_ropa'     => 'nunca',
                        'papel_carton'      => 'poco',
                    ],
                ],
                'mascota' => [
                    'nombre'         => 'Raíz',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 91,
                    'escaneos_semana'=> 10,
                    'racha_semanas'  => 6,
                    'mensaje_actual' => '¡30 días de racha! Eres una campeona 🏆',
                ],
            ],

            // ── 6. Pedro — sin hábitos, mascota en estado crítico ─────────────
            // (útil para probar el estado visual de mascota enferma)
            [
                'user' => [
                    'nombres'          => 'Pedro',
                    'apellido_paterno' => 'García',
                    'apellido_materno' => null,
                    'email'            => 'pedro@tiraparo.mx',
                    'password'         => bcrypt('123456'),
                    'colonia'          => 'Tepito',
                    'alcaldia'         => 'Cuauhtémoc',
                    'roles_id'         => 2,
                    'estatus_id'       => 1,
                    'puntos_totales'   => 10,
                    'puntos_semana'    => 0,
                    'nivel'            => 1,
                    'racha_dias'       => 0,
                    'co2_evitado_kg'   => 0.2,
                    'agua_ahorrada_litros' => 10.0,
                    'arboles_equivalentes' => 0.01,
                    // Sin perfil — para probar recordatorio de "crea tu plan"
                    'habitos_perfil'   => null,
                ],
                'mascota' => [
                    'nombre'         => 'Marchita',
                    'tipo'           => 'planta',
                    'nivel_salud'    => 15,
                    'escaneos_semana'=> 0,
                    'racha_semanas'  => 0,
                    'mensaje_actual' => '¡Necesito que recicles, por favor! Me estoy marchitando... 🥀',
                ],
            ],

        ];

        foreach ($usuarios as $item) {
            $user = User::create($item['user']);

            MascotaUsuario::create(array_merge(
                ['user_id' => $user->id],
                $item['mascota']
            ));
        }
    }
}
