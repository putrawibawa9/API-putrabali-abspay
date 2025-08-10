<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;


class AbsenceRequest extends FormRequest
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
    $routeMeeting = $this->route('meeting');
    $currentId = is_object($routeMeeting) ? ($routeMeeting->id ?? null) : $routeMeeting;

    return [
        'day'   => 'required|string',
        'date'  => 'required|date',
        'time'  => [
            'required','date_format:H:i',
            // Guru yang sama tidak boleh double-book di slot yang sama
            function ($attribute, $value, $fail) use ($currentId) {
                $exists = DB::table('meetings')
                    ->where('teacher_id', $this->teacher_id)
                    ->where('date', $this->date)
                    ->where('time', $value)
                    ->when($currentId, fn($q) => $q->where('id', '!=', $currentId))
                    ->exists();

                if ($exists) {
                    $fail('Anda sudah mengisi absen pada jam dan tanggal ini. Silahkan periksa riwayat mengajar anda');
                }
            },
        ],

        'course_id' => [
            'required','exists:courses,id',
            // Kelas yang sama tidak boleh diisi lagi (oleh guru mana pun) pada slot yang sama
            function ($attribute, $value, $fail) use ($currentId) {
                $conflict = DB::table('meetings')
                    ->where('course_id', $value)
                    ->where('date', $this->date)
                    ->where('time', $this->time)
                    ->when($currentId, fn($q) => $q->where('id', '!=', $currentId))
                    ->exists();

                if ($conflict) {
                    $fail('Kelas ini sudah diabsen oleh guru lain.');
                }
            },
        ],

        'teacher_id'  => 'required|exists:teachers,id',
        'attendances' => 'required|array',
        'attendances.*.students_courses_id' => 'required|exists:students_courses,id',
        'attendances.*.status'              => 'required|string',
    ];
}



}
