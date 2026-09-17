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
        Schema::create('academic.grupo_persona', function (Blueprint $table) {
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

            $table->foreign('codigo_grupo')
                ->references('codigo')
                ->on('academic.detalle_parametro')
                ->onDelete('cascade');

            $table->foreign('id_persona')
                ->references('id')
                ->on('academic.persona')
                ->onDelete('cascade');

            $table->foreign('id_sucursal')
                ->references('codigo')
                ->on('academic.detalle_parametro')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic.grupo_persona', function (Blueprint $table) {
            $table->dropForeign('codigo_grupo');
            $table->dropColumn('codigo_grupo');

            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });


        Schema::dropIfExists('academic.grupo_persona');
    }
};
