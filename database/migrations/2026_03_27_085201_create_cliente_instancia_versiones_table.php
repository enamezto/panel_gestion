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
        Schema::create('cliente_instancia_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente_desarrollo')->constrained('cliente_desarrollos')->onDelete('cascade');
            $table->foreignId('id_dev_version')->constrained('desarrollo_versiones')->onDelete('cascade');
            $table->foreignId('id_instancia')->constrained('cliente_instancias')->onDelete('cascade');
            $table->timestamp('fecha_descarga')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->string('resultado')->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_instancia_versiones');
    }
};
