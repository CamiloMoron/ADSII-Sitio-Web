<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes_clasificados', function (Blueprint $table) {
            $table->id();
            $table->string('material_sugerido');
            $table->string('confianza')->default('95%');
            $table->enum('validacion', ['Confirmada', 'Corregida']);
            $table->string('material_final');
            $table->foreignId('operario_id')->constrained('users')->restrictOnDelete();
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes_clasificados');
    }
};
