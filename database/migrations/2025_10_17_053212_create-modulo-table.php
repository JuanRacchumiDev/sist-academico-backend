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
        Schema::create('academic.modulo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_programa')->nullable();

            $table->string('titulo', 100);
            $table->string('titulo_url', 120);
            $table->string('descripcion', 150)->nullable();
            $table->text('temario')->nullable();
            $table->decimal('nota', 10, 2)->nullable();
            $table->integer('orden');
            $table->string('plan', 150)->nullable();

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

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
        Schema::table('academic.modulo', function (Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');
        });

        Schema::dropIfExists('academic.modulo');
    }
};
