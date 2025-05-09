<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(database_path('tests.csv'), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $values = [
                    "id" => $data['0'],
                    "title" => $data['1'],
                    "behaviour" => $data['3'],
                    "failure_severity" => $data['4'],
                    "execute_order" => $data['6'],
                    "test_function" => $data['7'],
                    "live" => $data['8'] == 't',
                ];
                if ($data['5']) $values["parent"] = $data['5'];
                if ($data['2']) $values["timeout"] = $data['2'];
                Test::create($values);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
