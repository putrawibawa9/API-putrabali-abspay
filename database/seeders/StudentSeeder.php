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

Student::create([
    'nis' => '1234567891',
    'name' => 'Ni Komang Ayu Saraswati',
    'wa_number' => '081234567891',
    'gender' => 'female',
    'school' => 'SMPN 2 Denpasar',
    'enroll_date' => '2021-02-01'
]);

Student::create([
    'nis' => '1234567892',
    'name' => 'I Made Surya Putra',
    'wa_number' => '081234567892',
    'gender' => 'male',
    'school' => 'SMPN 3 Denpasar',
    'enroll_date' => '2021-03-01'
]);

Student::create([
    'nis' => '1234567893',
    'name' => 'Ni Luh Putu Ningsih',
    'wa_number' => '081234567893',
    'gender' => 'female',
    'school' => 'SMPN 4 Denpasar',
    'enroll_date' => '2021-04-01'
]);

Student::create([
    'nis' => '1234567894',
    'name' => 'I Kadek Arya Pratama',
    'wa_number' => '081234567894',
    'gender' => 'male',
    'school' => 'SMPN 5 Denpasar',
    'enroll_date' => '2021-05-01'
]);

Student::create([
    'nis' => '1234567895',
    'name' => 'Ni Putu Ayu Widiani',
    'wa_number' => '081234567895',
    'gender' => 'female',
    'school' => 'SMPN 6 Denpasar',
    'enroll_date' => '2021-06-01'
]);

Student::create([
    'nis' => '1234567896',
    'name' => 'I Gede Wirawan',
    'wa_number' => '081234567896',
    'gender' => 'male',
    'school' => 'SMPN 7 Denpasar',
    'enroll_date' => '2021-07-01'
]);

Student::create([
    'nis' => '1234567897',
    'name' => 'Ni Made Ayu Pratiwi',
    'wa_number' => '081234567897',
    'gender' => 'female',
    'school' => 'SMPN 8 Denpasar',
    'enroll_date' => '2021-08-01'
]);

Student::create([
    'nis' => '1234567898',
    'name' => 'I Wayan Budiarta',
    'wa_number' => '081234567898',
    'gender' => 'male',
    'school' => 'SMPN 9 Denpasar',
    'enroll_date' => '2021-09-01'
]);

Student::create([
    'nis' => '1234567899',
    'name' => 'Ni Luh Desi Ariyani',
    'wa_number' => '081234567899',
    'gender' => 'female',
    'school' => 'SMPN 10 Denpasar',
    'enroll_date' => '2021-10-01'
]);

Student::create([
    'nis' => '1234567800',
    'name' => 'I Ketut Dharma Putra',
    'wa_number' => '081234567800',
    'gender' => 'male',
    'school' => 'SMPN 11 Denpasar',
    'enroll_date' => '2021-11-01'
]);

Student::create([
    'nis' => '1234567801',
    'name' => 'Ni Made Dewi Lestari',
    'wa_number' => '081234567801',
    'gender' => 'female',
    'school' => 'SMPN 12 Denpasar',
    'enroll_date' => '2021-12-01'
]);

Student::create([
    'nis' => '1234567802',
    'name' => 'I Gede Arta Suwitra',
    'wa_number' => '081234567802',
    'gender' => 'male',
    'school' => 'SMPN 13 Denpasar',
    'enroll_date' => '2021-01-15'
]);

Student::create([
    'nis' => '1234567803',
    'name' => 'Ni Komang Widiya Sari',
    'wa_number' => '081234567803',
    'gender' => 'female',
    'school' => 'SMPN 14 Denpasar',
    'enroll_date' => '2021-02-15'
]);

Student::create([
    'nis' => '1234567804',
    'name' => 'I Nyoman Sudarta',
    'wa_number' => '081234567804',
    'gender' => 'male',
    'school' => 'SMPN 15 Denpasar',
    'enroll_date' => '2021-03-15'
]);

Student::create([
    'nis' => '1234567805',
    'name' => 'Ni Luh Made Indriani',
    'wa_number' => '081234567805',
    'gender' => 'female',
    'school' => 'SMPN 16 Denpasar',
    'enroll_date' => '2021-04-15'
]);

Student::create([
    'nis' => '1234567806',
    'name' => 'I Ketut Jaya',
    'wa_number' => '081234567806',
    'gender' => 'male',
    'school' => 'SMPN 17 Denpasar',
    'enroll_date' => '2021-05-15'
]);

Student::create([
    'nis' => '1234567807',
    'name' => 'Ni Putu Ayu Laksmi',
    'wa_number' => '081234567807',
    'gender' => 'female',
    'school' => 'SMPN 18 Denpasar',
    'enroll_date' => '2021-06-15'
]);

Student::create([
    'nis' => '1234567808',
    'name' => 'I Wayan Sutarya',
    'wa_number' => '081234567808',
    'gender' => 'male',
    'school' => 'SMPN 19 Denpasar',
    'enroll_date' => '2021-07-15'
]);

Student::create([
    'nis' => '1234567809',
    'name' => 'Ni Komang Ayu Trisnawati',
    'wa_number' => '081234567809',
    'gender' => 'female',
    'school' => 'SMPN 20 Denpasar',
    'enroll_date' => '2021-08-15'
]);

Student::create([
    'nis' => '1234567810',
    'name' => 'I Gede Arsa Pratama',
    'wa_number' => '081234567810',
    'gender' => 'male',
    'school' => 'SMPN 21 Denpasar',
    'enroll_date' => '2021-09-15'
]);

