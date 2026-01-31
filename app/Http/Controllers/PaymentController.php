<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\Payment;
use App\Models\Student;
use App\Models\FinanceEntry;
use Illuminate\Http\Request;

use App\Models\StudentCourse;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function generateReceipt($id)
    {
        // get payment with student name
        $payment = Payment::where('id', $id)
            ->with(['student', 'course', 'user']) // Assuming 'user' is the admin who processed the payment
            ->first();
        // 

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
      
        // Generate receipt logic here
        $receipt = [
            'id' => $payment->id,
            'student_name' => $payment->student->name,
            'student_nis' => $payment->student->nis ?? 'N/A',
            'type' => $payment->type,
            'payment_month' => $payment->payment_month ?? '-',
            'course_name' => $payment->course->alias,
            'amount' => $payment->payment_amount,
            'date' => $payment->payment_date,
            'time' => $payment->created_at->format('H:i'),
            'admin' => $payment->user->name ?? $payment->teacher->name ?? 'Admin PB',
        ];

        return response()->json($receipt);
    }


    public function index()
    {
        // show all students that is enrolled in a class but
        $students = StudentCourse::with('student')
    ->select('student_id')
    ->distinct()
    ->orderBy('created_at', 'desc') // Order by the latest data
    ->get();


        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(PaymentRequest $request)
{
    // Ambil data yang SUDAH difilter & tervalidasi oleh PaymentRequest
    $validated  = $request->validated();
    $studentId  = $validated['student_id'];
    $courses    = $validated['courses'];

    if (empty($courses)) {
        return response()->json([
            'message' => 'Tidak ada pembayaran yang valid.',
        ], 422);
    }

    DB::transaction(function () use ($courses, $studentId, $request) {
        foreach ($courses as $courseData) {

            // Normalisasi tarif untuk jenis tertentu
            if (in_array($courseData['type'], ['modul', 'pendaftaran', 'ujian'], true)) {
                $courseData['payment_amount'] = 50000;
                $courseData['payment_month']  = null;
            }

            // 1) Simpan Payment (⬅️ TAMBAH payment_year)
            $payment = Payment::create([
                'student_id'     => $studentId,
                'course_id'      => $courseData['course_id'],
                'payment_date'   => $courseData['payment_date'],
                'payment_month'  => $courseData['payment_month'] ?? null,
                // payment year pakai tahun sekarang kalau null
                'payment_year'   => $courseData['payment_year'] ??  date('Y'),
                'type'           => $courseData['type'],
                'payment_amount' => $courseData['payment_amount'],
                'user_id'        => $request->user_id ?? null,
                'teacher_id'     => $request->teacher_id ?? null,
            ]);

            // 2) Tentukan kategori keuangan & catatan
            if ($courseData['type'] === 'spp') {
                $category = FinanceCategory::where('code', 'P001')->first();
                $note = 'Pembayaran SPP bulan ' .
                        ($courseData['payment_month'] ?? '-') .
                        ' ' . $courseData['payment_year'] .
                        ' - Kursus ID: ' . $courseData['course_id'];
            } elseif ($courseData['type'] === 'modul') {
                $category = FinanceCategory::where('code', 'P002')->first();
                $note = 'Pembayaran Modul ' .
                        $courseData['payment_year'] .
                        ' - Kursus ID: ' . $courseData['course_id'];
            } else {
                $category = FinanceCategory::where('code', 'P003')->first();
                $note = 'Pembayaran Ujian / Pendaftaran ' .
                        $courseData['payment_year'] .
                        ' - Kursus ID: ' . $courseData['course_id'];
            }

            if (!$category) {
                throw new \RuntimeException(
                    'Kategori keuangan tidak ditemukan untuk type: ' . $courseData['type']
                );
            }

            // 3) Catat ke FinanceEntry
            FinanceEntry::create([
                'finance_category_id' => $category->id,
                'direction'           => 'income',
                'amount'              => $courseData['payment_amount'],
                'note'                => $note,
            ]);

            // 4) Aktifkan course
            StudentCourse::where('student_id', $studentId)
                ->where('course_id', $courseData['course_id'])
                ->update(['is_active' => true]);
        }
    });

    return response()->json([
        'message'   => 'Payments saved successfully!',
        'processed' => count($courses),
    ], 201);
}






    /**
     * Display the specified resource.
     */
    public function show($student_id)
    {
        // check the course that the student in
        $payment = Payment::where('student_id', $student_id)
        ->with('course')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_month' => 'in:january,february,march,april,may,june,july,august,september,october,november,december',
        ]);

        $payment->update($request->all());

        // return eror response if payment_month is not valid
        if ($request->has('payment_month') && !in_array($request->payment_month, ['january','february','march','april','may','june','july','august','september','october','november','december'])) {
            return response()->json(['error' => 'Invalid payment_month value'], 400);
        }
    
        return response()->json(['message' => 'Payment updated successfully', 'payment' => $payment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    public function recapStudentPayments($studentId, $year)
    {
        // Query to sum up payments for a specific student in the given year
        $totalPayments = DB::table('payments')
                            ->where('student_id', $studentId)
                            ->whereYear('date', $year);
        
        dd($totalPayments);

        return response()->json([
            'student_id' => $studentId,
            'year' => $year,
            'total_payments' => $totalPayments,
        ]);
    }

public function getStudentPayment($id)
{
 
    // Get the student with courses and sorted payments using eager loading
    $student = Student::with([
        'activeCourses',
        'payments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        },
    ])->find($id);

    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    // Organize payments by course
    $coursePayments = [];
    foreach ($student->courses as $course) {
        $coursePayments[$course->id] = [
            'course' => $course,
            'payments' => $student->payments->where('course_id', $course->id),
        ];
    }

    // Return student data and payments
    return response()->json([
        'student' => $student,
        'course_payments' => $coursePayments,
    ]);
}


public function paymentRecap(Request $request)
{

    $startDate = $request->start_date;
    $endDate = $request->end_date;
    // sum the payments for the given date range
    $totalPayments = DB::table('payments')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('payment_amount');
    return response()->json([
        'start_date' => $startDate,
        'end_date' => $endDate,
        'total_payments' => $totalPayments,
    ]);
}

    public function monthlyPaymentStudent(){
        $payments = Payment::whereMonth('created_at', date('m'))->get();
        $totalPembayaran = $payments->sum('payment_amount');
        return response()->json(['totalPembayaran' => $totalPembayaran, 'payments' => $payments]);
    }

    public function paidAndUnpaidStudentsMonthly(Request $request){
        $month = $request->month;
        // dd($month);
        $students = Student::all();
        // dd($students);
        $paidStudents = [];
        $unpaidStudents = [];
        foreach ($students as $student) {
            // dd($student);
            $payment = Payment::where('student_id', $student->id)->where('payment_month', $month)->first();
            if ($payment) {
                $paidStudents[] = $student;
            } else {
                $unpaidStudents[] = $student;
            }
        }
        return response()->json([
            'paid_students' => $paidStudents,
            'unpaid_students' => $unpaidStudents,
        ]);
    }

   public function getUnpaidStudents()
{
    $bulanLalu = strtolower(now()->subMonth()->format('F'));
    $duaBulanLalu = strtolower(now()->subMonths(2)->format('F'));
    $tahunIni = now()->year;

    $students = Student::query()
        // Hanya murid aktif
        ->whereHas('activeCourses')

        // Hanya yang daftar sebelum bulan lalu
        ->whereDate('enroll_date', '<', now()->subMonth()->startOfMonth())

        // Tidak punya pembayaran bulan lalu
        ->whereDoesntHave('payments', function ($query) use ($bulanLalu, $tahunIni) {
            $query->where('type', 'spp')
                  ->where('payment_month', $bulanLalu)
                  ->whereYear('payment_date', $tahunIni);
        })
        // Dan tidak punya pembayaran dua bulan lalu
        ->whereDoesntHave('payments', function ($query) use ($duaBulanLalu, $tahunIni) {
            $query->where('type', 'spp')
                  ->where('payment_month', $duaBulanLalu)
                  ->whereYear('payment_date', $tahunIni);
        })
        ->select('id', 'nis', 'name', 'wa_number')
        ->get();

    return response()->json([
        'message' => 'Sukses mengambil data murid yang nunggak 2 bulan',
        'unpaid_months' => [$duaBulanLalu, $bulanLalu],
        'year' => $tahunIni,
        'count' => $students->count(),
        'data' => $students
    ]);
}



public function dailyRecap(Request $request)
{
    // VALIDASI (ditambah lokasi_pb)
    $request->validate([
        'start_date'    => 'sometimes|date',
        'end_date'      => 'sometimes|date',
        'payment_month' => 'sometimes|string|nullable',
        'course_id'     => 'sometimes|array',
        'course_id.*'   => 'integer',
        'user_id'       => 'sometimes|integer|nullable',
         'teacher_id'    => 'sometimes|integer|nullable', // ✅ tambah
        'lokasi_pb'     => 'sometimes|integer|nullable', // ✅ tambahan
        'type'         => 'sometimes|string|in:spp,modul,pendaftaran,ujian',
    ]);

    $startDate = $request->input(
        'start_date',
        Carbon::now()->startOfMonth()->toDateString()
    );

    $endDate = $request->input('end_date', $startDate);

    // Normalisasi course_id
    $courseIds = collect($request->input('course_id', []))
        ->filter(fn ($v) => $v !== null && $v !== '')
        ->map(fn ($v) => (int) $v)
        ->values()
        ->all();

    // ===============================
    // QUERY UTAMA (LOGIKA TETAP)
    // ===============================
    $query = Payment::with(['student', 'course', 'user', 'teacher'])
        ->whereBetween('payment_date', [$startDate, $endDate]);

    // payment_month
    if ($request->filled('payment_month')) {
        $query->where('payment_month', $request->input('payment_month'));
    }

    // type
if ($request->filled('type')) {
    $query->where('type', $request->input('type'));
}

    // course_id
    if (!empty($courseIds)) {
        $query->whereIn('course_id', $courseIds);
    }

    // user_id
    if ($request->filled('user_id')) {
        $query->where('user_id', (int) $request->input('user_id'));
    }

    // ===============================
    // 🔹 FILTER BARU: lokasi_pb
    // ===============================
    if ($request->filled('lokasi_pb')) {
        $lokasiPb = (int) $request->input('lokasi_pb');

        $query->whereHas('course', function ($q) use ($lokasiPb) {
            $q->where('lokasi_pb', $lokasiPb);
        });
    }

    // ===============================
// 🔹 FILTER BARU: teacher_id
// ===============================
if ($request->filled('teacher_id')) {
    $query->where('teacher_id', (int) $request->input('teacher_id'));
}

    // Urutan tetap
    $payments = $query->orderByDesc('created_at')->get();

    $totalPaymentAmount = $payments->sum('payment_amount');

    // Mapping response (TIDAK diubah)
    $paymentsData = $payments->map(function ($payment) {
        return [
            'id'             => $payment->id,
            'payment_month'  => $payment->payment_month ?? '-',
            'type'           => $payment->type,
            'student_name'   => optional($payment->student)->name,
            'student_id'     => optional($payment->student)->id,
            'course_alias'   => optional($payment->course)->alias,
            'course_id'      => optional($payment->course)->id,
            'payment_amount' => $payment->payment_amount,
            'payment_date'   => optional($payment->payment_date)->format('Y-m-d')
                                ?? $payment->payment_date,
            'admin_name'     => optional($payment->user)->name
                                ?? optional($payment->teacher)->name
                                ?? 'Admin PB',
        ];
    });

    return response()->json([
        'total_payment' => $totalPaymentAmount,
        'payments'      => $paymentsData,
        'range'         => [
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ],
        'filters'       => [
            'payment_month' => $request->input('payment_month'),
            'type'          => $request->input('type'),
            'course_id'     => $courseIds,
            'user_id'       => $request->input('user_id'),
            'lokasi_pb'     => $request->input('lokasi_pb'), // ✅ echo filter
        ],
    ]);
}

    
public function changeDate(Request $request){
    $paymentId = $request->payment_id;
    $newDate = $request->new_date;

    $payment = Payment::find($paymentId);
    if (!$payment) {
        return response()->json(['error' => 'Payment not found'], 404);
    }

    $payment->payment_date = $newDate;
    $payment->save();

    return response()->json(['message' => 'Payment date updated successfully', 'payment' => $payment]);
}

}
