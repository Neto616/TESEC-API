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
        Schema::create('clientes_domcilios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->integer('pais')->default(0);
            $table->string('estado', 255)->default('');
            $table->string('ciudad',255)->default('');
            $table->string('calle',255)->default('');
            $table->string('colonia',255)->default('');
            $table->string('numero_exterior',10)->default('');
            $table->string('numero_interior',10)->default('');
            $table->string('codigo_postal',12)->default('');
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_domcilios');
    }
};
