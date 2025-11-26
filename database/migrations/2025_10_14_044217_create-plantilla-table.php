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
        Schema::create('plantilla', function(Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('descripcion', 150)->nullable();
            $table->string('imagen', 150)->nullable();
            $table->string('path', 100);
            $table->string('user_crea', 10)->nullable();
            $table->string('user_actualiza', 10)->nullable();
            $table->string('user_elimina', 10)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla');
    }
};
