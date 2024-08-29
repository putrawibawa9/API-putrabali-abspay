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
            'alias' => 'mr',
        ]);
        Teacher::create([
            'name' => 'Sari Puspita',
            'alias' => 'sr',
        ]);
        Teacher::create([
            'name' => 'Anggreni',
            'alias' => 'ang',
        ]);
        Teacher::create([
            'name' => 'Wulan',
            'alias' => 'wl',
        ]);
        Teacher::create([
            'name' => 'Icitha Dewi',
            'alias' => 'id',
        ]);
        Teacher::create([
            'name' => 'Nonik',
            'alias' => 'nnk',
        ]);
    
    }
}
