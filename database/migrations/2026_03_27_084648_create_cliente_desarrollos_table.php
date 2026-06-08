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
        Schema::create('cliente_desarrollos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('id_dev')->constrained('desarrollos')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_alta')->useCurrent();
            $table->unique(['id_cliente', 'id_dev']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_desarrollos');
    }
};
