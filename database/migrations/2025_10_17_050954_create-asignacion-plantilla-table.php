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
        Schema::create('asignacion_plantilla', function (Blueprint $table) {
            $table->id();

            // Clave foránea con la tabla 'plantilla'
            $table->foreignId('id_plantilla')
                ->constrained('plantilla');

            // Clave foránea con la tabla 'programa'
            $table->foreignId('id_programa')
                ->constrained('programa');

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();

            $table->timestamps();

            $table->boolean('estado')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignacion_plantilla', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_plantilla');
            $table->dropConstrainedForeignId('id_programa');
        });

        Schema::dropIfExists('asignacion_plantilla');
    }
};
