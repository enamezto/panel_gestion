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
        Schema::create('desarrollo_dependencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dev_version')->constrained('desarrollo_versiones')->onDelete('cascade');
            $table->foreignId('id_dev_dependencia')->constrained('desarrollos')->onDelete('cascade');
            $table->string('version_min')->nullable();
            $table->string('version_max')->nullable();
            $table->boolean('obligatorio')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollo_dependencias');
    }
};
