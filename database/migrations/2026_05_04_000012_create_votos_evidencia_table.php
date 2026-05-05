<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votos_evidencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escaneo_id')->constrained('escaneos')->cascadeOnDelete();
            $table->foreignId('voter_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('voto', ['valido', 'invalido']);
            $table->unique(['escaneo_id', 'voter_user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votos_evidencia');
    }
};
