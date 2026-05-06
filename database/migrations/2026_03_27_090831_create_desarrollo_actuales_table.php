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
        Schema::create('desarrollo_actuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dev')->unique()->constrained('desarrollos')->onDelete('cascade');
            $table->foreignId('id_dev_version')->constrained('desarrollo_versiones')->onDelete('cascade');
            $table->timestamp('fecha')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollo_actuales');
    }
};
