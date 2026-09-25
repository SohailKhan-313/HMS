<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main hospital management dashboard.
     */
    public function index(): View
    {
        $today = Carbon::today()->toDateString();

        $totalPatients = PatientHistory::count();
        $totalDoctors = Doctor::count();
        $totalStaff = Staff::count();

        $todayAppointments = Appointment::whereDate('created_at', $today)->count();
        $pendingAppointments = Appointment::whereDate('created_at', $today)->where('status', 'Pending')->count();
        $completedAppointments = Appointment::whereDate('created_at', $today)->where('status', 'Completed')->count();
        $admittedPatients = Appointment::where('status', 'Admitted')->count();

        $recentAppointments = Appointment::with('doctor')->latest()->take(5)->get();

        return view('welcome', compact(
            'totalPatients',
            'totalDoctors',
            'totalStaff',
            'todayAppointments',
            'pendingAppointments',
            'completedAppointments',
            'admittedPatients',
            'recentAppointments'
        ));
    }
}
