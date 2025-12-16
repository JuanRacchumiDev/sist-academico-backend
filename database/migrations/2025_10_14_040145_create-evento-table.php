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
        Schema::create('evento', function(Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_tipoevento');
            $table->unsignedBigInteger('id_categoriaevento');

            $table->string('titulo', 100);
            $table->string('titulo_url', 120);
            $table->string('descripcion', 120)->nullable();
            $table->text('temario')->nullable();
            $table->string('fecha_inicio', 10);
            $table->string('fecha_final', 10);
            $table->string('duracion', 20);
            $table->enum('modalidad', ['VIRTUAL', 'PRESENCIAL', 'MIXTO'], 20)->default('VIRTUAL');
            $table->decimal('precio', 10, 2)->nullable();
            $table->integer('capacidad_minima')->nullable();
            $table->integer('capacidad_maxima')->nullable();
            $table->integer('cantidad_inscritos')->nullable();
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_tipoevento')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_categoriaevento')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento', function(Blueprint $table) {
            $table->dropForeign('id_tipoevento');
            $table->dropColumn('id_tipoevento');

            $table->dropForeign('id_categoriaevento');
            $table->dropColumn('id_categoriaevento');
        });

        Schema::dropIfExists('evento');
    }
};
