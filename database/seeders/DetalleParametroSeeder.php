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
                'abreviatura' => 'C',
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
                'abreviatura' => 'E',
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
                'nombre' => 'DIPLOMADO',
                'nombre_url' => 'diplomado',
                'descripcion' => 'DIPLOMADO',
                'abreviatura' => 'D',
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
                'parametro_clase' => 1003,
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
                'parametro_clase' => 1003,
                'nombre' => 'CERTIFICADO DE AGRADECIMIENTO',
                'nombre_url' => 'certificado-de-agradecimiento',
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
                'nombre' => 'CONSTANCIA DE ESTUDIOS',
                'nombre_url' => 'constancia-de-estudios',
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
                'nombre' => 'CERTIFICADO',
                'nombre_url' => 'certificado',
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
                'nombre' => 'MOYOBAMBA',
                'nombre_url' => 'moyobamba',
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
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1006,
                'nombre' => 'PAGO TOTAL',
                'nombre_url' => 'pago-total',
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
                'nombre' => 'PAGO PARCIAL',
                'nombre_url' => 'pago-parcial',
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
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1008,
                'nombre' => 'EDUCACIÓN',
                'nombre_url' => 'educacion',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'SALUD',
                'nombre_url' => 'salud',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'PSICOLOGÍA',
                'nombre_url' => 'psicologia',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'DERECHO',
                'nombre_url' => 'derecho',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'AGROPECUARIA',
                'nombre_url' => 'agropecuaria',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'INGENIERÍA',
                'nombre_url' => 'ingenieria',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1008,
                'nombre' => 'EMPRESARIALES',
                'nombre_url' => 'empresariales',
                'abreviatura' => null,
                'longitud' => null,
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1009,
                'nombre' => 'GRUPO ALUMNO',
                'nombre_url' => 'grupo-alumno',
                'descripcion' => 'Grupo que almacenará a todos los alumnos',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1009,
                'nombre' => 'GRUPO PROMOTOR',
                'nombre_url' => 'grupo-promotor',
                'descripcion' => 'Grupo que almacenará a todos los promotores',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1009,
                'nombre' => 'GRUPO DOCENTE',
                'nombre_url' => 'grupo-docente',
                'descripcion' => 'Grupo que almacenará a todos los docentes',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1009,
                'nombre' => 'GRUPO COBRADOR',
                'nombre_url' => 'grupo-cobrador',
                'descripcion' => 'Grupo que almacenará a todos los cobradores',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1010,
                'nombre' => 'PENDIENTE DE PAGO',
                'nombre_url' => 'pendiente-de-pago',
                'descripcion' => 'Estado pendiente de pago',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'PAGO PARCIAL',
                'nombre_url' => 'pago-parcial',
                'descripcion' => 'Estado pago parcial',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'PAGO TOTAL',
                'nombre_url' => 'pago-total',
                'descripcion' => 'Estado pago total',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'PENDIENTE DE VALIDACIÓN',
                'nombre_url' => 'pendiente-de-validacion',
                'descripcion' => 'Estado pendiente de validación',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'CONFIRMADO',
                'nombre_url' => 'confirmado',
                'descripcion' => 'Estado confirmado de pago',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1010,
                'nombre' => 'ANULADO',
                'nombre_url' => 'anulado',
                'descripcion' => 'Estado pago anulado',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1011,
                'nombre' => 'PRE MATRICULADO',
                'nombre_url' => 'pre-matricula',
                'descripcion' => 'Estado pre inscripción',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'MATRICULADO',
                'nombre_url' => 'matriculado',
                'descripcion' => 'Estado matriculado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'RESERVADA',
                'nombre_url' => 'reservada',
                'descripcion' => 'Estado matriculado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'RETIRADO',
                'nombre_url' => 'retirado',
                'descripcion' => 'Estado retirado',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1011,
                'nombre' => 'GRADUADO',
                'nombre_url' => 'graduado',
                'descripcion' => 'Estado graduado',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1012,
                'nombre' => 'BN-CAPACITA',
                'nombre_url' => 'bn-capacita',
                'valor' => '1111-2222-3333',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1012,
                'nombre' => 'BCP-CAPACITA',
                'nombre_url' => 'bcp-capacita',
                'valor' => '4444-5555-6666',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('detalle_parametro')->insert([
            [
                'parametro_clase' => 1013,
                'nombre' => 'EFECTIVO',
                'nombre_url' => 'efectivo',
                'descripcion' => 'Forma de pago efectivo',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'TARJETA DE DÉBITO',
                'nombre_url' => 'tarjeta-de-debito',
                'descripcion' => 'Forma de pago tarjeta de débito',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'TARJETA DE CRÉDITO',
                'nombre_url' => 'tarjeta-de-credito',
                'descripcion' => 'Forma de pago tarjeta de crédito',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'DEPÓSITO BANCARIO',
                'nombre_url' => 'deposito-bancario',
                'descripcion' => 'Forma de pago depósito bancario',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'TRANSFERENCIA',
                'nombre_url' => 'transferencia',
                'descripcion' => 'Forma de pago transferencia',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'YAPE',
                'nombre_url' => 'yape',
                'descripcion' => 'Forma de pago yape',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'PLIN',
                'nombre_url' => 'plin',
                'descripcion' => 'Forma de pago plin',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'NIUBIZ',
                'nombre_url' => 'niubiz',
                'descripcion' => 'Forma de pago niubiz',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1013,
                'nombre' => 'REFERIDOS',
                'nombre_url' => 'referidos',
                'descripcion' => 'Forma de pago referidos',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
