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
        Schema::create('persona_programa', function(Blueprint $table) {
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_programa');
            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id')
                ->on('persona');

            $table->foreign('id_programa')
                ->references('id')
                ->on('programa');

            $table->primary(['id_persona', 'id_programa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_programa');
    }
};
