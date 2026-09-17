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
        Schema::create('academic.adjunto', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_programa')->nullable();
            $table->unsignedBigInteger('id_modulo')->nullable();
            $table->unsignedBigInteger('id_sucursal')->nullable();

            $table->enum('tipo', ['FILE', 'URL', 'YOUTUBE', 'DRIVE'])->default('FILE');
            $table->string('titulo', 150);
            $table->string('titulo_url', 180);
            $table->string('descripcion', 250)->nullable();
            $table->text('url')->nullable();
            $table->string('filename', 120)->nullable();
            $table->string('originalname', 180)->nullable();
            $table->string('filepath', 150)->nullable();
            $table->string('mimetype', 200)->nullable();
            $table->unsignedBigInteger('size')->nullable();

            $table->boolean('is_descargable')->default(true);
            $table->boolean('is_visible')->default(true);

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->boolean('sistema')->default(false);
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_programa')
                ->references('id')
                ->on('academic.programa')
                ->onDelete('cascade');

            $table->foreign('id_modulo')
                ->references('id')
                ->on('academic.modulo')
                ->onDelete('cascade');

            $table->foreign('id_sucursal')
                ->references('codigo')
                ->on('academic.detalle_parametro')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic.adjunto', function (Blueprint $table) {
            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');

            $table->dropForeign('id_modulo');
            $table->dropColumn('id_modulo');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });

        Schema::dropIfExists('academic.adjunto');
    }
};
