<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelAppointmentsByDateRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request): View
    {
        $query = Appointment::query()->with('doctor');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $appointments = $query->latest()->paginate(10)->withQueryString();

        $today = Carbon::today()->toDateString();
        $totalToday = Appointment::whereDate('created_at', $today)->count();
        $pending = Appointment::whereDate('created_at', $today)->where('status', 'Pending')->count();
        $completed = Appointment::whereDate('created_at', $today)->where('status', 'Completed')->count();
        $cancelled = Appointment::whereDate('created_at', $today)->where('status', 'Cancelled')->count();
        $admitted = Appointment::whereDate('created_at', $today)->where('status', 'Admitted')->count();

        $doctors = Doctor::query()->orderBy('name')->get();

        return view('appointments.appointment', compact(
            'totalToday',
            'pending',
            'completed',
            'cancelled',
            'admitted',
            'appointments',
            'doctors'
        ));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('appointment.index');
    }

    /**
     * Store a newly created appointment.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        Appointment::create($request->validated());

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display a specific appointment.
     */
    public function show(int|string $id): JsonResponse|View
    {
        $appointment = Appointment::with('doctor')->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($appointment);
        }

        return view('appointments.appointment', [
            'appointment' => $appointment,
            'appointments' => Appointment::with('doctor')->latest()->paginate(10),
            'doctors' => Doctor::all(),
            'totalToday' => Appointment::whereDate('created_at', Carbon::today()->toDateString())->count(),
            'pending' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'admitted' => 0,
        ]);
    }

    /**
     * Show the form for editing an appointment.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $appointment = Appointment::with('doctor')->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($appointment);
        }

        return redirect()->route('appointment.index');
    }

    /**
     * Update an appointment.
     */
    public function update(UpdateAppointmentRequest $request, int|string $id): RedirectResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->validated());

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Cancel appointments by selected date.
     */
    public function cancelByDate(CancelAppointmentsByDateRequest $request): RedirectResponse
    {
        $selectedDate = $request->validated('date');

        Appointment::whereDate('created_at', $selectedDate)
            ->update(['status' => 'Cancelled']);

        return redirect()->back()
            ->with('success', "All appointments on {$selectedDate} have been cancelled.");
    }
}
