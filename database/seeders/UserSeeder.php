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

        $passwordHashed = Hash::make('admin123');

        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@peruinnova.com',
                'password' => $passwordHashed,
                'id_perfil' => $perfilAdmnin->codigo,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
