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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('productos');
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('sku')->nullable();
            $table->string('clave_sat', 10)->nullable();
            $table->string('descripcion',255);
            $table->string('marca')->nullable();
            $table->text('link')->nullable();
            $table->foreignId('id_proveedor')
                ->constrained('proveedores');
            $table->decimal('precio', 18, 6);
            $table->decimal('precio_publico', 18, 6);
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('productos');
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('descripcion',255);
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }
};
