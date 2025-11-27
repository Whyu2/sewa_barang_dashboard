<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Elektronik',
                'description' => 'Peralatan elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alat Kantor',
                'description' => 'Peralatan kantor umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
