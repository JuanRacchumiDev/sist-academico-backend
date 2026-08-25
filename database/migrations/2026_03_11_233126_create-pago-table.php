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
        Schema::create('pago', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_matricula');
            $table->unsignedBigInteger('id_modulo')->nullable();
            $table->unsignedBigInteger('codigo_estadopago')->nullable();
            $table->unsignedBigInteger('codigo_formapago');
            $table->unsignedBigInteger('id_sucursal');

            $table->string('concepto', 200);
            $table->integer('numero_modulo')->nullable();
            $table->string('numero_operacion', 20)->nullable();
            $table->string('fecha_pago', 10)->nullable();
            $table->string('fecha_vencimiento', 10)->nullable();
            $table->decimal('cantidad_efectivo', 10, 2)->nullable();
            $table->decimal('cantidad_operacion', 10, 2)->nullable();

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_matricula')
                ->references('id')
                ->on('matricula');

            $table->foreign('id_modulo')
                ->references('id')
                ->on('modulo');

            $table->foreign('codigo_estadopago')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('codigo_formapago')
                ->references('codigo')
                ->on('detalle_parametro');

            $table->foreign('id_sucursal')
                ->references('codigo')
                ->on('detalle_parametro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->dropForeign('id_matricula');
            $table->dropColumn('id_matricula');

            $table->dropForeign('id_modulo');
            $table->dropColumn('id_modulo');

            $table->dropForeign('codigo_estadopago');
            $table->dropColumn('codigo_estadopago');

            $table->dropForeign('codigo_formapago');
            $table->dropColumn('codigo_formapago');

            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });

        Schema::dropIfExists('pago');
    }
};
