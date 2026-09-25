<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCatagory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseCatagoryController extends Controller
{
    /**
     * Display a listing of expense categories.
     */
    public function index(): View
    {
        $categories = ExpenseCatagory::query()->latest()->paginate(10);

        return view('expenses.expense-catagory', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreExpenseCategoryRequest $request): RedirectResponse
    {
        ExpenseCatagory::create($request->validated());

        return redirect()->route('category.index')->with('success', 'Category added successfully.');
    }

    /**
     * Show edit form or return JSON for modal.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $category = ExpenseCatagory::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($category);
        }

        return redirect()->route('category.index');
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateExpenseCategoryRequest $request, int|string $id): RedirectResponse
    {
        $category = ExpenseCatagory::findOrFail($id);
        $category->update($request->validated());

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $category = ExpenseCatagory::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
