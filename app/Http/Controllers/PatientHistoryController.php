<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientHistoryRequest;
use App\Http\Requests\UpdatePatientHistoryRequest;
use App\Models\PatientHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientHistoryController extends Controller
{
    /**
     * Display a listing of patients with search and pagination.
     */
    public function index(Request $request): View
    {
        $query = PatientHistory::query();

        if ($request->filled('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cnic', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('history.p-history', compact('patients'));
    }

    /**
     * Show create patient form (modal-driven, redirects to index).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('patients.index');
    }

    /**
     * Store a newly created patient record.
     */
    public function store(StorePatientHistoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['due_amount'] = $data['due_amount'] ?? 0;
        $data['wallet_amount'] = $data['wallet_amount'] ?? 0;

        PatientHistory::create($data);

        return redirect()->route('patients.index')
            ->with('success', 'Patient added successfully.');
    }

    /**
     * Display the specified patient record.
     */
    public function show(int|string $id): JsonResponse|View
    {
        $patient = PatientHistory::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($patient);
        }

        return view('history.p-history', [
            'patient' => $patient,
            'patients' => PatientHistory::latest()->paginate(10),
        ]);
    }

    /**
     * Show edit patient form.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $patient = PatientHistory::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($patient);
        }

        return redirect()->route('patients.index');
    }

    /**
     * Update the specified patient record.
     */
    public function update(UpdatePatientHistoryRequest $request, int|string $id): RedirectResponse
    {
        $patient = PatientHistory::findOrFail($id);
        $patient->update($request->validated());

        return redirect()->route('patients.index')
            ->with('update', 'Patient updated successfully.');
    }

    /**
     * Delete the specified patient record.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $patient = PatientHistory::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('delete', 'Patient deleted successfully.');
    }
}
