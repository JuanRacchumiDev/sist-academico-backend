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
            ],
            [
                'clase' => 1005,
                'nombre' => 'Sede',
                'nombre_url' => 'sede',
                'descripcion' => 'Sede de instalaciones',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1006,
                'nombre' => 'Universidad',
                'nombre_url' => 'universidad',
                'descripcion' => 'Universidades públicas y privadas',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1007,
                'nombre' => 'Forma pago',
                'nombre_url' => 'forma-pago',
                'descripcion' => 'Formas de pago aceptadas',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1008,
                'nombre' => 'Moneda',
                'nombre_url' => 'moneda',
                'descripcion' => 'Monedas aceptadas',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1009,
                'nombre' => 'Segmento',
                'nombre_url' => 'segmento',
                'descripcion' => 'Segmentos aceptados',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1010,
                'nombre' => 'Grupo',
                'nombre_url' => 'grupo',
                'descripcion' => 'Grupos aceptados',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1011,
                'nombre' => 'Estado Pago',
                'nombre_url' => 'estado-pago',
                'descripcion' => 'Distintos estados de pago',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1012,
                'nombre' => 'Estado Matrícula',
                'nombre_url' => 'estado-matricula',
                'descripcion' => 'Distintos estados de matricula',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1013,
                'nombre' => 'Banco Cuenta',
                'nombre_url' => 'banco-cuenta',
                'descripcion' => 'Distintas cuentas de bancos',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1014,
                'nombre' => 'Método Pago',
                'nombre_url' => 'metodo-pago',
                'descripcion' => 'Distintos métodos de pago',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
