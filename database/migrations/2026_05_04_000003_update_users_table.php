<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable()->after('nombres');
            $table->string('apellido_materno')->nullable()->after('apellido_paterno');
            $table->string('foto')->nullable()->after('email');
            $table->string('colonia')->nullable()->after('foto');
            $table->string('alcaldia')->nullable()->after('colonia');
            $table->unsignedBigInteger('roles_id')->default(2)->after('alcaldia');
            $table->unsignedBigInteger('estatus_id')->default(1)->after('roles_id');

            // Gamificación
            $table->unsignedInteger('puntos_totales')->default(0)->after('estatus_id');
            $table->unsignedInteger('puntos_semana')->default(0)->after('puntos_totales');
            $table->unsignedInteger('nivel')->default(1)->after('puntos_semana');
            $table->unsignedInteger('racha_dias')->default(0)->after('nivel');

            // Impacto ambiental acumulado
            $table->decimal('co2_evitado_kg', 10, 3)->default(0)->after('racha_dias');
            $table->decimal('agua_ahorrada_litros', 10, 3)->default(0)->after('co2_evitado_kg');
            $table->decimal('arboles_equivalentes', 10, 3)->default(0)->after('agua_ahorrada_litros');

            $table->foreign('roles_id')->references('id')->on('roles');
            $table->foreign('estatus_id')->references('id')->on('estatus');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['roles_id']);
            $table->dropForeign(['estatus_id']);
            $table->dropColumn([
                'apellido_paterno', 'apellido_materno', 'foto',
                'colonia', 'alcaldia', 'roles_id', 'estatus_id',
                'puntos_totales', 'puntos_semana', 'nivel', 'racha_dias',
                'co2_evitado_kg', 'agua_ahorrada_litros', 'arboles_equivalentes',
            ]);
        });
    }
};
