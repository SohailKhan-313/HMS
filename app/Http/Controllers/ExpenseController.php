<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCatagory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(): View
    {
        $categories = ExpenseCatagory::query()->orderBy('name')->get();
        $expenses = Expense::query()->latest()->get();

        return view('expenses.expanse', compact('expenses', 'categories'));
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

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
