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
        Schema::table('academic.adjunto', function(Blueprint $table) {
            $table->string('disk', 50)
                ->nullable()
                ->comment('Disco de Filesystem');

            $table->string('bucket', 100)
                ->nullable()
                ->comment('Nombre del bucket o contenedor en la nube');

            $table->string('region', 50)
                ->nullable()
                ->comment('Región del proveedor de almacenamiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic.adjunto', function(Blueprint $table) {
            $table->dropColumn(['disk', 'bucket', 'region']);
        });
    }
};
