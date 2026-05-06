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
        Schema::create('desarrollo_advertencia_restricciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dev')->constrained('desarrollos')->onDelete('cascade');
            $table->foreignId('id_adj')->constrained('adjuntos')->onDelete('cascade');
            $table->text('mensaje_advertencia');
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollo_advertencia_restricciones');
    }
};
