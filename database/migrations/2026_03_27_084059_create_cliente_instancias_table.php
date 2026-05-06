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
        Schema::create('cliente_instancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');
            $table->string('nombre');
            $table->string('host')->nullable();
            $table->string('tipo')->nullable();
            $table->string('ip')->nullable();
            $table->timestamp('fecha_alta')->useCurrent();
            $table->timestamp('ultima_conexion')->nullable();
            //$table->string('registro_token')->unique();
            $table->timestamp('registro_fecha')->nullable();
            $table->string('ruta_listados')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_instancias');
    }
};
