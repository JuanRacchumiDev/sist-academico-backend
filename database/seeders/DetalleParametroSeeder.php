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
                'descripcion' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
                'abreviatura' => 'DNI',
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
                'descripcion' => 'CARNÉ DE EXTRANJERÍA',
                'abreviatura' => 'CE',
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
                'nombre' => 'ADMINISTRADOR',
                'nombre_url' => 'administrador',
                'descripcion' => 'ADMINISTRADOR',
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
                'nombre' => 'ALUMNO',
                'nombre_url' => 'alumno',
                'descripcion' => 'ALUMNO',
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
                'nombre' => 'DOCENTE',
                'nombre_url' => 'docente',
                'descripcion' => 'DOCENTE',
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
                'nombre' => 'ASESOR',
                'nombre_url' => 'asesor',
                'descripcion' => 'ASESOR',
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
                'nombre' => 'PROMOTOR',
                'nombre_url' => 'promotor',
                'descripcion' => 'PROMOTOR',
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
                'nombre' => 'COBRADOR',
                'nombre_url' => 'cobrador',
                'descripcion' => 'COBRADOR',
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
                'nombre' => 'CERTIFICACIÓN',
                'nombre_url' => 'certificacion',
                'descripcion' => 'CERTIFICACIÓN',
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
                'nombre' => 'ESPECIALIZACIÓN',
                'nombre_url' => 'especializacion',
                'descripcion' => 'ESPECIALIZACIÓN',
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
                'nombre' => 'CURSO',
                'nombre_url' => 'curso',
                'descripcion' => 'CURSO',
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
                'nombre' => 'TALLER',
                'nombre_url' => 'taller',
                'descripcion' => 'TALLER',
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
                'nombre' => 'CONFERENCIA',
                'nombre_url' => 'conferencia',
                'descripcion' => 'CONFERENCIA',
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
                'nombre' => 'TECNOLOGÍA',
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
                'parametro_clase' => 1003,
                'nombre' => 'NEGOCIOS',
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
                'parametro_clase' => 1003,
                'nombre' => 'SALUD',
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
                'parametro_clase' => 1003,
                'nombre' => 'EDUCACIÓN',
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
                'parametro_clase' => 1003,
                'nombre' => 'INGENIERÍA',
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
                'parametro_clase' => 1003,
                'nombre' => 'CIENCIAS',
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
                'nombre' => 'CERTIFICADO DE PARTICIPACIÓN',
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
                'nombre' => 'CERTIFICADO DE APROBACIÓN',
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
                'nombre' => 'DIPLOMA DE ESPECIALIZACIÓN',
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
                'nombre' => 'CERTIFICADO DE AGRADECIMIENTO',
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

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1005,
                'nombre' => 'MOYOBAMBA',
                'nombre_url' => 'moyobamba',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1005,
                'nombre' => 'CHICLAYO',
                'nombre_url' => 'chiclayo',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1005,
                'nombre' => 'LAMBAYEQUE',
                'nombre_url' => 'lambayeque',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1005,
                'nombre' => 'RIOJA',
                'nombre_url' => 'rioja',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1005,
                'nombre' => 'LIMA',
                'nombre_url' => 'lima',
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
                'parametro_clase' => 1006,
                'nombre' => 'UNIVERSIDAD NACIONAL PEDRO RUIZ GALLO',
                'nombre_url' => 'universidad-nacional-pedro-ruiz-gallo',
                'abreviatura' => 'UNPRG',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1006,
                'nombre' => 'UNIVERSIDAD NACIONAL DE TRUJILLO',
                'nombre_url' => 'universidad-nacional-de-trujillo',
                'abreviatura' => 'UNT',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1006,
                'nombre' => 'UNIVERSIDAD NACIONAL DE CAJAMARCA',
                'nombre_url' => 'universidad-nacional-de-cajamarca',
                'abreviatura' => 'UNC',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1006,
                'nombre' => 'UNIVERSIDAD NACIONAL MAYOR DE SAN MARCOS',
                'nombre_url' => 'universidad-nacional-mayor-de-san-marcos',
                'abreviatura' => 'UNMSM',
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
                'parametro_clase' => 1007,
                'nombre' => 'EFECTIVO',
                'nombre_url' => 'efectivo',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'DEPÓSITO',
                'nombre_url' => 'deposito',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'TRANSFERENCIA',
                'nombre_url' => 'transferencia',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'CHEQUE',
                'nombre_url' => 'cheque',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'PLANILLA',
                'nombre_url' => 'planilla',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'YAPE',
                'nombre_url' => 'yape',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'REFERIDOS',
                'nombre_url' => 'referidos',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'PLIN',
                'nombre_url' => 'plin',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1007,
                'nombre' => 'NIUBIZ',
                'nombre_url' => 'niubiz',
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
                'parametro_clase' => 1008,
                'nombre' => 'SOLES',
                'nombre_url' => 'soles',
                'abreviatura' => 'S/.',
                'longitud' => null,
                'en_persona' => true,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'DÓLARES',
                'nombre_url' => 'dolares',
                'abreviatura' => '$/.',
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
                'parametro_clase' => 1010,
                'nombre' => 'GRUPO ALUMNO',
                'nombre_url' => 'grupo-alumno',
                'descripcion' => 'Grupo que almacenará a todos los alumnos',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'GRUPO PROMOTOR',
                'nombre_url' => 'grupo-promotor',
                'descripcion' => 'Grupo que almacenará a todos los promotores',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'GRUPO DOCENTE',
                'nombre_url' => 'grupo-docente',
                'descripcion' => 'Grupo que almacenará a todos los docentes',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'GRUPO COBRADOR',
                'nombre_url' => 'grupo-cobrador',
                'descripcion' => 'Grupo que almacenará a todos los cobradores',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1011,
                'nombre' => 'PENDIENTE DE PAGO',
                'nombre_url' => 'pendiente-de-pago',
                'descripcion' => 'Estado pendiente de pago',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'PAGO PARCIAL',
                'nombre_url' => 'pago-parcial',
                'descripcion' => 'Estado pago parcial',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'PAGO TOTAL',
                'nombre_url' => 'pago-total',
                'descripcion' => 'Estado pago total',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'PENDIENTE DE VALIDACIÓN',
                'nombre_url' => 'pendiente-de-validacion',
                'descripcion' => 'Estado pendiente de validación',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'CONFIRMADO',
                'nombre_url' => 'confirmado',
                'descripcion' => 'Estado confirmado de pago',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'ANULADO',
                'nombre_url' => 'anulado',
                'descripcion' => 'Estado pago anulado',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1012,
                'nombre' => 'PRE MATRICULADO',
                'nombre_url' => 'pre-matricula',
                'descripcion' => 'Estado pre inscripción',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1012,
                'nombre' => 'MATRICULADO',
                'nombre_url' => 'matriculado',
                'descripcion' => 'Estado matriculado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1012,
                'nombre' => 'RESERVADA',
                'nombre_url' => 'reservada',
                'descripcion' => 'Estado matriculado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1012,
                'nombre' => 'RETIRADO',
                'nombre_url' => 'retirado',
                'descripcion' => 'Estado retirado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1012,
                'nombre' => 'GRADUADO',
                'nombre_url' => 'graduado',
                'descripcion' => 'Estado graduado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1014,
                'nombre' => 'EFECTIVO',
                'nombre_url' => 'efectivo',
                'descripcion' => 'Forma de pago efectivo',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1014,
                'nombre' => 'TARJETA',
                'nombre_url' => 'tarjeta',
                'descripcion' => 'Forma de pago tarjeta',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1014,
                'nombre' => 'MIXTA',
                'nombre_url' => 'mixta',
                'descripcion' => 'Forma de pago mixta',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
