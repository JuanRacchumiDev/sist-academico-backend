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
        Schema::create('cuestionario_persona', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_cuestionario');
            $table->unsignedBigInteger('id_persona');

            $table->integer('numero_intento')->default(1);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->decimal('puntaje_total', 5, 2)->nullable();
            $table->enum('estado_intento', ['EN_PROCESO', 'FINALIZADO', 'CORREGIDO'])->default('EN_PROCESO');

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_cuestionario')
                ->references('id')
                ->on('cuestionario');

            $table->foreign('id_persona')
                ->references('id')
                ->on('persona');

            // $table->foreign('id_cuestionario')->references('id')->on('cuestionario')->onDelete('cascade');
            // $table->foreign('id_persona')->references('id')->on('persona')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuestionario_persona', function (Blueprint $table) {
            $table->dropForeign('id_cuestionario');
            $table->dropColumn('id_cuestionario');

            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');
        });

        Schema::dropIfExists('cuestionario_persona');
    }
};
