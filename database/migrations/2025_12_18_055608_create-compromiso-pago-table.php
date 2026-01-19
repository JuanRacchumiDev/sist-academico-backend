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
        Schema::create('compromiso_pago', function(Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_pago')->nullable();
            $table->unsignedBigInteger('id_modulo')->nullable();
            
            $table->string('fecha_proximo_pago', 10)->nullable();
            $table->string('fecha_vencimiento', 10)->nullable();
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_alumno')
                ->references('id')
                ->on('persona');

            $table->foreign('id_pago')
                ->references('id')
                ->on('pago');

            $table->foreign('id_modulo')
                ->references('id')
                ->on('modulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compromiso_pago', function(Blueprint $table) {
            $table->dropForeign('id_alumno');
            $table->dropColumn('id_alumno');

            $table->dropForeign('id_pago');
            $table->dropColumn('id_pago');

            $table->dropForeign('id_modulo');
            $table->dropColumn('id_modulo');
        });

        Schema::dropIfExists('compromiso_pago');
    }
};
