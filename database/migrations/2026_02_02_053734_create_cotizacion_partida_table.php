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
        Schema::create('cotizacion_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cotizacion')
                ->constrained('cotizaciones')
                ->cascadeOnDelete();
            $table->foreignId('id_productos')
                ->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('total', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_partida');
    }
};
