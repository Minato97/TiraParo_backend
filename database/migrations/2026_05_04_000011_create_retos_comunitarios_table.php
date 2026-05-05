<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retos_comunitarios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->decimal('meta_kg', 10, 2);
            $table->decimal('actual_kg', 10, 2)->default(0);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            // null = todos los materiales
            $table->string('tipo_material')->nullable();
            // null = toda la ciudad
            $table->string('alcaldia')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retos_comunitarios');
    }
};
