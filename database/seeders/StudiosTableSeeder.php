<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudiosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('studios')->insert([
            [
                'name' => 'Studio Podcast A',
                'description' => 'Studio kedap suara untuk rekaman podcast.',
                'capacity' => 4,
                'price_per_hour' => 150000,
                'location' => 'Lantai 2 - Ruang 201',
                'assigned_manager_id' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Studio Foto B',
                'description' => 'Studio foto dengan lighting dasar.',
                'capacity' => 6,
                'price_per_hour' => 200000,
                'location' => 'Lantai 1 - Ruang 105',
                'assigned_manager_id' => 2,
                'status' => 'active',
            ],
        ]);
    }
}
