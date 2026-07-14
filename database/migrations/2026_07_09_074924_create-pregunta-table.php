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
        Schema::create('pregunta', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_cuestionario');

            $table->text('enunciado');
            $table->enum('tipo_respuesta', ['RADIO', 'CHECKBOX', 'TEXTO'])->default('RADIO');
            $table->decimal('puntos', 5, 2)->default(1.00);
            $table->integer('orden')->default(1);

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_cuestionario')
                ->references('id')
                ->on('cuestionario');

            // $table->foreign('id_cuestionario')->references('id')->on('cuestionario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pregunta', function (Blueprint $table) {
            $table->dropForeign('id_cuestionario');
            $table->dropColumn('id_cuestionario');
        });

        Schema::dropIfExists('pregunta');
    }
};
