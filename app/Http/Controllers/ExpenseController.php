<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCatagory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(Request $request): View
    {
        $categories = ExpenseCatagory::query()->orderBy('name')->get();

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $selectedCategory = $request->query('category', 'all');
        $search = $request->query('search');

        $query = Expense::query();

        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }

        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->where('catagory', $selectedCategory);
        }

        if ($search) {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('catagory', 'like', $term);
            });
        }

        $expenses = $query->orderByDesc('date')->orderByDesc('id')->get();
        $totalFilteredAmount = $expenses->sum('amount');
        $totalCount = $expenses->count();

        return view('expenses.expanse', compact(
            'expenses',
            'categories',
            'fromDate',
            'toDate',
            'selectedCategory',
            'search',
            'totalFilteredAmount',
            'totalCount'
        ));
    }

    /**
     * Show create form (modal based).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('expenses.index');
    }

    /**
     * Store a newly created expense.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        Expense::create($request->validated());

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Show edit form or return JSON for modal.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $expense = Expense::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($expense);
        }

        return redirect()->route('expenses.index');
    }

    /**
     * Update the specified expense.
     */
    public function update(UpdateExpenseRequest $request, int|string $id): RedirectResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->validated());

        return redirect()->route('expenses.index')->with('update', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('delete', 'Expense deleted successfully.');
    }
}
