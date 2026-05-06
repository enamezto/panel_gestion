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
        Schema::create('adjunto_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_adj')->constrained('adjuntos')->onDelete('cascade');
            $table->integer('version_major');
            $table->integer('version_minor');
            $table->integer('version_patch');
            $table->string('hash')->nullable();
            $table->timestamp('fecha')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjunto_versiones');
    }
};
