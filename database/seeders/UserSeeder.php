<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\DetalleParametro;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->truncate();

        $perfilAdmnin = DetalleParametro::where('parametro_clase', 1001)
            ->where('nombre_url', 'administrador')
            ->first();

        $perfilAlumno = DetalleParametro::where('parametro_clase', 1001)
            ->where('nombre_url', 'alumno')
            ->first();

        $perfilDocente = DetalleParametro::where('parametro_clase', 1001)
            ->where('nombre_url', 'docente')
            ->first();

        $passwordAdminHashedPI = Hash::make('admin123');

        $passwordAdminHashedIpede = Hash::make('ipede123');

        $passwordAlumnoHashedIpede = Hash::make("alumno123");

        $passwordDocenteHashedIpede = Hash::make("docente123");

        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@innovaperu.com',
                'password' => $passwordAdminHashedPI,
                'id_perfil' => $perfilAdmnin->codigo,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'ipede',
                'email' => 'admin@ipede.com',
                'password' => $passwordAdminHashedIpede,
                'id_perfil' => $perfilAdmnin->codigo,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'ualumno',
                'email' => 'alumno.test@gmail.com',
                'password' => $passwordAlumnoHashedIpede,
                'id_perfil' => $perfilAlumno->codigo,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
