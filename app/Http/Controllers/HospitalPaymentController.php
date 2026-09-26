<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expense;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalPaymentController extends Controller
{
    /**
     * Display the hospital payments, sales, and financial summary dashboard.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterType = $request->input('filter', 'all');

        // Preset quick filters
        if ($filterType === 'today') {
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($filterType === 'week') {
            $startDate = Carbon::now()->startOfWeek()->toDateString();
            $endDate = Carbon::now()->endOfWeek()->toDateString();
        } elseif ($filterType === 'month') {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }

        $query = Appointment::with('doctor')->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate));
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate));
        }

        // Paginated list of appointments with consultation fees
        $appointments = $query->paginate(15)->withQueryString();

        // Calculate all financial figures for the active filter range
        $allAppointments = (clone $query)->get();

        $consultationSales = (float) $allAppointments->sum(function ($apt): float {
            return $apt->doctor ? (float) $apt->doctor->fee : 0.0;
        });

        $medicineSales = 0.0;
        $procedureSales = 0.0;
        $labSales = 0.0;
        $totalDiscount = 0.0;
        $totalSales = $consultationSales + $medicineSales + $procedureSales + $labSales - $totalDiscount;

        // Daily expenses in range
        $expenseQuery = Expense::query();
        if ($startDate && $endDate) {
            $expenseQuery->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $expenseQuery->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $expenseQuery->whereDate('date', '<=', $endDate);
        }
        $dailyExpenses = (float) $expenseQuery->sum('amount');
        $staffPayroll = (float) Staff::sum('salary');

        // Net hospital revenue (Consultation fees minus operational expenses)
        $netRevenue = max(0.0, $consultationSales - $dailyExpenses);

        // Patient accounts & dues
        $totalPatients = PatientHistory::count();
        $totalDueAmount = (float) PatientHistory::sum('due_amount');
        $totalWalletAmount = (float) PatientHistory::sum('wallet_amount');
        $totalAppointments = $allAppointments->count();

        // Averages and rates
        $avgFeePerVisit = $totalAppointments > 0 ? round($consultationSales / $totalAppointments, 2) : 0.0;
        $collectionRate = ($consultationSales + $totalDueAmount) > 0
            ? round(($consultationSales / ($consultationSales + $totalDueAmount)) * 100, 1)
            : 100.0;

        return view('hospital-payments', compact(
            'appointments',
            'consultationSales',
            'medicineSales',
            'procedureSales',
            'labSales',
            'totalDiscount',
            'totalSales',
            'dailyExpenses',
            'staffPayroll',
            'netRevenue',
            'totalPatients',
            'totalDueAmount',
            'totalWalletAmount',
            'totalAppointments',
            'avgFeePerVisit',
            'collectionRate',
            'startDate',
            'endDate',
            'filterType'
        ));
    }
}
