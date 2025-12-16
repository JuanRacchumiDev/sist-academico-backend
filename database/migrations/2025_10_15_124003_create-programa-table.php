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
        Schema::create('programa', function(Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('id_segmento')->nullable();
            $table->unsignedBigInteger('id_tipoprograma')->nullable();
            $table->unsignedBigInteger('id_categoriaprograma')->nullable();

            $table->string('codigo_old', 10)->nullable();
            $table->string('sigla', 10)->nullable();
            $table->string('nombre', 150);
            $table->string('nombre_url', 180)->nullable();
            $table->string('descripcion', 150)->nullable();
            $table->string('fecha_inicio', 10)->nullable();
            $table->string('fecha_final', 10)->nullable();
            $table->string('duracion', 20)->nullable();
            $table->integer('horas_academicas')->nullable();
            $table->integer('modulos')->nullable();
            $table->integer('creditos')->nullable();
            $table->string('plan', 100)->nullable();
            $table->enum('modalidad', ['VIRTUAL', 'PRESENCIAL', 'MIXTO'], 20)->default('VIRTUAL');
            $table->string('temario', 1000)->nullable();
            $table->integer('capacidad_minima')->nullable();
            $table->integer('capacidad_maxima')->nullable();
            $table->integer('cantidad_inscritos')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->boolean('is_vigente')->default(true);
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_segmento')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_tipoprograma')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_categoriaprograma')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programa', function(Blueprint $table) {
            $table->dropForeign('id_segmento');
            $table->dropColumn('id_segmento');

            $table->dropForeign('id_tipoprograma');
            $table->dropColumn('id_tipoprograma');

            $table->dropForeign('id_categoriaprograma');
            $table->dropColumn('id_categoriaprograma');
        });

        Schema::dropIfExists('programa');
    }
};
