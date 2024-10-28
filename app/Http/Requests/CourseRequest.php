<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
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
        'level' => 'required|string|max:255',
        'section' => 'required|string|max:255',
        'subject' => [
            'required',
            'string',
            'in:English,Mapel',
            Rule::unique('courses')->where(function ($query) {
                return $query->where('level', $this->level)
                             ->where('section', $this->section);
            }),
        ],
        'alias' => 'nullable|string|max:255|unique:courses,alias',
        'payment_rate' => 'required|numeric|min:0',
    ];
}
}
