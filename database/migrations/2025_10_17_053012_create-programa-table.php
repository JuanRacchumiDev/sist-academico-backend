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
        
            $table->unsignedBigInteger('id_segmento');

            $table->string('codigo_old', 10)->nullable();
            $table->string('sigla', 10);
            $table->string('nombre', 100);
            $table->string('duracion', 20)->nullable();
            $table->integer('modulos')->nullable();
            $table->integer('creditos')->nullable();
            $table->string('plan', 100)->nullable();
            $table->boolean('is_vigente')->default(true);
            $table->string('user_crea', 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_segmento')
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
        });

        Schema::dropIfExists('programa');
    }
};
