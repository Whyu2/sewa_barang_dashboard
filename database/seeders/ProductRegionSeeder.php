<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductRegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_regions')->insert([
            [
                'product_id' =>1,
                'region_id' => 1,
                'qty' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
                    [
                'product_id' =>1,
                'region_id' => 2,
                'qty' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' =>2,
                'region_id' => 1,
                'qty' => 10,
                'created_at' => now(),
                'updated_at' => now(), 
            ],
                  [
                'product_id' =>2,
                'region_id' => 1,
                'qty' => 10,
                'created_at' => now(),
                'updated_at' => now(), 
            ],
        ]);
    }
}
