<?php
// database/seeders/RentTransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RentTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rent_transactions')->insert([
            [
                'product_id' => 1,
                'region_id' => 1,
                'renter_name' => 'Budi Santoso',
                'renter_phone' => '08123456789',
                'rent_date' => now()->subDays(2),
                'rent_price' => 100000,
                'expected_return_date' => now()->addDays(1),
                'qty' => 1,
                'return_date' => null,
                'status' => 'rented',
                'notes' => 'Dipinjam untuk presentasi.',
                'pickup_proof_url' => '/images/proof/pickup1.jpg',
                'return_proof_url' => null,
            ],
        ]);
    }
}
