<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
public function index(Request $request)
{
    $query = Appointment::query();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    $appointments = $query->with('doctor')->latest()->paginate(2); // ✅ paginated

    // Dashboard counts (unaffected by search, as they are "today" stats)
    $today = Carbon::today()->toDateString();
    $totalToday = Appointment::whereDate('created_at', $today)->count();
    $pending = Appointment::whereDate('created_at', $today)->where('status', 'Pending')->count();
    $completed = Appointment::whereDate('created_at', $today)->where('status', 'Completed')->count();
    $cancelled = Appointment::whereDate('created_at', $today)->where('status', 'Cancelled')->count();
    $admitted = Appointment::whereDate('created_at', $today)->where('status', 'Admitted')->count();

    $doctors = Doctor::all();

    return view('appointments.appointment', compact(
        'totalToday', 'pending', 'completed', 'cancelled', 'admitted',
        'appointments', 'doctors'
    ));
}
    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $doctors = Doctor::all();
        return view('appointments.create', compact('doctors'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'age' => 'required|integer|min:0|max:150',
            'status' => 'required|string|in:Pending,Completed,Cancelled,Admitted',
            'time' => 'required'
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'age' => $request->age,
            'status' => $request->status,
             'time' => $request->time,
        ]);

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display a specific appointment.
     */
    public function show($id)
    {
        $appointment = Appointment::with('doctor')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($appointment);
        }

        // Fallback for normal browser request (optional)
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing an appointment.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctors = Doctor::all();
        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update an appointment.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'age' => 'required|integer|min:0|max:150',
            'status' => 'required|string|in:Pending,Completed,Cancelled,Admitted',
            'time' => 'required'
        ]);

        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'gender'=>$request->gender,
            'age'=>$request->age,
            'status' => $request->status,
            'time' => $request->time
            ,
        ]);

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointment.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Cancel appointments by selected date.
     */
    public function cancelByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $selectedDate = $request->date;

        Appointment::whereDate('created_at', $selectedDate)
            ->update(['status' => 'Cancelled']);

        return redirect()->back()
            ->with('success', "All appointments on {$selectedDate} have been cancelled.");
    }
}
