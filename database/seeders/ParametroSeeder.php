<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('parametro')->truncate();
        
        DB::table('parametro')->insert([
            [
                'clase' => 1000,
                'nombre' => 'Tipo documento',
                'nombre_url' => 'tipo-documento',
                'descripcion' => 'Tipo de documentos para persona o empresa',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1001,
                'nombre' => 'Perfil',
                'nombre_url' => 'perfil',
                'descripcion' => 'Perfil de usuario',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1002,
                'nombre' => 'Tipo evento',
                'nombre_url' => 'tipo-evento',
                'descripcion' => 'Tipo de evento',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1003,
                'nombre' => 'Categoría evento',
                'nombre_url' => 'categoria-evento',
                'descripcion' => 'Categoría de evento',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1004,
                'nombre' => 'Tipo certificado',
                'nombre_url' => 'tipo-certificado',
                'descripcion' => 'Tipo de certificado',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
