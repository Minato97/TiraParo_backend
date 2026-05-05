<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escaneos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias_residuos');

            // Datos del modelo ML
            $table->string('ml_label');
            $table->decimal('confianza', 5, 4)->default(0);

            // Estado del escaneo
            $table->enum('estado', ['pendiente', 'validado', 'rechazado'])->default('pendiente');

            // Fotos (rutas relativas en storage)
            $table->string('foto_escaneo_url')->nullable();
            $table->string('foto_evidencia_url')->nullable();

            // Puntos
            $table->unsignedInteger('puntos_escaneo')->default(0);
            $table->unsignedInteger('puntos_evidencia')->default(0);

            // Geolocalización del escaneo
            $table->decimal('latitud_escaneo', 10, 7)->nullable();
            $table->decimal('longitud_escaneo', 10, 7)->nullable();

            // Geolocalización de la evidencia
            $table->decimal('latitud_evidencia', 10, 7)->nullable();
            $table->decimal('longitud_evidencia', 10, 7)->nullable();

            // Centro donde fue entregado (opcional)
            $table->foreignId('centro_destino_id')->nullable()->constrained('centros_destino')->nullOnDelete();

            // Cómo se validó la evidencia
            $table->enum('metodo_validacion', ['geolocalizacion', 'foto', 'comunidad', 'ninguno'])->default('ninguno');

            // Impacto ambiental de este escaneo
            $table->decimal('peso_estimado_kg', 8, 4)->default(0);
            $table->decimal('co2_evitado_g', 10, 4)->default(0);

            // Colonia/alcaldía al momento del escaneo (para mapa de impacto)
            $table->string('colonia_escaneo')->nullable();
            $table->string('alcaldia_escaneo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escaneos');
    }
};
