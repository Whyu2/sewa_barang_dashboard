<?php
// database/seeders/RentTransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->insert([
            [
                'name' => 'Semarang',
            ],
            [
                'name' => 'Yogyakarta',
            ],
            [
                'name' => 'Solo',
            ],
        ]);
    }
}
