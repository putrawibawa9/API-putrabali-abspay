<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StudentCourseRequest extends FormRequest
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
        'student_id' => [
            'required',
            Rule::unique('students_courses')->where(function ($query) {
                return $query->where('course_id', request()->course_id);
            }),
        ],
        'custom_payment_rate' => 'required|numeric|min:0',
    ];
}

    public function messages(): array
    {
        return [
            'student_id.unique' => 'The student has already join the class.',
            'course_id.exists' => 'The selected course id is invalid.',
        ];
    }
}