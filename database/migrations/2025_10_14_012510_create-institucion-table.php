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
        Schema::create('institucion', function(Blueprint $table) {
            $table->id();

            $table->string('nombre', 60);
            $table->string('sigla', 100)->nullable();
            $table->string('ruc', 13)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('logo_path', 150)->nullable();
            $table->string('firma_digital')->nullable();
            $table->string('color_primario')->nullable();

            $table->string('user_crea', 12)->nullable();
            $table->string('user_actualiza', 12)->nullable();
            $table->string('user_elimina', 12)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion');
    }
};
