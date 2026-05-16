<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecurringScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly'])],
            'day_of_week' => ['nullable', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'day_of_month' => ['nullable', 'integer', 'between:1,31'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:time'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $frequency = $this->input('frequency');

            if ($frequency === 'weekly' && !$this->filled('day_of_week')) {
                $validator->errors()->add('day_of_week', 'day_of_week wajib diisi untuk jadwal weekly.');
            }

            if ($frequency === 'monthly' && !$this->filled('day_of_month')) {
                $validator->errors()->add('day_of_month', 'day_of_month wajib diisi untuk jadwal monthly.');
            }
        });
    }
}
