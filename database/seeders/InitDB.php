<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitDB extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ruta al archivo SQL
        $path = database_path('seeders/cargram.sql');

        // Lee el contenido del archivo SQL
        $sql = File::get($path);

        // Ejecuta el SQL
        DB::unprepared($sql);
    }
}
