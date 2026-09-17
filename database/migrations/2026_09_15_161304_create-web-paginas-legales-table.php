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
        Schema::create('web.pagina_legal', function (Blueprint $table) {
            $table->id();

            // Clave única del documento legal
            $table->string('titulo', 80)->unique();
            $table->string('titulo_url', 100);

            $table->longText('contenido_html');

            $table->string('version', 10)->default('1.0');

            // SEO básico para estas páginas
            $table->string('meta_title', 150)->nullable();
            $table->text('meta_description')->nullable();

            $table->boolean('is_publicado')->default(true);

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();
            $table->string('fecha_publicacion', 10)->nullable();

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
        Schema::dropIfExists('web.paginas_legales');
    }
};
