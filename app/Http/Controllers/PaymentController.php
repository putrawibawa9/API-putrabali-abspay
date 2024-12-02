<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    // Check if the payment already exists for this specific combination
    $payment = Payment::where('student_id', $request->student_id)
        ->where('course_id', $request->course_id)
        ->where(function ($query) use ($request) {
            if ($request->type === 'spp') {
                // For SPP, ensure it matches the specific payment_month
                $query->where('type', 'spp')
                      ->where('payment_month', $request->payment_month);
            } else {
                // For other types (modul, pendaftaran), check type and NULL payment_month
                $query->where('type', $request->type)
                      ->whereNull('payment_month');
            }
        })
        ->first();

    if ($payment) {
        // Return unprocessed entity
        return response()->json(['error' => 'Payment already exists'], 422);
    }

    // Add new payment to the database
    $payment = new Payment();
    $payment->student_id = $request->student_id;
    $payment->course_id = $request->course_id;
    $payment->payment_month = $request->payment_month; // This is only relevant for SPP
    $payment->payment_amount = $request->payment_amount;
    $payment->user_id = $request->user_id;
    $payment->type = $request->type; // Identifies whether it's spp, modul, or pendaftaran
    $payment->save();

    return response()->json(['message' => 'Payment added successfully'], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
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

  


}
