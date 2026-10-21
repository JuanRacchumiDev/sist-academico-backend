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
        Schema::table('academic.plantilla', function(Blueprint $table) {
            $table->unsignedBigInteger('id_sucursal')
                ->nullable()
                ->comment('Identificador de sucursal');

            $table->foreign('id_sucursal')
                ->references('id')
                ->on('academic.institucion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic.plantilla', function (Blueprint $table) {
            $table->dropForeign('id_sucursal');
            $table->dropColumn('id_sucursal');
        });
    }
};
