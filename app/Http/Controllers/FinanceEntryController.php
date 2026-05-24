<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceCategory;
use App\Models\FinanceEntry;

class FinanceEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

   public function allCategoriesWithEntries(Request $request)
{
    $id = $request->query('id');
    $except = ['K001', 'K017']; // daftar kode yang mau dikecualikan

    if ($id) {
        $categories = FinanceCategory::with([
                'financeEntries' => fn ($q) => $q->orderByDesc('created_at')->orderByDesc('id'),
            ])
            ->where('id', $id)
            ->whereNotIn('code', $except) // ✅ exclude banyak value
            ->where('type', 'expense')
            ->first();
    } else {
        $categories = FinanceCategory::with([
                'financeEntries' => fn ($q) => $q->orderByDesc('created_at')->orderByDesc('id'),
            ])
            ->where('type', 'expense')
            ->whereNotIn('code', $except) // ✅ exclude banyak value
            ->get();
    }

    return response()->json($categories);
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
        $q->orderByDesc('created_at')->orderByDesc('id');
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
            'direction' => 'required|string|in:income,expense',
            'amount' => 'nullable|numeric|min:0|required_without_all:unit_price,quantity',
            'item_name' => 'nullable|string|max:255|required_with:unit_price,quantity',
            'unit_price' => 'nullable|numeric|min:0|required_with:item_name,quantity',
            'quantity' => 'nullable|integer|min:1|required_with:item_name,unit_price',
            'note' => 'nullable|string',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        $hasItemBreakdown = isset($validated['item_name'], $validated['unit_price'], $validated['quantity']);
        $calculatedAmount = $hasItemBreakdown
            ? (float) $validated['unit_price'] * (int) $validated['quantity']
            : (float) $validated['amount'];

        $financeEntryData = [
            'finance_category_id' => $validated['finance_category_id'],
            'direction' => $validated['direction'],
            'item_name' => $validated['item_name'] ?? null,
            'unit_price' => $validated['unit_price'] ?? null,
            'quantity' => $validated['quantity'] ?? null,
            'amount' => $calculatedAmount,
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
        $financeEntry = FinanceEntry::findOrFail($id);

        return response()->json([
            'data' => $financeEntry,
        ]);
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
        $financeEntry = FinanceEntry::findOrFail($id);
        $entrySnapshot = $financeEntry->only([
            'id',
            'finance_category_id',
            'direction',
            'item_name',
            'unit_price',
            'quantity',
            'amount',
            'note',
            'created_at',
        ]);

        $financeEntry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Finance entry deleted successfully.',
            'deleted_entry' => $entrySnapshot,
        ]);
    }
}
