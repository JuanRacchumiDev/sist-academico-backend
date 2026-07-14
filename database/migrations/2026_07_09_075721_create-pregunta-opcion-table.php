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
        Schema::create('pregunta_opcion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pregunta');

            $table->text('texto_opcion');
            $table->boolean('es_correcta')->default(false);
            $table->integer('orden')->default(1);

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_pregunta')
                ->references('id')
                ->on('pregunta');

            // $table->foreign('id_pregunta')->references('id')->on('pregunta')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pregunta_opcion', function (Blueprint $table) {
            $table->dropForeign('id_pregunta');
            $table->dropColumn('id_pregunta');
        });

        Schema::dropIfExists('pregunta_opcion');
    }
};
