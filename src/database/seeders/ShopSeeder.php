<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = database_path('csv/shops.csv'); 
        $csvData = File::get($csvFile);
        $rows = explode("\n", $csvData);

        foreach ($rows as $row) {
            $data = str_getcsv($row);

            DB::table('shops')->insert([
                'user_id' => 3,
                'name' => $data[0],
                'area' => $data[1],
                'ganre' => $data[2],
                'detail' => $data[3],
                'url' => $data[4],
            ]);
        }
    }
}
