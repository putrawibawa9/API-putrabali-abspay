<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\Meeting;
use App\Models\Teacher;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;
use DateTime;

class GenerateSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'   => ['required', 'exists:courses,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'schedule'    => ['required', 'array'],

            // validasi tiap hari
            'schedule.*.time'       => ['required', 'date_format:H:i'],
            'schedule.*.teacher_id' => ['required', 'exists:teachers,id'],
            'schedule.*.location'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $schedule = $this->schedule ?? [];
            $courseId = $this->course_id;

            $validDays = [
                'Monday','Tuesday','Wednesday',
                'Thursday','Friday','Saturday','Sunday'
            ];

            // =====================================================
            // A. VALIDASI NAMA HARI
            // =====================================================
            foreach ($schedule as $day => $data) {
                if (!in_array($day, $validDays)) {
                    $validator->errors()->add(
                        "schedule.$day",
                        "Hari '$day' tidak valid. Gunakan Monday–Sunday."
                    );
                }
            }

            // =====================================================
            // B. GENERATE SEMUA TANGGAL DALAM RANGE
            // =====================================================
            $period = new DatePeriod(
                new DateTime($this->start_date),
                new DateInterval('P1D'),
                (new DateTime($this->end_date))->modify('+1 day')
            );

            // =====================================================
            // C. VALIDASI COURSE TIDAK DUPLIKAT DI TANGGAL+JAM SAMA
            // =====================================================
            foreach ($period as $date) {

                $dayName = $date->format('l');

                if (!isset($schedule[$dayName])) {
                    continue;
                }

                $params  = $schedule[$dayName];
                $dateStr = $date->format('Y-m-d');

                $exists = Meeting::where('course_id', $courseId)
                    ->where('date', $dateStr)
                    ->where('time', $params['time'])
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        "schedule.$dayName.time",
                        "Kelas sudah memiliki jadwal pada $dateStr jam {$params['time']}."
                    );
                }
            }

            // =====================================================
            // D. VALIDASI GURU TIDAK DOUBLE BOOKING (PER TANGGAL)
            // =====================================================
            foreach ($period as $date) {

                $dayName = $date->format('l');

                if (!isset($schedule[$dayName])) {
                    continue;
                }

                $params    = $schedule[$dayName];
                $teacherId = $params['teacher_id'];
                $time      = $params['time'];
                $dateStr   = $date->format('Y-m-d');

                $conflict = Meeting::where('teacher_id', $teacherId)
                    ->where('date', $dateStr)
                    ->where('time', $time)
                    ->exists();

                if ($conflict) {
                    $teacher = Teacher::find($teacherId);
                    $name = $teacher?->name ?? "Guru ID $teacherId";

                    $validator->errors()->add(
                        "schedule.$dayName.teacher_id",
                        "Guru $name sudah mengajar pada $dateStr jam $time."
                    );
                }
            }

            // =====================================================
            // E. VALIDASI RUANGAN TIDAK DOUBLE BOOKING (PER TANGGAL)
            // =====================================================
            foreach ($period as $date) {

                $dayName = $date->format('l');

                if (!isset($schedule[$dayName])) {
                    continue;
                }

                $params    = $schedule[$dayName];
                $location  = $params['location'] ?? null;

                if (!$location) {
                    continue;
                }

                $dateStr = $date->format('Y-m-d');
                $time    = $params['time'];

                $roomConflict = Meeting::where('location', $location)
                    ->where('date', $dateStr)
                    ->where('time', $time)
                    ->exists();

                if ($roomConflict) {
                    $validator->errors()->add(
                        "schedule.$dayName.location",
                        "Ruangan $location sudah dipakai pada $dateStr jam $time."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'schedule.required' => 'Schedule wajib dikirim.',

            'schedule.*.time.required'    => 'Jam wajib diisi.',
            'schedule.*.time.date_format' => 'Format jam harus HH:MM (contoh 16:00).',

            'schedule.*.teacher_id.required' => 'Guru wajib diisi.',
            'schedule.*.teacher_id.exists'   => 'Guru tidak ditemukan.',

            'schedule.*.location.string' => 'Location harus berupa teks.',
        ];
    }
}
