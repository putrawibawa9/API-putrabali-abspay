<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            User::create([
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => bcrypt('password'),
            ]);

            User::create([
    'name' => 'Jane Smith',
    'email' => 'janesmith@gmail.com',
    'password' => bcrypt('password'),
]);

User::create([
    'name' => 'Robert Brown',
    'email' => 'robertbrown@gmail.com',
    'password' => bcrypt('password'),
]);

User::create([
    'name' => 'Alice Johnson',
    'email' => 'alicejohnson@gmail.com',
    'password' => bcrypt('password'),
]);

            
    }
}
