<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
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
        $payment->payment_date = $request->payment_date;
        $payment->payment_month = $request->payment_month;
        $payment->payment_amount = $request->payment_amount;
        $payment->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
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
}
