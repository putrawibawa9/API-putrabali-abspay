<?php

namespace Database\Seeders;

use App\Models\FamilyMember;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FamilyMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $data = [
    ['name' => 'Bapak', 'role' => 'Ayah', 'daily_salary' => 113333.33],
    ['name' => 'Ibu',   'role' => 'Ibu',  'daily_salary' => 83333.33],
    ['name' => 'Putra',  'role' => 'putra','daily_salary' => 50000.00],
];
        foreach ($data as $item) {
            FamilyMember::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
