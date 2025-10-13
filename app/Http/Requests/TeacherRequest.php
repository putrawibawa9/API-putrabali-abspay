<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher') ? $this->route('teacher')->id : null;

        return [
            'name' => [
                'sometimes', // Only validate if present in the request
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'name')->ignore($teacherId),
            ],
            'alias' => [
                'sometimes', // Only validate if present in the request
                'string',
                'max:50',
                Rule::unique('teachers', 'alias')->ignore($teacherId),
            ],
            'username' => [
                'sometimes', // Only validate if present in the request
                'required',
                'string',
                'max:50',
                Rule::unique('teachers', 'username')->ignore($teacherId),
            ],
            'password' => [
                'sometimes', // Only validate if present in the request
                'required',
                'string',
                'min:8'
            ],
            'instagram' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

   
}
