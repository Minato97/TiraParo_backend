<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centros_destino', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['reciclaje', 'donacion', 'composta', 'reutilizacion']);
            $table->string('descripcion')->nullable();
            $table->string('direccion');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->string('alcaldia');
            $table->string('colonia')->nullable();
            $table->string('ciudad')->default('Ciudad de México');
            $table->string('horario')->nullable();
            $table->string('telefono')->nullable();
            $table->string('sitio_web')->nullable();
            // Array JSON de ml_labels que acepta este centro (e.g. ["plastic","glass"])
            $table->json('materiales_aceptados')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centros_destino');
    }
};
