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
        Schema::create('cronograma_pago', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_matricula');
            $table->unsignedBigInteger('id_estadopago');

            $table->integer('numero_cuota');
            $table->string('fecha_notificacion');
            $table->string('fecha_pago');
            $table->decimal('monto_cuota');
            $table->decimal('monto_mora', 10, 2)->nullable();
            $table->decimal('monto_pagado', 10, 2)->nullable();
            $table->decimal('monto_pendiente', 10, 2)->nullable();

            $table->foreign('id_matricula')
                ->references('id')
                ->on('matricula');

            $table->foreign('id_estadopago')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cronograma_pago', function(Blueprint $table) {
            $table->dropForeign('id_matricula');
            $table->dropColumn('id_matricula');

            $table->dropForeign('id_estadopago');
            $table->dropColumn('id_estadopago');
        });

        Schema::dropIfExists('cronograma_pago');
    }
};
