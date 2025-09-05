<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceCategory;

class FinanceEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $categoryId = $request->query('category_id');

    $query = FinanceCategory::with('financeEntries');

    if ($categoryId) {
        $query->where('id', $categoryId);
    }

    $financeCategory = $query->get();

    $financeCategory = $financeCategory->map(function ($category) {
        $total = $category->financeEntries->sum('amount');
        return [
            'category' => $category->name,
            'total_amount' => $total,
        ];
    });
    return response()->json($financeCategory);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
