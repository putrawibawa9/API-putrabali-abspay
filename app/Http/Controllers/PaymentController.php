<?php

namespace App\Http\Controllers;
use Midtrans\Snap;
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

     public function createSnapToken(Request $request)
{
// dd(env('MIDTRANS_SERVER_KEY'));
// Set your Merchant Server Key
\Midtrans\Config::$serverKey = config('midtrans.serverKey');
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;
   
    $params = array(
    'transaction_details' => array(
        'order_id' => rand(),
        'gross_amount' => 10000,
    )
);

$snapToken = \Midtrans\Snap::getSnapToken($params);

    return response()->json([
        'snap_token' => $snapToken,
    ]);
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
       

        $studentId = $request['student_id'];
        $courses = $request['courses'];

        foreach ($courses as $courseData) {
            // Check the type and set payment_amount to 50000 if type is modul, pendaftaran, or ujian
            if (in_array($courseData['type'], ['modul', 'pendaftaran', 'ujian'])) {
                $courseData['payment_amount'] = 50000;
                $courseData['payment_month'] = null;
            }

            Payment::create([
                'student_id' => $studentId,
                'course_id' => $courseData['course_id'],
                'payment_date' => $courseData['payment_date'],
                'payment_month' => $courseData['payment_month'],
                'type' => $courseData['type'],
                'payment_amount' => $courseData['payment_amount'],
                'user_id' => $request->user_id, // Assuming user_id is passed in the request
            ]);
        }

        return response()->json([
            'message' => 'Payments saved successfully!',
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

  public function dailyRecap(Request $request){
    $startDate = $request->query('start_date', now()->format('Y-m-d'));
    $endDate = $request->query('end_date', now()->format('Y-m-d')); // jika tidak ada end_date, pakai start_date
// dd($startDate, $endDate);
    // Query dasar
    $query = Payment::with(['student', 'course'])
        ->whereBetween('payment_date', [$startDate, $endDate]);

    // Eksekusi query
    $payments = $query->get();
// dd($payments);
    // total payment amount
    $totalPaymentAmount = $payments->sum('payment_amount');

    // Transformasi data
    $paymentsData = $payments->map(function ($payment) {
        return [
            'id' => $payment->id,
            'student_name' => $payment->student->name,
            'course_alias' => $payment->course->alias,
            'payment_amount' => $payment->payment_amount,
            'payment_date' => $payment->created_at->format('Y-m-d'),
            'admin_name' => $payment->user->name ?? 'Unknown',
        ];
    });

    return response()->json([
        'total_payment' => $totalPaymentAmount,
        'payments' => $paymentsData,
    ]);
  }
    

}
