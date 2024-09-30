<?php

namespace App\Http\Requests;

use Log;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:students,name', // Check for unique name
            'wa_number' => 'required|string|regex:/^\d+$/|max:15|unique:students,wa_number', // Check for unique WhatsApp number
            'gender' => 'required|string|in:Male,Female', // Must be 'Male' or 'Female'
            'school' => 'required|string|max:255', // Max length 255
            'enroll_date' => 'required|date|date_format:Y-m-d', // Must be a valid date in Y-m-d format
        ];
    }

     

}
