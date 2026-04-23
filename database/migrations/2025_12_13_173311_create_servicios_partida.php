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
        Schema::create('servicios_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_servicio')->constrained('servicios')->cascadeOnDelete();
            $table->foreignId('id_producto')->constrained('productos')->cascadeOnDelete();
            $table->string('nombre')->default('');
            $table->decimal('cantidad',10,2)->default(0);
            $table->decimal('precio_unitario',10,2)->default(0);
            $table->decimal('precio_total',10,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_partida');
    }
};
