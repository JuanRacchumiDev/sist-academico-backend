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
        Schema::create('adjunto', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_programa')->nullable();
            $table->unsignedBigInteger('id_modulo')->nullable();
            $table->unsignedBigInteger('id_institucion')->nullable();

            $table->string('titulo', 100);
            $table->string('titulo_url', 120);
            $table->string('descripcion', 150)->nullable();
            $table->string('filename', 120);
            $table->string('originalname', 180);
            $table->string('filepath', 150);
            $table->string('mimetype', 20);
            $table->integer('size');
            $table->boolean('es_descargable')->default(true);
            $table->boolean('es_visible')->default(true);
            
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('sistema')->default(false);
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_programa')
                ->references('id')
                ->on('programa');

            $table->foreign('id_modulo')
                ->references('id')
                ->on('modulo');

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
        Schema::table('adjunto', function(Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_modulo');
            $table->dropColumn('id_modulo');

            $table->dropForeign('id_institucion');
            $table->dropColumn('id_institucion');
        });

        Schema::dropIfExists('adjunto');
    }
};
