<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('icono_sf')->default('star.fill');
            $table->string('color_hex')->default('#FFD700');
            // Tipo de criterio para desbloquear
            $table->enum('criterio_tipo', [
                'primer_escaneo',
                'escaneos_total',
                'categoria_count',
                'racha_dias',
                'co2_total_kg',
                'puntos_total',
                'validaciones_total',
                'comunidad_votos',
            ]);
            // Valor numérico del umbral (e.g. 10 escaneos, 5 días de racha)
            $table->unsignedInteger('criterio_valor')->default(1);
            // Si aplica a una categoría específica
            $table->string('categoria_ml_label')->nullable();
            $table->enum('rareza', ['comun', 'raro', 'epico', 'legendario'])->default('comun');
            // Para badges de edición limitada por temporada
            $table->string('temporada')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
