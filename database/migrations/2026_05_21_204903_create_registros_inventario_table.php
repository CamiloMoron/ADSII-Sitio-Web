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
        Schema::create('registros_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_clasificado_id')->constrained('lotes_clasificados')->restrictOnDelete();
            $table->integer('peso_bruto');
            $table->integer('peso_final');
            $table->integer('merma');
            $table->string('zona_almacen');
            $table->string('estado')->default('Cerrado');
            $table->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_inventario');
    }
};
