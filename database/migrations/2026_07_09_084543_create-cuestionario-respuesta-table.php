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
        Schema::create('cuestionario_respuesta', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_cuestionario_persona');
            $table->unsignedBigInteger('id_pregunta');
            $table->unsignedBigInteger('id_pregunta_opcion')->nullable()->comment('Para tipo RADIO O CHECKBOX');

            $table->text('respuesta_texto')->nullable()->comment('Para respuestas en caja de texto');

            $table->decimal('puntaje_obtenido', 5, 2)->default('0.00');
            $table->boolean('es_correcta')->nullable()->comment('Null si requiere revisión manual');

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_cuestionario_persona')
                ->references('id')
                ->on('cuestionario_persona');

            $table->foreign('id_pregunta')
                ->references('id')
                ->on('pregunta');

            $table->foreign('id_pregunta_opcion')
                ->references('id')
                ->on('pregunta_opcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuestionario_respuesta', function (Blueprint $table) {
            $table->dropForeign('id_cuestionario_persona');
            $table->dropColumn('id_cuestionario_persona');

            $table->dropForeign('id_pregunta');
            $table->dropColumn('id_pregunta');

            $table->dropForeign('id_pregunta_opcion');
            $table->dropColumn('id_pregunta_opcion');
        });

        Schema::dropIfExists('cuestionario_respuesta');
    }
};
