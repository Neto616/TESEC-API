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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo_contacto')->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->string('direccion')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('estatus')->default(1)->comment('0: Inactivo, 1: Activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('proveedores');
        Schema::enableForeignKeyConstraints();
    }
};
