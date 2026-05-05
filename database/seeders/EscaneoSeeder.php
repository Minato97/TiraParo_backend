<?php

namespace Database\Seeders;

use App\Models\CategoriaResiduo;
use App\Models\Escaneo;
use App\Models\User;
use Illuminate\Database\Seeder;

class EscaneoSeeder extends Seeder
{
    /**
     * Crea escaneos de prueba para cada usuario seedeado.
     * Esto permite probar historial, leaderboard, colonias e impacto.
     */
    public function run(): void
    {
        // Precarga el mapa ml_label → categoria_id para no hacer N queries
        $categorias = CategoriaResiduo::pluck('id', 'ml_label');

        // Mapa: email → escaneos de prueba
        $escaneosPorUsuario = [

            'admin@tiraparo.mx' => [
                ['ml_label' => 'plastic',       'confianza' => 0.94, 'estado' => 'validado',  'colonia' => 'Centro Histórico', 'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 9,  'puntos_evidencia' => 21,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 120],
                ['ml_label' => 'glass',         'confianza' => 0.88, 'estado' => 'validado',  'colonia' => 'Centro Histórico', 'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 8,  'puntos_evidencia' => 18,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 95],
                ['ml_label' => 'cardboard',     'confianza' => 0.91, 'estado' => 'validado',  'colonia' => 'Centro Histórico', 'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 6,  'puntos_evidencia' => 14,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 80],
                ['ml_label' => 'metal',         'confianza' => 0.85, 'estado' => 'validado',  'colonia' => 'Centro Histórico', 'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 12, 'puntos_evidencia' => 28,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 200],
                ['ml_label' => 'food_organics', 'confianza' => 0.79, 'estado' => 'pendiente', 'colonia' => 'Centro Histórico', 'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 8,  'puntos_evidencia' => 0,    'metodo_validacion' => 'ninguno',         'co2_evitado_g' => 0],
            ],

            'ana@tiraparo.mx' => [
                ['ml_label' => 'food_organics', 'confianza' => 0.92, 'estado' => 'validado',  'colonia' => 'Polanco',          'alcaldia' => 'Miguel Hidalgo', 'puntos_escaneo' => 8,  'puntos_evidencia' => 17,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 150],
                ['ml_label' => 'cardboard',     'confianza' => 0.87, 'estado' => 'validado',  'colonia' => 'Polanco',          'alcaldia' => 'Miguel Hidalgo', 'puntos_escaneo' => 6,  'puntos_evidencia' => 14,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 80],
                ['ml_label' => 'food_organics', 'confianza' => 0.95, 'estado' => 'validado',  'colonia' => 'Polanco',          'alcaldia' => 'Miguel Hidalgo', 'puntos_escaneo' => 8,  'puntos_evidencia' => 17,   'metodo_validacion' => 'comunidad',       'co2_evitado_g' => 150],
                ['ml_label' => 'glass',         'confianza' => 0.82, 'estado' => 'validado',  'colonia' => 'Polanco',          'alcaldia' => 'Miguel Hidalgo', 'puntos_escaneo' => 8,  'puntos_evidencia' => 18,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 95],
            ],

            'sofia@tiraparo.mx' => [
                ['ml_label' => 'food_organics', 'confianza' => 0.96, 'estado' => 'validado',  'colonia' => 'Coyoacán',         'alcaldia' => 'Coyoacán',       'puntos_escaneo' => 8,  'puntos_evidencia' => 17,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 150],
                ['ml_label' => 'plastic',       'confianza' => 0.90, 'estado' => 'validado',  'colonia' => 'Coyoacán',         'alcaldia' => 'Coyoacán',       'puntos_escaneo' => 9,  'puntos_evidencia' => 21,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 120],
                ['ml_label' => 'cardboard',     'confianza' => 0.88, 'estado' => 'validado',  'colonia' => 'Coyoacán',         'alcaldia' => 'Coyoacán',       'puntos_escaneo' => 6,  'puntos_evidencia' => 14,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 80],
                ['ml_label' => 'metal',         'confianza' => 0.83, 'estado' => 'validado',  'colonia' => 'Coyoacán',         'alcaldia' => 'Coyoacán',       'puntos_escaneo' => 12, 'puntos_evidencia' => 28,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 200],
                ['ml_label' => 'textile',       'confianza' => 0.77, 'estado' => 'pendiente', 'colonia' => 'Coyoacán',         'alcaldia' => 'Coyoacán',       'puntos_escaneo' => 11, 'puntos_evidencia' => 0,    'metodo_validacion' => 'ninguno',         'co2_evitado_g' => 0],
            ],

            'luis@tiraparo.mx' => [
                ['ml_label' => 'plastic',       'confianza' => 0.89, 'estado' => 'validado',  'colonia' => 'Nápoles',          'alcaldia' => 'Benito Juárez',  'puntos_escaneo' => 9,  'puntos_evidencia' => 21,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 120],
                ['ml_label' => 'cardboard',     'confianza' => 0.93, 'estado' => 'validado',  'colonia' => 'Nápoles',          'alcaldia' => 'Benito Juárez',  'puntos_escaneo' => 6,  'puntos_evidencia' => 14,   'metodo_validacion' => 'geolocalizacion', 'co2_evitado_g' => 80],
                ['ml_label' => 'textile',       'confianza' => 0.81, 'estado' => 'pendiente', 'colonia' => 'Nápoles',          'alcaldia' => 'Benito Juárez',  'puntos_escaneo' => 11, 'puntos_evidencia' => 0,    'metodo_validacion' => 'ninguno',         'co2_evitado_g' => 0],
            ],

            'carlos@tiraparo.mx' => [
                ['ml_label' => 'plastic',       'confianza' => 0.76, 'estado' => 'validado',  'colonia' => 'Del Valle',        'alcaldia' => 'Benito Juárez',  'puntos_escaneo' => 9,  'puntos_evidencia' => 21,   'metodo_validacion' => 'foto',            'co2_evitado_g' => 120],
                ['ml_label' => 'cardboard',     'confianza' => 0.80, 'estado' => 'pendiente', 'colonia' => 'Del Valle',        'alcaldia' => 'Benito Juárez',  'puntos_escaneo' => 6,  'puntos_evidencia' => 0,    'metodo_validacion' => 'ninguno',         'co2_evitado_g' => 0],
            ],

            'pedro@tiraparo.mx' => [
                ['ml_label' => 'trash',         'confianza' => 0.65, 'estado' => 'pendiente', 'colonia' => 'Tepito',           'alcaldia' => 'Cuauhtémoc',    'puntos_escaneo' => 3,  'puntos_evidencia' => 0,    'metodo_validacion' => 'ninguno',         'co2_evitado_g' => 0],
            ],
        ];

        foreach ($escaneosPorUsuario as $email => $escaneos) {
            $user = User::where('email', $email)->first();
            if (! $user) continue;

            foreach ($escaneos as $i => $data) {
                // Busca el categoria_id por ml_label; si no existe usa el de 'trash'
                $categoriaId = $categorias[$data['ml_label']]
                    ?? $categorias['trash']
                    ?? $categorias->first();

                Escaneo::create([
                    'user_id'            => $user->id,
                    'categoria_id'       => $categoriaId,
                    'ml_label'           => $data['ml_label'],
                    'confianza'          => $data['confianza'],
                    'estado'             => $data['estado'],
                    'puntos_escaneo'     => $data['puntos_escaneo'],
                    'puntos_evidencia'   => $data['puntos_evidencia'],
                    'metodo_validacion'  => $data['metodo_validacion'],
                    'colonia_escaneo'    => $data['colonia'],
                    'alcaldia_escaneo'   => $data['alcaldia'],
                    'latitud_escaneo'    => 19.4326 + ($i * 0.002),
                    'longitud_escaneo'   => -99.1332 + ($i * 0.002),
                    'peso_estimado_kg'   => 0.25,
                    'co2_evitado_g'      => $data['co2_evitado_g'],
                    'created_at'         => now()->subDays(rand(0, 30)),
                    'updated_at'         => now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }
}
