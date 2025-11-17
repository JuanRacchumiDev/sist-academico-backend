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
        Schema::create('detalle_parametro', function(Blueprint $table) {
            $table->id('codigo');
            $table->integer('parametro_clase');
            $table->string('nombre', 100);
            $table->string('nombre_url', 120);
            $table->string('descripcion', 100)->nullable();
            $table->string('valor', 20)->nullable();
            $table->string('abreviatura', 10)->nullable();
            $table->integer('longitud')->nullable();
            $table->boolean('en_persona')->default(false);
            $table->boolean('en_empresa')->default(false);
            $table->boolean('compra')->default(false);
            $table->boolean('venta')->default(false);
            $table->boolean('visible')->default(false);
            $table->string('user_crea', 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('parametro_clase')
                ->references('clase')
                ->on('parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_parametro');
    }
};
