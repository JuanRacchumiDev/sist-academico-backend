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
        Schema::create('academic.matricula', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('codigo_estadomatricula');
            $table->unsignedBigInteger('id_sucursal');

            $table->integer('numero_modulos');

            $table->string('fecha_matricula', 10);
            $table->string('fecha_retiro', 10)->nullable();
            $table->string('fecha_reserva', 10)->nullable();
            $table->string('fecha_anula', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id')
                ->on('academic.persona')
                ->onDelete('cascade');

            $table->foreign('codigo_estadomatricula')
                ->references('codigo')
                ->on('academic.detalle_parametro')
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
        Schema::table('academic.matricula', function (Blueprint $table) {
            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');

            $table->dropForeign('codigo_estadomatricula');
            $table->dropColumn('codigo_estadomatricula');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });

        Schema::dropIfExists('academic.matricula');
    }
};
