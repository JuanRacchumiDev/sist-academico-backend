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
        Schema::create('certificado', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_tipocertificado');
            $table->unsignedBigInteger('id_plantilla')->nullable();
            $table->unsignedBigInteger('id_programa')->nullable();
            $table->unsignedBigInteger('id_institucion')->nullable();

            $table->string('codigo_verificacion', 12)->nullable();
            $table->string('codigo_qr_path', 350)->nullable();
            $table->string('path_file', 350);
            $table->string('filename', 150);
            $table->string('nombre_impresion', 150);
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            
            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id')
                ->on('persona');

            $table->foreign('id_tipocertificado')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_plantilla')
                ->references('id')
                ->on('plantilla');

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
        Schema::table('certificado', function(Blueprint $table) {
            $table->dropForeign('id_persona');
            $table->dropColumn('id_persona');

            $table->dropForeign('id_tipocertificado');
            $table->dropColumn('id_tipocertificado');

            $table->dropForeign('id_plantilla');
            $table->dropColumn('id_plantilla');

            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_institucion');
            $table->dropColumn('id_institucion');
        });

        Schema::dropIfExists('certificado');
    }
};
