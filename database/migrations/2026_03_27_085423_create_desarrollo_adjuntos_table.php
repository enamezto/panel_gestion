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
        Schema::create('desarrollo_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dev_version')->constrained('desarrollo_versiones')->onDelete('cascade');
            $table->foreignId('id_adj_version')->constrained('adjunto_versiones')->onDelete('cascade');
            $table->boolean('obligatorio')->default(true);
            $table->integer('orden')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollo_adjuntos');
    }
};
