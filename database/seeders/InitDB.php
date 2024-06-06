<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
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
        $path = database_path('seeders/CargramLlenito.sql');

        // Lee el contenido del archivo SQL
        $sql = File::get($path);

        // Divide el SQL en consultas individuales
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        // Ejecuta cada consulta individualmente
        foreach ($queries as $query) {
            if (!empty($query)) {
                DB::statement($query);
            }
        }
    }
}
