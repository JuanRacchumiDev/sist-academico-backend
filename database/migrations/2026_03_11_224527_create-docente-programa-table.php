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
        Schema::create('academic.docente_programa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_programa');
            $table->unsignedBigInteger('id_modulo');

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id')
                ->on('academic.persona')
                ->onDelete('cascade');

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
        Schema::table('academic.docente_programa', function (Blueprint $table) {
            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');

            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');
        });

        Schema::dropIfExists('academic.docente_programa');
    }
};
