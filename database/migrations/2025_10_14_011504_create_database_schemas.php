<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS academic;');
        DB::statement('CREATE SCHEMA IF NOT EXISTS web;');

        // Schema::create('database_schemas', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS web CASCADE;');
        DB::statement('DROP SCHEMA IF EXISTS academic CASCADE;');

        // Schema::dropIfExists('database_schemas');
    }
};
