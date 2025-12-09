<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Meeting;
use App\Models\Teacher;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'date'       => ['sometimes', 'date'],
            'time'       => ['sometimes', 'date_format:H:i'],
            'location'   => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            /** @var Meeting $meeting */
            $meeting = $this->route('meeting');

            $newTeacher = $this->teacher_id ?? $meeting->teacher_id;
            $newDate    = $this->date ?? $meeting->date;
            $newTime    = $this->time ?? $meeting->time;

            // Buat nama hari baru jika date berubah
            $newDayName = date('l', strtotime($newDate));

            // Cek teacher exist (jika user kirim teacher_id)
            $teacher = Teacher::find($newTeacher);
            $teacherName = $teacher?->name ?? "Guru";

            // Cek konflik jadwal guru di tanggal + jam baru
            $conflict = Meeting::where('teacher_id', $newTeacher)
                ->where('date', $newDate)
                ->where('time', $newTime)
                ->where('id', '!=', $meeting->id)
                ->exists();

            if ($conflict) {
                $validator->errors()->add(
                    'teacher_id',
                    "Guru $teacherName sudah memiliki jadwal pada $newDayName pukul $newTime."
                );
            }
        });
    }
}
