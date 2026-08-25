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
        Schema::create('plantilla', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_institucion')->nullable();

            $table->string('nombre', 100);
            $table->string('descripcion', 150)->nullable();
            $table->string('path_imagen_fondo', 150)->nullable();
            $table->string('path_imagen_publica', 150)->nullable();
            $table->string('path_pdf_fondo', 150)->nullable();
            $table->string('tipo_disenio', 100)->nullable();
            $table->string('disenio_default', 100)->nullable();

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_institucion')
                ->references('id')
                ->on('institucion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantilla', function (Blueprint $table) {
            $table->dropForeign('id_institucion');
            $table->dropColumn('id_institucion');
        });

        Schema::dropIfExists('plantilla');
    }
};
