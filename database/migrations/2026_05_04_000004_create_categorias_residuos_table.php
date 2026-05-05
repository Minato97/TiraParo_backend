<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_residuos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            // Tipo de destino según flujo de la app
            $table->enum('tipo_destino', ['reciclaje', 'donacion', 'composta', 'reutilizacion']);
            // Label exacto que devuelve el modelo Core ML
            $table->string('ml_label')->unique();
            // Iconos SF Symbols para iOS
            $table->string('icono_sf')->default('trash');
            $table->string('color_hex')->default('#4CAF50');
            // Puntos base que otorga este residuo al escanearlo correctamente
            $table->unsignedInteger('puntos_base')->default(20);
            // Peso promedio estimado por objeto en kg (para calcular CO2)
            $table->decimal('peso_promedio_kg', 8, 4)->default(0.1);
            // CO2 evitado por kg de este material reciclado (en kg CO2)
            $table->decimal('co2_por_kg', 8, 4)->default(0.5);
            // Agua ahorrada por kg de este material reciclado (en litros)
            $table->decimal('agua_por_kg', 8, 2)->default(0);
            // Instrucciones en español para el usuario
            $table->text('instrucciones');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_residuos');
    }
};
