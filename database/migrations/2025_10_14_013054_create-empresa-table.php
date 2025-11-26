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
        Schema::create('empresa', function(Blueprint $table) {
            $table->id();

            $table->string('numero_ruc', 15);
            $table->string('razon_social', 100);
            $table->string('tipo_contribuyente', 50)->nullable();
            $table->string('estado_sunat', 20)->nullable();
            $table->string('condicion_sunat', 50)->nullable();
            $table->string('departamento', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('distrito', 50)->nullable();
            $table->string('direccion', 80)->nullable();
            $table->string('direccion_completa', 150)->nullable();
            $table->string('ubigeo_sunat', 10)->nullable();
            $table->enum('origen', ['API', 'WEB', 'APP'])->default('WEB');
            $table->string('telefonos', 50)->nullable();
            $table->string('horario_atencion', 200)->nullable();
            $table->string('user_crea', 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
