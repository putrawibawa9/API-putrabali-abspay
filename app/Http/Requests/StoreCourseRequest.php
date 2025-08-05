<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
        'level' => 'string|max:255',
        'section' => 'string|max:255',
        'subject' => [ 
            'string',
            'in:English,Mapel',
            Rule::unique('courses')->where(function ($query) {
                return $query->where('level', $this->level)
                             ->where('section', $this->section)
                             ->where('subject', $this->subject);
                           
            }),
        ],
        'alias' => 'nullable|string|max:255|unique:courses,alias',
        'payment_rate' => 'numeric|min:0',
    ];
}

    public function messages()
    {
        return [
    //    return error message if the combination of level, section, and subject already exists
            'subject.unique' => 'Kelas sudah ada',
            'alias.unique' => 'Kelas sudah ada',
        ];
    }

}
