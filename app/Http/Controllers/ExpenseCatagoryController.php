<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCatagory;

class ExpenseCatagoryController extends Controller
{
  public function index()
{
    // 10 items per page (adjust as needed)
    $categories = ExpenseCatagory::latest()->paginate(10);

    return view('expenses.expense-catagory', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required'
        ]);

        ExpenseCatagory::create($request->all());

        return redirect()->back()->with('success','Category Added');
    }

    public function update(Request $request,$id)
    {
        $category = ExpenseCatagory::findOrFail($id);

        $category->update([
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        return redirect()->back()->with('success','Category Updated');
    }

    public function destroy($id)
    {
        ExpenseCatagory::findOrFail($id)->delete();

        return redirect()->back()->with('success','Category Deleted');
    }
}