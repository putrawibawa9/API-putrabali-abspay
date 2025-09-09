<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceCategory;

class FinanceEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function allCategoriesWithEntries(Request $request){
        $id = $request->query('id');
        $except = 'K001';
        if ($id) {
            $categories = FinanceCategory::with('financeEntries')
                ->where('id', $id)
                ->where('code', '!=', $except)
                ->where('type', 'expense')
                ->first();
            return response()->json($categories);
        } else {
            $categories = FinanceCategory::with('financeEntries')
            ->where('type', 'expense')
            ->where('code', '!=', $except)
            ->get();
            return response()->json($categories);
        }
    }
    
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
            'code' => $category->code,
            'total_amount' => $total,
        ];
    })->values();

    $outcomeCategories = $financeCategories->filter(function ($category) {
        return $category->type === 'expense';
    })->map(function ($category) {
        $total = $category->financeEntries->sum('amount');
        return [
            'code' => $category->code,
            'category' => $category->name,
            'total_amount' => $total,
        ];
    })->values();

    $income_data = FinanceCategory::where('type', 'income')->get();
    $outcome_data = FinanceCategory::where('type', 'expense')->get();

    return response()->json([
        'income_category' => $incomeCategories,
        'outcome_category' => $outcomeCategories,
        'income_data' => $income_data,
        'outcome_data' => $outcome_data,
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
        $validated = $request->validate([
            'id' => 'sometimes|integer',
            'finance_category_id' => 'required|exists:finance_categories,id',
            'direction' => 'required|string',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        $financeEntryData = [
            'finance_category_id' => $validated['finance_category_id'],
            'direction' => $validated['direction'],
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
        ];
        if (isset($validated['id'])) {
            $financeEntryData['id'] = $validated['id'];
        }
        if (isset($validated['created_at'])) {
            $financeEntryData['created_at'] = $validated['created_at'];
        }
        if (isset($validated['updated_at'])) {
            $financeEntryData['updated_at'] = $validated['updated_at'];
        }

        $financeEntry = \App\Models\FinanceEntry::create($financeEntryData);

        return response()->json([
            'message' => 'Finance entry created successfully',
            'data' => $financeEntry
        ], 201);
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
