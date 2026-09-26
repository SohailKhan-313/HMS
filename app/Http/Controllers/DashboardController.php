<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main hospital management dashboard with dynamic metrics and analytics.
     */
    public function index(): View
    {
        $today = Carbon::today()->toDateString();

        // Patient metrics
        $totalPatients = PatientHistory::count();
        $totalDueAmount = (float) PatientHistory::sum('due_amount');
        $totalWalletAmount = (float) PatientHistory::sum('wallet_amount');

        // Medical & Staff
        $totalDoctors = Doctor::count();
        $totalStaff = Staff::count();
        $totalPayroll = (float) Staff::sum('salary');

        // Appointments
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('created_at', $today)->count();
        $pendingAppointments = Appointment::where('status', 'Pending')->count();
        $completedAppointments = Appointment::where('status', 'Completed')->count();
        $admittedAppointments = Appointment::where('status', 'Admitted')->count();
        $cancelledAppointments = Appointment::where('status', 'Cancelled')->count();

        // Expenses & Revenue
        $totalExpenses = (float) Expense::sum('amount');
        $todayExpenses = (float) Expense::whereDate('date', $today)->sum('amount');
        $totalRevenue = (float) Appointment::where('appointments.status', 'Completed')
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
            ->sum('doctors.fee');

        // Recent tables
        $recentAppointments = Appointment::with('doctor')->latest()->take(6)->get();
        $doctorsList = Doctor::withCount('appointments')->latest()->take(5)->get();
        $recentExpenses = Expense::latest()->take(5)->get();

        // 7-day appointment trend data
        $trendLabels = [];
        $trendTotal = [];
        $trendCompleted = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $trendLabels[] = $date->format('d M');
            $trendTotal[] = Appointment::whereDate('created_at', $dateStr)->count();
            $trendCompleted[] = Appointment::whereDate('created_at', $dateStr)->where('status', 'Completed')->count();
        }

        // Status donut chart distribution
        $statusSeries = [
            $completedAppointments,
            $pendingAppointments,
            $admittedAppointments,
            $cancelledAppointments,
        ];

        return view('welcome', compact(
            'totalPatients',
            'totalDueAmount',
            'totalWalletAmount',
            'totalDoctors',
            'totalStaff',
            'totalPayroll',
            'totalAppointments',
            'todayAppointments',
            'pendingAppointments',
            'completedAppointments',
            'admittedAppointments',
            'cancelledAppointments',
            'totalExpenses',
            'todayExpenses',
            'totalRevenue',
            'recentAppointments',
            'doctorsList',
            'recentExpenses',
            'trendLabels',
            'trendTotal',
            'trendCompleted',
            'statusSeries'
        ));
    }
}
