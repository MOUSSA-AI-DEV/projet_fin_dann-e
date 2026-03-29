<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fileToTable = [
            'marques.csv' => 'marques',
            'categories.csv' => 'categories',
            'pieces.csv' => 'pieces',
            'users.csv' => 'users',
            'commandes.csv' => 'commandes',
            'references.csv' => 'references',
            'voitures.csv' => 'voitures',
            'reference_voiture.csv' => 'reference_voiture',
            'commande_references.csv' => 'commande_references',
        ];

        foreach($fileToTable as $file => $tableName) {
            $path = database_path("data/{$file}");
            if (!file_exists($path)) continue;

            $handle = fopen($path, "r");
            if ($handle !== FALSE) {
                $header = fgetcsv($handle, 1000, ",");
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($header) !== count($row)) continue;

                    $sanitizedRow = array_map(function($val) {
                        return (strcasecmp($val, 'NULL') === 0) ? null : $val;
                    }, $row);

                    $data = array_combine($header, $sanitizedRow);
                    if (isset($data['id'])) {
                        $id = $data['id'];
                        unset($data['id']);
                        DB::table($tableName)->updateOrInsert(['id' => $id], $data);
                    } else {
                        DB::table($tableName)->insert($data);
                    }
                }
                fclose($handle);
            }
        }
    }
}
