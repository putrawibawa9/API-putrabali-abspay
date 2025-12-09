<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Meeting;
use App\Models\Teacher;

class ChangeRecurringScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_day'        => ['required', Rule::in(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])],
            'new_day'        => ['required', Rule::in(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])],
            'new_time'       => ['required', 'date_format:H:i'],
            'effective_from' => ['required', 'date'],
            'teacher_id'     => ['sometimes', 'exists:teachers,id'],
            'location'       => ['sometimes', 'string', 'max:255']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $courseId  = $this->route('course')->id;
            $teacherId = $this->teacher_id;
            $newTime   = $this->new_time;

            // tanggal mulai
            $fromDate = $this->effective_from;

            // Jika guru ingin diganti, validasi exist
            if ($teacherId) {
                $teacher = Teacher::find($teacherId);
                $teacherName = $teacher?->name ?? "Guru";

                // cek apakah guru bentrok di jam & hari baru untuk future meetings
                $conflict = Meeting::where('teacher_id', $teacherId)
                    ->where('date', '>=', $fromDate)
                    ->where('time', $newTime)
                    ->exists();

                if ($conflict) {
                    $validator->errors()->add(
                        'teacher_id',
                        "Guru $teacherName sudah memiliki jadwal bentrok di jam $newTime."
                    );
                }
            }
        });
    }
}
