<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Meeting;
use App\Models\Teacher;

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

            // validasi atribut setiap hari
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
                'Monday', 'Tuesday', 'Wednesday', 'Thursday',
                'Friday', 'Saturday', 'Sunday'
            ];

            // ------------------------------------
            // A. Validasi nama hari harus valid
            // ------------------------------------
            foreach ($schedule as $day => $data) {
                if (!in_array($day, $validDays)) {
                    $validator->errors()->add(
                        "schedule.$day",
                        "Hari '$day' tidak valid. Gunakan format English day name (Monday–Sunday)."
                    );
                }
            }

            // ------------------------------------
            // B. Validasi guru tidak bentrok dalam 1 hari (duplikasi)
            // ------------------------------------
            $teacherUsage = [];

            foreach ($schedule as $day => $params) {
                $teacher = $params['teacher_id'];

                if (!isset($teacherUsage[$day])) {
                    $teacherUsage[$day] = [];
                }

                if (in_array($teacher, $teacherUsage[$day])) {

                    $teacherModel = Teacher::find($teacher);
                    $name = $teacherModel?->name ?? "Guru ID $teacher";

                    $validator->errors()->add(
                        "schedule.$day.teacher_id",
                        "Guru $name sudah dipakai lebih dari sekali pada hari $day."
                    );

                } else {
                    $teacherUsage[$day][] = $teacher;
                }
            }

            // ------------------------------------
            // C. Validasi semester overlap untuk course ini
            // ------------------------------------
            $start = $this->start_date;
            $end   = $this->end_date;

            $hasOverlap = Meeting::where('course_id', $courseId)
                ->whereBetween('date', [$start, $end])
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add(
                    'date',
                    "Kelas ini sudah punya jadwal."
                );
            }

            // ------------------------------------
            // D. Validasi global: guru tidak boleh double booking
            // ------------------------------------
            foreach ($schedule as $day => $params) {

                $teacherId = $params['teacher_id'];
                $time      = $params['time'];

                // cek apakah guru ini punya meeting lain pada hari+jam ini
                $conflict = Meeting::where('teacher_id', $teacherId)
                    ->where('day', $day)
                    ->where('time', $time)
                    ->exists();

                if ($conflict) {

                    $teacher = Teacher::find($teacherId);
                    $teacherName = $teacher ? $teacher->name : "Guru ID $teacherId";

                    $validator->errors()->add(
                        "schedule.$day.teacher_id",
                        "Guru $teacherName sudah punya jadwal pada hari $day jam $time."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'schedule.required' => 'Schedule wajib dikirim.',

            'schedule.*.time.required'    => 'Time wajib diisi.',
            'schedule.*.time.date_format' => 'Format time harus HH:MM (contoh: 16:00).',

            'schedule.*.teacher_id.required' => 'Teacher wajib diisi.',
            'schedule.*.teacher_id.exists'   => 'Teacher tidak ditemukan.',

            'schedule.*.location.string'  => 'Location harus berupa teks.'
        ];
    }
}
