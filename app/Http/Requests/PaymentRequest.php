<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
             'student_id' => 'required|integer|exists:students,id',
            'courses' => 'required|array',
            'courses.*.course_id' => 'required|integer|exists:courses,id',
            'courses.*.payment_date' => 'required|date',
            'courses.*.payment_month' => 'required|string',
            'courses.*.type' => 'required|string',
            'courses.*.payment_amount' => 'required|numeric',
        ];
    }
}