Student::create([
    'nis' => '1234567831',
    'name' => 'Ni Komang Dewi Ratna',
    'wa_number' => '081234567831',
    'gender' => 'female',
    'school' => 'SMPN 42 Denpasar',
    'enroll_date' => '2021-06-01'
]);

Student::create([
    'nis' => '1234567832',
    'name' => 'I Ketut Wirawan',
    'wa_number' => '081234567832',
    'gender' => 'male',
    'school' => 'SMPN 43 Denpasar',
    'enroll_date' => '2021-07-01'
]);

Student::create([
    'nis' => '1234567833',
    'name' => 'Ni Luh Sari Wulandari',
    'wa_number' => '081234567833',
    'gender' => 'female',
    'school' => 'SMPN 44 Denpasar',
    'enroll_date' => '2021-08-01'
]);

Student::create([
    'nis' => '1234567834',
    'name' => 'I Wayan Sudharta',
    'wa_number' => '081234567834',
    'gender' => 'male',
    'school' => 'SMPN 45 Denpasar',
    'enroll_date' => '2021-09-01'
]);

Student::create([
    'nis' => '1234567835',
    'name' => 'Ni Made Desi Kurnia',
    'wa_number' => '081234567835',
    'gender' => 'female',
    'school' => 'SMPN 46 Denpasar',
    'enroll_date' => '2021-10-01'
]);

Student::create([
    'nis' => '1234567836',
    'name' => 'I Nyoman Pratama',
    'wa_number' => '081234567836',
    'gender' => 'male',
    'school' => 'SMPN 47 Denpasar',
    'enroll_date' => '2021-11-01'
]);

Student::create([
    'nis' => '1234567837',
    'name' => 'Ni Luh Puspa Dewi',
    'wa_number' => '081234567837',
    'gender' => 'female',
    'school' => 'SMPN 48 Denpasar',
    'enroll_date' => '2021-12-01'
]);

Student::create([
    'nis' => '1234567838',
    'name' => 'I Gede Alit Putra',
    'wa_number' => '081234567838',
    'gender' => 'male',
    'school' => 'SMPN 49 Denpasar',
    'enroll_date' => '2021-01-05'
]);

Student::create([
    'nis' => '1234567839',
    'name' => 'Ni Komang Eka Sari',
    'wa_number' => '081234567839',
    'gender' => 'female',
    'school' => 'SMPN 50 Denpasar',
    'enroll_date' => '2021-02-05'
]);

Student::create([
    'nis' => '1234567840',
    'name' => 'I Ketut Suarta',
    'wa_number' => '081234567840',
    'gender' => 'male',
    'school' => 'SMPN 51 Denpasar',
    'enroll_date' => '2021-03-05'
]);

Student::create([
    'nis' => '1234567841',
    'name' => 'Ni Luh Yuliana',
    'wa_number' => '081234567841',
    'gender' => 'female',
    'school' => 'SMPN 52 Denpasar',
    'enroll_date' => '2021-04-05'
]);

Student::create([
    'nis' => '1234567842',
    'name' => 'I Wayan Jaya Putra',
    'wa_number' => '081234567842',
    'gender' => 'male',
    'school' => 'SMPN 53 Denpasar',
    'enroll_date' => '2021-05-05'
]);

Student::create([
    'nis' => '1234567843',
    'name' => 'Ni Komang Ratu',
    'wa_number' => '081234567843',
    'gender' => 'female',
    'school' => 'SMPN 54 Denpasar',
    'enroll_date' => '2021-06-05'
]);

Student::create([
    'nis' => '1234567844',
    'name' => 'I Nyoman Santosa',
    'wa_number' => '081234567844',
    'gender' => 'male',
    'school' => 'SMPN 55 Denpasar',
    'enroll_date' => '2021-07-05'
]);

Student::create([
    'nis' => '1234567845',
    'name' => 'Ni Luh Maya',
    'wa_number' => '081234567845',
    'gender' => 'female',
    'school' => 'SMPN 56 Denpasar',
    'enroll_date' => '2021-08-05'
]);

Student::create([
    'nis' => '1234567846',
    'name' => 'I Ketut Darma',
    'wa_number' => '081234567846',
    'gender' => 'male',
    'school' => 'SMPN 57 Denpasar',
    'enroll_date' => '2021-09-05'
]);

Student::create([
    'nis' => '1234567847',
    'name' => 'Ni Made Sinta',
    'wa_number' => '081234567847',
    'gender' => 'female',
    'school' => 'SMPN 58 Denpasar',
    'enroll_date' => '2021-10-05'
]);

Student::create([
    'nis' => '1234567848',
    'name' => 'I Gede Agus',
    'wa_number' => '081234567848',
    'gender' => 'male',
    'school' => 'SMPN 59 Denpasar',
    'enroll_date' => '2021-11-05'
]);

Student::create([
    'nis' => '1234567849',
    'name' => 'Ni Komang Yuliani',
    'wa_number' => '081234567849',
    'gender' => 'female',
    'school' => 'SMPN 60 Denpasar',
    'enroll_date' => '2021-12-05'
]);

Student::create([
    'nis' => '1234567850',
    'name' => 'I Ketut Sari',
    'wa_number' => '081234567850',
    'gender' => 'male',
    'school' => 'SMPN 61 Denpasar',
    'enroll_date' => '2021-01-15'
]);



    }
}
