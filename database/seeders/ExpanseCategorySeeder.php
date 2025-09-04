<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpanseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => 'K001', 'description' => 'Biaya honor PTK'],
            ['code' => 'K002', 'description' => 'Biaya Operasional Lembaga'],
            ['code' => 'K003', 'description' => 'Biaya Pembelian Media Pembelajaran'],
            ['code' => 'K004', 'description' => 'Biaya Pengembangan Usaha'],
            ['code' => 'K005', 'description' => 'Biaya Renovasi Ruangan'],
            ['code' => 'K006', 'description' => 'Biaya Pemeliharaan'],
            ['code' => 'K007', 'description' => 'Biaya Investasi Sarana'],
            ['code' => 'K008', 'description' => 'Biaya Investasi Prasarana'],
            ['code' => 'K009', 'description' => 'Biaya Investasi SDM'],
            ['code' => 'K010', 'description' => 'Biaya Iuran BPJS Kesehatan'],
            ['code' => 'K011', 'description' => 'Biaya BPJS Ketenagakerjaan'],
            ['code' => 'K012', 'description' => 'Biaya Recruitment dan promosi'],
            ['code' => 'K013', 'description' => 'Biaya Kegiatan CSR'],
            ['code' => 'K014', 'description' => 'Biaya Pajak'],
            ['code' => 'K015', 'description' => 'Bunga Bank'],
            ['code' => 'K016', 'description' => 'Biaya Ujian Kompetensi'],
            ['code' => 'K017', 'description' => 'Iuran desa'],
        ];

        DB::table('expanse_categories')->insert($data);
    }
}
