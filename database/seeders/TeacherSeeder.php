<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::create([
    'name' => 'Meirawati',
    'username' => 'username1',
    'password' => bcrypt('password'),
    'alias' => 'mr',
]);

Teacher::create([
    'name' => 'Budi Santoso',
     'username' => 'username2',
    'password' => bcrypt('password'),
    'alias' => 'bs',
]);

Teacher::create([
    'name' => 'Siti Aminah',
       'username' => 'username3',
    'password' => bcrypt('password'),
    'alias' => 'sa',
]);

Teacher::create([
    'name' => 'Ahmad Fauzi',
      'username' => 'username9',
    'password' => bcrypt('password'),
    'alias' => 'af',
]);

Teacher::create([
    'name' => 'Dewi Kartika',
   'username' => 'username4',
    'password' => bcrypt('password'),
    'alias' => 'dk',
]);

Teacher::create([
    'name' => 'Rama Wijaya',
      'username' => 'username5',
    'password' => bcrypt('password'),
    'alias' => 'rw',
]);

Teacher::create([
    'name' => 'Nurhayati',
       'username' => 'username6',
    'password' => bcrypt('password'),
    'alias' => 'nh',
]);

Teacher::create([
    'name' => 'Joko Prasetyo',
   'username' => 'username7',
    'password' => bcrypt('password'),
    'alias' => 'jp',
]);

    }
}
