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
        Schema::create('academic.programa_horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_programa');

            $table->integer('anio');
            $table->tinyInteger('mes');

            $table->string('plataforma', 50)->nullable();
            $table->string('link_plataforma', 255)->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable();
            $table->string('zona_horaria', 30)->default('Lima GMT-5');
            $table->string('frecuencia_texto', 255)->nullable()->comment('Ej: 1er sábado, 1er domingo... de cada mes');
            $table->jsonb('fechas_sesiones')->nullable();

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_programa')
                ->references('id')
                ->on('academic.programa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic.programa_horarios', function (Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');
        });

        Schema::dropIfExists('academic.programa_horarios');
    }
};
