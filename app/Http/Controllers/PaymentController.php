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

        // check if the student has paid the specific class for the month
        $payment = Payment::where('student_id', $request->student_id)->where('course_id', $request->course_id)->where('payment_month', $request->payment_month)->first();
        if ($payment) {
            return response()->json(['message' => 'Student has already paid for this month']);
        }
        // Add new payment to the database
        $payment = new Payment();
        $payment->student_id = $request->student_id;
        $payment->course_id = $request->course_id;
        $payment->payment_month = $request->payment_month;
        $payment->payment_amount = $request->payment_amount;
        $payment->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($student_id)
    {
        // check the course that the student in
        $payment = Payment::where('student_id', $student_id)->with('course')->get();
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
    // Get the student with courses and payments using eager loading
    $student = Student::with(['courses', 'payments'])->find($id);
    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    // Organize payments by course
    $coursePayments = [];
    foreach ($student->courses as $course) {
        // Filter payments related to this specific course
        $coursePayments[$course->id] = [
            'course' => $course,
            'payments' => $student->payments->where('course_id', $course->id),
        ];
    }
    // return student data and payments
    return response()->json([
        'student' => $student,
        'course_payments' => $coursePayments,
    ]);
}


}
