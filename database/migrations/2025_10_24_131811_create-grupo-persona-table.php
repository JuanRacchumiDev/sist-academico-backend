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
        Schema::create('grupo_persona', function(Blueprint $table) {
            $table->unsignedBigInteger('codigo_detalle_parametro');
            $table->unsignedBigInteger('id_persona');

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->timestamps();

            // Definición explícita de la clave foránea para 'detalle_parametro'
            $table->foreign('codigo_detalle_parametro')
                  ->references('codigo') // La clave primaria de detalle_parametro se llama 'codigo'
                  ->on('detalle_parametro');
                //   ->onDelete('cascade');

            // Definición explícita de la clave foránea para 'persona'
            $table->foreign('id_persona')
                  ->references('id') // La clave primaria de persona se llama 'id'
                  ->on('persona');
                //   ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_persona');
    }
};
