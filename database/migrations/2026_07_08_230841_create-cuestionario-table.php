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
        Schema::create('cuestionario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_programa')->nullable();
            $table->unsignedBigInteger('id_modulo')->nullable();

            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->integer('duracion_minutos')->nullable()->comment('Tiempo máximo en minutos');
            $table->decimal('nota_minima_aprobatoria', 5, 2)->default(11.0);
            $table->integer('intentos_permitidos')->default(1);

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
                ->on('programa');

            $table->foreign('id_modulo')
                ->references('id')
                ->on('modulo');

            // $table->foreign('id_programa')->references('id')->on('programa')->onDelete('cascade');
            // $table->foreign('id_modulo')->references('id')->on('modulo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuestionario', function (Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_modulo');
            $table->dropColumn('id_modulo');
        });

        Schema::dropIfExists('cuestionario');
    }
};
