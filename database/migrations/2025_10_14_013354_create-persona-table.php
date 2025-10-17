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
        Schema::create('persona', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipodocumento');
            $table->string('numero_documento', 13);
            $table->string('nombres', 30);
            $table->string('apellido_paterno', 30);
            $table->string('apellido_materno', 30);
            $table->string('departamento', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('distrito', 50)->nullable();
            $table->string('direccion', 60)->nullable();
            $table->string('direccion_completa', 150)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('telefono', 13)->nullable();
            $table->string('ubigeo_reniec', 12)->nullable();
            $table->string('ubigeo_sunat', 12)->nullable();
            $table->string('ubigeo', 12)->nullable();
            $table->string('fecha_nacimiento', 10);
            $table->string('estado_civil', 20)->nullable();
            $table->string('foto', 100)->nullable();
            $table->enum('sexo', ['M', 'F'])->default('M');
            $table->enum('origen', ['API', 'WEB', 'APP'])->default('API');
            $table->string("user_crea", 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_tipodocumento')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function(Blueprint $table) {
            $table->dropForeign('id_tipodocumento');
            $table->dropColumn('id_tipodocumento');
        });

        Schema::dropIfExists('persona');
    }
};
