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
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $query = FinanceCategory::with(['financeEntries' => function ($q) use ($startDate, $endDate) {
        if ($startDate) {
            $q->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $q->whereDate('created_at', '<=', $endDate);
        }
    }]);

    if ($categoryId) {
        $query->where('id', $categoryId);
    }

    $financeCategories = $query->get();

    $incomeCategories = $financeCategories->filter(function ($category) {
        return $category->type === 'income';
    })->map(function ($category) {
        $total = $category->financeEntries->sum('amount');
        return [
            'category' => $category->name,
            'total_amount' => $total,
        ];
    })->values();

    $outcomeCategories = $financeCategories->filter(function ($category) {
        return $category->type === 'expense';
    })->map(function ($category) {
        $total = $category->financeEntries->sum('amount');
        return [
            'category' => $category->name,
            'total_amount' => $total,
        ];
    })->values();

    return response()->json([
        'income_category' => $incomeCategories,
        'outcome_category' => $outcomeCategories,
    ]);
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
