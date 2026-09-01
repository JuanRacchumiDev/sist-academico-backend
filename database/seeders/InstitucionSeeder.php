<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $fechaCrea = $now->format('Y-m-d');

        DB::table('institucion')->truncate();

        DB::table('institucion')->insert([
            [
                'nombre' => 'INNOVAPERÚ',
                'sigla' => 'Aprendizaje continúo para ti',
                'ruc' => '20204040200',
                'direccion' => 'Chiclayo',
                'telefono_contacto' => '998877665',
                'logo_path' => 'logo-innovaperu.png',
                'nombre_director' => 'ALEXANDER ROBERTO FLORES GONZÁLES',
                'is_cliente' => false,
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nombre' => 'PERUAGRO',
                'sigla' => 'Aprendizaje continúo para ti',
                'ruc' => '20204040200',
                'direccion' => 'Chiclayo',
                'telefono_contacto' => '990033664',
                'logo_path' => 'logo_peruagro.png',
                'nombre_director' => 'ALEXANDER ROBERTO FLORES GONZÁLES',
                'is_cliente' => false,
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nombre' => 'IPEA',
                'sigla' => 'Aprendizaje continúo para ti',
                'ruc' => '20204040200',
                'direccion' => 'Chiclayo',
                'telefono_contacto' => '990033664',
                'logo_path' => 'logo_ipea.png',
                'nombre_director' => 'ALEXANDER ROBERTO FLORES GONZÁLES',
                'is_cliente' => false,
                'fecha_crea' => $fechaCrea,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
