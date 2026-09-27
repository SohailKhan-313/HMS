<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     */
    public function index(Request $request): View
    {
        $query = Staff::query();

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $staff = $query->latest()->paginate(10)->withQueryString();

        return view('staff.staff', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member (modal on index).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('staff.index');
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['designation'] = $data['designation'] ?? 'Not Assigned';
        $data['salary'] = $data['salary'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('staff', 'public');
        } else {
            unset($data['image']);
        }

        Staff::create($data);

        return redirect()->route('staff.index')->with('success', 'Staff added successfully!');
    }

    /**
     * Display the specified staff member.
     */
    public function show(int|string $id): JsonResponse|RedirectResponse
    {
        $staffMember = Staff::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($staffMember);
        }

        return redirect()->route('staff.index');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $staffMember = Staff::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($staffMember);
        }

        return redirect()->route('staff.index');
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(UpdateStaffRequest $request, int|string $id): RedirectResponse
    {
        $staffMember = Staff::findOrFail($id);
        $data = $request->validated();
        $data['designation'] = $data['designation'] ?? 'Not Assigned';
        $data['salary'] = $data['salary'] ?? 0;

        if ($request->hasFile('image')) {
            if ($staffMember->image) {
                Storage::disk('public')->delete($staffMember->image);
            }
            $data['image'] = $request->file('image')->store('staff', 'public');
        } else {
            unset($data['image']);
        }

        $staffMember->update($data);

        return redirect()->route('staff.index')->with('update', 'Staff updated successfully!');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $staffMember = Staff::findOrFail($id);

        if ($staffMember->image) {
            Storage::disk('public')->delete($staffMember->image);
        }

        $staffMember->delete();

        return redirect()->route('staff.index')->with('delete', 'Staff deleted successfully.');
    }

    /**
     * Backward-compatible alias for delete route.
     */
    public function delete(int|string $id): RedirectResponse
    {
        return $this->destroy($id);
    }
}
