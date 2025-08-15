<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'level' => 'string|max:255',
            'section' => 'string|max:255',
            'subject' => [
                
                'string',
                'in:English,Mapel',
                Rule::unique('courses')->where(function ($query) use ($courseId) {
                    return $query->where('level', $this->level)
                                 ->where('section', $this->section)
                                 ->where('subject', $this->subject)
                                 ->where('alias', $this->alias)
                                 ->where('id', '!=', $courseId);
                }),
            ],
            'alias' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'alias')->ignore($courseId),
            ],
            'payment_rate' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.unique' => 'Kelas sudah ada',
            'alias.unique' => 'Alias sudah ada',
        ];
    }
}
