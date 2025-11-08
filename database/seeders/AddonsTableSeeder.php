<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('addons')->insert([
            [
                'name' => 'Mic Condenser',
                'description' => 'Mikrofon untuk rekaman podcast.',
                'price' => 50000,
            ],
            [
                'name' => 'Kamera DSLR',
                'description' => 'Kamera untuk sesi foto/video.',
                'price' => 100000,
            ],
            [
                'name' => 'Softbox Lighting',
                'description' => 'Pencahayaan tambahan.',
                'price' => 75000,
            ],
        ]);
    }
}
