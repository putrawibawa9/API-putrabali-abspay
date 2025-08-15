<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Payment; // Import the Payment model

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Return true if the user is authorized to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Base rules
        $rules = [
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:students,id',
            'courses' => 'required|array',
            'courses.*.course_id' => 'required|exists:courses,id',
            'courses.*.payment_date' => 'required|date|after_or_equal:today',
            'courses.*.type' => 'required|string',
            'courses.*.payment_month' => 'sometimes|string',
        ];

        // Add custom uniqueness rule for each course
        foreach ($this->input('courses', []) as $index => $course) {
            $rules["courses.$index.course_id"][] = Rule::unique('payments')->where(function ($query) use ($course) {
                return $query->where('student_id', $this->input('student_id'))
                             ->where('course_id', $course['course_id'])
                             ->where('type', $course['type'])
                             ->where('payment_month', $course['payment_month'] ?? "Select Month")
                             ->whereYear('payment_date', date('Y', strtotime($course['payment_date'])));
            });
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'courses.*.course_id.unique' => 'Murid telah tercatat membayar bulan ' . ($this->input('courses.0.payment_month') ?? "Bulan Tidak Diketahui") . '.',
        ];
    }
}