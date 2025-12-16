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
        Schema::create('matricula', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_sede')->nullable();
            $table->unsignedBigInteger('id_formapago')->nullable();
            $table->unsignedBigInteger('id_estadomatricula')->nullable();
            
            $table->string('nombre_alumno', 100)->nullable();
            $table->string('nombre_sede', 60)->nullable();
            $table->string('nombre_formapago', 60)->nullable();
            $table->string('nombre_estadomatricula', 60)->nullable();

            $table->string('fecha_matricula', 10);
            $table->decimal('pago_inicial', 10, 2)->nullable();
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_alumno')
                ->references('id')
                ->on('persona');

            $table->foreign('id_sede')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_formapago')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_estadomatricula')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matricula', function(Blueprint $table) {
            $table->dropForeign('id_alumno');
            $table->dropColumn('id_alumno');

            $table->dropForeign('id_sede');
            $table->dropColumn('id_sede');

            $table->dropForeign('id_metodopago');
            $table->dropColumn('id_metodopago');
            
            $table->dropForeign('id_estadomatricula');
            $table->dropColumn('id_estadomatricula');
        });

        Schema::dropIfExists('matricula');
    }
};
