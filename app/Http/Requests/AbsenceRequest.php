<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;


class AbsenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    return [
        'day' => 'required|string',
        'date' => 'required|date',
        'time' => 'required|string',
        'course_id' => 'required|exists:courses,id',
        'teacher_id' => 'required|exists:teachers,id',
        'attendances' => 'required|array',
        'attendances.*.students_courses_id' => 'required|exists:students_courses,id',
        'attendances.*.status' => 'required|string',
        
        // Custom rule to check uniqueness
        '*' => [
            function ($attribute, $value, $fail) {
                $exists = DB::table('meetings')
                    ->where('day', $this->day)
                    ->where('date', $this->date)
                    ->where('time', $this->time)
                    ->where('course_id', $this->course_id)
                    ->where('teacher_id', $this->teacher_id)
                    ->exists();

                if ($exists) {
                    $fail('Kelas ini sudah diabsen pada waktu tersebut.');
                }
            }
        ],
    ];
}
}
