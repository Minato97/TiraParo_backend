<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perfil de hábitos del usuario (JSON almacenado completo)
            // Estructura: { dieta, frecuencia_cocina, bebidas_envase,
            //               habito_compras, residuos_ropa, papel_carton }
            $table->json('habitos_perfil')->nullable()->after('arboles_equivalentes');
        });
    }
//hola
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('habitos_perfil');
        });
    }
};
