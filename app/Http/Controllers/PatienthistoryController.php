<?php

namespace App\Http\Controllers;

use App\Models\PatientHistory;
use Illuminate\Http\Request;

class PatientHistoryController extends Controller
{
    // ✅ LIST + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = PatientHistory::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(2);

        // ✅ Blade is now at resources/views/patients/index.blade.php
        return view('history.p-history', compact('patients'));
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'age'            => 'integer|min:0',
            'phone'          => 'string|max:20',
            'cnic'           => 'nullable|string|max:20',
            'due_amount'     => 'numeric|min:0',
            'wallet_amount'  => 'numeric|min:0',
        ]);

        PatientHistory::create($data);

        return redirect()->route('patients.index')
            ->with('success', 'Patient added successfully');
    }

    // ✅ SHOW (optional – you can keep or remove)
    public function show($id)
    {
        $patient = PatientHistory::findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $patient = PatientHistory::findOrFail($id);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'age'            => 'required|integer|min:0',
            'phone'          => 'required|string|max:20',
            'cnic'           => 'nullable|string|max:20',
            'due_amount'     => 'required|numeric|min:0',
            'wallet_amount'  => 'required|numeric|min:0',
        ]);

        $patient->update($data);

        return redirect()->route('patients.index')
            ->with('update', 'Patient updated successfully');
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $patient = PatientHistory::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('delete', 'Patient deleted successfully');
    }
}