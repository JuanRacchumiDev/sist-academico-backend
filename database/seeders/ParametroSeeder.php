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
        $fechaCrea = $now->format('Y-m-d');

        DB::table('parametro')->truncate();

        DB::table('parametro')->insert([
            [
                'clase' => 1000,
                'nombre' => 'Tipo documento',
                'nombre_url' => 'tipo-documento',
                'descripcion' => 'Tipo de documentos para persona o empresa',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1001,
                'nombre' => 'Perfil',
                'nombre_url' => 'perfil',
                'descripcion' => 'Perfil de usuario',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1002,
                'nombre' => 'Tipo programa',
                'nombre_url' => 'tipo-programa',
                'descripcion' => 'Distintos tipos de programas',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1003,
                'nombre' => 'Tipo certificado',
                'nombre_url' => 'tipo-certificado',
                'descripcion' => 'Tipo de certificado',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1004,
                'nombre' => 'Sucursal',
                'nombre_url' => 'sucursal',
                'descripcion' => 'Sucursales institucionales',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1005,
                'nombre' => 'Universidad',
                'nombre_url' => 'universidad',
                'descripcion' => 'Universidades públicas y privadas',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1006,
                'nombre' => 'Segmento',
                'nombre_url' => 'segmento',
                'descripcion' => 'Segmentos aceptados',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1007,
                'nombre' => 'Grupo',
                'nombre_url' => 'grupo',
                'descripcion' => 'Grupos aceptados',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1008,
                'nombre' => 'Banco Cuenta',
                'nombre_url' => 'banco-cuenta',
                'descripcion' => 'Distintas cuentas de bancos',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1009,
                'nombre' => 'Categoría programa',
                'nombre_url' => 'categoria-programa',
                'descripcion' => 'Distintas categorías de programa',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1010,
                'nombre' => 'Estado matrícula',
                'nombre_url' => 'estado-matricula',
                'descripcion' => 'Distintos estados de matrícula',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1011,
                'nombre' => 'Estado pago',
                'nombre_url' => 'estado-pago',
                'descripcion' => 'Distintos estados de pago',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1012,
                'nombre' => 'Forma pago',
                'nombre_url' => 'forma-pago',
                'descripcion' => 'Distintas formas de pago',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'clase' => 1013,
                'nombre' => 'Sede',
                'nombre_url' => 'sede',
                'descripcion' => 'Distintos sedes',
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
