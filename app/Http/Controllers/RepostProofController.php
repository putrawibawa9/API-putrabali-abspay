<?php

namespace App\Http\Controllers;

use App\Models\RepostProof;
use Illuminate\Http\Request;

class RepostProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $reposts = RepostProof::with('teacher')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reposts.index', compact('reposts', 'month', 'year'));
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
    try {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf,mp4|max:8192',
        ]);

        $teacherId = $request->input('teacher_id');
        $path = $request->file('proof')->store('reposts/' . date('Y/m'), 'public');

        $proof = \App\Models\RepostProof::create([
            'teacher_id' => $teacherId,
            'proof_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Upload berhasil',
            'data' => [
                'id' => $proof->id,
                'teacher_id' => $teacherId,
                'url' => asset('storage/' . $path),
                'uploaded_at' => $proof->created_at->format('d M Y H:i'),
            ],
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // ⛔ kalau validasi gagal
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // ⛔ kalau ada error lain (misalnya storage penuh)
        return response()->json([
            'success' => false,
            'message' => 'Upload gagal: ' . $e->getMessage(),
        ], 500);
    }
}




    /**
     * Display the specified resource.
     */
  public function show(Request $request, $teacher_id)
{
    // Ambil bulan & tahun dari query (opsional)
    $month = $request->input('month', now()->month);
    $year  = $request->input('year', now()->year);

    // Validasi kalau guru tidak ada
    if (!\App\Models\Teacher::where('id', $teacher_id)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Teacher not found',
        ], 404);
    }

    // Hitung jumlah upload guru pada bulan & tahun tertentu
    $count = \App\Models\RepostProof::where('teacher_id', $teacher_id)
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->count();
        $month = (int) $request->input('month', now()->month);
$year  = (int) $request->input('year', now()->year);

    return response()->json([
        'teacher_id' => $teacher_id,
        'month' => \Carbon\Carbon::create()->month($month)->format('F'),
        'year' => $year,
        'count' => $count,
    ]);
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepostProof $repostProof)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepostProof $repostProof)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepostProof $repostProof)
    {
        //
    }
}
