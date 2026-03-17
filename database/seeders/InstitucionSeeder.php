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

        DB::table('institucion')->truncate();

        DB::table('institucion')->insert([
            [
                'nombre' => 'peruinnova',
                'sigla' => 'Aprendizaje continúo para ti',
                'ruc' => '20204040200',
                'ubicacion' => 'Chiclayo',
                'logo_path' => 'logo_peruinnova.png',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nombre' => 'peruagro',
                'sigla' => 'Aprendizaje continúo para ti',
                'ruc' => '20204040200',
                'ubicacion' => 'Chiclayo',
                'logo_path' => 'logo_peruagro.png',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
