<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nombre')->default('Plantita');
            $table->enum('tipo', ['planta', 'criatura'])->default('planta');
            // Salud 0-100: baja 10 pts por día sin escanear, sube 20 pts por escaneo validado
            $table->unsignedInteger('nivel_salud')->default(80);
            $table->unsignedInteger('escaneos_semana')->default(0);
            $table->timestamp('ultimo_escaneo_at')->nullable();
            $table->unsignedInteger('racha_semanas')->default(0);
            // Mensaje personalizado generado por Foundation Models
            $table->text('mensaje_actual')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas_usuario');
    }
};
