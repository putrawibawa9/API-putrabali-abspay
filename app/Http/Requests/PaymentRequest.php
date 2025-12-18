<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $courses = collect($this->input('courses', []))
            ->map(function ($c) {

                $c['type']          = $c['type'] ?? '';
                $c['payment_date']  = $c['payment_date'] ?? null;
                $c['payment_month'] = ($c['payment_month'] ?? '') === '' ? null : $c['payment_month'];
                $c['payment_year']  = ($c['payment_year'] ?? '') === '' ? null : (int) $c['payment_year'];

                $c['payment_amount'] = isset($c['payment_amount']) && $c['payment_amount'] !== ''
                    ? (int) $c['payment_amount']
                    : null;

                // NON-SPP → month & year dipaksa null
                if (in_array($c['type'], ['modul','pendaftaran','ujian'], true)) {
                    $c['payment_month'] = null;
                    $c['payment_year']  = null;
                }

                return $c;
            })
            ->filter(function ($c) {

                if (empty($c['type']) || empty($c['course_id']) || empty($c['payment_date'])) {
                    return false;
                }

                if ($c['type'] === 'spp') {
                    return is_numeric($c['payment_amount']);
                }

                return in_array($c['type'], ['modul','pendaftaran','ujian'], true);
            })
            ->values()
            ->all();

        $this->merge(['courses' => $courses]);
    }

    public function rules(): array
    {
        $rules = [
            'student_id' => 'required|exists:students,id',
            'courses'    => 'required|array|min:1',

            'courses.*.course_id'      => 'required|exists:courses,id',
            'courses.*.payment_date'   => 'required|date',
            'courses.*.type'           => 'required|string|in:spp,modul,pendaftaran,ujian',
            'courses.*.payment_month'  => 'nullable|string',
            'courses.*.payment_year'   => 'nullable|integer',
            'courses.*.payment_amount' => 'nullable|integer',
        ];

        foreach ($this->input('courses', []) as $i => $c) {

            if (($c['type'] ?? null) === 'spp') {

                // SPP → month & year WAJIB
                $rules["courses.$i.payment_amount"] = ['required', 'integer'];
                $rules["courses.$i.payment_month"]  = ['required', 'string'];
                $rules["courses.$i.payment_year"]   = ['required', 'integer'];

                // UNIQUE SPP (pakai month + year)
                $rules["courses.$i.course_id"][] = Rule::unique('payments')
                    ->where(function ($q) use ($c) {
                        return $q->where('student_id', $this->input('student_id'))
                                 ->where('course_id', $c['course_id'])
                                 ->where('type', 'spp')
                                 ->where('payment_month', $c['payment_month'])
                                 ->where('payment_year', $c['payment_year']);
                    });

            } else {

                // UNIQUE non-SPP (tanpa month & year)
                $rules["courses.$i.course_id"][] = Rule::unique('payments')
                    ->where(function ($q) use ($c) {
                        return $q->where('student_id', $this->input('student_id'))
                                 ->where('course_id', $c['course_id'])
                                 ->where('type', $c['type']);
                    });
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'courses.required' => 'Minimal ada 1 pembayaran yang valid.',
            'courses.*.payment_year.required' =>
                'Tahun pembayaran wajib diisi untuk SPP.',
            'courses.*.course_id.unique' =>
                'Pembayaran untuk item ini sudah tercatat sebelumnya.',
        ];
    }
}
