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
public function rules()
{
    // Get the ID of the student being updated (if it's an update request)
    $studentId = $this->student ? $this->student->id : null;

    return [
        'name' => [
            'sometimes', // Only validate if present in the request
            'required',
            'string',
            'max:255',
            // 'unique:students,name,' . $studentId
        ],
        'wa_number' => [
            'sometimes', // Only validate if present in the request
            'required',
            'string',
            'regex:/^\d+$/',
            'max:15',
            'unique:students,wa_number,' . $studentId
        ],
        'gender' => [
            'sometimes',
            'required',
            'string',
            'in:Male,Female'
        ],
        'school' => [
            'sometimes',
            'required',
            'string',
            'max:255'
        ],
        'enroll_date' => [
            'sometimes',
            'required',
            'date',
            'date_format:Y-m-d'
        ],
    ];
}

     

}

     


