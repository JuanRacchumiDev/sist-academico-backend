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
            $table->unsignedBigInteger('detalle_parametro_codigo');

            // Columna que referencia a 'persona'.
            // Siempre 'unsignedBigInteger' si referencia al 'id()' de otra tabla.
            $table->unsignedBigInteger('persona_id');

            $table->timestamps();

            // Definición explícita de la clave foránea para 'detalle_parametro'
            $table->foreign('detalle_parametro_codigo')
                  ->references('codigo') // La clave primaria de detalle_parametro se llama 'codigo'
                  ->on('detalle_parametro');
                //   ->onDelete('cascade');

            // Definición explícita de la clave foránea para 'persona'
            $table->foreign('persona_id')
                  ->references('id') // La clave primaria de persona se llama 'id'
                  ->on('persona');
                //   ->onDelete('cascade');

            $table->primary(['detalle_parametro_codigo', 'persona_id']);

            // $table->id();

            // $table->foreign('id_grupo')
            //     ->constrained('detalle_parametro');

            // $table->foreign('id_persona')
            //     ->constrained('persona');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('grupo_persona', function(Blueprint $table) {
        //     $table->dropConstrainedForeignId('id_grupo');
        //     $table->dropConstrainedForeignId('id_persona');
        // });

        Schema::dropIfExists('grupo_persona');
    }
};
