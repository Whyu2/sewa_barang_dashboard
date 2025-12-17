<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Proyektor Epson X200',
                'category_id' => 1,
                'qty' => 2,
                'qr_code_url' => 'QR-001',
                'qr_uuid' => 'xxxxxxx',
                'photo_url' => '/images/products/proyektor.jpg',
                'status' => 'available',
                'description' => 'Proyektor untuk presentasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptop ThinkPad L14',
                'category_id' => 1,
                'qty' => 5,
                'qr_code_url' => 'QR-002',
                'qr_uuid' => 'xxxxxxx',
                'photo_url' => '/images/products/laptop.jpg',
                'status' => 'available',
                'description' => 'Laptop kantor.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
