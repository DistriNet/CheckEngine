<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(database_path('categories.csv'), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $values = [
                    "id" => $data['0'],
                    "title" => $data['2'],
                    "description" => $data['3'],
                    "execute_order" => $data['4'],
                    "live" => $data['5'] == 't',
                    "in_default" => $data['6'] == 't',
                    "short_description" => $data['7'],
                ];
                if ($data['1']) $values["parent"] = $data['1'];
                Category::create($values);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
