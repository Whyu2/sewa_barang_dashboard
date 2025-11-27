<?php
// database/seeders/ProductLogSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_logs')->insert([
            [
                'product_id' => 1,
                'action' => 'created',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 1,
                'action' => 'rented',
                'user_id' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }
}
