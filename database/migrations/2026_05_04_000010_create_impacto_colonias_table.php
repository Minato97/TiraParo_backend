<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impacto_colonias', function (Blueprint $table) {
            $table->id();
            $table->string('colonia');
            $table->string('alcaldia');
            $table->string('ciudad')->default('Ciudad de México');
            $table->unsignedSmallInteger('mes');
            $table->unsignedSmallInteger('anio');
            $table->decimal('kg_reciclados', 10, 3)->default(0);
            $table->decimal('co2_evitado_kg', 10, 3)->default(0);
            $table->unsignedInteger('num_escaneos')->default(0);
            $table->unsignedInteger('num_usuarios')->default(0);
            $table->unique(['colonia', 'alcaldia', 'mes', 'anio']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impacto_colonias');
    }
};
