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
        Schema::create('web.configuracion', function (Blueprint $table) {
            $table->id();

            // Identificador único de la configuración (ej: whatsapp_ventas, facebook_url, etc.)
            $table->string('clave', 60)->unique();
            $table->string('clave_slug', 80);

            // Valor de la configuración
            $table->text('value')->nullable();

            // Agrupador para la interfaz de administración (ej: REDES_SOCIALES, CONTACTO, INSTITUCIONAL)
            $table->string('grupo', 50)->default('GENERAL');

            // Etiqueta descriptiva para el panel administrativo (ej: Whatsapp de Soporte)
            $table->string('etiqueta', 100);

            // Tipo de input para renderizar en el panel de control (text, textarea, number, url, image)
            $table->string('tipo_input', 30)->default('text');

            $table->string('fecha_crea', 10)->nullable();
            $table->string('fecha_actualiza', 10)->nullable();
            $table->string('fecha_elimina', 10)->nullable();

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
        Schema::dropIfExists('web.configuracion');
    }
};
