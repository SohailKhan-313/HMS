<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

public function index()
{
    $categories = \App\Models\ExpenseCatagory::all();
    $expenses = Expense::latest()->get();
    return view('expenses.expanse', compact('expenses', 'categories'));
}

public function store(Request $request)
{
    $request->validate([
        'date'=>'required',
        'name'=>'required',
        'catagory'=>'required',
        'amount'=>'required'
    ]);

    Expense::create($request->all());

    return redirect()->back()->with('success','Expense added successfully');
}

public function update(Request $request,$id)
{
    $request->validate([
        'date'=>'required',
        'name'=>'required',
        'catagory'=>'required',
        'amount'=>'required'
    ]);

    $expense = Expense::findOrFail($id);
    $expense->update($request->all());

    return redirect()->route('expenses.index')
           ->with('success','Updated successfully');
}

public function destroy($id)
{
    $expense = Expense::findOrFail($id);
    $expense->delete();

    return redirect()->back()
           ->with('success','Deleted successfully');
}

}