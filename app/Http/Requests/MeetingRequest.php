<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
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
        'course_id' => [
            'required',
            Rule::unique('meetings')->where(function ($query) {
                return $query->where('date', $this->date)
                             ->where('time', $this->time);
            }),
        ],
        'day' => 'required|string',
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'teacher_id' => 'required|integer|exists:teachers,id',
    ];
    }
}
