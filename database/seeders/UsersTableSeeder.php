<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@studiorent.test',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ],
            [
                'name' => 'Studio Manager',
                'email' => 'manager@studiorent.test',
                'password' => Hash::make('password'),
                'role_id' => 2,
            ],
            [
                'name' => 'User Penyewa',
                'email' => 'user@studiorent.test',
                'password' => Hash::make('password'),
                'role_id' => 3,
            ],
        ]);
    }
}
