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
        Schema::create('productos_paquetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_paquete')->constrained('paquetes')->cascadeOnDelete();
            $table->foreignId('id_producto')->constrained('productos')->cascadeOnDelete();
            $table->decimal('cantidad')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_paquetes');
    }
};
