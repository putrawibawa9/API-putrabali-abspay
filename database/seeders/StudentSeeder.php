<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run()
    {
        Student::create([
            'nis' => '1234567890',
            'name' => 'I Gede Putra Wibawa',
            'wa_number' => '081234567890',
            'gender' => 'male',
            'school' => 'SMPN 1 Denpasar',
            'enroll_date' => '2021-01-01'
        ]);

        Student::create([
            'nis' => '0987654321',
            'name' => 'Ni Luh Putu Ayu',
            'wa_number' => '081234567891',
            'gender' => 'female',
            'school' => 'SMPN 1 Denpasar',
            'enroll_date' => '2021-01-03'
        ]);

        Student::create([
    'nis' => '1231231230',
    'name' => 'Kadek Ari Sutami',
    'wa_number' => '081234567892',
    'gender' => 'female',
    'school' => 'SMPN 2 Denpasar',
    'enroll_date' => '2021-02-15'
]);

Student::create([
    'nis' => '1231231231',
    'name' => 'Made Surya Mahardika',
    'wa_number' => '081234567893',
    'gender' => 'male',
    'school' => 'SMPN 3 Denpasar',
    'enroll_date' => '2021-03-10'
]);

Student::create([
    'nis' => '1231231232',
    'name' => 'Putu Ayu Dewi',
    'wa_number' => '081234567894',
    'gender' => 'female',
    'school' => 'SMPN 4 Denpasar',
    'enroll_date' => '2021-04-05'
]);

Student::create([
    'nis' => '1231231233',
    'name' => 'I Nyoman Gede',
    'wa_number' => '081234567895',
    'gender' => 'male',
    'school' => 'SMPN 1 Gianyar',
    'enroll_date' => '2021-05-01'
]);

Student::create([
    'nis' => '1231231234',
    'name' => 'Ni Putu Mirah',
    'wa_number' => '081234567896',
    'gender' => 'female',
    'school' => 'SMPN 2 Gianyar',
    'enroll_date' => '2021-06-12'
]);

Student::create([
    'nis' => '1231231235',
    'name' => 'Wayan Ketut Adi',
    'wa_number' => '081234567897',
    'gender' => 'male',
    'school' => 'SMPN 3 Gianyar',
    'enroll_date' => '2021-07-20'
]);

Student::create([
    'nis' => '1231231236',
    'name' => 'Gede Putra Sanjaya',
    'wa_number' => '081234567898',
    'gender' => 'male',
    'school' => 'SMPN 4 Gianyar',
    'enroll_date' => '2021-08-11'
]);

Student::create([
    'nis' => '1231231237',
    'name' => 'Ni Made Kartika',
    'wa_number' => '081234567899',
    'gender' => 'female',
    'school' => 'SMPN 5 Denpasar',
    'enroll_date' => '2021-09-08'
]);

Student::create([
    'nis' => '1231231238',
    'name' => 'Putu Budi Susila',
    'wa_number' => '081234567800',
    'gender' => 'male',
    'school' => 'SMPN 6 Denpasar',
    'enroll_date' => '2021-10-15'
]);

Student::create([
    'nis' => '1231231239',
    'name' => 'I Made Agung',
    'wa_number' => '081234567801',
    'gender' => 'male',
    'school' => 'SMPN 7 Denpasar',
    'enroll_date' => '2021-11-25'
]);


    }
}
