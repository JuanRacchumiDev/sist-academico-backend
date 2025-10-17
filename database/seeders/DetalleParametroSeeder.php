<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DetalleParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('detalle_parametro')->truncate();

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1000,
                'nombre' => 'DNI',
                'nombre_url' => 'dni',
                'descripcion' => 'Documento nacional de identidad',
                'longitud' => 8,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1000,
                'nombre' => 'CARNÉ EXT.',
                'nombre_url' => 'carne-ext',
                'descripcion' => 'Carné de extranjería',
                'longitud' => 12,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1001,
                'nombre' => 'Administrador',
                'nombre_url' => 'administrador',
                'descripcion' => 'Administrador',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1001,
                'nombre' => 'Alumno',
                'nombre_url' => 'alumno',
                'descripcion' => 'Alumno',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1001,
                'nombre' => 'Docente',
                'nombre_url' => 'docente',
                'descripcion' => 'Docente',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1002,
                'nombre' => 'Certificación',
                'nombre_url' => 'certificacion',
                'descripcion' => 'Certificación',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Especialización',
                'nombre_url' => 'especializacion',
                'descripcion' => 'Especialización',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Curso',
                'nombre_url' => 'curso',
                'descripcion' => 'Curso',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Taller',
                'nombre_url' => 'taller',
                'descripcion' => 'Taller',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Conferencia',
                'nombre_url' => 'conferencia',
                'descripcion' => 'Conferencia',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1003,
                'nombre' => 'Tecnología',
                'nombre_url' => 'tecnologia',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Negocios',
                'nombre_url' => 'negocios',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Salud',
                'nombre_url' => 'salud',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Educación',
                'nombre_url' => 'educacion',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Ingeniería',
                'nombre_url' => 'ingenieria',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'Ciencias',
                'nombre_url' => 'ciencias',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1004,
                'nombre' => 'Certificado de participación',
                'nombre_url' => 'certificado-de-participacion',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1004,
                'nombre' => 'Certificado de aprobación',
                'nombre_url' => 'certificado-de-aprobacion',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1004,
                'nombre' => 'Diploma de especialización',
                'nombre_url' => 'diploma-de-especializacion',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1004,
                'nombre' => 'Certificado de agradecimiento',
                'nombre_url' => 'certificado-de-agradecimiento',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
