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
        Schema::create('detalle_matricula', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_matricula');
            $table->unsignedBigInteger('id_programa');

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->foreign('id_matricula')
                ->references('id')
                ->on('matricula');

            $table->foreign('id_programa')
                ->references('id')
                ->on('programa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_matricula', function(Blueprint $table) {
            $table->dropForeign('id_matricula');
            $table->dropColumn('id_matricula');

            $table->dropForeign('id_programa');
            $table->dropColumn('id_programa');
        });

        Schema::dropIfExists('detalle_matricula');
    }
};
