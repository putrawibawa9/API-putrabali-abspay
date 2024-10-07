<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Set to true if you want to allow this form request to be used
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:courses,name',
            'alias' => 'nullable|string|max:255|unique:courses,alias',
            'payment_rate' => 'required|numeric|min:0',
            'type' => 'required|string|in:English,Mapel', 
        ];
    }
}
