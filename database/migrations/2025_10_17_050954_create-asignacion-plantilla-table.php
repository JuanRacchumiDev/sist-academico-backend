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
        Schema::create('academic.asignacion_plantilla', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_plantilla');
            $table->unsignedBigInteger('id_programa');

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_plantilla')
                ->references('id')
                ->on('academic.plantilla')
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
        Schema::table('academic.asignacion_plantilla', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_plantilla');
            $table->dropConstrainedForeignId('id_programa');
        });

        Schema::dropIfExists('academic.asignacion_plantilla');
    }
};
