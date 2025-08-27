<?php

namespace App\Http\Requests;

use App\Models\StudentCourse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StudentCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                Rule::unique('students_courses')->where(function ($query) {
                    return $query->where('course_id', request()->course_id)
                                 ->where('is_active', true);
                }),
            ],
            'custom_payment_rate' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.unique' => 'The student has already joined this class.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $studentId = $this->input('student_id');

            $activeCoursesCount = DB::table('students_courses')
                ->where('student_id', $studentId)
                ->where('is_active', true)
                ->count();

            if ($activeCoursesCount >= 4) {
                $validator->errors()->add('student_id', 'A student cannot be enrolled in more than 4 active courses.');
            }
        });
    }
}
