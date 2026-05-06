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
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tipo')->constrained('adjunto_tipos')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->string('arbol_carpeta')->nullable();
            $table->string('principal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjuntos');
    }
};
