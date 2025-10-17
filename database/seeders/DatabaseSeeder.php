<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Deshabilita verificación de claves foráneas
        Schema::disableForeignKeyConstraints();
        
        $this->call([
            ParametroSeeder::class,
            DetalleParametroSeeder::class,
            UserSeeder::class
        ]);

        // Habilita verificación de claves foráneas
        Schema::enableForeignKeyConstraints();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
