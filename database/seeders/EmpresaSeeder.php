<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('empresa')->truncate();

        DB::table('empresa')->insert([
            [
                'numero_ruc' => '20603337337',
                'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
                'departamento' => 'LAMBAYEQUE',
                'provincia' => 'CHICLAYO',
                'distrito' => 'CHICLAYO',
                'origen' => 'WEB',
                'telefonos' => '042-604894,922214846,957105145',
                'horario_atencion' => 'LUNES A VIERNES: 08:00AM a 01:00PM; 02:00PM a 05:30 PM, SÁBADOS: 08:00AM a 01:00PM'
            ]
        ]);
    }
}
