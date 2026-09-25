<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Staff;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Staff::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('designation', 'like', '%' . $request->search . '%');
        }

        $staff = $query->paginate(2);

        return view('staff.staff', compact('staff'));
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
    // 1️⃣ Validate request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:staff,email',
        'phone' => 'required|string|max:20',
        'designation' => 'nullable|string|max:100',
        'salary' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    // 2️⃣ Extract fields
    $data = $request->only(['name', 'email', 'phone', 'designation', 'salary']);

    // 3️⃣ Set default values for nullable fields
    $data['designation'] = $data['designation'] ?? 'Not Assigned';
    $data['salary'] = $data['salary'] ?? 0;

    // 4️⃣ Handle image upload safely
    if ($request->hasFile('image')) {
        try {
            $data['image'] = $request->file('image')->store('staff', 'public');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Image upload failed: ' . $e->getMessage());
        }
    }

    // 5️⃣ Insert into database with error handling
    try {
        $staff = Staff::create($data);

        if (!$staff) {
            return redirect()->back()->with('error', 'Staff record could not be inserted. Check table name and primary key.');
        }
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
    }

    // 6️⃣ Success
    return redirect()->back()->with('success', 'Staff added successfully!');
}

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
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'salary' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('staff', 'public');
            $staff->image = $imagePath;
        }

        $staff->name = $request->name;
        $staff->designation = $request->designation;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        $staff->salary = $request->salary;

        $staff->save();

        return redirect()->back()->with('update', 'Staff Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->image) {
            Storage::disk('public')->delete($staff->image);
        }

        $staff->delete();

        return redirect()->back()->with('delete', 'Staff Deleted Successfully');
    }
}
