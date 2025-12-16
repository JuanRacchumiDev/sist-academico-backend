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
        Schema::create('pago', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_matricula');
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_formapago');
            $table->unsignedBigInteger('id_metodopago');
            $table->unsignedBigInteger('id_estadopago');

            $table->string('concepto', 150);
            $table->string('fecha_pago', 10);
            $table->string('nro_operacion', 30)->nullable();
            $table->string('fecha_proximo_pago')->nullable();
            $table->string('fecha_compromiso_pago')->nullable();
            $table->integer('nro_cuota')->nullable();
            $table->decimal('monto_efectivo', 10, 2)->default(0);
            $table->decimal('monto_tarjeta', 10, 2)->default(0);
            $table->decimal('monto_total', 10, 2);
            $table->decimal('monto_pagado', 10, 2);
            $table->decimal('monto_saldo', 10, 2)->default(0);
            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_matricula')
                ->references('id')
                ->on('matricula');

            $table->foreign('id_alumno')
                ->references('id')
                ->on('persona');

            $table->foreign('id_formapago')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_metodopago')
                ->references('codigo')
                ->on('detalle_parametro');

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
        Schema::table('pago', function(Blueprint $table) {
            $table->dropForeign('id_matricula');
            $table->dropColumn('id_matricula');

            $table->dropForeign('id_alumno');
            $table->dropColumn('id_alumno');

            $table->dropForeign('id_formapago');
            $table->dropColumn('id_formapago');

            $table->dropForeign('id_metodopago');
            $table->dropColumn('id_metodopago');

            $table->dropForeign('id_estadopago');
            $table->dropColumn('id_estadopago');
        });

        Schema::dropIfExists('pago');
    }
};
