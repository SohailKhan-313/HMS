<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of system users with roles and access statistics.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role', 'all');
        $statusFilter = $request->input('status', 'all');

        $query = User::with('doctor')->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $users = $query->paginate(15)->withQueryString();

        // System role breakdown metrics
        $metrics = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'doctors' => User::where('role', 'doctor')->count(),
            'hr' => User::where('role', 'hr')->count(),
            'accountants' => User::where('role', 'accountant')->count(),
            'receptionists' => User::where('role', 'receptionist')->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        $doctors = Doctor::orderBy('name')->get();

        return view('users.index', compact('users', 'metrics', 'doctors', 'search', 'roleFilter', 'statusFilter'));
    }

    /**
     * Store a newly created user and assign their role.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return redirect()->route('users.index')
            ->with('success', "User account '{$user->name}' created successfully with role '{$user->role}'.");
    }

    /**
     * Update the specified user's profile and assigned role.
     */
    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        // Safety check: Prevent removing the only remaining admin
        if ($user->isAdmin() && $data['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot change role: At least one Administrator account is required.');
            }
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "User account '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Security restriction: You cannot delete your own logged-in user account.');
        }

        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the only remaining Administrator account.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$userName}' has been deleted from the hospital system.");
    }

    /**
     * Toggle a user's active / inactive status.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' has been {$statusText}.");
    }
}
