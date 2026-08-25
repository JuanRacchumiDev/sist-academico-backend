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
        Schema::create('grupo_persona', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo_grupo');
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_sucursal')->nullable();

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            // Definición explícita de la clave foránea para 'detalle_parametro'
            $table->foreign('codigo_grupo')
                ->references('codigo') // La clave primaria de detalle_parametro se llama 'codigo'
                ->on('detalle_parametro');
            //   ->onDelete('cascade');

            // Definición explícita de la clave foránea para 'persona'
            $table->foreign('id_persona')
                ->references('id') // La clave primaria de persona se llama 'id'
                ->on('persona');
            //   ->onDelete('cascade');

            $table->foreign('id_sucursal')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupo_persona', function (Blueprint $table) {
            $table->dropForeign('codigo_grupo');
            $table->dropColumn('codigo_grupo');

            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });


        Schema::dropIfExists('grupo_persona');
    }
};
