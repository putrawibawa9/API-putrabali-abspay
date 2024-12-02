<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
       return [
    'name' => [
        'sometimes', // Only validate if present in the request
        'required',
        'string',
        'max:255',
        'unique:teachers,name'
    ],
    'alias' => [
        'sometimes', // Only validate if present in the request
        'string',
        'max:50',
        'unique:teachers,alias'
    ],
    'username' => [
        'sometimes', // Only validate if present in the request
        'required',
        'string',
        'max:50',
        'unique:teachers,username'
    ],
    'password' => [
        'sometimes', // Only validate if present in the request
        'required',
        'string',
        'min:8'
    ],
];
    }

   
}
