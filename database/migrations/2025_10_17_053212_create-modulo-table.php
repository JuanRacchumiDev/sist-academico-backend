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
        Schema::create('modulo', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_programa')->nullable();
            $table->unsignedBigInteger('id_institucion')->nullable();

            $table->string('titulo', 100);
            $table->string('titulo_url', 120);
            $table->string('descripcion', 150)->nullable();
            $table->integer('orden');

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_programa')
                ->references('id')
                ->on('programa');

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
        Schema::table('modulo', function(Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_institucion');
            $table->dropColumn('id_institucion');
        });

        Schema::dropIfExists('modulo');
    }
};
