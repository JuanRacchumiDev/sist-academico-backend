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
        Schema::create('institucion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_sede')->nullable();

            $table->string('nombre', 60);
            $table->string('sigla', 100)->nullable();
            $table->string('ruc', 13)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('logo_path', 150)->nullable();
            $table->string('firma_digital', 150)->nullable();
            $table->string('color_primario', 20)->nullable();
            $table->string('nombre_director', 150)->nullable();
            $table->string('nombre_representante', 150)->nullable();
            $table->string('firma_director_path', 150)->nullable();
            $table->string('firma_representante_path', 150)->nullable();

            $table->boolean('is_cliente')->default(false);

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('codigo_sede')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institucion', function (Blueprint $table) {
            $table->dropForeign('codigo_sede');
            $table->dropColumn('codigo_sede');
        });

        Schema::dropIfExists('institucion');
    }
};
