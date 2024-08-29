<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Paymentseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'student_id' => 1,
            'course_id' => 1,
            'payment_date' => '2021-01-01',
            'payment_month' => 'January',
            'payment_amount' => 80000,
        ]);

        Payment::create([
    'student_id' => 2,
    'course_id' => 3,
    'payment_date' => '2021-02-01',
    'payment_month' => 'February',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 3,
    'course_id' => 2,
    'payment_date' => '2021-03-01',
    'payment_month' => 'March',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 4,
    'course_id' => 1,
    'payment_date' => '2021-04-01',
    'payment_month' => 'April',
    'payment_amount' => 80000,
]);

Payment::create([
    'student_id' => 5,
    'course_id' => 4,
    'payment_date' => '2021-05-01',
    'payment_month' => 'May',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 6,
    'course_id' => 5,
    'payment_date' => '2021-06-01',
    'payment_month' => 'June',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 7,
    'course_id' => 6,
    'payment_date' => '2021-07-01',
    'payment_month' => 'July',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 8,
    'course_id' => 2,
    'payment_date' => '2021-08-01',
    'payment_month' => 'August',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 9,
    'course_id' => 3,
    'payment_date' => '2021-09-01',
    'payment_month' => 'September',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 10,
    'course_id' => 4,
    'payment_date' => '2021-10-01',
    'payment_month' => 'October',
    'payment_amount' => 140000,
]);

Payment::create([
    'student_id' => 11,
    'course_id' => 5,
    'payment_date' => '2021-11-01',
    'payment_month' => 'November',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 12,
    'course_id' => 6,
    'payment_date' => '2021-12-01',
    'payment_month' => 'December',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 1,
    'course_id' => 2,
    'payment_date' => '2022-01-15',
    'payment_month' => 'January',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 2,
    'course_id' => 4,
    'payment_date' => '2022-02-15',
    'payment_month' => 'February',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 3,
    'course_id' => 6,
    'payment_date' => '2022-03-15',
    'payment_month' => 'March',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 4,
    'course_id' => 3,
    'payment_date' => '2022-04-15',
    'payment_month' => 'April',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 5,
    'course_id' => 5,
    'payment_date' => '2022-05-15',
    'payment_month' => 'May',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 6,
    'course_id' => 1,
    'payment_date' => '2022-06-15',
    'payment_month' => 'June',
    'payment_amount' => 80000,
]);

Payment::create([
    'student_id' => 7,
    'course_id' => 2,
    'payment_date' => '2022-07-15',
    'payment_month' => 'July',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 8,
    'course_id' => 4,
    'payment_date' => '2022-08-15',
    'payment_month' => 'August',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 9,
    'course_id' => 6,
    'payment_date' => '2022-09-15',
    'payment_month' => 'September',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 10,
    'course_id' => 3,
    'payment_date' => '2022-10-15',
    'payment_month' => 'October',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 11,
    'course_id' => 5,
    'payment_date' => '2022-11-15',
    'payment_month' => 'November',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 12,
    'course_id' => 1,
    'payment_date' => '2022-12-15',
    'payment_month' => 'December',
    'payment_amount' => 80000,
]);



    }
}
