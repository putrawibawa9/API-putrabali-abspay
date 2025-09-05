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
            ['code' => 'K001', 'name' => 'Biaya honor PTK', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K002', 'name' => 'Biaya Operasional Lembaga', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K003', 'name' => 'Biaya Pembelian Media Pembelajaran', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K004', 'name' => 'Biaya Pengembangan Usaha', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K005', 'name' => 'Biaya Renovasi Ruangan', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K006', 'name' => 'Biaya Pemeliharaan', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K007', 'name' => 'Biaya Investasi Sarana', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K008', 'name' => 'Biaya Investasi Prasarana', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K009', 'name' => 'Biaya Investasi SDM', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K010', 'name' => 'Biaya Iuran BPJS Kesehatan', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K011', 'name' => 'Biaya BPJS Ketenagakerjaan', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K012', 'name' => 'Biaya Recruitment dan promosi', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K013', 'name' => 'Biaya Kegiatan CSR', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K014', 'name' => 'Biaya Pajak', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K015', 'name' => 'Bunga Bank', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K016', 'name' => 'Biaya Ujian Kompetensi', 'type' => 'expense', 'is_active' => true],
            ['code' => 'K017', 'name' => 'Iuran desa', 'type' => 'expense', 'is_active' => true],
            ['code' => 'P001', 'name' => 'Pembayaran Peserta Didik', 'type' => 'income', 'is_active' => true],
            ['code' => 'P002', 'name' => 'Pembayaran Modul-modul', 'type' => 'income', 'is_active' => true],
            ['code' => 'P003', 'name' => 'Lain -lain', 'type' => 'income', 'is_active' => true],
        ];

        DB::table('finance_categories')->insert($data);
    }
}
