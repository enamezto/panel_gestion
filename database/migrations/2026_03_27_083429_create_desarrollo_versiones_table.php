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
        Schema::create('desarrollo_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dev')->constrained('desarrollos')->onDelete('cascade');
            $table->integer('version_major');
            $table->integer('version_minor');
            $table->integer('version_patch');
            $table->timestamp('fecha')->useCurrent();
            $table->text('descripcion_cambios')->nullable();
            $table->boolean('requiere_parada')->default(false);
            $table->string('version_a3erp_min')->nullable();
            $table->string('version_a3erp_max')->nullable();
            $table->string('hash')->nullable();
            $table->string('ruta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollo_versiones');
    }
};
