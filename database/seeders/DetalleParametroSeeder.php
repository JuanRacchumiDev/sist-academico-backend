<?php

namespace Database\Seeders;

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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'parametro_clase' => 1002,
                'nombre' => 'CERTIFICACIÓN',
                'nombre_url' => 'certificacion',
                'descripcion' => 'CERTIFICACIÓN',
                'abreviatura' => 'C',
                'longitud' => null,
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
                'en_empresa' => false,
                'compra' => false,
                'venta' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'parametro_clase' => 1002,
                'nombre' => 'CAPACITACIÓN',
                'nombre_url' => 'capacitacion',
                'descripcion' => 'CAPACITACIÓN',
                'abreviatura' => 'CA',
                'longitud' => null,
                'en_persona' => false,
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
                'abreviatura' => 'CO',
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
                'parametro_clase' => 1003,
                'nombre' => 'CERTIFICADO DE PARTICIPACIÓN',
                'nombre_url' => 'certificado-de-participacion',
                'longitud' => null,
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'en_persona' => false,
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
                'parametro_clase' => 1004,
                'nombre' => 'IPEA',
                'nombre_url' => 'ipea',
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
                'parametro_clase' => 1004,
                'nombre' => 'INNOVAPERU',
                'nombre_url' => 'innovaperu',
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
                'parametro_clase' => 1004,
                'nombre' => 'PERUAGRO',
                'nombre_url' => 'peruagro',
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
                'parametro_clase' => 1005,
                'nombre' => 'UNIVERSIDAD NACIONAL DE TRUJILLO',
                'nombre_url' => 'universidad-nacional-de-trujillo',
                'abreviatura' => 'UNT',
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
                'parametro_clase' => 1006,
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
            ],
            [
                'parametro_clase' => 1006,
                'nombre' => 'AGRONOMÍA',
                'nombre_url' => 'agronomia',
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
                'parametro_clase' => 1007,
                'nombre' => 'GRUPO ALUMNO',
                'nombre_url' => 'grupo-alumno',
                'descripcion' => 'Grupo que almacenará a todos los alumnos',
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
                'parametro_clase' => 1007,
                'nombre' => 'GRUPO PROMOTOR',
                'nombre_url' => 'grupo-promotor',
                'descripcion' => 'Grupo que almacenará a todos los promotores',
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
                'parametro_clase' => 1007,
                'nombre' => 'GRUPO DOCENTE',
                'nombre_url' => 'grupo-docente',
                'descripcion' => 'Grupo que almacenará a todos los docentes',
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
                'parametro_clase' => 1007,
                'nombre' => 'GRUPO COBRADOR',
                'nombre_url' => 'grupo-cobrador',
                'descripcion' => 'Grupo que almacenará a todos los cobradores',
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
                'parametro_clase' => 1008,
                'nombre' => 'BN-INNOVAPERU',
                'nombre_url' => 'bn-innovaperu',
                'valor' => '1111-2222-3333',
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
                'nombre' => 'BCP-INNOVAPERU',
                'nombre_url' => 'bcp-innovaperu',
                'valor' => '4444-5555-6666',
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
                'parametro_clase' => 1010,
                'nombre' => 'Activo',
                'nombre_url' => 'activo',
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
                'parametro_clase' => 1010,
                'nombre' => 'Suspendido',
                'nombre_url' => 'suspendido',
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
                'parametro_clase' => 1010,
                'nombre' => 'Completada',
                'nombre_url' => 'completada',
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
                'parametro_clase' => 1011,
                'nombre' => 'Pendiente',
                'nombre_url' => 'pendiente',
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
                'parametro_clase' => 1011,
                'nombre' => 'Pagado',
                'nombre_url' => 'pagado',
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
                'parametro_clase' => 1011,
                'nombre' => 'Atrasado',
                'nombre_url' => 'atrasado',
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
                'parametro_clase' => 1012,
                'nombre' => 'Transferencia',
                'nombre_url' => 'transferencia',
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
                'parametro_clase' => 1012,
                'nombre' => 'Efectivo',
                'nombre_url' => 'efectivo',
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
                'parametro_clase' => 1012,
                'nombre' => 'Yape',
                'nombre_url' => 'yape',
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
                'parametro_clase' => 1012,
                'nombre' => 'Plin',
                'nombre_url' => 'plin',
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
                'parametro_clase' => 1012,
                'nombre' => 'Mixto',
                'nombre_url' => 'mixto',
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
    }
}
