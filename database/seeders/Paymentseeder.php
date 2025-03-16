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
            'type' => 'Pembayaran SPP',
            'payment_month' => 'January',
            'payment_amount' => 80000,
        ]);

        Payment::create([
    'student_id' => 2,
    'course_id' => 3,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'February',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 3,
    'course_id' => 2,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'March',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 4,
    'course_id' => 1,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'April',
    'payment_amount' => 80000,
]);

Payment::create([
    'student_id' => 5,
    'course_id' => 4,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'May',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 6,
    'course_id' => 5,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'June',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 7,
    'course_id' => 6,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'July',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 8,
    'course_id' => 2,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'August',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 9,
    'course_id' => 3,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'September',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 10,
    'course_id' => 4,
      'type' => 'Pembayaran SPP',
    'payment_month' => 'October',
    'payment_amount' => 140000,
]);

Payment::create([
    'student_id' => 11,
    'course_id' => 5,
    'payment_month' => 'November',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 12,
    'course_id' => 6,
    'payment_month' => 'December',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 1,
    'course_id' => 2,
    'payment_month' => 'January',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 2,
    'course_id' => 4,
    'payment_month' => 'February',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 3,
    'course_id' => 6,
    'payment_month' => 'March',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 4,
    'course_id' => 3,
    'payment_month' => 'April',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 5,
    'course_id' => 5,
    'payment_month' => 'May',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 6,
    'course_id' => 1,
    'payment_month' => 'June',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 80000,
]);

Payment::create([
    'student_id' => 7,
    'course_id' => 2,
    'payment_month' => 'July',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 120000,
]);

Payment::create([
    'student_id' => 8,
    'course_id' => 4,
    'payment_month' => 'August',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 130000,
]);

Payment::create([
    'student_id' => 9,
    'course_id' => 6,
    'payment_month' => 'September',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 110000,
]);

Payment::create([
    'student_id' => 10,
    'course_id' => 3,
    'payment_month' => 'October',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 100000,
]);

Payment::create([
    'student_id' => 11,
    'course_id' => 5,
    'payment_month' => 'November',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 90000,
]);

Payment::create([
    'student_id' => 12,
    'course_id' => 1,
    'payment_month' => 'December',
          'type' => 'Pembayaran SPP',
    'payment_amount' => 80000,
]);



    }
}
