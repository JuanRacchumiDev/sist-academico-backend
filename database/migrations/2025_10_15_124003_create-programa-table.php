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
        Schema::create('programa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('codigo_segmento')->nullable();
            $table->unsignedBigInteger('codigo_tipoprograma')->nullable();
            $table->unsignedBigInteger('codigo_categoriaprograma')->nullable();
            $table->unsignedBigInteger('id_sucursal')->nullable();

            $table->string('codigo_old', 10)->nullable();
            $table->string('sigla', 10)->nullable();
            $table->string('titulo', 100);
            $table->string('titulo_url', 120);
            $table->string('descripcion', 150)->nullable();
            $table->text('temario')->nullable();
            $table->string('fecha_inicio', 10)->nullable();
            $table->string('fecha_final', 10)->nullable();
            $table->string('duracion', 20)->nullable();
            $table->integer('horas_academicas')->nullable();
            $table->integer('numero_modulos')->nullable();
            $table->integer('creditos')->nullable();
            $table->string('plan', 100)->nullable();
            $table->enum('modalidad', ['VIRTUAL', 'PRESENCIAL', 'MIXTO'])->default('VIRTUAL');
            $table->integer('capacidad_minima')->nullable();
            $table->integer('capacidad_maxima')->nullable();
            $table->integer('cantidad_inscritos')->nullable();
            $table->decimal('precio_modulo', 10, 2)->nullable();
            $table->boolean('is_vigente')->default(true);

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('codigo_segmento')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('codigo_tipoprograma')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('codigo_categoriaprograma')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_sucursal')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programa', function (Blueprint $table) {
            $table->dropForeign('codigo_segmento');
            $table->dropColumn('codigo_segmento');

            $table->dropForeign('codigo_tipoprograma');
            $table->dropColumn('codigo_tipoprograma');

            $table->dropForeign('codigo_categoriaprograma');
            $table->dropColumn('codigo_categoriaprograma');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });

        Schema::dropIfExists('programa');
    }
};
