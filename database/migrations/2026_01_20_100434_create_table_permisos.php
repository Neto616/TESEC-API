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
        Schema::create('perfiles', function(Blueprint $table){
            $table->id();
            $table->string('perfil');
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
            $table->foreignId('perfil_id')
                ->nullable()
                ->after('estatus')
                ->constrained('perfiles');
        });

        Schema::create('secciones', function(Blueprint $table) {
            $table->id();
            $table->string('nombre_seccion');
            $table->timestamps();
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('permiso');
            $table->timestamps();
        });

        Schema::create('perfil_permisos', function(Blueprint $table) {
            $table->foreignId('perfil_id')
                ->constrained('perfiles');

            $table->foreignId('permiso_id')
                ->constrained('permisos');

            $table->foreignId('seccion_id')
                ->constrained('secciones');

            $table->primary(['perfil_id', 'permiso_id', 'seccion_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropConstrainedForeignId('perfil_id');
        });
        Schema::dropIfExists('perfil_permisos');
        Schema::dropIfExists('permisos');
        Schema::dropIfExists('secciones');
        Schema::dropIfExists('perfiles'); 
    }
};
