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
        Schema::create('nota', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_programa');
            $table->unsignedBigInteger('id_docente');

            $table->decimal('calificacion', 10, 2);
            $table->string('descripcion', 100);
            $table->string('fecha_registro', 10);
            $table->string('user_crea', 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_alumno')
                ->references('id')
                ->on('persona');

            $table->foreign('id_programa')
                ->references('id')
                ->on('programa');

            $table->foreign('id_docente')
                ->references('id')
                ->on('persona');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota', function(Blueprint $table) {
            $table->dropForeign('id_alumno');
            $table->dropColumn('id_alumno');

            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_docente');
            $table->dropColumn('id_docente');
        });

        Schema::dropIfExists('nota');
    }
};
