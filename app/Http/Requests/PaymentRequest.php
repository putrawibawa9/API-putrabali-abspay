<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Buang item kursus yang tidak dibayar (payment_amount null/0/“”)
     * sebelum rules dieksekusi. Tidak ubah nama field sama sekali.
     */
   protected function prepareForValidation(): void
{
    $courses = collect($this->input('courses', []))
        ->map(function ($c) {
            // normalisasi
            $c['type']           = $c['type']           ?? '';
            $c['payment_date']   = $c['payment_date']   ?? null;
            $c['payment_month']  = ($c['payment_month'] ?? '') === '' ? null : $c['payment_month'];
            $c['payment_year'] = date('Y', strtotime($c['payment_date']));
            $c['payment_amount'] = isset($c['payment_amount']) && $c['payment_amount'] !== ''
                ? (int) $c['payment_amount'] : null;

            // month wajib hanya utk SPP; utk non-SPP set null
            if (in_array($c['type'], ['modul','pendaftaran','ujian'], true)) {
                $c['payment_month'] = null;
            }

            return $c;
        })
        ->filter(function ($c) {
            // Buang baris yang benar2 kosong/invalid
            if (empty($c['type']) || empty($c['course_id']) || empty($c['payment_date'])) {
                return false;
            }
            // SPP: harus ada nominal > 0
            if ($c['type'] === 'spp') {
                return is_numeric($c['payment_amount']);
            }
            // Non-SPP: boleh tanpa nominal (server set 50k)
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
'courses.*.payment_year' => 'required|integer',

        'courses.*.course_id'     => 'required|exists:courses,id',
        'courses.*.payment_date'  => 'required|date',
        'courses.*.type'          => 'required|string|in:spp,modul,pendaftaran,ujian',
        'courses.*.payment_month' => 'nullable|string',
        'courses.*.payment_amount'=> 'nullable|integer',
    ];

    foreach ($this->input('courses', []) as $i => $c) {
        // Wajibkan payment_amount & month untuk SPP
        if (($c['type'] ?? null) === 'spp') {
    $rules["courses.$i.payment_amount"] = ['required', 'integer'];
            $rules["courses.$i.payment_month"][]  = \Illuminate\Validation\Rule::requiredIf(true);
        }

        // Unique per student+course+type+payment_month+year(payment_date)
        $rules["courses.$i.course_id"][] = \Illuminate\Validation\Rule::unique('payments')
            ->where(function ($q) use ($c) {
                return $q->where('student_id', $this->input('student_id'))
                         ->where('course_id', $c['course_id'])
                         ->where('type', $c['type'])
                         ->where('payment_month', $c['payment_month'] ?? 'Select Month')
                         ->whereYear('payment_date', date('Y', strtotime($c['payment_date'])));
            });
    }

    return $rules;
}

    public function messages(): array
    {
        return [
            'courses.required'           => 'Minimal ada 1 pembayaran yang valid.',
            'courses.*.course_id.unique' => 'Murid telah tercatat membayar bulan ' . ($this->input('courses.0.payment_month') ?? 'Bulan Tidak Diketahui') . '.',
        ];
    }
}
