<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRecurringScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'effective_from' => ['nullable', 'date'],
            'delete_future_meetings' => ['nullable', 'boolean'],
        ];
    }
}
